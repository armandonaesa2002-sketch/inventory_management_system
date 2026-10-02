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
        Schema::table('device_types', function (Blueprint $table) {
            $table->boolean('has_basic')->default(true);
            $table->boolean('has_hardware')->default(false);
            $table->boolean('has_purchase')->default(false);
            $table->boolean('has_license_notes')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_types', function (Blueprint $table) {
            $table->dropColumn([
                'has_basic',
                'has_hardware',
                'has_purchase',
                'has_license_notes'
            ]);
        });
    }
};
