<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@expense-tracker.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create demo wallets
        $cashWallet = $user->wallets()->create([
            'name' => 'Kas',
            'currency_code' => 'IDR',
            'opening_balance' => 500000,
        ]);

        $bankWallet = $user->wallets()->create([
            'name' => 'Bank BCA',
            'currency_code' => 'IDR',
            'opening_balance' => 2000000,
        ]);

        // Get some categories
        $expenseCategories = Category::where('type', 'expense')->whereNull('user_id')->get();
        $incomeCategories = Category::where('type', 'income')->whereNull('user_id')->get();

        // Create demo transactions for the last 3 months
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        // Income transactions
        for ($i = 0; $i < 6; $i++) {
            $user->transactions()->create([
                'wallet_id' => $bankWallet->id,
                'category_id' => $incomeCategories->random()->id,
                'type' => 'income',
                'amount' => rand(5000000, 8000000), // 5-8 million IDR
                'occurred_at' => $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate))),
                'note' => 'Gaji bulan ' . $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate)))->format('F'),
            ]);
        }

        // Expense transactions
        for ($i = 0; $i < 50; $i++) {
            $wallet = rand(0, 1) ? $cashWallet : $bankWallet;
            $category = $expenseCategories->random();
            
            $amount = match($category->name) {
                'Makanan & Minuman' => rand(15000, 150000),
                'Transportasi' => rand(10000, 100000),
                'Hiburan' => rand(50000, 300000),
                'Belanja' => rand(25000, 500000),
                'Kesehatan' => rand(50000, 1000000),
                'Tagihan' => rand(100000, 500000),
                default => rand(20000, 200000),
            };

            $merchants = [
                'Makanan & Minuman' => ['Warung Makan Sederhana', 'McDonald\'s', 'Starbucks', 'Indomaret', 'Alfamart'],
                'Transportasi' => ['Grab', 'Gojek', 'TransJakarta', 'Pertamina', 'Shell'],
                'Hiburan' => ['CGV Cinemas', 'Netflix', 'Spotify', 'Steam', 'Disney+ Hotstar'],
                'Belanja' => ['Shopee', 'Tokopedia', 'Uniqlo', 'H&M', 'Zara'],
                'Kesehatan' => ['Apotik Kimia Farma', 'Guardian', 'Rumah Sakit', 'Klinik'],
                'Pendidikan' => ['Udemy', 'Coursera', 'Gramedia', 'Toko Buku'],
                'Tagihan' => ['PLN', 'PDAM', 'Telkom', 'Indihome', 'XL'],
                'Rumah Tangga' => ['Indomaret', 'Alfamart', 'Carrefour', 'Hypermart'],
                'Komunikasi' => ['Telkomsel', 'XL', 'Indosat', 'Smartfren'],
                'Lain-lain' => ['Toko Serba Ada', 'Online Shop', 'Pasar'],
            ];

            $user->transactions()->create([
                'wallet_id' => $wallet->id,
                'category_id' => $category->id,
                'type' => 'expense',
                'amount' => $amount,
                'occurred_at' => $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate))),
                'merchant' => $merchants[$category->name][rand(0, count($merchants[$category->name]) - 1)] ?? null,
                'note' => 'Pengeluaran untuk ' . strtolower($category->name),
            ]);
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: demo@expense-tracker.com');
        $this->command->info('Password: password');
    }
}
