<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->enum('type', ['expense', 'income']);
            $table->decimal('amount', 15, 2);
            $table->date('occurred_at');
            $table->string('merchant')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'occurred_at']);
            $table->index(['user_id', 'category_id', 'occurred_at']);
            $table->index(['user_id', 'wallet_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
