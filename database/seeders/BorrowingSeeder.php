<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Technician;
use App\Models\Tool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $technicians = Technician::all();
        $tools = Tool::all();

        if (!$admin || $technicians->isEmpty() || $tools->isEmpty()) {
            return;
        }

        $pastBorrowings = [
            [
                'tool' => $tools[0] ?? null,
                'technician' => $technicians[0] ?? null,
                'borrowed_at' => Carbon::now()->subDays(6)->setHour(8)->setMinute(30),
                'returned_at' => Carbon::now()->subDays(5)->setHour(16)->setMinute(45),
                'return_condition' => 'good',
            ],
            [
                'tool' => $tools[5] ?? null,
                'technician' => $technicians[6] ?? null,
                'borrowed_at' => Carbon::now()->subDays(4)->setHour(9)->setMinute(15),
                'returned_at' => Carbon::now()->subDays(4)->setHour(17)->setMinute(0),
                'return_condition' => 'good',
            ],
            [
                'tool' => $tools[2] ?? null,
                'technician' => $technicians[1] ?? null,
                'borrowed_at' => Carbon::now()->subDays(3)->setHour(13)->setMinute(0),
                'returned_at' => Carbon::now()->subDays(3)->setHour(15)->setMinute(30),
                'return_condition' => 'good',
            ],
            [
                'tool' => $tools[4] ?? null,
                'technician' => $technicians[4] ?? null,
                'borrowed_at' => Carbon::now()->subDays(2)->setHour(8)->setMinute(0),
                'returned_at' => Carbon::now()->subDays(2)->setHour(11)->setMinute(20),
                'return_condition' => 'needs_inspection',
            ],
            [
                'tool' => $tools[11] ?? null,
                'technician' => $technicians[2] ?? null,
                'borrowed_at' => Carbon::now()->subDays(5)->setHour(8)->setMinute(45),
                'returned_at' => Carbon::now()->subDays(4)->setHour(16)->setMinute(15),
                'return_condition' => 'damaged',
            ],
        ];

        foreach ($pastBorrowings as $item) {
            if ($item['tool'] && $item['technician']) {
                Borrowing::create([
                    'tool_id' => $item['tool']->id,
                    'technician_id' => $item['technician']->id,
                    'recorded_by' => $admin->id,
                    'returned_by' => $admin->id,
                    'borrowed_at' => $item['borrowed_at'],
                    'returned_at' => $item['returned_at'],
                    'return_condition' => $item['return_condition'],
                    'borrowing_notes' => null,
                    'return_notes' => null,
                ]);
            }
        }

        $activeBorrowings = [
            [
                'tool' => $tools[0] ?? null,
                'technician' => $technicians[0] ?? null,
                'borrowed_at' => Carbon::now()->subHours(1),
            ],
            [
                'tool' => $tools[2] ?? null,
                'technician' => $technicians[1] ?? null,
                'borrowed_at' => Carbon::now()->subHours(3),
            ],
            [
                'tool' => $tools[1] ?? null,
                'technician' => $technicians[2] ?? null,
                'borrowed_at' => Carbon::now()->subHours(2),
            ],
            [
                'tool' => $tools[3] ?? null,
                'technician' => $technicians[3] ?? null,
                'borrowed_at' => Carbon::now()->subHours(4),
            ],
            [
                'tool' => $tools[6] ?? null,
                'technician' => $technicians[5] ?? null,
                'borrowed_at' => Carbon::now()->subHours(5),
            ],
        ];

        foreach ($activeBorrowings as $item) {
            if ($item['tool'] && $item['technician']) {
                Borrowing::create([
                    'tool_id' => $item['tool']->id,
                    'technician_id' => $item['technician']->id,
                    'recorded_by' => $admin->id,
                    'returned_by' => null,
                    'borrowed_at' => $item['borrowed_at'],
                    'returned_at' => null,
                    'return_condition' => null,
                    'borrowing_notes' => null,
                    'return_notes' => null,
                ]);
            }
        }
    }
}
