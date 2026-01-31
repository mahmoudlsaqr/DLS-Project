<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this ->call([
            PermissionsSeeder::class,
            UsersSeeder::class,
            RegionSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            NationalitiesSeeder::class,
//            BranchesSeeder::class,
        ]);

//        $faker = Faker::create();
//        foreach (range(1,10) as $index) {
//            User::insert([
//                'name' => $faker ->name,
//                'username' => $faker ->username,
//                'email' => $faker ->email,
//                'password' => bcrypt($faker ->password),
//            ]);
//        }
    }
}
