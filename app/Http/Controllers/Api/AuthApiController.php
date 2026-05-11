<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * POST /api/login
     * Authenticate user & return Sanctum token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Revoke existing tokens (single session)
        $user->tokens()->delete();

        $token = $user->createToken('mobile_app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'         => $user->id,
                'nama'       => $user->nama,
                'nip'        => $user->nip,
                'email'      => $user->email,
                'role'       => $user->role,
                'departemen' => $user->departemen,
                'divisi'     => $user->divisi,
            ],
        ]);
    }

    /**
     * POST /api/logout
     * Revoke current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * GET /api/user
     * Return authenticated user
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'         => $user->id,
            'nama'       => $user->nama,
            'nip'        => $user->nip,
            'email'      => $user->email,
            'role'       => $user->role,
            'departemen' => $user->departemen,
            'divisi'     => $user->divisi,
        ]);
    }
}
