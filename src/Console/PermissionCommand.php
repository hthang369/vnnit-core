<?php

namespace Vnnit\Core\Console;

use App\Models\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Vnnit\Core\Permissions\Permission;
use Vnnit\Core\Permissions\Role;
use Vnnit\Core\Permissions\RoleHasPermissions;
use Vnnit\Core\Permissions\Sections;

class PermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:fresh {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all permissions and reload from config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            if ($this->option('init')) {
                $this->truncatePermissionTables();
                $this->seedSection();
                $this->seedPermission();
                $this->grantPermissionsToRoles();
                $this->assignRoleToUsers();
            } else {
                $this->seedSection();
                $defaultPermissions = $this->seedPermission();
                $this->deleteOldPermission($defaultPermissions);
                $this->assignRoleToUsers();
            }
        });
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function truncatePermissionTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('role_has_permissions')->truncate();
        DB::table('user_has_roles')->truncate();
        Sections::truncate();
        Permission::truncate();
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $this->info('All permission tables truncated successfully');
    }

    protected function seedSection(): void
    {
        //seed the defaults sections
        $sections = config('permission.sections');

        $currentSectionIds = Sections::pluck('id')->toArray();
        $newSectionIds = [];

        $sectionsHaveParent = [];
        $notExistsParents = [];
        foreach ($sections as $section) {
            if (isset($section['parent'])) {
                $sectionParent = $section['parent'];
                unset($section['parent']);
            } else {
                $sectionParent = null;
            }

            $sectionModel = Sections::firstOrCreate($section);

            $newSectionIds[] = $sectionModel->id;

            if ($sectionParent) {
                $sectionsHaveParent[] = [
                    'model'  => $sectionModel,
                    'parent' => $sectionParent
                ];
            }
        }

        // Update parent_id
        foreach ($sectionsHaveParent as $section) {
            $parent = Sections::where('code', $section['parent'])->first();
            if (!$parent) {
                $notExistsParents[] = $section['parent'];
                continue;
            }
            $section['model']->parent_id = $parent->id;
            $section['model']->save();
        }

        if (!empty($notExistsParents)) {
            foreach ($notExistsParents as $notExistsParent) {
                $this->warn('Parent section "' . $notExistsParent . '" not found. Ignored.');
            }
        }

        // Delete unused sections
        $unused = array_diff($currentSectionIds, $newSectionIds);
        $this->deleteOldSection($unused);
    }

    protected function deleteOldSection($ids)
    {
        if (empty($ids)) {
            return;
        }

        $sections = Sections::whereIn('id', $ids)->get();
        $deleteSections = $sections->map(function ($item) {
            return $item['id'] . '_' . $item['code'];
        })->toArray();

        $this->info("\n" . 'The following sections are no longer used: '. implode(',', $deleteSections));

        if ($this->confirm('Do you want to delete them? ')) {
            Sections::whereIn('id', $ids)->delete();
            $this->info('Unused permission have been deleted successfully.');
        }
    }

    protected function seedPermission()
    {
        // Seed the default permissions
        $permissions = Permission::defaultPermissions();
        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }
        $this->info('Default Permissions added.');
        return $permissions;
    }

    protected function deleteOldPermission($defaultPermissions)
    {
        $currentPermissions = Permission::pluck('name')->toArray();
        $deletePermissions = array_diff($currentPermissions, $defaultPermissions);

        if (empty($deletePermissions)) {
            return;
        }

        $this->info("\n" . 'The following permissions are no longer used: '. implode(',', $deletePermissions));

        if ($this->confirm('Do you want to delete them? ')) {
            $deleteIds = Permission::whereIn('name', $deletePermissions)->get(['id'])->pluck('id')->toArray();
            Permission::whereIn('id', $deleteIds)->delete();
            RoleHasPermissions::whereIn('permission_id', $deleteIds)->delete();
            $this->info('Unused permission have been deleted successfully.');
        }
    }

    protected function grantPermissionsToRoles(): void
    {
        $roles_array = config('permission.roles');
        // add roles
        foreach ($roles_array as $role) {
            $role = Role::firstOrCreate($role);

            if ($role->name == $roles_array[0]['name']) {
                // assign all permissions
                $role->syncPermissions(Permission::all());
                $this->info('System Admin granted all the permissions');
            } else {
                // for others by default only read access
                $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());
            }
        }
    }

    protected function assignRoleToUsers(): void
    {
        User::all()->each(function ($u) {
            $u->assignRole('System Admin');
        });
        $this->info('All users has been assigned System Admin role');
    }
}
