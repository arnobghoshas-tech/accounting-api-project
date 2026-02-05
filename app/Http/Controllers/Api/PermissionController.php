<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;
use App\Http\Resources\PermissionResource;
use App\Http\Responses\ApiResponse;
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
        return ApiResponse::success(PermissionResource::collection($permissions), 'Permissions fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionService->store([
            'name' => $request->name,
        ]);

        return ApiResponse::created(new PermissionResource($permission), 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = $this->permissionService->show($id);

        if (!$permission) {
            return ApiResponse::notFound('Permission not found');
        }

        return ApiResponse::success(new PermissionResource($permission), 'Permission fetched successfully');
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
            return ApiResponse::notFound('Permission not found');
        }

        return ApiResponse::success(new PermissionResource($permission), 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ok = $this->permissionService->destroy($id);
            if (! $ok) {
                return ApiResponse::notFound('Permission not found');
            }
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Permission not found');
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1451 ? 409 : 500;
            $message = ($code === 409) ? 'Permission is in use and cannot be deleted' : 'Database error';
            if ($code === 409) {
                return ApiResponse::conflict($message);
            }
            return ApiResponse::serverError($message);
        } catch (\Throwable $e) {
            return ApiResponse::serverError('Unexpected error occurred');
        }

        return ApiResponse::success(null, 'Permission deleted successfully');
    }
}
