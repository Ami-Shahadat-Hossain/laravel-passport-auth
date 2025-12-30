<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\RefreshTokenRepository;

class AuthController extends Controller
{
/**
 * Register a new user
 */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role'     => 'required|in:admin,user',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            // $scopes = $user->isAdmin() ? ['admin-access', 'user-read', 'user-write'] : ['user-read'];

            // $token = $user->createToken('AuthToken', $scopes)->accessToken;

            // Create token WITHOUT scopes (this fixes the error)
            $token = $user->createToken('AuthToken')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data'    => [
                    'user'         => $user,
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            if (! Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $user = Auth::user();
            // $scopes = $user->isAdmin() ? ['admin-access', 'user-read', 'user-write'] : ['user-read'];

            // $token = $user->createToken('AuthToken', $scopes)->accessToken;

            // Create token WITHOUT scopes
            $token = $user->createToken('AuthToken')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data'    => [
                    'user'         => $user,
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'success' => true,
                // 'data'    => $request->user(),
                'data'    => [
                    'user'        => $user,
                    'permissions' => $this->getUserPermissions($user),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user (Revoke token)
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            $token->revoke();

            // Also revoke refresh tokens
            $tokenRepository = app(RefreshTokenRepository::class);
            $tokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user permissions based on role
     * This is a custom implementation instead of OAuth scopes
     */
    private function getUserPermissions($user)
    {
        if ($user->isAdmin()) {
            return [
                'can_read_users'         => true,
                'can_write_users'        => true,
                'can_delete_users'       => true,
                'can_access_admin_panel' => true,
            ];
        }

        return [
            'can_read_users'         => false,
            'can_write_users'        => false,
            'can_delete_users'       => false,
            'can_access_admin_panel' => false,
        ];
    }
}
