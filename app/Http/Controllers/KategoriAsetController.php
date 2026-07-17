<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use Illuminate\Http\Request;

class KategoriAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriAset = KategoriAset::all();
        return response()->json([
            'status' => 'success',
            'data' => $kategoriAset
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_aset',
        ]);

        $kategoriAset = KategoriAset::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori aset berhasil ditambahkan',
            'data' => $kategoriAset
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategoriAset = KategoriAset::find($id);

        if (!$kategoriAset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori aset tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $kategoriAset
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategoriAset = KategoriAset::find($id);

        if (!$kategoriAset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori aset tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_aset,' . $id,
        ]);

        $kategoriAset->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori aset berhasil diperbarui',
            'data' => $kategoriAset
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategoriAset = KategoriAset::find($id);

        if (!$kategoriAset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori aset tidak ditemukan'
            ], 404);
        }

        $kategoriAset->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori aset berhasil dihapus'
        ]);
    }
}

