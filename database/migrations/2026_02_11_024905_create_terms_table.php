<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('terms', function(Blueprint $table){
      $table->id();
      $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
      $table->string('name',30); // Term 1, Term 2
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->timestamps();
      $table->unique(['academic_year_id','name']);
    });
  }
  public function down(): void { Schema::dropIfExists('terms'); }
};
