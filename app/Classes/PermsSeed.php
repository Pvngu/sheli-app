<?php

namespace App\Classes;

use App\Models\Permission;
use Nwidart\Modules\Facades\Module;

class PermsSeed
{
    public static $mainPermissionsArray = [
        
        // Users
        'users_view' => [
            'name' => 'users_view',
            'display_name' => 'Staff Member View'
        ],
        'users_create' => [
            'name' => 'users_create',
            'display_name' => 'Staff Member Create'
        ],
        'users_edit' => [
            'name' => 'users_edit',
            'display_name' => 'Staff Member Edit'
        ],
        'users_delete' => [
            'name' => 'users_delete',
            'display_name' => 'Staff Member Delete'
        ],

        // Role
        'roles_view' => [
            'name' => 'roles_view',
            'display_name' => 'Role View'
        ],
        'roles_create' => [
            'name' => 'roles_create',
            'display_name' => 'Role Create'
        ],
        'roles_edit' => [
            'name' => 'roles_edit',
            'display_name' => 'Role Edit'
        ],
        'roles_delete' => [
            'name' => 'roles_delete',
            'display_name' => 'Role Delete'
        ],

        // Company
        'companies_edit' => [
            'name' => 'companies_edit',
            'display_name' => 'Company Edit'
        ],

        // Accident
        'accidents_view' => [
            'name' => 'accidents_view',
            'display_name' => 'Accident View'
        ],
        'accidents_create' => [
            'name' => 'accidents_create',
            'display_name' => 'Accident Create'
        ],
        'accidents_edit' => [
            'name' => 'accidents_edit',
            'display_name' => 'Accident Edit'
        ],
        'accidents_delete' => [
            'name' => 'accidents_delete',
            'display_name' => 'Accident Delete'
        ],

        // Area
        'areas_view' => [
            'name' => 'areas_view',
            'display_name' => 'Area View'
        ],
        'areas_create' => [
            'name' => 'areas_create',
            'display_name' => 'Area Create'
        ],
        'areas_edit' => [
            'name' => 'areas_edit',
            'display_name' => 'Area Edit'
        ],
        'areas_delete' => [
            'name' => 'areas_delete',
            'display_name' => 'Area Delete'
        ],

        // Translation
        'translations_view' => [
            'name' => 'translations_view',
            'display_name' => 'Translation View'
        ],
        'translations_create' => [
            'name' => 'translations_create',
            'display_name' => 'Translation Create'
        ],
        'translations_edit' => [
            'name' => 'translations_edit',
            'display_name' => 'Translation Edit'
        ],
        'translations_delete' => [
            'name' => 'translations_delete',
            'display_name' => 'Translation Delete'
        ],

        // Team Chat Settings
        'team_chat_view' => [
            'name' => 'team_chat_view',
            'display_name' => 'Team Chat View'
        ],
        'team_chat_create_group' => [
            'name' => 'team_chat_create_group',
            'display_name' => 'Team Chat Create Group'
        ],

        // Report Settings
        'reports_view' => [
            'name' => 'reports_view',
            'display_name' => 'Reports View'
        ],

        // Document Settings
        'documents_view' => [
            'name' => 'documents_view',
            'display_name' => 'Documents View'
        ],
        'documents_create' => [
            'name' => 'documents_create',
            'display_name' => 'Documents Create'
        ],
        'documents_delete' => [
            'name' => 'documents_delete',
            'display_name' => 'Documents Delete'
        ],

        // Audits Settings
        'audits_view' => [
            'name' => 'audits_view',
            'display_name' => 'Audits View'
        ],
        'audits_create' => [
            'name' => 'audits_create',
            'display_name' => 'Audits Create'
        ],
        'audits_edit' => [
            'name' => 'audits_edit',
            'display_name' => 'Audits Edit'
        ],
        'audits_delete' => [
            'name' => 'audits_delete',
            'display_name' => 'Audits Delete'
        ],

        // Enrollments Settings
        'enrollments_view' => [
            'name' => 'enrollments_view',
            'display_name' => 'Enrollments View'
        ],
        'enrollments_create' => [
            'name' => 'enrollments_create',
            'display_name' => 'Enrollments Create'
        ],
        'enrollments_edit' => [
            'name' => 'enrollments_edit',
            'display_name' => 'Enrollments Edit'
        ],
        'enrollments_delete' => [
            'name' => 'enrollments_delete',
            'display_name' => 'Enrollments Delete'
        ],
    ];

    public static $eStorePermissions = [];

    public static function getPermissionArray($moduleName)
    {
        return self::$mainPermissionsArray;
    }

    public static function seedPermissions($moduleName = '')
    {
        $permissions = self::getPermissionArray($moduleName);

        foreach ($permissions as $group => $permission) {
            $permissionCount = Permission::where('name', $permission['name'])->count();

            if ($permissionCount == 0) {
                $newPermission = new Permission();
                $newPermission->name = $permission['name'];
                $newPermission->display_name = $permission['display_name'];
                $newPermission->save();
            }
        }
    }

    public static function seedMainPermissions()
    {
        // Main Module
        self::seedPermissions();
    }

    public static function seedAllModulesPermissions()
    {
        // Main Module
        self::seedMainPermissions();

        $allModules = Module::all();
        foreach ($allModules as $allModule) {
            self::seedPermissions($allModule);
        }
    }
}
