<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('type')->nullable();
            $table->decimal('opening_stock')->default(0.00);
            $table->decimal('item_cost_price')->default(0.00);
            $table->integer('item_unit_code')->nullable();
            $table->decimal('sp_wholesale')->default(0.00);
            $table->decimal('sp_retail')->default(0.00);
            $table->decimal('sp_box')->default(0.00);
            $table->decimal('sp_kg')->default(0.00);
            $table->decimal('item_qty_remaining')->default(0.00);
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('fda_no')->nullable();
            $table->string('item_no', 20)->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('additions')->default(0.00);
            $table->decimal('cp_box')->storedAs('item_cost_price + 0');
            $table->decimal('cp_kg')->storedAs(('cp_box / 10'));
            $table->decimal('total_stock')->storedAs('opening_stock + additions');
            $table->decimal('total')->storedAs('total_stock * item_cost_price');
            $table->timestamps();

            $table->index(['item_no', 'category_id'], 'item_no_category_index');
            $table->index('item', 'item_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
