<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'inventory@example.com'],
            [
                'name' => 'IT Inventory',
                'password' => Hash::make(
                    env('INVENTORY_ADMIN_PASSWORD')
                ),
            ]
        );
    }
}
