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
        Schema::create('ink_stock', function (Blueprint $table) {
            $table->id();
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->string('color')->nullable();
            $table->string('stock')->nullable();
            $table->integer('reorder_level')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ink_stock');
    }
};
