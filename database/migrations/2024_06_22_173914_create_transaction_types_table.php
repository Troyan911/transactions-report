<?php

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
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->unique();
            $table->string('debit', 20);
            $table->string('credit', 20);
            $table->string('cash_operation', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};
