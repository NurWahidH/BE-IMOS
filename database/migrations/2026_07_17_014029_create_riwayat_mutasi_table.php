<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_mutasi', function (Blueprint $table) {$table->id();
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');$table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');$table->timestamp('time_stamp')->useCurrent();
            $table->text('catatan')->nullable();$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_mutasi');
    }
};