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
            $table->decimal('discount')->default(0.00);
            $table->decimal('total_tax')->default(0.00);
            $table->decimal('qty_sold')->default(0);
            $table->string('selling_code');
            $table->unsignedBigInteger('user_id')->nullable();
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
