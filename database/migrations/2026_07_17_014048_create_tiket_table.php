<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket', function (Blueprint $table) {
            $table->id();$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');$table->string('kondisi', 255)->nullable();
            $table->text('catatan')->nullable();$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket');
    }
};