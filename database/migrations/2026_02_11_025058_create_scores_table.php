<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('scores', function(Blueprint $table){
      $table->id();
      $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
      $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
      $table->foreignId('term_id')->nullable()->constrained()->nullOnDelete();
      $table->decimal('score', 6, 2);
      $table->string('grade_letter',5)->nullable();
      $table->string('remark',255)->nullable();
      $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamps();
      $table->unique(['enrollment_id','subject_id','term_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('scores'); }
};
