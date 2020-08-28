<?php

namespace App\Services\Deal;

use App\Models\Deal;
use App\Services\ServiceBase;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class DealService extends ServiceBase
{
    protected Deal $deal;

    /**
     * DealService constructor.
     * @param Deal $deal
     */
    public function __construct(Deal $deal)
    {
        $config = [
            'email'   => $deal->account->getSetting('should_email_lead'),
            'archive' => $deal->account->getSetting('should_archive_lead')
        ];

        parent::__construct($deal);
        $this->deal = $deal;
    }

    /**
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject = '', $body = '', $template = 'deal')
    {
        return (new DealEmail($this->deal, $subject, $body))->execute();
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        return (new GeneratePdf($this->deal, $contact, $update))->execute();
    }

}