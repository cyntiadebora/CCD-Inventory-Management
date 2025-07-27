<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Kolom baru: id_number
            $table->string('id_number')->unique()->nullable(); 

            // Kolom role dan status
            $table->enum('role', ['admin', 'cabin_crew'])->default('cabin_crew');
            // Hanya active dan inactive untuk status
            $table->enum('status', ['active', 'inactive'])->default('inactive');

            $table->enum('gender', ['male', 'female']);
            $table->string('base');
            $table->date('join_date');
            $table->string('rank')->nullable();
            $table->unsignedTinyInteger('batch')->nullable();

            // Kolom foto
            $table->string('photo')->nullable(); // menyimpan nama file atau path gambar

            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};