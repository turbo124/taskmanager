<?php

namespace App\Http\Controllers;

use App\CompanyLedger;
use App\Transformers\CompanyLedgerTransformer;
use Illuminate\Http\Request;

class CompanyLedgerController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        $company_ledger = CompanyLedger::whereAccountId(auth()->user()->account_user()->account_id)->orderBy(
            'id',
            'ASC'
        );

        return response()->json($company_ledger);
    }


}
