<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->orderByDesc('created_at')->get();
        return view('admin.transactions', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('user', 'details.game')->findOrFail($id);
        return view('admin.transaction_detail', compact('transaction'));
    }
}
