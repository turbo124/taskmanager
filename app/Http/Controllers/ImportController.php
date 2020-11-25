<?php

namespace App\Http\Controllers;

use App\Components\Import\InvoiceImporter;
use App\Components\OFX\OFXImport;
use App\Factory\BankAccountFactory;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\Expense;
use App\Repositories\BankAccountRepository;
use App\Repositories\CompanyContactRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ExpenseRepository;
use App\Requests\BankAccount\CreateBankAccountRequest;
use App\Requests\BankAccount\UpdateBankAccountRequest;
use App\Requests\SearchRequest;
use App\Search\BankAccountSearch;
use App\Transformations\BankAccountTransformable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ImportController
 * @package App\Http\Controllers
 */
class ImportController extends Controller
{
    /**
     * ImportController constructor.
     */
    public function __construct(BankAccountRepository $bank_account_repository)
    {
       
    }

    /**
     * @return JsonResponse
     */
    public function import(Request $request)
    {

        try {
            $importer = (new InvoiceImporter(auth()->user()->account_user()->account, auth()->user()))->setCsvFile(public_path('storage/testimport.csv'));
            $importer->run();
        } catch (Exception $e) {
            $errors = $e->getMessage();

            echo $e->getMessage();
            die('test');
        }

        return response()->json($bank_accounts);
    }
}
