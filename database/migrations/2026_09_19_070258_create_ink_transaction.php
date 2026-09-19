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
        Schema::create('ink_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ink_id')->constrained('ink_stock')->onDelete('cascade');
            $table->string('type');
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->string('received_by');
            $table->string('released_to');
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ink_transaction');
    }
};
