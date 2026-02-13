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
        // Check if column exists before adding
        if (!Schema::hasColumn('scores', 'exam_type')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->enum('exam_type', ['month_1', 'month_2', 'month_3', 'semester_exam'])
                      ->nullable()
                      ->after('term_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            if (Schema::hasColumn('scores', 'exam_type')) {
                $table->dropColumn('exam_type');
            }
        });
    }
};
