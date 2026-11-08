<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya satu akun pengguna untuk sistem pos gudang
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@sparehub.my.id')],
            [
                'name' => env('ADMIN_NAME', 'Pak Anto (Kepala Gudang)'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );
    }
}
