<?php

namespace App\Services\Lead;

use App\Models\Lead;
use App\Repositories\LeadRepository;
use App\Services\ServiceBase;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class LeadService extends ServiceBase
{
    protected Lead $lead;

    /**
     * LeadService constructor.
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $config = [
            'email'   => $lead->account->getSetting('should_email_lead'),
            'archive' => $lead->account->getSetting('should_archive_lead')
        ];

        parent::__construct($lead);
        $this->lead = $lead;
    }

    /**
     * @return $this
     */
    public function convertLead(): Lead
    {
        $lead = (new ConvertLead($this->lead))->execute();

        // trigger
        $subject = trans('texts.lead_converted_subject');
        $body = trans('texts.lead_converted_body');
        $this->trigger($subject, $body, new LeadRepository(new Lead));

        return $lead;
    }

    /**
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($subject = '', $body = '', $template = 'lead')
    {
        return (new LeadEmail($this->lead, $subject, $body))->execute();
    }

    public function generatePdf()
    {
        return '';
    }

}
