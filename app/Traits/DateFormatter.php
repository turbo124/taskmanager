<?php


namespace App\Traits;


use Carbon\Carbon;

trait DateFormatter
{

    public function formatDate($entity, $value)
    {
        $date_format = (get_class($entity) === 'App\Models\Customer')
            ? $this->entity->getSetting(
                'date_format'
            )
            : ((!empty($this->entity->customer)) ? $this->entity->customer->getSetting(
                'date_format'
            ) : $this->entity->account->settings->date_format);

        $date_format = $this->convertDateFormat($date_format);

        try {
            return Carbon::parse($value)->format($date_format);
        } catch (\Exception $e) {
            return '';
        }

        return '';
    }

    private function convertDateFormat($date_format)
    {
        switch ($date_format) {
            case 'DD/MMM/YYYY':
                return 'D M Y';
            case 'DD-MMM-YYYY':
                return 'D-M-Y';
            case 'DD-MMMM-YYYY':
                return 'D-M-Y';
        }

        return $date_format;
    }

}