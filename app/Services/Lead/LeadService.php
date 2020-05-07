<?php

namespace App\Services\Lead;

use App\Lead;
use App\Services\ServiceBase;
use App\Repositories\LeadRepository;
use App\Lead;

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
        $config = [
            'email' => $lead->customer->getSetting('should_email_lead'),
            'archive' => $lead->customer->getSetting('should_archive_lead')
        ];

        parent::__construct($lead);
        $this->lead = $lead;
    }

    /**
     * @return $this
     */
    public function convertLead(): Lead
    {
       $lead = (new ConvertLead($this->lead))->run();
  
        // run actions
        $subject = trans('texts.lead_converted_subject');
        $body = trans('texts.lead_converted_body');
        $this->runTriggersForAction($subject, $body, new LeadRepository(new Lead));

        return $lead;
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
