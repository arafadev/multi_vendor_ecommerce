<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([

            // Admin

            [
                'name'      => 'Admin',
                'username'  => 'admin',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('admin'),
                'role'      => 'admin',
                'status'    => 'active',
            ],

            // Vendor

            [
                'name'      => 'Ahmed Vendor',
                'username'  => 'vendor',
                'email'     => 'vendor@gmail.com',
                'password'  => Hash::make('vendor'),
                'role'      => 'vendor',
                'status'    => 'active',
            ],

            // User or Customer

            [
                'name'      => 'User',
                'username'  => 'user',
                'email'     => 'user@gmail.com',
                'password'  => Hash::make('user'),
                'role'      => 'user',
                'status'    => 'active',
            ],

        ]);
    }
}
