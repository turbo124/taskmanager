<?php

namespace App\Presenters;

use App\Presenters\EntityPresenter;
use App\Utils\Number;

class OrderPresenter extends EntityPresenter
{
    public function customerName()
    {
        return $this->customer->present()->name();
    }
}
