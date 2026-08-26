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
        Schema::table('hardware_specs', function (Blueprint $table) {
            $table->string('gpu')->nullable();
            $table->string('power_supply')->nullable();
            $table->string('peripherals')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hardware_specs', function (Blueprint $table) {
            //
        });
    }
};
