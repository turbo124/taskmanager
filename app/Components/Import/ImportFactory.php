<?php


namespace App\Components\Import;


use App\Models\Account;
use App\Models\User;

class ImportFactory
{
    /**
     * @param $type
     * @param Account $account
     * @param User $user
     * @return CustomerImporter|DealImporter|InvoiceImporter|LeadImporter
     * @throws CsvImporterException
     */
    public function loadImporter($type, Account $account, User $user)
    {
        switch ($type) {
            case 'customer':
                return new CustomerImporter($account, $user);
                break;

            case 'deal':
                return new DealImporter($account, $user);
                break;

            case 'lead':
                return new LeadImporter($account, $user);
                break;

            case 'invoice':
                return new InvoiceImporter($account, $user);
                break;
        }
    }
}
