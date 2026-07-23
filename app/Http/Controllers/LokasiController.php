<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index ()
    {
        $dataUnit = Unit::all();

        return response()->json([
            'status' => 'success',
            'data' => $dataUnit
        ]);
    }
    
    public function store (Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
        ]);
        
        $unitBaru = Unit::create($request->only('unit'));

        return response()->json([
            'status' => 'success',
            'message' => 'Data unit berhasil ditambahkan',
            'data' => $unitBaru
        ], 201);
    }
}
