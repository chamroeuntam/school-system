<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('subject_rules', function(Blueprint $table){
      $table->id();
      $table->foreignId('grade_level_id')->constrained()->cascadeOnDelete();
      $table->foreignId('stream_id')->constrained()->cascadeOnDelete();
      $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
      $table->unsignedInteger('max_score');
      $table->timestamps();
      $table->unique(['grade_level_id','stream_id','subject_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('subject_rules'); }
};
