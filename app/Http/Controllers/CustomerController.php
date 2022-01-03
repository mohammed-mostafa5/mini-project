<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customerTransactions()
    {
        $transactions = auth()->user()->transactions()->with('payments')->get();

        return response()->json(compact('transactions'));
    }
}
