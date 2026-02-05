<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use App\Http\Resources\RoleResource;
use App\Http\Responses\ApiResponse;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleService->index();
        return ApiResponse::success(RoleResource::collection($roles), 'Roles fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->store([
            'name' => $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        return ApiResponse::created(new RoleResource($role), 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->roleService->show($id);

        if (!$role) {
            return ApiResponse::notFound('Role not found');
        }

        return ApiResponse::success(new RoleResource($role), 'Role fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $role = $this->roleService->update($id, [
            'name' => $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        if (!$role) {
            return ApiResponse::notFound('Role not found');
        }

        return ApiResponse::success(new RoleResource($role), 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ok = $this->roleService->destroy($id);
        if (! $ok) {
            return ApiResponse::notFound('Role not found');
        }

        return ApiResponse::success(null, 'Role deleted successfully');
    }
}
