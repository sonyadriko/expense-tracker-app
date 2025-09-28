<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->transactions()
            ->with(['wallet', 'category', 'attachments'])
            ->latest('occurred_at');

        if ($request->filled('from') && $request->filled('to')) {
            $query->byDateRange($request->from, $request->to);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('wallet_id')) {
            $query->byWallet($request->wallet_id);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('note', 'like', '%' . $request->search . '%')
                  ->orWhere('merchant', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        $wallets = Auth::user()->wallets()->where('is_archived', false)->get();
        $categories = Category::forUser(Auth::id())->where('is_archived', false)->get();

        return view('transactions.index', compact('transactions', 'wallets', 'categories'));
    }

    public function create()
    {
        $wallets = Auth::user()->wallets()->where('is_archived', false)->get();
        $categories = Category::forUser(Auth::id())->where('is_archived', false)->get();

        return view('transactions.create', compact('wallets', 'categories'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = Auth::user()->transactions()->create($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['wallet', 'category', 'attachments']);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $wallets = Auth::user()->wallets()->where('is_archived', false)->get();
        $categories = Category::forUser(Auth::id())->where('is_archived', false)->get();

        return view('transactions.edit', compact('transaction', 'wallets', 'categories'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
