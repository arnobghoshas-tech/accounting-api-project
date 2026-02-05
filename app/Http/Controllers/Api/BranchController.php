<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
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
        return response()->json([
            'success' => true,
            'data' => BranchResource::collection($branches),
        ]);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->store($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Branch created successfully',
            'data' => new BranchResource($branch),
        ], 201);
    }

    public function show(string $id)
    {
        $branch = $this->branchService->show($id);
        if (! $branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new BranchResource($branch),
        ]);
    }

    public function update(UpdateBranchRequest $request, string $id)
    {
        $branch = $this->branchService->update($id, $request->validated());
        if (! $branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Branch updated successfully',
            'data' => new BranchResource($branch),
        ]);
    }

    public function destroy(string $id)
    {
        try {
            $ok = $this->branchService->destroy($id);
            if (! $ok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found',
                ], 404);
            }
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1451 ? 409 : 500;
            $message = ($code === 409) ? 'Branch is in use and cannot be deleted' : 'Database error';
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
            'message' => 'Branch deleted successfully',
        ]);
    }
}
