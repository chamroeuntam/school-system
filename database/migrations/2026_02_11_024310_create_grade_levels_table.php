<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('grade_levels', function(Blueprint $table){
      $table->id();
      $table->string('name',10)->unique(); // 10,11,12
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('grade_levels'); }
};
