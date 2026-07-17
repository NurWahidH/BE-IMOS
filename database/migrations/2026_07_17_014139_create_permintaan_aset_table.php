<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_aset', function (Blueprint $table) {
            $table->id();$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->nullable()->constrained('aset')->onDelete('set null');$table->text('keperluan')->nullable();
            $table->string('status', 50)->nullable();$table->text('catatan')->nullable();
            
            // Relasi disetujui_oleh merujuk kembali ke tabel users
            $table->unsignedBigInteger('disetujui_oleh')->nullable();$table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamp('disetujui_pada')->nullable();$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_aset');
    }
};  