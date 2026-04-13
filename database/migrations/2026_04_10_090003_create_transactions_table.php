<?php

use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();

            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', TransactionStatus::values());
            $table->enum('status', TransactionStatus::values());

            $table->timestamps();

            $table->index(['restaurant_id', 'created_at']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};