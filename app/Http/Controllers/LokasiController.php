<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index ()
    {
        $dataLokasi = Lokasi::all();

        return response()->json([
            'status' => 'success',
            'data' => $dataLokasi
        ]);
    }
    
    public function store (Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'nama_ruangan' => 'required|string',
        ]);
        
        $lokasiBaru = Lokasi::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data lokasi berhasil ditambahkan',
            'data' => $lokasiBaru
        ], 201);
    }
}
