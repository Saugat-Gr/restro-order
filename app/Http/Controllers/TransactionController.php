<?php

namespace App\Http\Controllers;

use App\Enums\TransactionMethod;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'perPage' => 'nullable|integer|min:1|max:100',
            'transaction_method' => 'nullable|in:' . implode(',', TransactionMethod::values()),
            'searchTerm' => 'nullable|string|max:255',
        ]);

        $perPage = $request->input('perPage', 10);
        $transaction_method = $request->input('transaction_method');
        $searchTerm = $request->input('searchTerm');

        $transactions = Transaction::with('user')->when($transaction_method, function (Builder $q) use ($transaction_method) {
            $q->where('payment_method', $transaction_method);
        })->when($searchTerm, function (Builder $q) use ($searchTerm) {
            $q->where('transaction_number', 'LIKE', '%' . $searchTerm . '%');
        })->paginate($perPage)->withQueryString();


        return Inertia::render('Restaurant/Transactions/Index', [
            "app" => [
                "title" => "Transactions"
            ],
            'transactions' => $transactions,
            'transaction_methods' => TransactionMethod::values()
        ]);

    }



}
