<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class KategoriAset extends Model
{
    use LogsActivity;

    // Beri tahu Laravel nama tabel aslinya secara spesifik
    protected $table = 'kategori_aset';

    // Kolom apa saja yang boleh diisi lewat API
    protected $fillable = [
        'nama_kategori'
    ];
}