<?php

namespace App\Http\Controllers;

use App\Components\Import\ImportFactory;
use App\Components\Import\JsonConverter;
use App\Repositories\BankAccountRepository;
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
        $mappings = json_decode($request->input('mappings'), true);

        try {
            $importer = (new ImportFactory())->loadImporter(
                $request->input('import_type'),
                auth()->user()->account_user()->account,
                auth()->user()
            );

            $file_path = public_path('uploads/' . $request->input('hash'));

            $is_json = !empty($request->input('file_type')) && $request->input('file_type') === 'json';

            if ($is_json) {
                $jsonString = file_get_contents($file_path);
                $file_path = $this->convert($jsonString);
            }

            $importer->setCsvFile($file_path);

            $importer->setColumnMappings($mappings);

            $importer->run(true);

            Storage::delete($file_path);
        } catch (Exception $e) {
            $errors = $e->getMessage();

            echo $e->getMessage();
            echo $e->getTraceAsString();
            die('test');
        }

        return response()->json($importer->getSuccess());
    }

    public function importPreview(Request $request)
    {
        try {
            $request->validate(
                [
                    'file' => 'required|mimes:csv,txt|max:2048'
                ]
            );

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

            $fileName = time() . '_' . $request->file->getClientOriginalName();

            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

            $importer->setCsvFile($file_path);

            $headers = $importer->getHeaders();

            $data = [
                'headers'  => $headers,
                'columns'  => $importer->getImportColumns(),
                'filename' => $fileName
            ];
        } catch (Exception $e) {
            $errors = $e->getMessage();

            echo $e->getTraceAsString();
            die('test');
        }

        return response()->json($data);
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
