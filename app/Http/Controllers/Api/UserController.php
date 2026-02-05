<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->index();
        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        log::info($request->all());
        try {
            $user = $this->userService->store($request->validated(), $request->role);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1062 ? 409 : 500;
            $message = ($code === 409) ? 'Email already exists' : 'Database error';
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $code);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->show($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->update($id, $request->validated(), $request->role);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ok = $this->userService->destroy($id);
        if (! $ok) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
