<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;
use App\Http\Resources\PermissionResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends Controller
{
    public function __construct(private PermissionService $permissionService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = $this->permissionService->index();
        return response()->json([
            'success' => true,
            'data' => PermissionResource::collection($permissions)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionService->store([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => new PermissionResource($permission)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = $this->permissionService->show($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PermissionResource($permission)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, string $id)
    {
        $permission = $this->permissionService->update($id, [
            'name' => $request->name,
        ]);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => new PermissionResource($permission)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ok = $this->permissionService->destroy($id);
            if (! $ok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found'
                ], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1451 ? 409 : 500;
            $message = ($code === 409) ? 'Permission is in use and cannot be deleted' : 'Database error';
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
            'message' => 'Permission deleted successfully'
        ]);
    }
}
