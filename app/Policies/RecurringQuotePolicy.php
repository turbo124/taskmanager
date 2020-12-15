<?php

namespace App\Policies;

use App\Models\RecurringQuote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecurringQuotePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\RecurringQuote $recurringQuote
     * @return mixed
     */
    public function view(User $user, RecurringQuote $recurringQuote)
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
            )->is_owner || $user->hasPermissionTo('recurringquotecontroller.store');
    }
}
