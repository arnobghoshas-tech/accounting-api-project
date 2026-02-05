<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Resources\UserResource;

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

        $result = $this->authService->login($request->email, $request->password);
        $user = $result['user']->load(['company', 'branch']);
        $result['user'] = new UserResource($user);

        return response()->json($result);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        $user = $this->authService->currentUser($request->user())->load(['company', 'branch']);
        return response()->json(new UserResource($user));
    }
}
