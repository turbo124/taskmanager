<?php

namespace App\Policies;

use App\Models\Cases;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CasePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Cases $cases
     * @return mixed
     */
    public function view(User $user, Cases $cases)
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
            )->is_owner || $user->hasPermissionTo('casecontroller.store');
    }
}
