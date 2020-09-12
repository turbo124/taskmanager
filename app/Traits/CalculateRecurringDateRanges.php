<?php

namespace App\Traits;

trait CalculateRecurringDateRanges
{

    public function calculate($start_date, $end_date) {
        $begin = new DateTime($start_date);

        if(empty($end_date) || $endless === true) {
            $end_date = date('Y-m-d', strtotime('+1 years'));
        }

        $end = new DateTime($end_date);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $date_ranges = [];

        foreach ($period as $dt) {
            $date_ranges[] = [
                'next_send_date' => $dt->format("l Y-m-d H:i:s\n"),
                'due_date' => $this->calculateDueDate()
            ];
        }
    }

    private function calculateDueDate()
    {
        $days = (!empty($this->grace_period)) ? $this->grace_period : ((!empty($this->customer->getSetting('payment_terms'))) ? this->customer->getSetting('payment_terms')  : null);
        return !empty($days) ? Carbon::now()->addDays($days)->format('Y-m-d H:i:s') : null;
    }
}
