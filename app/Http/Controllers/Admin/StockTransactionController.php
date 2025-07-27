<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Size;
use App\Models\StockTransaction;
use App\Models\Request as RequestModel; // tambahkan ini di atas

class StockTransactionController extends Controller
{
    public function create()
    {
        $items = Item::all();
        $sizes = Size::all(); // untuk dropdown size

        return view('admin.transactions.create', compact('items', 'sizes'));
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'item_id' => 'required|uuid|exists:items,id',
        'size_id' => 'nullable|exists:sizes,id',
        'quantity' => 'required|integer|min:1',
        'transaction_date' => 'required|date',
        'description' => 'nullable|string',
    ]);

    $item = Item::findOrFail($validated['item_id']);

    $variant = ItemVariant::where('item_id', $item->id)
        ->when($item->has_size, fn ($query) => $query->where('size_id', $validated['size_id']))
        ->when(!$item->has_size, fn ($query) => $query->whereNull('size_id'))
        ->firstOrFail();

    // 🔴 CEK apakah penambahan melebihi batas max_stock
    $calculatedStock = $variant->current_stock + $validated['quantity'];
    if ($calculatedStock > $variant->max_stock) {
        return back()->withErrors([
            'quantity' => 'Stock exceeds the allowed maximum limit (maximum: ' . $variant->max_stock . ').'
        ])->withInput();
    }

    // ✅ Simpan transaksi
    StockTransaction::create([
        'item_variant_id' => $variant->id,
        'transaction_type' => 'in',
        'quantity' => $validated['quantity'],
        'transaction_date' => $validated['transaction_date'],
        
        'description' => $validated['description'],
    ]);

    // ✅ Update stok
    $variant->increment('current_stock', $validated['quantity']);

    return redirect()->route('dashboard')->with('success', 'Stock In berhasil ditambahkan.');
}


public function index(Request $request)
{
    $type = $request->get('type'); // ?type=in|out|all
    $month = $request->get('month'); // ?month=5
    $year = $request->get('year');   // ?year=2025

    $query = StockTransaction::with(['itemVariant.item', 'itemVariant.size', 'request.user']); // ✅ tambahkan ini

    if ($type === 'in' || $type === 'out') {
        $query->where('transaction_type', $type);
    }

    if ($month) {
        $query->whereMonth('transaction_date', $month);
    }

    if ($year) {
        $query->whereYear('transaction_date', $year);
    }

    $transactions = $query->latest('transaction_date')->get();

    return view('admin.transactions.index', compact('transactions', 'type'));
}


public function storeOutFromRequest(Request $req, $requestId)
{
    $validated = $req->validate([
        'quantity' => 'required|integer|min:1',
        'transaction_date' => 'required|date',
    ]);

    $requestModel = RequestModel::with('itemVariant')->findOrFail($requestId);
    $variant = $requestModel->itemVariant;

    if ($variant->current_stock < $validated['quantity']) {
        return back()->withErrors([
            'quantity' => 'Stock not sufficient. Available: ' . $variant->current_stock,
        ])->withInput();
    }

    StockTransaction::create([
        'item_variant_id'  => $variant->id,
        'transaction_type' => 'out',
        'quantity'         => $validated['quantity'],
        'transaction_date' => $validated['transaction_date'],
        'request_id'       => $requestId,
        'description'      => 'Approved Request',
    ]);

    $variant->decrement('current_stock', $validated['quantity']);

    return redirect()->route('admin.transactions.index', ['type' => 'out'])
        ->with('success', 'Stock Out berhasil dicatat dari request.');
}


}
