<?php

use App\Models\Order;
use App\Models\Product;
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
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity', false, true);
            $table->decimal('total_price', 10, 2)->storedAs('unit_price * quantity');
            $table->foreignUuidFor(Product::class)->constrained()->nullOnDelete();
            $table->foreignUuidFor(Order::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
