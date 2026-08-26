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
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('designation')->nullable();
            $table->string('inclusion')->nullable();
            $table->string('prepared_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'remarks',
                'designation',
                'inclusion',
                'prepared_by'
            ]);
        });
    }
};
