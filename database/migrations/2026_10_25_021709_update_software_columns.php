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
        Schema::table('software', function (Blueprint $table) {
            $table->string('operating_system')->nullable()->change();
            $table->string('product_key_os')->nullable()->change();
            $table->string('product_key_other')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software', function (Blueprint $table) {
            $table->string('operating_system')->nullable(false)->change();
            $table->string('product_key_os')->nullable(false)->change();
            $table->string('product_key_other')->nullable(false)->change();
        });
    }
};
