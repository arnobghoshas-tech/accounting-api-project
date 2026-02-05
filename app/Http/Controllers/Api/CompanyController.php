<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use Illuminate\Database\QueryException;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $companyService)
    {
    }

    public function index()
    {
        $companies = $this->companyService->index();
        return response()->json([
            'success' => true,
            'data' => CompanyResource::collection($companies),
        ]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->companyService->store($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Company created successfully',
            'data' => new CompanyResource($company),
        ], 201);
    }

    public function show(string $id)
    {
        $company = $this->companyService->show($id);
        if (! $company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new CompanyResource($company),
        ]);
    }

    public function update(UpdateCompanyRequest $request, string $id)
    {
        $company = $this->companyService->update($id, $request->validated());
        if (! $company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully',
            'data' => new CompanyResource($company),
        ]);
    }

    public function destroy(string $id)
    {
        try {
            $ok = $this->companyService->destroy($id);
            if (! $ok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found',
                ], 404);
            }
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1451 ? 409 : 500;
            $message = ($code === 409) ? 'Company is in use and cannot be deleted' : 'Database error';
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
            'message' => 'Company deleted successfully',
        ]);
    }
}
