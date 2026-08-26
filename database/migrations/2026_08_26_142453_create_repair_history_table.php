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
        Schema::create('repair_history', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_id');
            $table->integer('asset_id');
            $table->string('asset_tag');
            $table->string('user_name');
            $table->string('type');
            $table->string('repair_status');
            $table->text('description')->nullable();
            $table->string('return_outcome')->nullable();
            $table->text('previous_remarks')->nullable();
            $table->string('repair_status_after')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_history');
    }
};
