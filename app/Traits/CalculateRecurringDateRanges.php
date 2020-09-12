<?php

namespace App\Traits;

trait CalculateRecurringDateRanges
{

    public function calculate() {
        $begin = new DateTime($this->start_date);

        if(empty($this->end_date) || $endless === true) {
            $this->end_date = date('Y-m-d', strtotime('+1 years'));
        }

        $end = new DateTime($this->end_date);

        $interval = DateInterval::createFromDateString($this->frequency . ' day');
        $period = new DatePeriod($begin, $interval, $end);

        $date_ranges = [];

        foreach ($period as $dt) {
            $date_ranges[] = [
                'start_date' => $dt->format("l Y-m-d H:i:s\n"),
                'next_send_date' => null,
                'due_date' => $this->calculateDueDate()
            ];
        }
    }

    private function calculateDueDate($end_date)
    {
        $days = (!empty($this->grace_period)) ? $this->grace_period : ((!empty($this->customer->getSetting('payment_terms'))) ? this->customer->getSetting('payment_terms')  : null);
        return !empty($days) ? Carbon($end_date)->addDays($days)->format('Y-m-d H:i:s') : null;
    }
}
