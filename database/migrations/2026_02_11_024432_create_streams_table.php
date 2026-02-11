<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('streams', function(Blueprint $table){
      $table->id();
      $table->string('code',30)->unique(); // science, social_science
      $table->string('name',100);
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('streams'); }
};
