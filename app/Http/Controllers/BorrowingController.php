<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Technician;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource with form embedded on top.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with([
            'tool',
            'technician',
            'recordedBy',
            'returnedBy',
        ]);

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        if ($request->filled('condition')) {
            $query->where('return_condition', $request->condition);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('tool', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%")
                       ->orWhere('rack_location', 'like', "%{$search}%");
                })
                ->orWhereHas('technician', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%")
                       ->orWhere('department', 'like', "%{$search}%");
                });
            });
        }

        $borrowings = $query->latest('borrowed_at')->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Redirect to dashboard since borrowing form is on dashboard.
     */
    public function create()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Store a newly created borrowing.
     */
    public function store(Request $request)
    {
        if (!$request->filled('tool_id') && $request->filled('tool_name')) {
            $matchedTool = Tool::where('name', trim($request->tool_name))->first();
            if ($matchedTool) {
                $request->merge(['tool_id' => $matchedTool->id]);
            }
        }

        if (!$request->filled('quantity')) {
            $request->merge(['quantity' => 1]);
        }

        $validated = $request->validate([
            'tool_id' => ['required', 'exists:tools,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'technician_name' => ['required', 'string', 'max:255'],
            'technician_department' => ['required', 'string', 'max:255'],
            'technician_phone' => ['nullable', 'string', 'max:20'],
            'borrowing_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'tool_id.required' => 'Pilih alat yang akan dipinjam.',
            'quantity.required' => 'Jumlah alat yang dipinjam wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah pinjam minimal 1 unit.',
            'technician_name.required' => 'Masukkan nama teknisi peminjam.',
            'technician_department.required' => 'Departemen teknisi wajib diisi.',
        ]);

        $tool = Tool::withSum(['activeBorrowings as active_borrowings_sum_quantity'], 'quantity')->findOrFail($validated['tool_id']);
        if ($tool->available_quantity <= 0) {
            return back()
                ->withInput()
                ->with('error', 'Semua unit alat "' . $tool->name . '" saat ini sedang dipinjam atau dalam perbaikan.');
        }

        if ($validated['quantity'] > $tool->available_quantity) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah yang ingin dipinjam (' . $validated['quantity'] . ' unit) melebihi stok yang tersedia (' . $tool->available_quantity . ' unit).');
        }

        $phone = $validated['technician_phone'] ?? null;

        $technician = Technician::firstOrCreate(
            ['name' => trim($validated['technician_name'])],
            [
                'department' => trim($validated['technician_department']),
                'phone' => $phone,
            ]
        );

        if ($technician->department !== $validated['technician_department'] || ($phone && $technician->phone !== $phone)) {
            $technician->update([
                'department' => $validated['technician_department'],
                'phone' => $phone ?: $technician->phone,
            ]);
        }

        Borrowing::create([
            'tool_id' => $tool->id,
            'quantity' => $validated['quantity'],
            'technician_id' => $technician->id,
            'recorded_by' => auth()->id() ?? \App\Models\User::first()->id,
            'borrowed_at' => now(),
            'borrowing_notes' => $validated['borrowing_notes'] ?? null,
        ]);

        $qtyText = $validated['quantity'] > 1 ? $validated['quantity'] . ' unit ' : '';

        return back()
            ->with('success', 'Peminjaman ' . $qtyText . 'alat "' . $tool->name . '" untuk ' . $technician->name . ' berhasil dicatat.');
    }

    public function returnForm(Borrowing $borrowing)
    {
        return redirect()->route('borrowings.index');
    }

    public function returnDirect(Request $request)
    {
        $validated = $request->validate([
            'borrowing_id' => ['required', 'exists:borrowings,id'],
            'return_condition' => ['required', 'in:good,damaged,needs_inspection'],
            'return_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'borrowing_id.required' => 'Pilih peminjaman alat yang akan dikembalikan terlebih dahulu.',
            'borrowing_id.exists' => 'Data peminjaman tidak ditemukan di sistem.',
            'return_condition.required' => 'Kondisi fisik alat saat dikembalikan wajib dipilih.',
            'return_condition.in' => 'Kondisi alat tidak valid.',
        ]);

        $borrowing = Borrowing::findOrFail($validated['borrowing_id']);

        return $this->processReturn($borrowing, $validated);
    }

    public function returnTool(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'return_condition' => ['required', 'in:good,damaged,needs_inspection'],
            'return_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'return_condition.required' => 'Pilih kondisi alat saat dikembalikan.',
            'return_condition.in' => 'Kondisi alat tidak valid.',
        ]);

        return $this->processReturn($borrowing, $validated);
    }

    protected function processReturn(Borrowing $borrowing, array $validated)
    {
        if ($borrowing->returned_at !== null) {
            return back()
                ->with('error', 'Alat ini sudah tercatat telah dikembalikan sebelumnya.');
        }

        $borrowing->update([
            'returned_at' => now(),
            'returned_by' => auth()->id() ?? \App\Models\User::first()->id,
            'return_condition' => $validated['return_condition'],
            'return_notes' => $validated['return_notes'] ?? null,
        ]);

        if (in_array($validated['return_condition'], ['damaged', 'needs_inspection'])) {
            $tool = $borrowing->tool;
            $qty = (int) ($borrowing->quantity ?? 1);
            if ($tool) {
                $maxCanAdd = max(0, $tool->total_quantity - $tool->maintenance_quantity);
                $addQty = min($qty, $maxCanAdd);
                if ($addQty > 0) {
                    $tool->increment('maintenance_quantity', $addQty);
                }
            }
        }

        $qtyText = ($borrowing->quantity && $borrowing->quantity > 1) ? ' (' . $borrowing->quantity . ' unit)' : '';

        return back()
            ->with('success', 'Pengembalian alat "' . ($borrowing->tool->name ?? 'Alat') . '"' . $qtyText . ' berhasil dicatat.');
    }
}
