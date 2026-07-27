<?php

namespace App\Http\Controllers;

use App\Events\UsersChanged;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
            'role_id' => 'nullable|integer|exists:role,id',
            'status' => 'nullable|in:Aktif,Non-Aktif'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status ?? 'Aktif'
        ]);

        UsersChanged::dispatch('created', $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'sometimes|string|unique:users,username,'.$id,
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'password' => ['sometimes', 'string', Password::min(8)->mixedCase()->numbers()],
            'role_id' => 'nullable|integer|exists:role,id',
            'status' => 'sometimes|in:Aktif,Non-Aktif'
        ]);

        if ($request->has('username')) {
            $user->username = $request->username;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        if ($request->has('status')) {
            $user->status = $request->status;
        }

        $user->save();

        // Resolve pending password reset tickets for this user
        \App\Models\Tiket::where('user_id', $user->id)
            ->where('kondisi', 'Menunggu Reset Password')
            ->delete();

        UsersChanged::dispatch('updated', $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Non-Aktif';
        $user->save();

        UsersChanged::dispatch('deactivated', $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'User deactivated successfully',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        UsersChanged::dispatch('deleted', (int) $id);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }

    public function passwordRequests()
    {
        $tickets = \App\Models\Tiket::with('user')
            ->where('kondisi', 'Menunggu Reset Password')
            ->orderBy('created_at', 'desc')
            ->get();

        $formatted = $tickets->map(function ($ticket) {
            return [
                'id' => (string) $ticket->id,
                'userId' => (string) $ticket->user_id,
                'email' => $ticket->user?->email ?? '-',
                'requestedAt' => $ticket->created_at ? $ticket->created_at->format('d M Y, H:i') : '-',
                'status' => 'Menunggu'
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted
        ]);
    }

    public function rejectPasswordRequest($id)
    {
        $ticket = \App\Models\Tiket::with('user')
            ->where('id', $id)
            ->where('kondisi', 'Menunggu Reset Password')
            ->firstOrFail();

        $user = $ticket->user;

        if ($user && $user->email) {
            Mail::send([], [], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('IMOS - Permintaan Reset Password Ditolak')
                    ->html("
                        <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
                            <h2 style='color: #e11d48;'>Password Reset Ditolak</h2>
                            <p>Halo, <strong>{$user->username}</strong>.</p>
                            <p>Mohon maaf, permintaan reset kata sandi Anda telah <strong>ditolak</strong> oleh Administrator.</p>
                            <p>Jika Anda merasa ini adalah kesalahan, harap hubungi Administrator Sistem secara langsung.</p>
                            <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                            <p style='font-size: 10px; color: #94a3b8; text-align: center;'>Sistem IMOS - Antam Medika</p>
                        </div>
                    ");
            });
        }

        $ticket->delete();

        UsersChanged::dispatch('password_request_rejected');

        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan reset password ditolak.'
        ]);
    }

    public function approvePasswordRequest($id)
    {
        $ticket = \App\Models\Tiket::with('user')
            ->where('id', $id)
            ->where('kondisi', 'Menunggu Reset Password')
            ->firstOrFail();

        $user = $ticket->user;
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        // Generate a branded, user-friendly password that meets Laravel validation rules:
        // - Min 8 characters ('Antam' + 4 digits = 9 chars)
        // - Mixed case (A is uppercase, ntam is lowercase)
        // - Numbers (4 random digits)
        $newPassword = 'Antam' . rand(1000, 9999); 

        $user->password = Hash::make($newPassword);
        $user->save();

        if ($user->email) {
            Mail::send([], [], function ($message) use ($user, $newPassword) {
                $message->to($user->email)
                    ->subject('IMOS - Permintaan Reset Password Disetujui')
                    ->html("
                        <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
                            <h2 style='color: #0d9488;'>Password Reset Berhasil</h2>
                            <p>Halo, <strong>{$user->username}</strong>.</p>
                            <p>Permintaan reset kata sandi Anda telah disetujui oleh Administrator.</p>
                            <p>Berikut adalah kata sandi baru Anda:</p>
                            <div style='background-color: #f1f5f9; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; font-family: monospace; letter-spacing: 1px; color: #0f172a; margin: 15px 0; text-align: center;'>
                                {$newPassword}
                            </div>
                            <p style='color: #ef4444; font-size: 11px;'>Harap segera ubah kata sandi Anda setelah masuk ke sistem demi keamanan akun Anda.</p>
                            <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                            <p style='font-size: 10px; color: #94a3b8; text-align: center;'>Sistem IMOS - Antam Medika</p>
                        </div>
                    ");
            });
        }

        $ticket->delete();

        UsersChanged::dispatch('password_request_approved');

        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan reset password disetujui dan email telah dikirim.'
        ]);
    }
}
