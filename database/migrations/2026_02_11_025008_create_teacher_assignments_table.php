<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('teacher_assignments', function(Blueprint $table){
      $table->id();
      $table->foreignId('teacher_user_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
      $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
      $table->foreignId('term_id')->nullable()->constrained()->nullOnDelete();
      $table->timestamps();
      $table->unique(['teacher_user_id','school_class_id','subject_id','term_id'],'uq_teacher_assign');
    });
  }
  public function down(): void { Schema::dropIfExists('teacher_assignments'); }
};
