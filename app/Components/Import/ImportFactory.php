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
    public function loadImporter($type)
    {
        switch($type) {
            case 'customer'

            break;

           case 'deal'

            break;

           case 'lead'

            break;

            case 'invoice'

            break;
        }
    }
}
