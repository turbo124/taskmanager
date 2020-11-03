<?php

namespace App\Events\Project;

use App\Models\Cases;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class ProjectWasArchived
{
    use SerializesModels;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * ProjectWasArchived constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
