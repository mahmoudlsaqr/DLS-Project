<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{

    public function run(Generator $faker)
    {
        $roles = [
            'owner',
            'admin',
            'physician',
            'supervising',
            'designer',
            'technician',
            'delivery'
        ];

        $roleObjects = collect($roles)->mapWithKeys(function ($role) {
            return [$role => Role::create(['name' => $role, 'default' => 'true'])];
        });

        $permissions = Permission::pluck('id')->all();
        $roleObjects->each(function ($role) use ($permissions) {
            $role->syncPermissions($permissions);
        });

        $users = [
            ['name' => 'owner', 'email' => 'owner@demo.com', 'username' => 'owner_01', 'role' => 'owner'],
            ['name' => 'admin', 'email' => 'admin@demo.com', 'username' => 'admin_01', 'role' => 'admin'],
            ['name' => 'physician', 'email' => 'physician@demo.com', 'username' => 'physician_01', 'role' => 'physician'],
            ['name' => 'supervising', 'email' => 'supervising@demo.com', 'username' => 'supervising_01', 'role' => 'supervising'],
            ['name' => 'designer', 'email' => 'designer@demo.com', 'username' => 'designer_01', 'role' => 'designer'],
            ['name' => 'technician', 'email' => 'technician@demo.com', 'username' => 'technician_01', 'role' => 'technician'],
            ['name' => 'delivery', 'email' => 'delivery@demo.com', 'username' => 'delivery_01', 'role' => 'delivery'],
        ];

        $createdUsers = collect($users)->map(function ($userData) use ($roleObjects, $faker) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $userData['email'],
                'username' => $userData['username'],
                'role' => $userData['role'],
                'status' => 'true',
                'password' => bcrypt('1111'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            $user->assignRole([$roleObjects[$userData['role']]->id]);

            return $user;
        });

        $physician = $createdUsers->where('role', 'physician')->first();
        $supervisingId = $createdUsers->where('role', 'supervising')->first()->id;
        $physician->update(['supervising' => $supervisingId]);
    }
}
