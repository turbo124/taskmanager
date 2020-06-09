<?php


namespace App\Http\Controllers;


use App\Customer;
use App\Helpers\Shipping\ShippoShipment;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController
{

    public function getRates(Request $request)
    {
        $customer = Customer::find($request->input('customer_id'));

        $objShipping = new ShippoShipment($customer, $request->input('products'));

        $objShipping->createShippingProcess();

        return response()->json(['rates' => $objShipping->getRates()]);
    }
}