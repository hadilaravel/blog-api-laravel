<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Access\Entities\Permission;
use Modules\Access\Entities\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


//        admin
        $user = User::query()->where('username' , 'admin')->first();
        if(empty($user)) {
            $user =  User::query()->create([
                'name' => 'admin',
                'username' => 'admin',
                'password' => bcrypt('Blog@22'),
                'user_type' => 1,
                'activation' => 1,
                'profile' => 'image/admin/admin.png'
            ]);
        }
        $role = Role::query()->where('name' , 'super admin')->first();
        if(empty($role)) {
            $role = Role::query()->create([
                'name' => 'super admin',
                'description' => 'ادمین کل',
            ]);
        }
        $user->roles()->sync($role);

        foreach (Permission::$permissions as $permission)
        {
            $permissionExists = Permission::query()->where('name' , $permission)->first();
            if(empty($permissionExists)) {
                Permission::query()->create([
                    'name' => $permission,
                ]);
            }
        }
        $perRol = Permission::all();
        $role->permissions()->sync($perRol);

    }
}
