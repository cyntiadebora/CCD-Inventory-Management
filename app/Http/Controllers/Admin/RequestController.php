<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\ItemVariant;
use App\Models\StockTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;


class RequestController extends Controller
{
    // Menampilkan semua request
    public function index()
    {
       $requests = RequestModel::with([
    'user', // untuk menampilkan nama user
    'requestItems.itemVariant.item', // untuk akses nama item dari itemVariant
    'requestItems.itemVariant.size'  // untuk akses size dari itemVariant
])->orderBy('created_at', 'desc')->get();


    $totalRequests = $requests->count();

    return view('admin.requests.index', compact('requests', 'totalRequests'));
    }

    // Approve request dan kurangi stok
public function approve($id, Request $req)
{
    $req->validate([
    'admin_message' => 'required|string|max:1000',
]);
    $request = RequestModel::with(['requestItems.itemVariant.item', 'requestItems.itemVariant.size', 'user'])->findOrFail($id);

    $adminMessage = $req->input('admin_message', null);
    $request->admin_message = $adminMessage;

    // 🟡 Tambahkan pengecekan apakah status = pending atau waiting_return
    if (!in_array($request->status, ['waiting_return', 'pending'])) {
        return redirect()->back()->with('error', 'Only pending or waiting-return requests can be approved.');
    }

    // Kurangi stok
    foreach ($request->requestItems as $requestItem) {
        $variant = $requestItem->itemVariant;
        if (!$variant) {
            return redirect()->back()->with('error', "Item variant not found.");
        }

        if ($variant->current_stock < $requestItem->quantity) {
            return redirect()->back()->with('error', "Insufficient stock.");
        }

        $variant->current_stock -= $requestItem->quantity;
        $variant->save();

        StockTransaction::create([
            'id' => Str::uuid(),
            'item_variant_id' => $variant->id,
            'transaction_type' => 'out',
            'quantity' => $requestItem->quantity,
            'description' => 'Stock out for approved request #' . $request->id,
            'transaction_date' => now(),
        ]);
    }

    $request->status = 'approved';
    $request->approved_by = auth()->id();
    $request->save();

    try {
        if ($request->user && $request->user->email) {
            Mail::to($request->user->email)->send(new SendEmail($request->user, $request, $adminMessage));
        }
    } catch (\Exception $e) {
        Log::error('Gagal mengirim email ke user: ' . $e->getMessage());
    }

    return redirect()->back()->with('success', 'Request approved. Stock updated and email sent.');
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
    public function bulkDelete(Request $request)
{
    $request->validate([
        'selected_requests' => 'required|array',
    ]);

    // Hapus request dan juga relasi terkait jika perlu
    foreach ($request->selected_requests as $id) {
        $req = \App\Models\Request::find($id);
        if ($req) {
            $req->requestItems()->delete(); // jika ada relasi
            $req->delete();
        }
    }

    return redirect()->route('admin.requests.index')->with('success', 'Selected requests have been deleted.');
}

// Menyetujui request setelah barang rusak dikembalikan
public function waitingReturn($id, Request $req)
{
    $req->validate([
    'admin_message' => 'required|string|max:1000',
]);
    $request = RequestModel::findOrFail($id);

    if ($request->status !== 'pending') {
        return redirect()->back()->with('error', 'Only pending requests can be marked as waiting for return.');
    }

    $adminMessage = $req->input('admin_message');
    $request->admin_message = $adminMessage;
    $request->status = 'waiting_return';
    $request->approved_by = auth()->id();
    $request->save();

    try {
    if ($request->user && $request->user->email) {
        Mail::to($request->user->email)->send(new SendEmail($request->user, $request, $adminMessage));
    }
} catch (\Exception $e) {
    Log::error('Gagal mengirim email saat waitingReturn: ' . $e->getMessage());
}

    return redirect()->back()->with('success', 'Request marked as waiting for item return.');
}



}
