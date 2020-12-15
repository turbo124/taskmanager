<?php

namespace App\Policies;

use App\Models\TaxRate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxRatePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\TaxRate $taxRate
     * @return mixed
     */
    public function view(User $user, TaxRate $taxRate)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('taxratecontroller.store');
    }
}
