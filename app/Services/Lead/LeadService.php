<?php

namespace App\Services\Lead;

use App\Lead;
use App\Services\ServiceBase;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class LeadService extends ServiceBase
{
    protected $lead;

    /**
     * LeadService constructor.
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return $this
     */
    public function convertLead()
    {
        $convert_lead = new ConvertLead($this->lead);

        $this->lead = $convert_lead->run();

        return $this;
    }

    /**
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($subject = '', $body = '', $template = 'lead')
    {

        return (new LeadEmail($this->lead, $subject, $body))->run();
    }

    public function getPdf()
    {
        return '';
    }

}
