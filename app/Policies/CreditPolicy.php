<?php

namespace App\Policies;

use App\Models\Credit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreditPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Credit $credit
     * @return mixed
     */
    public function view(User $user, Credit $credit)
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
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $user->hasPermissionTo('creditcontroller.store');
    }
}
