<?php

namespace App\Traits;

use DateInterval;
use DatePeriod;
use DateTime;

trait CalculateRecurringDateRanges
{

    public function calculateDateRanges()
    {
        $begin = new DateTime($this->start_date);

        $endless = false;

        if (empty($this->expiry_date) || $endless === true) {
            $this->expiry_date = date('Y-m-d', strtotime('+1 years'));
        }

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P' . $this->frequency . 'D');

        $realEnd = new DateTime($this->expiry_date);

        $period = new DatePeriod(new DateTime($this->start_date), $interval, $realEnd);

        $date_ranges = [];

        // Use loop to store date into array
        foreach ($period as $date) {
            $due_date = $this->calculateDueDate($date);

            $date_to_send = clone $date;
            $date_to_send = $date_to_send->modify('+' . $this->frequency . ' day');


            $date_ranges[] = [
                'expiry_date'  => $date->format('Y-m-d'),
                'due_date'     => $due_date->format('Y-m-d'),
                'date_to_send' => $date_to_send->format('Y-m-d')
            ];
        }

        return $date_ranges;
    }

    private function calculateDueDate($date)
    {
        $due_date = clone $date;

        $days = (!empty($this->grace_period))
            ? $this->grace_period
            : ((!empty(
            $this->customer->getSetting(
                'payment_terms'
            )
            )) ? $this->customer->getSetting('payment_terms') : null);

        $due_date = $due_date->modify('+' . $days . ' day');

        return $due_date;
    }
}
