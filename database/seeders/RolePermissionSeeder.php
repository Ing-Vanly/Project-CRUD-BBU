<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\PermissionMap;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $allPermissions = PermissionMap::allPermissions();

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $rolePermissions = [
            'super-admin' => $allPermissions,
            'manager' => [
                'dashboard.view',
                'post.view',
                'post.create',
                'post.edit',
                'post.delete',
                'post.publish',
                'category.view',
                'category.create',
                'category.edit',
                'category.delete',
                'unit.view',
                'unit.create',
                'unit.edit',
                'unit.delete',
                'brand.view',
                'brand.create',
                'brand.edit',
                'brand.delete',
                'product.view',
                'product.create',
                'product.edit',
                'product.delete',
                'business_location.view',
                'business_location.create',
                'business_location.edit',
                'business_location.delete',
                'author.view',
                'author.create',
                'author.edit',
                'author.delete',
                'order.view',
                'order.create',
                'order.edit',
                'order.delete',
                'order.export',
            ],
            'editor' => [
                'dashboard.view',
                'post.view',
                'post.create',
                'post.edit',
                'author.view',
                'author.create',
                'author.edit',
                'category.view',
                'brand.view',
                'product.view',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        $seedUsers = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => 'password',
                'role' => 'super-admin',
            ],
        ];

        foreach ($seedUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}
    