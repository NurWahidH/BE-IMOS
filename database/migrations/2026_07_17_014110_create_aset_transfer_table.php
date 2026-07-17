<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_transfer', function (Blueprint $table) {
            $table->id();$table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');$table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->onDelete('set null');
            
            // Karena merujuk ke tabel yang sama (lokasi), definisikan manual agar tidak bentrok
            $table->unsignedBigInteger('lokasi_tujuan_id')->nullable();$table->foreign('lokasi_tujuan_id')->references('id')->on('lokasi')->onDelete('set null');
            
            $table->text('catatan')->nullable();$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset_transfer');
    }
};