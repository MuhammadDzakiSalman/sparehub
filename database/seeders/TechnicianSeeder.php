<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicians = [
            [
                'name' => 'Budi Santoso',
                'department' => 'Mechanical Engineering',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'department' => 'Electrical Maintenance',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Rian Hidayat',
                'department' => 'Plant Maintenance',
                'phone' => '081234567893',
            ],
            [
                'name' => 'Siti Rahmawati',
                'department' => 'Quality Inspection',
                'phone' => '081234567894',
            ],
            [
                'name' => 'Hendra Pratama',
                'department' => 'Mechanical Engineering',
                'phone' => '081234567895',
            ],
            [
                'name' => 'Doni Kusuma',
                'department' => 'Hydraulics & Pneumatics',
                'phone' => '081234567896',
            ],
            [
                'name' => 'Eko Prasetyo',
                'department' => 'Welding & Fabrication',
                'phone' => '081234567897',
            ],
        ];

        foreach ($technicians as $technician) {
            Technician::firstOrCreate(['name' => $technician['name']], $technician);
        }
    }
}
