<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
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
        return ApiResponse::success(UserResource::collection($users), 'Users fetched successfully');
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
            return ApiResponse::unprocessable($e->getMessage());
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1062 ? 409 : 500;
            $message = ($code === 409) ? 'Email already exists' : 'Database error';
            if ($code === 409) {
                return ApiResponse::conflict($message);
            }
            return ApiResponse::serverError($message);
        } catch (\Throwable $e) {
            return ApiResponse::serverError('Unexpected error occurred');
        }

        return ApiResponse::created(new UserResource($user), 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->show($id);

        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::success(new UserResource($user), 'User fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->update($id, $request->validated(), $request->role);

        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::success(new UserResource($user), 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ok = $this->userService->destroy($id);
        if (! $ok) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::success(null, 'User deleted successfully');
    }
}
