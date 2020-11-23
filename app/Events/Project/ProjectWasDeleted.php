<?php

namespace App\Events\Project;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class ProjectWasDeleted
{
    use SerializesModels;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * ProjectWasDeleted constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
