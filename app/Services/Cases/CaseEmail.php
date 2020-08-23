<?php

namespace App\Services\Cases;

use App\Jobs\Email\SendEmail;
use App\Models\Cases;
use App\Traits\MakesInvoiceHtml;

class CaseEmail
{
    use MakesInvoiceHtml;

    private Cases $case;

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * CaseEmail constructor.
     * @param Cases $case
     * @param string $subject
     * @param string $body
     */
    public function __construct(Cases $case, $subject = '', $body = '')
    {
        $this->case = $case;
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
        $subject = strlen($this->subject) > 0 ? $this->subject : $this->case->account->getSetting('email_subject_case');
        $body = strlen($this->body) > 0 ? $this->body : $this->case->account->getSetting('email_template_case');

        SendEmail::dispatchNow($this->case, $subject, $body, 'case', $this->case->customer->contacts->first());
    }
}
