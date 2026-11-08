<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tools = Tool::query()
            ->with(['activeBorrowings.technician'])
            ->withCount(['activeBorrowings'])
            ->withSum(['activeBorrowings as active_borrowings_sum_quantity'], 'quantity')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('rack_location', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tool = new Tool();

        return view('tools.create', compact('tool'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'rack_location' => ['nullable', 'string', 'max:100'],
            'total_quantity' => ['required', 'integer', 'min:1'],
            'maintenance_quantity' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama alat wajib diisi.',
            'total_quantity.required' => 'Jumlah alat wajib diisi.',
            'total_quantity.min' => 'Jumlah alat minimal 1 unit.',
            'maintenance_quantity.min' => 'Jumlah unit perbaikan tidak boleh negatif.',
        ]);

        $validated['status'] = 'available';
        $validated['maintenance_quantity'] = (int) ($validated['maintenance_quantity'] ?? 0);

        if ($validated['maintenance_quantity'] > $validated['total_quantity']) {
            return back()->withInput()->withErrors([
                'maintenance_quantity' => 'Jumlah unit perbaikan tidak boleh melebihi total unit alat.',
            ]);
        }

        Tool::create($validated);

        return redirect()
            ->route('tools.index')
            ->with('success', 'Data alat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        $tool->load([
            'activeBorrowings.technician',
            'borrowings.technician',
        ]);

        return view('tools.show', compact('tool'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool)
    {
        return view('tools.edit', compact('tool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'rack_location' => ['nullable', 'string', 'max:100'],
            'total_quantity' => ['required', 'integer', 'min:1'],
            'maintenance_quantity' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama alat wajib diisi.',
            'total_quantity.required' => 'Jumlah alat wajib diisi.',
            'total_quantity.min' => 'Jumlah alat minimal 1 unit.',
            'maintenance_quantity.min' => 'Jumlah unit perbaikan tidak boleh negatif.',
        ]);

        $validated['maintenance_quantity'] = (int) ($validated['maintenance_quantity'] ?? 0);

        $borrowed = (int) $tool->activeBorrowings()->sum('quantity');
        if ($validated['total_quantity'] < $borrowed) {
            return back()->withInput()->withErrors([
                'total_quantity' => "Total unit tidak boleh lebih kecil dari {$borrowed} unit yang sedang dipinjam.",
            ]);
        }

        $maxMaintenance = max(0, $validated['total_quantity'] - $borrowed);
        if ($validated['maintenance_quantity'] > $maxMaintenance) {
            return back()->withInput()->withErrors([
                'maintenance_quantity' => "Jumlah unit perbaikan maksimal {$maxMaintenance} unit (karena ada {$borrowed} unit yang sedang dipinjam).",
            ]);
        }

        $tool->update($validated);

        return redirect()
            ->route('tools.index')
            ->with('success', 'Data alat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tool $tool)
    {
        if ($tool->borrowings()->exists()) {
            return redirect()
                ->route('tools.index')
                ->with('error', 'Alat tidak dapat dihapus karena sudah memiliki riwayat peminjaman.');
        }

        $tool->delete();

        return redirect()
            ->route('tools.index')
            ->with('success', 'Data alat berhasil dihapus.');
    }
}
