<?php

namespace App\Transformations;

use App\Models\Address;
use App\Models\ClientContact;
use App\Models\Customer;
use App\Models\Design;
use App\Repositories\ClientContactRepository;
use App\Repositories\CustomerRepository;

trait DesignTransformable
{

    /**
     * @param Design $design
     * @return array
     */
    public function transformDesign(Design $design)
    {
        return [
            'id'        => (int)$design->id,
            'name'      => (string)$design->name,
            'is_custom' => (bool)$design->is_custom,
            'is_active' => (bool)$design->is_active,
            'design'    => $design->design,
        ];
    }

}
