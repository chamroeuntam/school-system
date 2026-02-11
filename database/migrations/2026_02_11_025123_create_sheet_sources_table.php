<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('sheet_sources', function(Blueprint $table){
      $table->id();
      $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
      $table->enum('type',['attendance','score']);
      $table->string('sheet_id');
      $table->string('tab_name',100);

      $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
      $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
      $table->foreignId('term_id')->nullable()->constrained()->nullOnDelete();

      $table->json('column_map')->nullable(); // optional mapping
      $table->boolean('is_active')->default(true);
      $table->timestamp('last_synced_at')->nullable();
      $table->timestamps();

      $table->unique(['type','sheet_id','tab_name','school_class_id','subject_id','term_id'],'uq_sheet_source');
    });
  }
  public function down(): void { Schema::dropIfExists('sheet_sources'); }
};
