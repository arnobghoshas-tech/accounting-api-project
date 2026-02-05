<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use App\Http\Resources\RoleResource;

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
        return response()->json([
            'success' => true,
            'data' => RoleResource::collection($roles)
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => new RoleResource($role)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->roleService->show($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role)
        ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => new RoleResource($role)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ok = $this->roleService->destroy($id);
        if (! $ok) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}
