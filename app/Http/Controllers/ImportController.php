<?php

namespace App\Http\Controllers;

use App\Components\Import\ImportFactory;
use App\Components\Import\JsonConverter;
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
    use JsonConverter;

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

            $file_path = $request->file('file')->getPathname();

            $is_json = !empty($request->input('file_type')) && $request->input('file_type') === 'json';

            if ($is_json) {
                $jsonString = file_get_contents($file_path);
                $file_path = $this->convert($jsonString);
            }

            $importer->setCsvFile($file_path);

            $importer->run(true);
        } catch (Exception $e) {
            $errors = $e->getMessage();

            echo $e->getMessage();
            die('test');
        }

        return response()->json($importer->getSuccess());
    }

    public function importPreview() 
    {
        try {
            $importer = (new ImportFactory())->loadImporter(
                $request->input('import_type'),
                auth()->user()->account_user()->account,
                auth()->user()
            );

            $file_path = $request->file('file')->getPathname();

            $is_json = !empty($request->input('file_type')) && $request->input('file_type') === 'json';

            if ($is_json) {
                $jsonString = file_get_contents($file_path);
                $file_path = $this->convert($jsonString);
            }
 
            $key = Str::random(32);
            Cache::put($key, file_get_contents($file_path), 60);

            $importer->setCsvFile($file_path);

            $headers = $importer->getHeaders();
   
            $data = [
                'headers' => $headers,
                'columns' => $importer->getImportColumns()
                'key' => $key
            ];

        } catch (Exception $e) {
            $errors = $e->getMessage();

            echo $e->getMessage();
            die('test');
        }

        return response()->json($data);
    }

    private function mapColumns(Request $request)
    {
        $mappings = $request->input('mappings');

        $new_array = [];

        foreach($data as $index => $items) {
            foreach($items as $key => $value) {

                $new_array[$index][$renameMap[$key]] = $value;
            }
        }

        return $new_array;
    }

    public function export(Request $request)
    {
        $objImporter = (new ImportFactory())->loadImporter(
            $request->input('export_type'),
            auth()->user()->account_user()->account,
            auth()->user()
        );

        $objImporter->export();

        return response()->json(['data' => $objImporter->getContent()]);
    }
}
