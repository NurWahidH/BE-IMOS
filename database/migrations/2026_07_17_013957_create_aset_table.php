<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {$table->id();
            $table->string('kode_barcode', 100)->unique()->nullable();$table->string('nama_aset', 255);
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_aset')->onDelete('set null');$table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');$table->date('tanggal_pembelian')->nullable();
            $table->decimal('harga_beli', 15, 2)->nullable();$table->integer('umur_ekonomis_tahun')->nullable();
            $table->decimal('nilai_residu', 15, 2)->nullable();$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};