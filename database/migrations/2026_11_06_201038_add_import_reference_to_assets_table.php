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
    Schema::table('assets', function (Blueprint $table) {
        $table->string('import_reference', 64)
            ->nullable()
            ->unique();
    });
}

public function down(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropUnique(['import_reference']);
        $table->dropColumn('import_reference');
    });
}
};
