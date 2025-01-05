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
        Schema::create('gov_tax_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no');
            $table->string('tax_name');
            $table->decimal('percentage');
            $table->decimal('total');
            $table->timestamps();

            $table->index('batch_no', 'batch_no_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gov_tax_transactions');
    }
};
