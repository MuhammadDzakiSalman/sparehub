<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Technician;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource and dashboard metrics.
     */
    public function index()
    {
        $totalTools = (int) Tool::sum('total_quantity');

        $borrowedTools = (int) Borrowing::whereNull('returned_at')
            ->sum('quantity');

        $inspectionTools = (int) Tool::sum('maintenance_quantity')
            + (int) Tool::where('status', 'unavailable')->sum('total_quantity');

        $availableTools = max(0, $totalTools - $borrowedTools - $inspectionTools);

        $availableToolsList = Tool::withSum(['activeBorrowings as active_borrowings_sum_quantity'], 'quantity')
            ->where('status', 'available')
            ->get()
            ->filter(function ($tool) {
                return $tool->available_quantity > 0;
            });

        $savedTechnicians = Technician::orderBy('name')->get();

        $activeBorrowings = Borrowing::with([
            'tool',
            'technician',
        ])
            ->whereNull('returned_at')
            ->latest('borrowed_at')
            ->get();

        return view('dashboard.index', compact(
            'totalTools',
            'availableTools',
            'borrowedTools',
            'inspectionTools',
            'activeBorrowings',
            'availableToolsList',
            'savedTechnicians'
        ));
    }
}
