<?php

namespace App\Events\Project;

use App\Models\Project;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class ExpenseWasCreated
 * @package App\Events\RecurringInvoice
 */
class ProjectWasCreated
{
    use SerializesModels;
    use SendSubscription;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * ProjectWasCreated constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->send($project, get_class($this));
    }
}
