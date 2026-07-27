<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser');
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
            $table->string('action'); 
            $table->string('target_table')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('description');
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_user');
    }
};