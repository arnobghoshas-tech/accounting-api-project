<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function index()
    {
        return Role::with('permissions')->get();
    }

    public function store(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => config('auth.defaults.guard'),
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    public function show(string $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    public function update(string $id, array $data): ?Role
    {
        $role = Role::find($id);
        if (! $role) {
            return null;
        }

        $role->update(['name' => $data['name']]);

        if (array_key_exists('permissions', $data)) {
            $role->syncPermissions($data['permissions'] ?? []);
        }

        return $role->load('permissions');
    }

    public function destroy(string $id): bool
    {
        $role = Role::find($id);
        if (! $role) {
            return false;
        }
        $role->delete();
        return true;
    }
}
