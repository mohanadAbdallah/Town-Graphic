<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission;
use \App\Models\User\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);//1
        Role::create(['name' => 'agent', 'guard_name' => 'web']);//2

        $permission1 = Permission::create(['name' => 'show users', 'guard_name' => 'web']); //1
        $permission2 = Permission::create(['name' => 'create users', 'guard_name' => 'web']);//2
        $permission3 = Permission::create(['name' => 'edit users', 'guard_name' => 'web']);//3
        $permission4 = Permission::create(['name' => 'delete users', 'guard_name' => 'web']);//4

        $role = Role::get()->first();
        $role->syncPermissions([$permission1->id, $permission2->id, $permission3->id, $permission4->id]);
        $user = User::get()->first();
        $user->assignRole('admin');

        $role = Role::get()->skip(1)->first();
        $role->syncPermissions([ $permission1->id, $permission2->id, $permission3->id, $permission4->id]);

    }
}
