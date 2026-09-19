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
            $table->dropForeign('ink_transaction_ink_id_foreign');

            $table->renameColumn('ink_id', 'ink_stock_id');
        });

        Schema::table('ink_transactions', function (Blueprint $table) {
            $table->foreign('ink_stock_id')
                ->references('id')
                ->on('ink_stocks')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ink_transactions', function (Blueprint $table) {
            $table->dropForeign('ink_transaction_ink_id_foreign');

            $table->renameColumn('ink_stock_id', 'ink_id');
        });

        Schema::table('ink_transactions', function (Blueprint $table) {
            $table->foreign('ink_id')
                ->references('id')
                ->on('ink_stocks')
                ->cascadeOnDelete();
        });
    }
};
