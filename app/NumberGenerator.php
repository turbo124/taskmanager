<?php

namespace App;

class NumberGenerator
{
    /**
     * @param Customer $customer
     * @param $entity_obj
     * @return string
     * @throws \Exception
     */
    public function getNextNumberForEntity(Customer $customer, $entity_obj): string
    {
        $resource = get_class($entity_obj);
        $entity_id = strtolower(explode('\\', $resource)[1]);

        $pattern_entity = "{$entity_id}_number_pattern";
        $counter_var = "{$entity_id}_number_counter";

        $pattern = trim($customer->getSetting($pattern_entity));

        if ($resource === Customer::class || strpos($pattern, 'clientCounter')) {
            $counter = $customer->getSetting($counter_var);
            $counter_entity = $customer;
        } elseif (strpos($pattern, 'groupCounter')) {
            $counter = $customer->group_settings->{$counter_var};
            $counter_entity = $customer->group_settings;
        } else {
            $counter = $customer->account->settings->{$counter_var};
            $counter_entity = $customer->account;
        }

        //Return a valid counter
        $pattern = $customer->getSetting($pattern_entity);
        $padding = $customer->getSetting('counter_padding');

        $number = $this->checkEntityNumber($resource, $customer, $counter, $padding, $pattern);

        if (in_array($resource, [RecurringInvoice::class, RecurringQuote::class])) {
            $number = $this->prefixCounter($number, $customer->getSetting('recurring_number_prefix'));
        }

        $this->incrementCounter($counter_entity, $counter_var);
        return $number;
    }

    /**
     *  Saves counters at both the account and customer level
     * @param $entity
     * @param string $counter_name
     */
    private function incrementCounter($entity, string $counter_name): void
    {
        $settings = $entity->settings;
        $settings->{$counter_name} = $settings->{$counter_name} + 1;
        $entity->settings = $settings;
        $entity->save();
    }

    private function prefixCounter($counter, $prefix): string
    {
        if (strlen($prefix) == 0) {
            return $counter;
        }
        return $prefix . $counter;
    }

    /**
     * Pads a number with leading 000000's
     *
     * @param int $counter The counter
     * @param int $padding The padding
     *
     * @return     int  the padded counter
     */
    private function padCounter($counter, $padding): string
    {
        return str_pad($counter, $padding, '0', STR_PAD_LEFT);
    }

    private function applyNumberPattern(Customer $customer, string $counter, $pattern): string
    {

        if (!$pattern) {
            return $counter;
        }
        $search = ['{$year}'];
        $replace = [date('Y')];
        $search[] = '{$counter}';
        $replace[] = $counter;
        $search[] = '{$clientCounter}';
        $replace[] = $counter;
        $search[] = '{$groupCounter}';
        $replace[] = $counter;
        if (strstr($pattern, '{$user_id}')) {
            $user_id = $customer->user_id ? $customer->user_id : 0;
            $search[] = '{$user_id}';
            $replace[] = str_pad(($user_id), 2, '0', STR_PAD_LEFT);
        }
        $matches = false;
        preg_match('/{\$date:(.*?)}/', $pattern, $matches);
        if (count($matches) > 1) {
            $format = $matches[1];
            $search[] = $matches[0];
            /* The following adjusts for the company timezone - may bork tests depending on the time of day the tests are run!!!!!!*/
            $date = Carbon::now($customer->account->timezone()->name)->format($format);
            $replace[] = str_replace($format, $date, $matches[1]);
        }
        $search[] = '{$custom1}';
        $replace[] = $customer->custom_value1;
        $search[] = '{$custom2}';
        $replace[] = $customer->custom_value2;
        $search[] = '{$custom3}';
        $replace[] = $customer->custom_value3;
        $search[] = '{$custom4}';
        $replace[] = $customer->custom_value4;
        $search[] = '{$id_number}';
        $replace[] = $customer->id_number;

        return str_replace($search, $replace, $pattern);
    }

    private function checkEntityNumber($class, $customer, $counter, $padding, $pattern)
    {

        $check = false;
        do {
            $number = $this->padCounter($counter, $padding);
            $number = $this->applyNumberPattern($customer, $number, $pattern);

            if ($class == Customer::class) {
                $check = $class::whereAccountId($customer->account_id)->whereIdNumber($number)->withTrashed()->first();
            } else {
                $check = $class::whereAccountId($customer->account_id)->whereNumber($number)->withTrashed()->first();
            }

            $counter++;
        } while ($check);
        return $number;
    }
}
