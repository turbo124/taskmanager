<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
        $transaction = Transaction::whereAccountId(auth()->user()->account_user()->account_id)->orderBy(
            'id',
            'ASC'
        );

        return response()->json($transaction);
    }


}
