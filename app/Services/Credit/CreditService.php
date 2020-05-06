<?php

namespace App\Services\Credit;

use App\Credit;
use App\Services\ServiceBase;
use Carbon\Carbon;
use App\Events\Credit\CreditWasEmailed;

class CreditService extends ServiceBase
{
    protected $credit;

    public function __construct(Credit $credit)
    {
        parent::__construct($credit);
        $this->credit = $credit;
    }

    public function getPdf($contact)
    {
        $get_credit_pdf = new GetPdf($this->credit, $contact);

        return $get_credit_pdf->run();
    }

    /**
     * @param null $contact
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject, $body, $template = 'credit'): ?Credit
    {
        if (!$this->sendInvitationEmails($subject, $body, $template, $contact)) {
            return null;
        }

        event(new CreditWasEmailed($this->credit->invitations->first()));
        return $this->credit;
    }

    public function calculateInvoiceTotals(): Credit
    {
        return $this->calculateTotals($this->credit);
    }
}
