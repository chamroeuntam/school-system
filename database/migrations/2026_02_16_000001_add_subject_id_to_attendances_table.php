<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // If column exists from previous failed attempt, clean it up
        if (Schema::hasColumn('attendances', 'subject_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                try {
                    $table->dropForeign(['subject_id']);
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
                $table->dropColumn('subject_id');
            });
        }

        // Use raw SQL to add column and modify constraints in one step
        DB::statement('
            ALTER TABLE attendances
            DROP INDEX attendances_enrollment_id_attendance_date_unique,
            ADD COLUMN subject_id BIGINT UNSIGNED NULL AFTER enrollment_id,
            ADD CONSTRAINT attendances_subject_id_foreign FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL,
            ADD UNIQUE attendances_enrollment_id_subject_id_attendance_date_unique (enrollment_id, subject_id, attendance_date)
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE attendances
            DROP INDEX attendances_enrollment_id_subject_id_attendance_date_unique,
            DROP FOREIGN KEY attendances_subject_id_foreign,
            DROP COLUMN subject_id,
            ADD UNIQUE attendances_enrollment_id_attendance_date_unique (enrollment_id, attendance_date)
        ');
    }
};
