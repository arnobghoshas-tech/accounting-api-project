<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Responses\ApiResponse;
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
        return ApiResponse::success(CompanyResource::collection($companies), 'Companies fetched successfully');
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->companyService->store($request->validated());
        return ApiResponse::created(new CompanyResource($company), 'Company created successfully');
    }

    public function show(string $id)
    {
        $company = $this->companyService->show($id);
        if (! $company) {
            return ApiResponse::notFound('Company not found');
        }
        return ApiResponse::success(new CompanyResource($company), 'Company fetched successfully');
    }

    public function update(UpdateCompanyRequest $request, string $id)
    {
        $company = $this->companyService->update($id, $request->validated());
        if (! $company) {
            return ApiResponse::notFound('Company not found');
        }
        return ApiResponse::success(new CompanyResource($company), 'Company updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            $ok = $this->companyService->destroy($id);
            if (! $ok) {
                return ApiResponse::notFound('Company not found');
            }
        } catch (QueryException $e) {
            $code = ($e->errorInfo[1] ?? null) === 1451 ? 409 : 500;
            $message = ($code === 409) ? 'Company is in use and cannot be deleted' : 'Database error';
            if ($code === 409) {
                return ApiResponse::conflict($message);
            }
            return ApiResponse::serverError($message);
        } catch (\Throwable $e) {
            return ApiResponse::serverError('Unexpected error occurred');
        }

        return ApiResponse::success(null, 'Company deleted successfully');
    }
}
