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
        Schema::create('daily_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no');
            $table->date('transaction_date');
            $table->unsignedBigInteger('stock_id');
            $table->unsignedBigInteger('payment_type_id');
            $table->decimal('item_amount')->default(0.00);
            $table->decimal('amount_tendered')->default(0.00);
            $table->decimal('change_given')->default(0.00);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('qty_wholesale_sold')->default(0);
            $table->integer(column: 'qty_retail_sold')->default(0);
            $table->decimal(column: 'total_wholesale_retail_qty_sold')->storedAs('qty_wholesale_sold + qty_retail_sold');
            $table->decimal('total_wholesale_sold')->default(0.00);
            $table->decimal('total_retail_sold')->default(0.00);
            $table->decimal('total_wholesale_retail_sold')->storedAs('total_wholesale_sold + total_retail_sold');
            $table->integer('qty_box_sold')->default(0);
            $table->integer(column: 'qty_kg_sold')->default(0);
            $table->decimal('total_qty_box_sold')->default(0.00);
            $table->decimal('total_qty_kg_sold')->default(0.00);

            $table->timestamps();
            $table->index('batch_no', 'batch_no_index');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_transactions');
    }
};
