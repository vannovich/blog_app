<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create posts',
            'edit own posts',
            'edit all posts',
            'delete own posts',
            'delete all posts',
            'publish posts',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions(Permission::all());

        // Editor role
        $editorRole = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        $editorRole->syncPermissions([
            'create posts',
            'edit all posts',
            'delete all posts',
            'publish posts',
        ]);

        // Author role
        $authorRole = Role::firstOrCreate([
            'name' => 'author',
            'guard_name' => 'web',
        ]);

        $authorRole->syncPermissions([
            'create posts',
            'edit own posts',
            'delete own posts',
        ]);

        // Subscriber role
        $subscriberRole = Role::firstOrCreate([
            'name' => 'subscriber',
            'guard_name' => 'web',
        ]);

        // Subscriber has no permissions.
        $subscriberRole->syncPermissions([]);

        // Clear cache again after changes
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}
