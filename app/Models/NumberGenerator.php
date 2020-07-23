<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\RecurringInvoice;
use App\Models\RecurringQuote;
use Illuminate\Support\Facades\Log;

class NumberGenerator
{
    private $entity_obj;

    /**
     * @param Customer $customer
     * @param $entity_obj
     * @return string
     * @throws \Exception
     */
    public function getNextNumberForEntity($entity_obj, Customer $customer = null): string
    {
        $this->entity_obj = $entity_obj;
        $resource = get_class($entity_obj);
        $entity_id = strtolower((new \ReflectionClass($entity_obj))->getShortName());
        $pattern_entity = "{$entity_id}_number_pattern";
        $counter_var = "{$entity_id}_number_counter";

        $this->setType($pattern_entity, $counter_var, $resource, $customer);

        $padding = $customer !== null ? $customer->getSetting(
            'counter_padding'
        ) : $entity_obj->account->settings->counter_padding;

        $number = $this->checkEntityNumber($resource, $customer, $this->counter, $padding);

        $number = $this->addPrefixToCounter($number, $resource, $customer);

        $this->updateEntityCounter($this->counter_entity, $counter_var);

        return $number;
    }

    /**
     * @param $pattern_entity
     * @param $counter_var
     * @param $resource
     * @param Customer|null $customer
     * @return bool
     */
    private function setType($pattern_entity, $counter_var, $resource, Customer $customer = null)
    {
        $pattern = $customer !== null
            ? trim($customer->getSetting($pattern_entity))
            : trim(
                $this->entity_obj->account->settings->{$pattern_entity}
            );
        $this->counter = $customer !== null ? $customer->getSetting(
            $counter_var
        ) : $this->entity_obj->account->settings->{$counter_var};
        $this->counter_entity = $this->entity_obj->account;

        if ($customer === null) {
            return true;
        }

        if ($resource === Customer::class || strpos($pattern, 'clientCounter')) {
            $this->counter = $customer->getSetting($counter_var);
            $this->counter_entity = $customer;
        } elseif (strpos($pattern, 'groupCounter')) {
            $this->counter = $customer->group_settings->{$counter_var};
            $this->counter_entity = $customer->group_settings;
        }

        return true;
    }

    /**
     *  Saves counters at both the account and customer level
     * @param $entity
     * @param string $counter_name
     */
    private function updateEntityCounter($entity, string $counter_name): void
    {
        $settings = $entity->settings;
        $settings->{$counter_name} = !empty($settings->{$counter_name}) ? $settings->{$counter_name} + 1 : 1;
        $entity->settings = $settings;
        $entity->save();
    }

    /**
     * @param $number
     * @param $resource
     * @param Customer|null $customer
     * @return string
     */
    private function addPrefixToCounter($number, $resource, Customer $customer = null): string
    {
        $recurring_prefix = $customer !== null ? $customer->getSetting(
            'recurring_number_prefix'
        ) : $this->entity_obj->account->settings->recurring_number_prefix;

        if (in_array($resource, [RecurringInvoice::class, RecurringQuote::class]) && !empty($recurring_prefix)) {
            return $recurring_prefix . $number;
        }

        return $number;
    }

    private function checkEntityNumber($class, $customer, $counter, $padding)
    {
        $check = false;
        do {
            $number = str_pad($counter, $padding, '0', STR_PAD_LEFT);
            $check = $class::whereAccountId($this->entity_obj->account->id)->whereNumber($number)->withTrashed()->first(
            );

            $counter++;
        } while ($check);
        return $number;
    }
}
