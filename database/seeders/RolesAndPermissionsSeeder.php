<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * All roles
         */
        $permissions = [
            'manage_users' => 'User management',
            'manage_roles' => 'Access and roles management',
            'manage_settings' => 'App settings management',
            'view_admin' => 'View admin dashboard',
            'manage_content' => 'Manage user created content',
        ];

        foreach ($permissions as $name => $display_name) {
            Permission::query()->firstOrCreate([
                'name' => $name,
                'display_name' => $display_name,
                'description' => $display_name
            ]);
        }

        /**
         * Admin roles
         */

        $adminRole = Role::query()->firstOrCreate([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full access'
        ]);
        $adminRole->syncPermissions(array_keys($permissions));

        /**
         * Editor roles
         */

        $editorRole = Role::query()->firstOrCreate([
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Can manage content'
        ]);
        $editorPermissions = [
            'manage_content',
            'view_admin',
        ];
        $editorRole->syncPermissions($editorPermissions);

        /**
         * User roles
         */

        $userRole = Role::query()->firstOrCreate([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Simple user'
        ]);
        $userRole->syncPermissions(['view_admin']);
    }
}
