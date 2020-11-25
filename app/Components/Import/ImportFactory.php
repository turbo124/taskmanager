?php


namespace App\Components\Import;


use App\Factory\InvoiceFactory;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Repositories\InvoiceRepository;

class ImportFactory
{
    public function loadImporter($type, User $user, Account $account)
    {
        switch($type) {
            case 'customer'
                return new CustomerImoporter($user, $account);
            break;

           case 'deal'
               return new DealImporter($user, $account);
            break;

           case 'lead'
               return new LeadImporter($user, $account);
            break;

            case 'invoice'
                return new InvoiceImporter($user, $account);
            break;
        }
    }
}
