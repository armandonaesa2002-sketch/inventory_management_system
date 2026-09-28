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
        Schema::table('ink_transactions', function (Blueprint $table) {
            $table->string('received_by')->nullable()->change();
            $table->string('released_to')->nullable()->change();
            $table->string('remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ink_transactions', function (Blueprint $table) {
            $table->string('received_by')->nullable(false)->change();
            $table->string('released_to')->nullable(false)->change();
            $table->string('remarks')->nullable(false)->change();
        });
    }
};
