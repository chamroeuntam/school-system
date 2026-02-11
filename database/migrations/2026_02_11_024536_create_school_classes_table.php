<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('school_classes', function(Blueprint $table){
      $table->id();
      $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
      $table->foreignId('grade_level_id')->constrained()->cascadeOnDelete();
      $table->foreignId('stream_id')->nullable()->constrained()->nullOnDelete(); // for 11/12
      $table->string('name',20); // 12A, 11A...
      $table->timestamps();
      $table->unique(['academic_year_id','name']);
    });
  }
  public function down(): void { Schema::dropIfExists('school_classes'); }
};
