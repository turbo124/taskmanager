<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Lead $lead
     * @return mixed
     */
    public function view(User $user, Lead $lead)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $entity->user_id === $user->id || $user->hasPermissionTo('leadcontroller.show') || (!empty($entity->assigned_to) && $entity->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function update(User $user, Lead $lead)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $entity->user_id === $user->id || $user->hasPermissionTo('leadcontroller.update') || (!empty($entity->assigned_to) && $entity->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invoice $invoice
     * @return mixed
     */
    public function delete(User $user, Lead $lead)
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
            )->is_owner || $user->hasPermissionTo('leadcontroller.store');
    }
}
