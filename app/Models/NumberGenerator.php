<?php

namespace App\Models;

use App\Traits\CalculateRecurring;
use Carbon\Carbon;
use Exception;
use ReflectionClass;

class NumberGenerator
{
    use CalculateRecurring;

    private $entity_obj;

    /**
     * @var string
     */
    private $counter_entity;

    private $counter_var;

    /**
     * @var array|string[]
     */
    private array $availiable_reset_types = [
        'task',
        'project',
        'invoice',
        'credit',
        'order',
        'expense',
        'company',
        'lead',
        'case',
        'payment',
        'customer',
        'recurringinvoice',
        'recurringquote',
        'quote',
        'deal',
        'purchaseorder'

    ];

    /**
     * @var string
     */
    private string $pattern = '';

    /**
     * @var int
     */
    private int $counter = 0;

    /**
     * @param Customer $customer
     * @param $entity_obj
     * @return string
     * @throws Exception
     */
    public function getNextNumberForEntity($entity_obj, Customer $customer = null): string
    {
        $this->entity_obj = $entity_obj;
        $resource = get_class($entity_obj);

        $this->setType($entity_obj, $resource, $customer);
        $this->setPrefix($customer);

        $padding = $customer !== null ? $customer->getSetting(
            'counter_padding'
        ) : $entity_obj->account->settings->counter_padding;

        $number = $this->checkEntityNumber($resource, $customer, $this->counter, $padding);

        $this->updateEntityCounter();

        $this->resetCounters($entity_obj);

        return $number;
    }

    /**
     * @param $pattern_entity
     * @param $counter_var
     * @param $resource
     * @param Customer|null $customer
     * @return bool
     */
    private function setType($entity_object, $resource, Customer $customer = null)
    {
        $entity_id = strtolower((new ReflectionClass($entity_object))->getShortName());
        $pattern_entity = "{$entity_id}_number_prefix";
        $this->counter_var = "{$entity_id}_number_counter";
        $counter_type = "{$entity_id}_counter_type";

        $this->pattern = $customer !== null
            ? trim($customer->getSetting($pattern_entity))
            : trim(
                $this->entity_obj->account->settings->{$pattern_entity}
            );

        $this->counter = $customer !== null ? $customer->getSetting(
            $this->counter_var
        ) : $this->entity_obj->account->settings->{$this->counter_var};

        $this->counter_type = $customer !== null ? $customer->getSetting(
            $counter_type
        ) : $this->entity_obj->account->settings->{$counter_type};

        $this->counter_entity = $this->entity_obj->account;

        if ($customer === null) {
            return true;
        }

        if ($resource === Customer::class || $this->counter_type === 'customer') {
            $this->counter = $customer->getSetting($this->counter_var);
            $this->counter_entity = $customer;
        } elseif ($this->counter_type === 'group') {
            $this->counter = $customer->group_settings->{$this->counter_var};
            $this->counter_entity = $customer->group_settings;
        }

        return true;
    }

    private function setPrefix(Customer $customer = null)
    {
        $this->recurring_prefix = $customer !== null ? $customer->getSetting(
            'recurring_number_prefix'
        ) : $this->entity_obj->account->settings->recurring_number_prefix;
    }

    private function checkEntityNumber($class, $customer, $counter, $padding)
    {
        $check = false;
        do {
            $number = str_pad($counter, $padding, '0', STR_PAD_LEFT);
            $number = $this->formtPrefix($number, $customer);

            if ($this->isRecurring($class)) {
                $number = $this->addPrefixToCounter($number, $class, $customer);
            }

            $check = $class::whereAccountId($this->entity_obj->account->id)->whereNumber($number)->withTrashed()->first(
            );

            $counter++;
        } while ($check);
        return $number;
    }

    private function formtPrefix($number, Customer $customer = null)
    {
        $prefix = '';

        switch ($this->pattern) {
            case 'YEAR':
                $prefix = date('Y');
                break;
            case 'DATE':
                $prefix = date('d-m-Y');
                break;
            case 'MONTH':
                $prefix = date('M');
                break;
            case 'CUSTOMER':
                if (!empty($customer)) {
                    $prefix = $customer->number;
                }

                break;
        }

        return !empty($prefix) ? "{$prefix}-{$number}" : $number;
    }

    private function isRecurring($resource)
    {
        return in_array($resource, [RecurringInvoice::class, RecurringQuote::class]) && !empty($this->recurring_prefix);
    }

    /**
     * @param $number
     * @param $resource
     * @param Customer|null $customer
     * @return string
     */
    private function addPrefixToCounter($number, $resource, Customer $customer = null): string
    {
        if (!$this->isRecurring($resource)) {
            return $number;
        }

        return $this->recurring_prefix . $number;
    }

    private function updateEntityCounter(): void
    {
        $settings = $this->counter_entity->settings;
        $settings->{$this->counter_var} = !empty($settings->{$this->counter_var}) ? $settings->{$this->counter_var} + 1 : 1;
        $this->counter_entity->settings = $settings;
        $this->counter_entity->save();
    }

    /**
     * @param $entity
     * @param bool $reset_all
     * @param bool $reset_customer
     * @return bool
     * @throws \ReflectionException
     */
    public function resetCounters($entity, bool $reset_all = true, bool $reset_customer = true): bool
    {
        $date_to_reset = !empty($entity->customer) ? $entity->customer->getSetting(
            'date_counter_next_reset'
        ) : $entity->account->settings->{'date_counter_next_reset'};

        $frequency_type = !empty($entity->customer) ? $entity->customer->getSetting(
            'counter_frequency_type'
        ) : $entity->account->settings->{'counter_frequency_type'};

        if (!Carbon::parse($date_to_reset)->isToday()) {
            return false;
        }

        $next_date_to_send = $this->calculateDate($frequency_type)->format('Y-m-d');

        if (!$reset_all) {
            $entity_id = strtolower((new ReflectionClass($entity))->getShortName());
            $counter_var = "{$entity_id}_number_counter";
            $this->resetVariable($counter_var, $entity, $next_date_to_send);
        }

        foreach ($this->availiable_reset_types as $availiable_reset_type) {
            $counter_var = "{$availiable_reset_type}_number_counter";
            $this->resetVariable($counter_var, $entity, $next_date_to_send);
        }

        return true;
    }

    /**
     * @param string $variable
     * @param $entity
     * @param string $next_send_date
     * @param bool $reset_customer
     */
    private function resetVariable(string $variable, $entity, string $next_send_date, bool $reset_customer = true): bool
    {
        if (!empty($entity->customer) && $reset_customer) {
            $entity->customer->settings->{$variable} = 1;
            $entity->customer->settings->date_counter_next_reset = $next_send_date;
            $entity->customer->save();
        }

        $entity->account->settings->{$variable} = 1;
        $entity->account->settings->date_counter_next_reset = $next_send_date;
        $entity->account->save();

        return true;
    }
}
