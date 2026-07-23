<?php

namespace App\Http\Controllers;

use App\Events\UsersChanged;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'unit'])->get();
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
            'unit_id' => 'nullable|integer|exists:unit,id',
            'status' => 'nullable|in:Aktif,Non-Aktif'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'unit_id' => (int) $request->role_id === 3 ? $request->unit_id : null,
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
            'unit_id' => 'nullable|integer|exists:unit,id',
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
            if ((int) $request->role_id !== 3) {
                $user->unit_id = null;
            }
        }

        if ($request->has('unit_id')) {
            $user->unit_id = (int) ($request->role_id ?? $user->role_id) === 3 ? $request->unit_id : null;
        }

        if ($request->has('status')) {
            $user->status = $request->status;
        }

        $user->save();

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
}
