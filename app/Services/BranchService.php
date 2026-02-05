<?php

namespace App\Services;

use App\Models\Branch;

class BranchService
{
    public function index()
    {
        return Branch::query()->get();
    }

    public function store(array $data): Branch
    {
        $branch = Branch::create($data);
        return $branch;
    }

    public function show(string $id): ?Branch
    {
        return Branch::find($id);
    }

    public function update(string $id, array $data): ?Branch
    {
        $branch = Branch::find($id);
        if (! $branch) {
            return null;
        }
        $branch->update($data);
        return $branch;
    }

    public function destroy(string $id): bool
    {
        $branch = Branch::find($id);
        if (! $branch) {
            return false;
        }
        $branch->delete();
        return true;
    }
}
