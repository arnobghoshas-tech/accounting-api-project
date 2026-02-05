<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $result = $this->authService->login($request->email, $request->password);
        } catch (AuthenticationException $e) {
            return ApiResponse::unauthorized('Invalid credentials');
        }

        $user = $result['user']->load(['company', 'branch']);
        $resource = new UserResource($user);

        return ApiResponse::success([
            'user' => $resource,
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return ApiResponse::success(null, 'Logged out successfully');
    }

    public function user(Request $request)
    {
        $user = $this->authService->currentUser($request->user())->load(['company', 'branch']);
        return ApiResponse::success(new UserResource($user), 'Current user fetched successfully');
    }
}
