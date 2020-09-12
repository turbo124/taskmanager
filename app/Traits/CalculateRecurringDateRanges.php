<?php

namespace App\Traits;

trait CalculateRecurringDateRanges
{

    public function calculate($start_date, $end_date) {
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            echo $dt->format("l Y-m-d H:i:s\n");
        }
    }
}
