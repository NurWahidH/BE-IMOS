<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Check if user status is deactivated
        if ($user->status === 'Non-Aktif') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda dinonaktifkan. Silakan hubungi Administrator.'
            ], 403);
        }

        // 1. Check if there is already a pending ticket in the queue
        $pendingTicketExists = \App\Models\Tiket::where('user_id', $user->id)
            ->where('kondisi', 'Menunggu Reset Password')
            ->exists();

        if ($pendingTicketExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permintaan reset password Anda sebelumnya masih dalam antrean persetujuan admin.'
            ], 422);
        }

        // 2. Check 1-week (7 days) rate limit using Cache
        $cacheKey = 'password-reset-request:' . $user->email;
        if (Cache::has($cacheKey)) {
            $secondsRemaining = Cache::ttl($cacheKey);
            $days = ceil($secondsRemaining / 86400);

            return response()->json([
                'status' => 'error',
                'message' => "Anda hanya dapat melakukan reset password sekali dalam seminggu. Silakan coba lagi dalam {$days} hari."
            ], 422);
        }

        // Create a ticket for resetting password
        \App\Models\Tiket::create([
            'user_id' => $user->id,
            'aset_id' => null, // null because it's a system ticket, not asset related
            'kondisi' => 'Menunggu Reset Password',
            'catatan' => "Permintaan Reset Password untuk Username {$user->username} , Kirim Password baru ke Email {$user->email}"
        ]);

        // Put request in cache for 7 days
        Cache::put($cacheKey, true, now()->addDays(7));

        \App\Events\UsersChanged::dispatch('password_request_created', $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan reset password telah dikirim ke Master Admin.'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('api')->user();

        if ($user?->status === 'Non-Aktif') {
            Auth::guard('api')->logout();

            return response()->json([
                'error' => 'Account inactive',
                'message' => 'Akun Anda sedang nonaktif.'
            ], 403);
        }

        \App\Models\LogUser::create([
            'idUser' => $user->id,
            'action' => 'login',
            'description' => "User {$user->username} berhasil login.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return $this->respondWithToken($token);
    }

    public function profile()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            \App\Models\LogUser::create([
                'idUser' => $user->id,
                'action' => 'logout',
                'description' => "User {$user->username} logout.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }

        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
