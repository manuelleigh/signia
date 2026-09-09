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
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->integer('balance_qpse')->default(0)->after('company_name');
            $table->integer('balance_native')->default(0)->after('balance_qpse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['balance_qpse', 'balance_native']);
            $table->integer('balance')->default(0);
        });
    }
};
