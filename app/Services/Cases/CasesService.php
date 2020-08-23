<?php

namespace App\Services\Cases;

use App\Models\Cases;
use App\Services\ServiceBase;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class CasesService extends ServiceBase
{
    /**
     * @var Cases
     */
    protected Cases $case;

    /**
     * CasesService constructor.
     * @param Cases $case
     */
    public function __construct(Cases $case)
    {
        $config = [
            'email'   => $case->account->getSetting('should_email_lead'),
            'archive' => $case->account->getSetting('should_archive_lead')
        ];

        parent::__construct($case);
        $this->case = $case;
    }

    /**
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject = '', $body = '', $template = 'deal')
    {
        return (new CaseEmail($this->case, $subject, $body))->execute();
    }

    /**
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        return (new GeneratePdf($this->case, $contact, $update))->execute();
    }

}
