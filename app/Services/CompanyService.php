<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public function index()
    {
        return Company::query()->get();
    }

    public function store(array $data): Company
    {
        $company = Company::create($data);
        return $company;
    }

    public function show(string $id): ?Company
    {
        return Company::find($id);
    }

    public function update(string $id, array $data): ?Company
    {
        $company = Company::find($id);
        if (! $company) {
            return null;
        }
        $company->update($data);
        return $company;
    }

    public function destroy(string $id): bool
    {
        $company = Company::find($id);
        if (! $company) {
            return false;
        }
        $company->delete();
        return true;
    }
}
