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
        Schema::table('hr_policy_settings', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('late_punishment');
            $table->string('signature')->nullable()->after('logo');
            $table->string('account_name')->nullable()->after('signature');
            $table->string('account_number')->nullable()->after('account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_policy_settings', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('signature');
            $table->dropColumn('account_name');
            $table->dropColumn('account_number');
        });
    }
};
