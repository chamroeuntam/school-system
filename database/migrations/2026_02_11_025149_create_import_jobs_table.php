<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('import_jobs', function(Blueprint $table){
      $table->id();
      $table->foreignId('sheet_source_id')->constrained()->cascadeOnDelete();
      $table->foreignId('run_by')->constrained('users')->cascadeOnDelete();
      $table->enum('status',['running','success','failed'])->default('running');
      $table->unsignedInteger('total_rows')->default(0);
      $table->unsignedInteger('success_rows')->default(0);
      $table->unsignedInteger('failed_rows')->default(0);
      $table->text('message')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('import_jobs'); }
};
