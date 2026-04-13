<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2);
            $table->enum('status', Status::values())->default(Status::ACTIVE->value);
            $table->boolean('is_in_stock')->default(true);
            $table->foreignId('restaurant_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('menu_item_category_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['restaurant_id', 'status', 'is_in_stock']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
