<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\StockLog;
use Illuminate\Support\Str;

class OpeningStockController extends Controller
{
    public function create()
    {
        $items = Item::all(); // hanya kirim data item
        return view('admin.opening_stock.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_variant_id' => 'required|exists:item_variants,id',
            'log_date' => 'required|date',
            'opening_stock' => 'required|integer|min:0',
        ]);

        // Cek jika sudah ada log untuk bulan itu
        $exists = StockLog::where('item_variant_id', $validated['item_variant_id'])
            ->where('log_date', $validated['log_date'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Opening stock for this month has already been recorded.');
        }

        // Simpan ke stock_logs
        StockLog::create([
            'id' => Str::uuid(),
            'item_variant_id' => $validated['item_variant_id'],
            'log_date' => $validated['log_date'],
            'opening_stock' => $validated['opening_stock'],
            'stock_in' => 0,
            'stock_out' => 0,
            'closing_stock' => $validated['opening_stock'],
        ]);

        // Update current_stock di item_variants
        $variant = ItemVariant::findOrFail($validated['item_variant_id']);
        $variant->current_stock = $validated['opening_stock'];
        $variant->save();

        return redirect()->route('admin.opening-stock.create')->with('success', 'Opening stock successfully saved.');
    }

    public function getSizes($itemId)
    {
        $item = Item::findOrFail($itemId);

        if (!$item->has_size) {
            // Ambil satu variant pertama (jika ada) untuk item tanpa ukuran
            $variant = ItemVariant::where('item_id', $itemId)->first();

            if ($variant) {
                return response()->json([[
                    'id' => $variant->id,
                    'size' => null
                ]]);
            } else {
                return response()->json([]);
            }
        }

        // Jika item memiliki ukuran
        $variants = ItemVariant::with('size')
            ->where('item_id', $itemId)
            ->get();

        return response()->json($variants);
    }
    public function index()
{
    $stockLogs = StockLog::with([
        'itemVariant.item',   // akses nama item
        'itemVariant'         // akses variant_code
    ])
    ->orderBy('log_date', 'desc')
    ->get();

    return view('admin.opening_stock.index', compact('stockLogs'));
}
public function bulkDelete(Request $request)
{
    $ids = $request->input('ids', []);
    
    if (count($ids)) {
        StockLog::whereIn('id', $ids)->delete();
        return back()->with('success', 'Selected opening stock records deleted successfully.');
    }

    return back()->with('error', 'No records selected.');
}

}
