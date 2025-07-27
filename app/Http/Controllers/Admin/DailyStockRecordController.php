<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyStockRecord;
use App\Models\Item;
use App\Models\Size;
use App\Models\ItemVariant;

class DailyStockRecordController extends Controller
{
    /**
     * Menampilkan daftar laporan stok harian
     */
    public function index(Request $request)
    {
        $query = DailyStockRecord::with(['item', 'size']);

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        $records = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.daily_stock_records.index', compact('records'));
    }

    /**
     * Menampilkan form input stok harian
     */
    public function create()
    {
        $items = Item::all();
        $sizes = Size::all();
        return view('admin.daily_stock_records.create', compact('items', 'sizes'));
    }

    /**
     * Menyimpan data stok harian yang baru + update min/max ke ItemVariant
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'item_id'    => 'required|exists:items,id',
            'size_id'    => 'required|exists:sizes,id',
            'stok_awal'  => 'required|integer|min:0',
            'masuk'      => 'required|integer|min:0',
            'min_stok'   => 'nullable|integer|min:0',
            'max_stok'   => 'nullable|integer|min:0',
        ]);

        $stok_awal = $request->stok_awal;
        $masuk = $request->masuk;
        $keluar = 0; // default
        $stok_akhir = $stok_awal + $masuk - $keluar;

        // Simpan data stok harian
        DailyStockRecord::create([
            'tanggal'    => $request->tanggal,
            'item_id'    => $request->item_id,
            'size_id'    => $request->size_id,
            'stok_awal'  => $stok_awal,
            'masuk'      => $masuk,
            'keluar'     => $keluar,
            'stok_akhir' => $stok_akhir,
        ]);

        // Update atau buat entri ItemVariant
        $variant = ItemVariant::firstOrCreate(
            [
                'item_id' => $request->item_id,
                'size_id' => $request->size_id,
            ],
            [
                'current_stock' => $stok_akhir, // jika baru
            ]
        );

        if ($request->filled('min_stok')) {
            $variant->min_stock = $request->min_stok;
        }

        if ($request->filled('max_stok')) {
            $variant->max_stock = $request->max_stok;
        }

        // Update stok terakhir
        $variant->current_stock = $stok_akhir;
        $variant->save();

        return redirect()->route('daily_stock_records.index')->with('success', 'Stok harian dan batas stok berhasil disimpan.');
    }

    /**
     * Memperbarui data stok harian (opsional)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_awal' => 'required|integer|min:0',
            'masuk'     => 'required|integer|min:0',
        ]);

        $record = DailyStockRecord::findOrFail($id);

        $record->stok_awal  = $request->stok_awal;
        $record->masuk      = $request->masuk;
        $record->stok_akhir = $record->stok_awal + $record->masuk - $record->keluar;
        $record->save();

        return redirect()->back()->with('success', 'Data stok harian berhasil diperbarui.');
    }
}
