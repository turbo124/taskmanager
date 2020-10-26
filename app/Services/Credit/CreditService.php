<?php

namespace App\Services\Credit;

use App\Components\Pdf\InvoicePdf;
use App\Jobs\Pdf\CreatePdf;
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
        if (!$contact) {
            $contact = $this->credit->customer->primary_contact()->first();
        }

        return CreatePdf::dispatchNow((new InvoicePdf($this->credit)), $this->credit, $contact, $update);
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
