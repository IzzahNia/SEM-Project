<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'admin', // Admin role
            'contact_number' => '0123456789', // Example contact number
        ])->assignRole('admin'); // Assign role if using Spatie permissions

        User::create([
            'name' => 'Vayne Lee',
            'email' => 'vayne@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'admin', // Admin role
            'contact_number' => '0123456789', // Example contact number
        ])->assignRole('admin'); // Assign role if using Spatie permissions

        // Create a normal user
        User::create([
            'name' => 'Afiq Rashidi',
            'email' => 'afiq@gmail.com',
            'password' => Hash::make('test1234'), 
            'role' => 'user',
            'contact_number' => '0987623321', 
        ])->assignRole('user'); 

        User::create([
            'name' => 'Jing Wen',
            'email' => 'jingwen@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0187634221',
        ])->assignRole('user');

        User::create([
            'name' => 'Chua Kian Pheng',
            'email' => 'chua@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0137654321',
        ])->assignRole('user');

        User::create([
            'name' => 'Chong Xue Liang',
            'email' => 'chong@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0137654321',
        ])->assignRole('user');

        User::create([
            'name' => 'Muthu',
            'email' => 'muthu@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0117611321',
        ])->assignRole('user');

        User::create([
            'name' => 'Ahmad Bin Jamek',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0127611999',
        ])->assignRole('user');

        User::create([
            'name' => 'Siti Binti Abdullah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0167611312',
        ])->assignRole('user');

        User::create([
            'name' => 'Najib Bin Khalid',
            'email' => 'najib@gmail.com',
            'password' => Hash::make('test1234'), // Replace with a secure password
            'role' => 'user', // Normal user role
            'contact_number' => '0122611321',
        ])->assignRole('user');
    }
}
