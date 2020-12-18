<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $project->user_id === $user->id || $user->hasPermissionTo('projectcontroller.show') || (!empty($project->assigned_to) && $project->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param Project $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $project->user_id === $user->id || $user->hasPermissionTo('projectcontroller.update') || (!empty($project->assigned_to) && $project->assigned_to === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $user->account_user()->is_admin || $user->account_user(
            )->is_owner || $project->user_id === $user->id || $user->hasPermissionTo('projectcontroller.destroy') || (!empty($project->assigned_to) && $project->assigned_to === $user->id);
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
            )->is_owner || $user->hasPermissionTo('projectcontroller.store');
    }
}
