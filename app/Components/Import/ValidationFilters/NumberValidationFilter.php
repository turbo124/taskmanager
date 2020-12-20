<?php

namespace App\Components\Import\ValidationFilters;

use App\Components\Import\BaseValidationFilter;

class NumberValidationFilter extends BaseValidationFilter
    {
        /**
         * @var string
         */
        protected $name = 'number_validation';

        /**
         * @param mixed $value
         * @return bool
         */
        public function filter($value)
        {
            if (strpos($value, 'bad_word') !== false) {
                return false;
            }

            return true;
        }
    }
