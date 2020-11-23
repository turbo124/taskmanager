<?php

namespace App\Events\Project;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class ProjectWasUpdated
{
    use SerializesModels;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * ProjectWasUpdated constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
