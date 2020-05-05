<?php

namespace App\Transformations;

use App\Address;
use App\ClientContact;
use App\Customer;
use App\Design;
use App\Repositories\ClientContactRepository;
use App\Repositories\CustomerRepository;

trait DesignTransformable
{

    /**
     * @param Address $address
     * @return Address
     */
    public function transformDesign(Design $design)
    {
        return [
        'id' => (int)$design->id,
        'name' => (string)$design->name,
        'is_custom' => (bool)$design->is_custom,
        'is_active' => (bool)$design->is_active,
        'design' => $design->design,
        ];
    }

}
