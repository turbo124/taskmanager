<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function view(User $user, Invoice $invoice)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $entity->user_id === $user->id || $user->hasPermissionTo('invoicecontroller.show') || (!empty($entity->assigned_to) && $entity->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function update(User $user, Invoice $invoice)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $entity->user_id === $user->id || $user->hasPermissionTo('invoicecontroller.update') || (!empty($entity->assigned_to) && $entity->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function delete(User $user, Invoice $invoice)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $entity->user_id === $user->id || (!empty($entity->assigned_to) && $entity->assigned_to === $user->id);
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
            )->is_owner || $user->hasPermissionTo('invoicecontroller.store');
    }
}
