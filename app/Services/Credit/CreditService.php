<?php

namespace App\Services\Credit;

use App\Events\Credit\CreditWasEmailed;
use App\Models\Credit;
use App\Services\ServiceBase;

class CreditService extends ServiceBase
{
    protected $credit;

    public function __construct(Credit $credit)
    {
        parent::__construct($credit);
        $this->credit = $credit;
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        $get_credit_pdf = new GeneratePdf($this->credit, $contact, $update);

        return $get_credit_pdf->execute();
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

        return $this->credit;
    }

    public function calculateInvoiceTotals(): Credit
    {
        return $this->calculateTotals($this->credit);
    }
}
