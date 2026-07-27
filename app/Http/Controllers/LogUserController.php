<?php

namespace App\Http\Controllers;

use App\Models\LogUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LogUserController extends Controller
{
    /**
     * Display a listing of user activity logs.
     */
    public function index(Request $request)
    {
        $query = LogUser::with(['user.role']);

        // 1. Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Role filter
        if ($request->has('role') && !empty($request->role) && $request->role !== 'all') {
            $role = $request->role;
            $query->whereHas('user.role', function ($q) use ($role) {
                $q->where('nama_role', $role)
                  ->orWhere('nama_role', strtolower($role))
                  ->orWhere('nama_role', 'masterAdmin' === $role ? 'masterAdmin' : '');
            });
        }

        // 3. Module filter
        if ($request->has('module') && !empty($request->module) && $request->module !== 'all') {
            $module = strtolower($request->module);
            if ($module === 'autentikasi') {
                $query->where(function ($q) {
                    $q->whereIn('action', ['login', 'logout'])
                      ->orWhereNull('target_table');
                });
            } elseif ($module === 'inventori' || $module === 'aset') {
                $query->whereIn('target_table', ['aset', 'kategori_aset']);
            } elseif ($module === 'pengguna' || $module === 'users') {
                $query->where('target_table', 'users');
            } elseif ($module === 'tiket' || $module === 'laporan') {
                $query->where('target_table', 'tiket');
            } elseif ($module === 'sistem') {
                $query->whereNull('target_table')
                      ->whereNotIn('action', ['login', 'logout']);
            }
        }

        // 4. Days filter
        if ($request->has('days') && !empty($request->days) && $request->days !== 'all') {
            $days = intval($request->days);
            if ($days > 0) {
                $query->where('created_at', '>=', Carbon::now()->subDays($days));
            }
        }

        // Get logs ordered by created_at desc
        $logs = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $logs
        ]);
    }
}
