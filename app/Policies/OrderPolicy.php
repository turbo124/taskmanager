<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $order->user_id === $user->id || $user->hasPermissionTo('ordercontroller.show') || (!empty($order->assigned_to) && $order->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $order->user_id === $user->id || $user->hasPermissionTo('ordercontroller.update') || (!empty($order->assigned_to) && $order->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $order->user_id === $user->id || $user->hasPermissionTo('ordercontroller.destroy') || (!empty($order->assigned_to) && $order->assigned_to === $user->id);
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
            )->is_owner || $user->hasPermissionTo('ordercontroller.store');
    }
}
