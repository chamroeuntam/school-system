<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add session column if it doesn't exist
        if (!Schema::hasColumn('attendances', 'session')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->enum('session', ['morning', 'evening'])->default('morning')->after('subject_id');
            });
        }

        // Update unique constraint to include session
        DB::statement('
            ALTER TABLE attendances
            DROP INDEX attendances_enrollment_id_subject_id_attendance_date_unique,
            ADD UNIQUE att_enr_subj_date_sess_uniq (enrollment_id, subject_id, attendance_date, session)
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE attendances
            DROP INDEX att_enr_subj_date_sess_uniq,
            ADD UNIQUE attendances_enrollment_id_subject_id_attendance_date_unique (enrollment_id, subject_id, attendance_date)
        ');

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('session');
        });
    }
};
