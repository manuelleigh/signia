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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('sol_user')->nullable()->after('engine_type');
            $table->string('sol_pass')->nullable()->after('sol_user');
            $table->string('qpse_external_id')->nullable()->after('sol_pass');
            $table->string('qpse_username')->nullable()->after('qpse_external_id');
            $table->string('qpse_password')->nullable()->after('qpse_username');
            $table->string('qpse_plan_type', 2)->default('01')->after('qpse_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'sol_user',
                'sol_pass',
                'qpse_external_id',
                'qpse_username',
                'qpse_password',
                'qpse_plan_type'
            ]);
        });
    }
};
