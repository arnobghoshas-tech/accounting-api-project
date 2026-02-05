<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use InvalidArgumentException;

class UserService
{
    public function index()
    {
        return User::with(['roles', 'company', 'branch'])->get();
    }

    public function store(array $data, string $role): User
    {
        $roleModel = Role::where('name', $role)->first();
        if (! $roleModel) {
            throw new InvalidArgumentException('Role not found');
        }
        $data['password'] = Hash::make($data['password']);

        if ($role === 'superadmin') {
            $data['company_id'] = null;
            $data['branch_id'] = null;
        }

        $user = User::create($data);
        $user->assignRole($roleModel);

        return $user->load(['roles', 'company', 'branch']);
    }

    public function show(string $id): ?User
    {
        return User::with(['roles', 'company', 'branch'])->find($id);
    }

    public function update(string $id, array $data, string $role): ?User
    {
        $user = User::find($id);
        if (! $user) {
            return null;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($role === 'superadmin') {
            $data['company_id'] = null;
            $data['branch_id'] = null;
        }

        $user->update($data);
        $user->syncRoles([$role]);

        return $user->load(['roles', 'company', 'branch']);
    }

    public function destroy(string $id): bool
    {
        $user = User::find($id);
        if (! $user) {
            return false;
        }
        $user->delete();
        return true;
    }
}
