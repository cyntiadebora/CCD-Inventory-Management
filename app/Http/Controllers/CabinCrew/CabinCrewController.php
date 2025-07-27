<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class CabinCrewController extends Controller
{
    public function index()
    {
        // Ambil user + relasi yang dibutuhkan
        $user = Auth::user()->load([
            'userItemSizes.itemVariant.item',
            'userItemSizes.itemVariant.size',
            'requests.requestItems.item',
            'requests.requestItems.size',
        ]);

        // Ambil request terakhir
        $latestRequest = $user->requests->sortByDesc('created_at')->first();

        // Ambil semua item sebagai header kolom
        $allItems = Item::orderBy('name')->get();

        // Ambil semua request (full histori)
        $allRequests = $user->requests->sortByDesc('created_at');

        return view('cabin_crew.dashboard', compact('user', 'latestRequest', 'allItems', 'allRequests'));
    }
    
}
