<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisMonthStart = Carbon::now()->startOfMonth();

        // Today's totals
        $todayStats = $user->transactions()
            ->whereDate('occurred_at', $today)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $todayIncome = $todayStats['income'] ?? 0;
        $todayExpense = $todayStats['expense'] ?? 0;

        // This week's totals
        $weekStats = $user->transactions()
            ->where('occurred_at', '>=', $thisWeekStart)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $weekIncome = $weekStats['income'] ?? 0;
        $weekExpense = $weekStats['expense'] ?? 0;

        // This month's totals
        $monthStats = $user->transactions()
            ->where('occurred_at', '>=', $thisMonthStart)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $monthIncome = $monthStats['income'] ?? 0;
        $monthExpense = $monthStats['expense'] ?? 0;

        // Top categories this month
        $topCategories = $user->transactions()
            ->where('occurred_at', '>=', $thisMonthStart)
            ->where('transactions.type', 'expense')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.color, SUM(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Recent transactions
        $recentTransactions = $user->transactions()
            ->with(['wallet', 'category'])
            ->latest('occurred_at')
            ->limit(10)
            ->get();

        // Wallet balances
        $wallets = $user->wallets()
            ->where('is_archived', false)
            ->get()
            ->map(function ($wallet) {
                return [
                    'id' => $wallet->id,
                    'name' => $wallet->name,
                    'balance' => $wallet->balance,
                    'currency' => $wallet->currency_code,
                ];
            });

        // Monthly chart data for the last 6 months
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyStats = $user->transactions()
                ->whereBetween('occurred_at', [$monthStart, $monthEnd])
                ->selectRaw('type, SUM(amount) as total')
                ->groupBy('type')
                ->pluck('total', 'type');

            $chartData[] = [
                'month' => $month->format('M Y'),
                'income' => $monthlyStats['income'] ?? 0,
                'expense' => $monthlyStats['expense'] ?? 0,
            ];
        }

        return view('dashboard', compact(
            'todayIncome', 'todayExpense',
            'weekIncome', 'weekExpense',
            'monthIncome', 'monthExpense',
            'topCategories', 'recentTransactions',
            'wallets', 'chartData'
        ));
    }
}
