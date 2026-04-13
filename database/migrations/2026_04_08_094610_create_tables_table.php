<?php

use App\Enums\TableStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number');
            $table->integer('capacity');

            $table->foreignId('restaurant_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('assigned_staff_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', TableStatus::values())->default(TableStatus::AVAILABLE->value);

            $table->unique(['restaurant_id', 'table_number']);

            $table->index(['restaurant_id', 'status']);           // dashboard + list views
            $table->index('assigned_staff_id');                   // find tables per waiter
            $table->index(['restaurant_id', 'assigned_staff_id']); // combined queries
            $table->index('table_number');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
