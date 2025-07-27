<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\RequestItem;
use App\Models\Request as RequestModel;
use App\Models\ItemVariant;

class DashboardController extends Controller
{
    public function index()
{
    // Ambil semua item + relasi variants + size
    $items = Item::with(['variants.size'])
        ->orderByRaw("FIELD(type, 'Female', 'Male', 'All')")
        ->orderBy('name')
        ->get();

    $activeCrewCount = User::where('role', 'cabin_crew')
        ->where('status', 'active')
        ->count();

    $totalRequests = RequestModel::where('status', 'pending')->count();

    $reorderCount = $items->flatMap(function ($item) {
        return $item->variants->filter(function ($variant) {
            return $variant->current_stock <= $variant->min_stock;
        });
    })->count();

    // Pie Chart Data
    $stockPerItem = $items->map(function ($item) {
        return [
            'item_name' => $item->name,
            'total_stock' => $item->variants->sum('current_stock'),
            'sizes' => $item->variants->map(function ($variant) {
                return [
                    'size' => optional($variant->size)->size_label ?? 'No Size',
                    'stock' => $variant->current_stock
                ];
            })
        ];
    });

    return view('dashboard', compact('items', 'activeCrewCount', 'totalRequests', 'reorderCount', 'stockPerItem'));
}
}
