<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\ItemVariant;

class RequestController extends Controller
{
    // Menampilkan semua request
    public function index()
    {
       $requests = RequestModel::with('requestItems.item')->orderBy('created_at', 'desc')->get();

    $totalRequests = $requests->count();

    return view('admin.requests.index', compact('requests', 'totalRequests'));
    }

    // Approve request dan kurangi stok
    public function approve($id)
    {
        $request = RequestModel::with('requestItems.item', 'requestItems.size')->findOrFail($id);

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'The request has already been processed.');
        }

        foreach ($request->requestItems as $requestItem) {
            $item = $requestItem->item;

            if ($item->has_size) {
                // Jika item memiliki ukuran
                $variant = ItemVariant::where('item_id', $item->id)
                    ->where('size', $requestItem->size->size_label) // sesuaikan jika beda nama
                    ->first();

                if ($variant && $variant->current_stock >= $requestItem->quantity) {
                    $variant->current_stock -= $requestItem->quantity;
                    $variant->save();
                } else {
                    return redirect()->back()->with('error', "Insufficient stock for item {$item->name} size {$requestItem->size->size_label}.");
                }
            } else {
                // Jika item tidak memiliki ukuran
                if ($item->current_stock >= $requestItem->quantity) {
                    $item->current_stock -= $requestItem->quantity;
                    $item->save();
                } else {
                    return redirect()->back()->with('error', "Insufficient stock for item {$item->name}.");
                }
            }
        }

        $request->status = 'approved';
        $request->save();

        return redirect()->back()->with('success', 'Request approved and stock updated.');
    }

    // Reject request
    public function reject($id)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'The request has already been processed.');
        }

        $request->status = 'rejected';
        $request->save();

        return redirect()->back()->with('success', 'The request has been rejected.');
    }
}
