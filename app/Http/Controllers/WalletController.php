<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Auth::user()->wallets()
            ->where('is_archived', false)
            ->withCount('transactions')
            ->get();

        return view('wallets.index', compact('wallets'));
    }

    public function create()
    {
        return view('wallets.create');
    }

    public function store(StoreWalletRequest $request)
    {
        $wallet = Auth::user()->wallets()->create($request->validated());

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet created successfully.');
    }

    public function show(Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        $transactions = $wallet->transactions()
            ->with(['category', 'attachments'])
            ->latest('occurred_at')
            ->paginate(20);

        return view('wallets.show', compact('wallet', 'transactions'));
    }

    public function edit(Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        return view('wallets.edit', compact('wallet'));
    }

    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $wallet->update($request->validated());

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet updated successfully.');
    }

    public function destroy(Wallet $wallet)
    {
        $this->authorize('delete', $wallet);

        if ($wallet->transactions()->count() > 0) {
            $wallet->update(['is_archived' => true]);
            $message = 'Wallet archived successfully (has transactions).';
        } else {
            $wallet->delete();
            $message = 'Wallet deleted successfully.';
        }

        return redirect()->route('wallets.index')
            ->with('success', $message);
    }
}
