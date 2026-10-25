
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('assignments', 'prepared_by')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropColumn('prepared_by');
            });
        }

        if (!Schema::hasColumn('assignments', 'preparedby_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->foreignId('preparedby_id')
                    ->constrained('prepared_by_options');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('assignments', 'preparedby_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropForeign(['preparedby_id']);
                $table->dropColumn('preparedby_id');
            });
        }

        if (!Schema::hasColumn('assignments', 'prepared_by')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->string('prepared_by');
            });
        }
    }
};
