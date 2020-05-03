<?php

namespace App\Services\Credit;

use App\Credit;
use App\Services\ServiceBase;
use Carbon\Carbon;

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
    public function sendEmail($contact = null, $subject, $body, $template = 'credit')
    {
        return (new CreditEmail($this->credit, $subject, $body, $template, $contact))->run();
    }

    public function calculateInvoiceTotals(): Credit
    {
        return $this->calculateTotals($this->credit);
    }
}
