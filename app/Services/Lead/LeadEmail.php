<?php

namespace App\Services\Lead;

use App\Models\Lead;
use App\Jobs\Email\SendEmail;
use App\Traits\MakesInvoiceHtml;
use Illuminate\Support\Carbon;

class LeadEmail
{
    use MakesInvoiceHtml;

    private $lead;

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * LeadEmail constructor.
     * @param \App\Models\Lead $lead
     * @param string $subject
     * @param string $body
     */
    public function __construct(Lead $lead, $subject = '', $body = '')
    {
        $this->lead = $lead;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Builds the correct template to send
     * @param string $reminder_template The template name ie reminder1
     * @return array
     */
    public function execute()
    {
        $subject = strlen($this->subject) > 0 ? $this->subject : $this->lead->account->getSetting('email_subject_lead');
        $body = strlen($this->body) > 0 ? $this->body : $this->lead->account->getSetting('email_template_lead');

        SendEmail::dispatchNow($this->lead, $subject, $body, 'lead', $this->lead);
    }
}
