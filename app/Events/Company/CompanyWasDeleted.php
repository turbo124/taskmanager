<?php

namespace App\Events\Company;

use App\Models\Company;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasMarkedSent.
 */
class CompanyWasDeleted
{
    use SerializesModels;

    /**
     * @var Company
     */
    public Company $company;

    /**
     * CompanyWasDeleted constructor.
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }
}
