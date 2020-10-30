<?php


namespace App\Traits;


use Carbon\Carbon;

trait CalculateRecurring
{

    public function calculate($frequency) {
        switch($frequency) {
            case 'DAILY':
                return Carbon::today()->addDay();
            break;

            case 'WEEKLY':
                return Carbon::today()->addWeek();
            break;

            case 'FORTNIGHT':
                return Carbon::today()->addWeeks(2);
            break;

            case 'MONTHLY':
                return Carbon::today()->addMonth();
            break;

            case 'TWO_MONTHS':
                return Carbon::today()->addMonths(2);
            break;

            case 'THREE_MONTHS':
                return Carbon::today()->addMonths(3);
            break;

            case 'FOUR_MONTHS':
                return Carbon::today()->addMonths(4);
            break;
 
            case 'SIX_MONTHS':
                return Carbon::today()->addMonths(6);
            break;

            case 'YEARLY':
                return Carbon::today()->addYear();
            break;
        }
    }

}
