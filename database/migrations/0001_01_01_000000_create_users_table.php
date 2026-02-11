<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // Login fields
            $table->string('phone', 20)->unique();             // primary login
            $table->string('telegram_chat_id', 50)->nullable()->unique();

            // Optional fields
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Auth
            $table->string('password');

            // Roles
            $table->enum('role', ['admin','teacher','student','parent'])->default('student');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
