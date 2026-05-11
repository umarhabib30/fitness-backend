<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('permissions') || !Schema::hasTable('role_has_permissions')) {
            return;
        }

        $permissionId = DB::table('permissions')
            ->where('name', 'level-list')
            ->where('guard_name', 'web')
            ->value('id');

        if (!$permissionId) {
            $parentId = DB::table('permissions')
                ->where('name', 'level')
                ->where('guard_name', 'web')
                ->value('id');

            $permissionId = DB::table('permissions')->insertGetId([
                'name'       => 'level-list',
                'title'      => 'Level List',
                'guard_name' => 'web',
                'parent_id'  => $parentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $trainerRoleId = DB::table('roles')
            ->where('name', 'trainer')
            ->where('guard_name', 'web')
            ->value('id');

        if ($trainerRoleId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id'       => $trainerRoleId,
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('permissions') || !Schema::hasTable('role_has_permissions')) {
            return;
        }

        $permissionId = DB::table('permissions')
            ->where('name', 'level-list')
            ->where('guard_name', 'web')
            ->value('id');

        $trainerRoleId = DB::table('roles')
            ->where('name', 'trainer')
            ->where('guard_name', 'web')
            ->value('id');

        if ($permissionId && $trainerRoleId) {
            DB::table('role_has_permissions')
                ->where('permission_id', $permissionId)
                ->where('role_id', $trainerRoleId)
                ->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
