<?php


namespace App\Traits;


use Carbon\Carbon;

trait CalculateRecurring
{

    public function calculateDate($frequency, $date = null) {
        $date = empty($date) ? Carbon::today() : Carbon::parse($date);

        switch($frequency) {
            case 'DAILY':
                return $date->addDay();
            break;

            case 'WEEKLY':
                return $date->addWeek();
            break;

            case 'FORTNIGHT':
                return $date->addWeeks(2);
            break;

            case 'MONTHLY':
                return $date->addMonth();
            break;

            case 'TWO_MONTHS':
                return $date->addMonths(2);
            break;

            case 'THREE_MONTHS':
                return $date->addMonths(3);
            break;

            case 'FOUR_MONTHS':
                return $date->addMonths(4);
            break;
 
            case 'SIX_MONTHS':
                return $date->addMonths(6);
            break;

            case 'YEARLY':
                return $date->addYear();
            break;
        }
    }

}
