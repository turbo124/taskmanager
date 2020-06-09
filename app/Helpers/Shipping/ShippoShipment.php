<?php

namespace App\Helpers\Shipping;

use App\Address;
use App\Customer;
use App\Product;
use App\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Shippo;
use Shippo_Shipment;

class ShippoShipment
{
    /**
     * @var Customer
     */
    private Customer $customer;

    /**
     * @var array
     */
    private array $rates;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * The address where to pick up the item for delivery
     *
     * @var $warehouseAddress
     */
    protected $warehouseAddress;

    /**
     * The address of the customer where the item is to be delivered
     *
     * @var $deliveryAddress
     */
    protected $deliveryAddress;

    /**
     * The item/s
     *
     * @var $parcel
     */
    protected $parcel;

    /**
     * Shipment
     *
     * @var $shipment
     */
    protected $shipment;

    private $line_items;

    /**
     * ShippoShipment constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer, $line_items)
    {
        Shippo::setApiKey(env('SHIPPO_PRIVATE'));
        $this->customer = $customer;
        $this->line_items = $line_items;
    }

    public function createShippingProcess()
    {
        if ($this->customer->addresses()->count() > 0 && count($this->line_items) > 0) {
            $this->setPickupAddress();
            $deliveryAddress = $this->customer->addresses->where('address_type', '=', 2)->first();
            $this->setDeliveryAddress($deliveryAddress);
            $this->readyParcel($this->line_items);

            return $this->readyShipment();
        }
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function createLabel(Order $order)
    {
        $transaction = \Shippo_Transaction::create(
            array(
                'rate'            => $order->shipping_id,
                'label_file_type' => "PDF",
                'async'           => false
            )
        );

        if ($transaction["status"] == "SUCCESS" && !empty($transaction["label_url"])) {
            $order->shipping_label_url = $transaction["label_url"];
            $order->save();

            return true;
        }

        return false;
    }

    /**
     * Address where the shipment will be picked up
     */
    private function setPickupAddress()
    {
        $warehouse = [
            'name'    => config('app.name'),
            'street1' => config('shop.warehouse.address_1'),
            'city'    => config('shop.warehouse.city'),
            'state'   => config('shop.warehouse.state'),
            'zip'     => config('shop.warehouse.zip'),
            'country' => config('shop.warehouse.country'),
            'phone'   => config('shop.phone'),
            'email'   => config('shop.email')
        ];

        $this->warehouseAddress = $warehouse;
    }

    /**
     * @param Address $address
     */
    private function setDeliveryAddress(Address $address)
    {
        $delivery = [
            'name'    => $this->customer->name,
            'street1' => $address->address_1,
            'city'    => $address->city,
            'state'   => $address->state_code,
            'zip'     => $address->zip,
            'country' => $address->country->iso,
            'phone'   => '',
            'email'   => $this->customer->email
        ];

        $this->deliveryAddress = $delivery;
    }

    /**
     * @return \Shippo_Shipment
     */
    private function readyShipment()
    {
        $shipment = Shippo_Shipment::create(
            array(
                'address_from' => $this->warehouseAddress,
                'address_to'   => $this->deliveryAddress,
                'parcels'      => $this->parcel,
                'async'        => false
            )
        );

        foreach ($shipment['rates'] as $key => $rate) {
            $this->rates[$key]['amount'] = $rate->amount;
            $this->rates[$key]['name'] = $rate->provider;
            $this->rates[$key]['object_id'] = $rate->object_id;
        }

        return $shipment;
    }

    public function getRates()
    {
        return $this->rates;
    }

    /**
     * @param string $id
     * @param string $currency
     * @return \Shippo_Get_Shipping_Rates
     */
//    public function getRates(string $id, string $currency = 'USD')
//    {
//        return Shippo_Shipment::get_shipping_rates(compact('id', 'currency'));
//    }

    /**
     * @param Collection $collection
     *
     * @return void
     */
    private function readyParcel(array $line_items)
    {
        $weight = collect($line_items)->map(
            function ($item) {
                $product = Product::find($item['product_id']);

                return [
                    'weight'    => $product->weight * $item['quantity'],
                    'mass_unit' => $product->mass_unit
                ];
            }
        )->map(
            function ($item) {
                $total = 0;
                switch ($item['mass_unit']) {
                    case Product::MASS_UNIT['OUNCES'] :
                        $oz = $item['weight'] / 16;
                        $total += $oz;
                        break;
                    case Product::MASS_UNIT['GRAMS'] :
                        $oz = $item['weight'] * 0.0022;
                        $total += $oz;
                        break;
                    default:
                        $total += $item['weight'];
                }
                return [
                    'weight' => $total
                ];
            }
        )->sum('weight');

        $parcel = array(
            'length'        => '5',
            'width'         => '5',
            'height'        => '5',
            'distance_unit' => 'in',
            'weight'        => $weight,
            'mass_unit'     => 'lb',
        );

        $this->parcel = $parcel;
    }
}
