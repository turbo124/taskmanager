<?php

namespace App\Http\Controllers;

use App\Components\Import\ImportFactory;
use App\Repositories\BankAccountRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $importer = (new ImportFactory())->loadImporter(
                $request->input('import_type'),
                auth()->user()->account_user()->account,
                auth()->user()
            );

            $importer->setCsvFile($request->file('file')->getPathname());

            $importer->run();
        } catch (Exception $e) {
            $errors = $e->getMessage();

            echo $e->getMessage();
            die('test');
        }

        return response()->json($importer->getSuccess());
    }
}
