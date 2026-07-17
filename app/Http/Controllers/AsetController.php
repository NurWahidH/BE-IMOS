<?php

namespace App\Http\Controllers;

use App\Models\Aset; 
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index() 
    {
        // Mengambil seluruh data dari tabel 'aset'
        $dataAset = Aset::all();
        
        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => $dataAset
        ]);
        }
    public function store (Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string',
            'kode_barcode' => 'required|string',
            'kategori_id'=> 'required|integer',
            'lokasi_saat_ini_id' => 'required|integer',
            'nilai_residu' => 'required|numeric',
            'pic_saat_ini_id' => 'required|integer',
            'tanggal_pembelian' => 'required|date',
            'umur_ekonomis_tahun' => 'required|integer',
            'kondisi' => 'nullable|string',
            'harga_beli' => 'required|numeric'
        ]);

        $asetBaru = Aset::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data aset berhasil ditambahkan',
            'data' => $asetBaru
        ], 201);
    }}

      