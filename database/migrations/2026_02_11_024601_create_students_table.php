<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('students', function(Blueprint $table){
      $table->id();
      $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete(); // student login
      $table->string('student_code',50)->unique(); // teacher input
      $table->string('full_name',150);
      $table->enum('gender',['M','F','O'])->nullable();
      $table->date('dob')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('students'); }
};
