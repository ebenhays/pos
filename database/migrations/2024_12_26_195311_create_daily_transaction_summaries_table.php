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
        Schema::create('daily_transaction_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->unsignedBigInteger('stock_id');
            $table->decimal('total_wholesale_sales')->default(0.00);
            $table->decimal('total_retail_sales')->default(0.00);
            $table->decimal('total_wholesale_retail_sales')->storedAs('total_wholesale_sales + total_retail_sales');
            $table->decimal('COS_wholesale')->default(0.00);
            $table->decimal('COS_retail')->default(0.00);
            $table->decimal('total_COS_retail_wholesale')->storedAs('COS_wholesale + COS_retail');
            $table->decimal('total_profit_wholesale')->storedAs('total_wholesale_sales - COS_wholesale');
            $table->decimal('total_profit_retail')->storedAs('total_retail_sales - COS_retail');
            $table->decimal('total_profit_wholesale_retail')->storedAs('total_profit_wholesale + total_profit_retail');
            $table->decimal('other_income_wholesale_retail')->default(0.00);
            $table->decimal('gross_profit_wholesale_retail')->storedAs('total_profit_wholesale_retail + other_income_wholesale_retail');
            $table->decimal('expenses_wholesale_retail')->default(0.00);
            $table->decimal('net_profit_wholesale_retail')->storedAs('gross_profit_wholesale_retail - expenses_wholesale_retail');

            $table->decimal('total_sales_in_box')->default(0.00);
            $table->decimal('total_sales_in_kilos')->default(0.00);
            $table->decimal('total_sales_in_box_kilos')->storedAs('total_sales_in_box + total_sales_in_kilos');
            $table->decimal('COS_box')->default(0.00);
            $table->decimal('COS_kilos')->default(0.00);
            $table->decimal('total_COS_box_kilos')->storedAs('COS_box + COS_kilos');
            $table->decimal('total_profit_in_box')->storedAs('total_sales_in_box - COS_box');
            $table->decimal('total_profit_in_kilos')->storedAs('total_sales_in_kilos - COS_kilos');
            $table->decimal('total_profit_box_kilos')->storedAs('total_profit_in_box + total_profit_in_kilos');
            $table->decimal('other_income_box_kilos')->default(0.00);
            $table->decimal('gross_profilt_box_kilos')->storedAs('total_profit_in_box + other_income_box_kilos');
            $table->decimal('expenses_box_kilos')->default(0.00);
            $table->decimal('net_profit_box_kilos')->storedAs('gross_profilt_box_kilos - expenses_box_kilos');

            $table->timestamps();

            $table->index('stock_id', 'stock_id_index');
            $table->index('transaction_date', 'transaction_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_transaction_summaries');
    }
};
