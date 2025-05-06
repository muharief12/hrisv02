<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Livewire\after;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            $table->foreignId('attendance_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->after('id');
            $table->string('task')->after('attendance_id');
            $table->boolean('status')->default(false)->after('task');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            $table->dropColumn('attendance_id');
            $table->dropColumn('task');
            $table->dropColumn('status');
        });
    }
};
