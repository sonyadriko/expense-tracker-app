<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Policies\CategoryPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\WalletPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Wallet::class => WalletPolicy::class,
        Category::class => CategoryPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
