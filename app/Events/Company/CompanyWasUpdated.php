<?php

namespace App\Events\Company;

use App\Models\Cases;
use App\Models\Company;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class CompanyWasUpdated
{
    use SerializesModels;

    /**
     * @var Company
     */
    public Company $company;

    /**
     * CompanyWasUpdated constructor.
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }
}
