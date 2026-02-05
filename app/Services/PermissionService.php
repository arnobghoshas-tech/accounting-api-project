<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function index()
    {
        return Permission::all();
    }

    public function store(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => config('auth.defaults.guard'),
        ]);
    }

    public function show(string $id): ?Permission
    {
        return Permission::find($id);
    }

    public function update(string $id, array $data): ?Permission
    {
        $permission = Permission::find($id);
        if (! $permission) {
            return null;
        }
        $permission->update(['name' => $data['name']]);
        return $permission;
    }

    public function destroy(string $id): bool
    {
       $permission = Permission::find($id);

    if (! $permission) {
        return false;
    }

    // Required for Spatie
    $permission->roles()->detach();

    $permission->delete();


    return true;
    }
}
