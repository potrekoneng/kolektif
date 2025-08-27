<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'peserta'],
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'username' => 'potrekoneng',
            'locked' => 'unlocked',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role_id' => '1',
        ]);
        // User::factory(10)->create();

        // Role::factory()->create([
        //     'name' => 'Admin',
        // ]);

        // User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@email.com',
        //     'password' => 'ajisoko0',
        // ]);
    }
}
