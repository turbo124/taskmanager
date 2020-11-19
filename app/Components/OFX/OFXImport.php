<?php


namespace App\Components\OFX;


use App\Models\Company;
use App\Transformations\OfxImportTransformable;
use OfxParser\Entities\Transaction;

class OFXImport
{
    use OfxImportTransformable;

    private array $companies;
    
    public function __construct()
    {
        $this->companies = array_change_key_case(Company::get()->keyBy('name')->toArray(), CASE_LOWER);
    }

    public function import(array $transactions, array $selected_transactions)
    {
        foreach ($transactions as $transaction) {
            if (!in_array($transaction['uniqueId'], $selected_transactions)) {
                continue;
            }
        }
    }

    public function preview($file)
    {
        $transactions = collect($this->buildImport($file, true))->where('amount', '<', 0);

        $transactions = $transactions->map(
            function (Transaction $transaction) {
                return $this->transform($transaction);
            }
        )->all();

        return array_values($transactions);
    }

    private function buildImport($file, $is_preview = false)
    {
        $ofxParser = new \OfxParser\Parser();
        $ofx = $ofxParser->loadFromFile($file);

        $bankAccount = reset($ofx->bankAccounts);

// Get the statement start and end dates
        $startDate = $bankAccount->statement->startDate;
        $endDate = $bankAccount->statement->endDate;

// Get the statement transactions for the account
        $transactions = $bankAccount->statement->transactions;

        if ($is_preview) {
            return $transactions;
        }
    }
}