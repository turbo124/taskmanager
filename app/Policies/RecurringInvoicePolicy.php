<?php

namespace App\Policies;

use App\Models\RecurringInvoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecurringInvoicePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\RecurringInvoice $recurringInvoice
     * @return mixed
     */
    public function view(User $user, RecurringInvoice $recurringInvoice)
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
        return $user->hasPermissionTo('recurringinvoicecontroller.store');
    }
}
