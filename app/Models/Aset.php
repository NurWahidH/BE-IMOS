<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';

    protected $fillable = [
        'nama_aset', 
        'kategori_id',
        'kode_barcode',
        'kondisi',
        'lokasi_saat_ini_id',
        'nama_aset',
        'nilai_residu',
        'pic_saat_ini_id',
        'tanggal_pembelian',
        'umur_ekonomis_tahun',
        'harga_beli'
    ];
}