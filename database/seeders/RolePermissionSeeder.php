<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear Spatie permission cache
        app('cache')->forget('spatie.permission.cache');

        // ==================== DEFINE PERMISSIONS ====================
        $permissions = [
            // Dashboard
            ['name' => 'Dashboard', 'module_name' => 'Dashboard', 'parent' => null],
            ['name' => 'view-dashboard', 'module_name' => 'Dashboard', 'parent' => 'Dashboard'],
          
            // Users Management 
            ['name'=> 'Users', 'module_name' => 'Users' , 'parent' => null],
            ['name' => 'view-users', 'module_name' => 'Users', 'parent' => 'Users'],
            ['name' => 'add-users', 'module_name' => 'Users', 'parent' => 'Users'],
            ['name' => 'edit-users', 'module_name' => 'Users', 'parent' => 'Users'],
            ['name' => 'delete-users', 'module_name' => 'Users', 'parent' => 'Users'],

            // Roles Management
            ['name'=> 'Roles', 'module_name' => 'Roles', 'parent' => 'Users'],
            ['name' => 'view-roles', 'module_name' => 'Roles', 'parent' => 'Roles'],
            ['name' => 'add-roles', 'module_name' => 'Roles', 'parent' => 'Roles'],
            ['name' => 'edit-roles', 'module_name' => 'Roles', 'parent' => 'Roles'],
            ['name' => 'delete-roles', 'module_name' => 'Roles', 'parent' => 'Roles'], 

            
            ];

        // Create permissions
        $permissionNames = [];
        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['module_name' => $perm['module_name'], 'parent' => $perm['parent']]
            );
            $permissionNames[] = $permission->name;
        }

        // ==================== CREATE ROLES ====================
        // 1. Super Admin - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super_Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissionNames);

        // ==================== CREATE TEST USERS ====================
        // 1. Super Admin User
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
            ]
        );
        $superAdminUser->assignRole('Super_Admin');


$superAdminRole = \Spatie\Permission\Models\Role::where('name', 'Super_Admin')->first();

 }
}