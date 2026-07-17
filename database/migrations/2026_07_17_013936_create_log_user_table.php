<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_user', function (Blueprint $table) {$table->id();
            // Menggunakan nama kolom idUser sesuai ERD lama Anda
            $table->unsignedBigInteger('idUser');
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_user');
    }
};