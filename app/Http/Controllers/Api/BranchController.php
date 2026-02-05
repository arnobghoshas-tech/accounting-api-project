<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Responses\ApiResponse;
use App\Services\BranchService;
use Illuminate\Database\QueryException;

class BranchController extends Controller
{
    public function __construct(private BranchService $branchService)
    {
    }

    public function index()
    {
        $branches = $this->branchService->index();
        return ApiResponse::success(BranchResource::collection($branches), 'Branches fetched successfully');
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->store($request->validated());
        return ApiResponse::created(new BranchResource($branch), 'Branch created successfully');
    }

    public function show(string $id)
    {
        $branch = $this->branchService->show($id);
        if (! $branch) {
            return ApiResponse::notFound('Branch not found');
        }
        return ApiResponse::success(new BranchResource($branch), 'Branch fetched successfully');
    }

    public function update(UpdateBranchRequest $request, string $id)
    {
        $branch = $this->branchService->update($id, $request->validated());
        if (! $branch) {
            return ApiResponse::notFound('Branch not found');
        }
        return ApiResponse::success(new BranchResource($branch), 'Branch updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            $ok = $this->branchService->destroy($id);
            if (! $ok) {
                return ApiResponse::notFound('Branch not found');
            }
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1451 ? 409 : 500;
            $message = ($code === 409) ? 'Branch is in use and cannot be deleted' : 'Database error';
            if ($code === 409) {
                return ApiResponse::conflict($message);
            }
            return ApiResponse::serverError($message);
        } catch (\Throwable $e) {
            return ApiResponse::serverError('Unexpected error occurred');
        }

        return ApiResponse::success(null, 'Branch deleted successfully');
    }
}
