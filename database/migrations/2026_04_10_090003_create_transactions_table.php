<?php

use App\Enums\TransactionMethod;
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

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', TransactionMethod::values());
            $table->enum('status', TransactionStatus::values());

            $table->timestamp('paid_at')->nullable();

            $table->uuid('transaction_number')->unique();

            $table->timestamps();
            $table->index(['restaurant_id', 'status']);
            $table->index(['restaurant_id', 'paid_at']);           
            $table->index(['restaurant_id', 'payment_method']);
            $table->index('processed_by');
            $table->index('transaction_number');

            // Composite for fast lookups
            $table->index(['restaurant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};