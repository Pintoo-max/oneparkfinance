<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $user->createToken('API')->plainTextToken;

            return response()->json([
                'message' => 'Login success',
                'token'   => $token,
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Login failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
