<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tools = [
            [
                'name' => 'Cordless Drill Bosch GSR 120-LI',
                'rack_location' => 'Rak A-01',
                'total_quantity' => 3,
                'maintenance_quantity' => 1,
                'status' => 'available',
            ],
            [
                'name' => 'Obeng Listrik Bosch Go 3.6V',
                'rack_location' => 'Rak A-05',
                'total_quantity' => 5,
                'maintenance_quantity' => 0,
                'status' => 'available',
            ],
            [
                'name' => 'Digital Multimeter Fluke 87V',
                'rack_location' => 'Lemari Alat Ukur L-01',
                'total_quantity' => 2,
                'status' => 'available',
            ],
            [
                'name' => 'Jangka Sorong Mitutoyo 150mm',
                'rack_location' => 'Lemari Alat Ukur L-05',
                'total_quantity' => 4,
                'status' => 'available',
            ],
            [
                'name' => 'Torque Wrench KTC 1/2 inch 40-200Nm',
                'rack_location' => 'Rak B-01',
                'total_quantity' => 2,
                'status' => 'available',
            ],
            [
                'name' => 'Angle Grinder Maktec MT954',
                'rack_location' => 'Rak A-02',
                'total_quantity' => 3,
                'status' => 'available',
            ],
            [
                'name' => 'Impact Wrench DeWalt DCF899 18V',
                'rack_location' => 'Rak A-03',
                'total_quantity' => 2,
                'status' => 'available',
            ],
            [
                'name' => 'Insulation Tester Megger MIT310',
                'rack_location' => 'Lemari Alat Ukur L-02',
                'total_quantity' => 1,
                'status' => 'available',
            ],
            [
                'name' => 'Hydraulic Bottle Jack 10 Ton Tekiro',
                'rack_location' => 'Area Lantai Bawah R-01',
                'total_quantity' => 2,
                'status' => 'available',
            ],
            [
                'name' => 'Laser Distance Meter Bosch GLM 50C',
                'rack_location' => 'Lemari Alat Ukur L-03',
                'total_quantity' => 2,
                'status' => 'available',
            ],
            [
                'name' => 'Combination Wrench Set 14 pcs Tekiro',
                'rack_location' => 'Rak B-02',
                'total_quantity' => 4,
                'status' => 'available',
            ],
            [
                'name' => 'Reciprocating Saw Makita JR3050T',
                'rack_location' => 'Rak A-04',
                'total_quantity' => 1,
                'status' => 'unavailable',
            ],
        ];

        foreach ($tools as $tool) {
            Tool::firstOrCreate(['name' => $tool['name']], $tool);
        }
    }
}
