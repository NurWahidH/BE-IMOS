<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AssetController extends Controller
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

    public function scan($qr_code)
    {
        $aset = Aset::where('kode_barcode', $qr_code)->first();

        if (!$aset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $aset
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string',
            'kode_barcode' => 'required|string',
            'kategori_id' => 'required|integer',
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
    }

    public function upload(Request $request)
    {
        // Implementasi upload CSV/Excel
        return response()->json([
            'status' => 'success',
            'message' => 'Bulk upload data aset diproses'
        ]);
    }

    public function update(Request $request, $id)
    {
        $aset = Aset::find($id);

        if (!$aset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset tidak ditemukan'
            ], 404);
        }

        $aset->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data aset berhasil diupdate',
            'data' => $aset
        ]);
    }
}
