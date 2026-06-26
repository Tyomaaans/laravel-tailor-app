<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $customerRecord = Customer::query()->firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'phone' => '+10000000004',
                'address' => '123 Tailor Street',
            ]
        );

        $users = [
            ['name' => 'John Doe', 'email' => 'johndoe@example.com', 'role' => 'admin'],
            ['name' => 'Alexis', 'email' => 'alexis@example.com', 'role' => 'manager'],
            ['name' => 'Xander Will', 'email' => 'willxander@example.com', 'role' => 'tailor'],
        ];

        foreach ($users as $data) {
            $user = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('@Indonesia1993'),
                    'phone' => '+1000000000'.substr($data['email'], 0, 1),
                    'address' => '123 Tailor Street',
                    'role'     => $data['role'],
                ]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
