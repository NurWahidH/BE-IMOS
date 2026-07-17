<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriAset extends Model
{
    // Beri tahu Laravel nama tabel aslinya secara spesifik
    protected $table = 'kategori_aset';

    // Kolom apa saja yang boleh diisi lewat API
    protected $fillable = [
        'nama_kategori'
    ];
}