<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@sparehub.my.id')],
            [
                'name' => env('ADMIN_NAME', 'Admin Sparehub'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );
    }
}
