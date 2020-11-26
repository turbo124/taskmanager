<?php


namespace App\Components\Import;


use App\Models\Customer;
use App\Models\Product;

trait ImportMapper
{
    private array $converters = [
        'product'       => 'getProduct',
        'customer_name' => 'getCustomer'
    ];

    public function after()
    {
        //TODO
    }

    /**
     * Will be executed for a csv line if it passed validation
     * @param $items
     * @return bool
     */
    public function handle($items)
    {
        $object = $this->buildObject($items);

        $factory = $this->factory($items);

        if (!$factory) {
            return false;
        }

        $repo = $this->repository();

        $result = $repo->save($object, $factory);

        if(method_exists($this, 'saveCallback')) {
            return $this->saveCallback($result, $object);
        }

        return $this->result;
    }

    private function buildObject($items)
    {
        $object = [];
        $count = 0;

        foreach ($this->mappings as $key => $columns) {
            if (is_array($columns)) {
                foreach ($columns as $column => $field) {
                    if (!isset($items[$column])) {
                        continue;
                    }

                    if (isset($this->converters[$column])) {
                        $value = $this->{$this->converters[$column]}($items[$column]);

                        $object[$key][$count][$field] = $value;

                        continue;
                    }

                    $object[$key][$count][$field] = $items[$column];
                }

                continue;
            }

            if (!isset($items[$key])) {
                continue;
            }

            if (isset($this->converters[$key])) {
                $value = $this->{$this->converters[$key]}($items[$key]);

                $object[$columns] = $value;

                continue;
            }

            $object[$columns] = $items[$key];

            $count++;
        }

        return $object;
    }


    /**
     *  Will be executed if a csv line did not pass validation
     *
     * @param $item
     * @return void
     */
    public function invalid($item)
    {
        echo '<pre>';
        print_r($item);
        die('mike');

        $this->insertTo('invalid_entities', $item);
    }

    /**
     * @param $value
     * @return int|null
     */
    private function getProduct($value): ?int
    {
        //$query->whereRaw('LOWER(`newsTitle`) LIKE ? ',[trim(strtolower($newsTitle)).'%']);
        $product = Product::whereRaw('LOWER(`name`) = ? ', [trim(strtolower($value))])->first();

        if (empty($product)) {
            return null;
        }

        return $product->id;
    }

    /**
     * @param $value
     * @return int|null
     */
    private function getCustomer($value): ?int
    {
        $customer_obj = Customer::whereRaw('LOWER(`name`) = ? ', [trim(strtolower($value))])->first();

        if (empty($customer_obj)) {
            return null;
        }

        $this->customer = $customer_obj;

        return $this->customer->id;
    }
}
