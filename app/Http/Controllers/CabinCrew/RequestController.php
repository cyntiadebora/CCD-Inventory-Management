<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\Item;
use App\Models\RequestItem;
use App\Models\ItemVariant;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function create()
    {
        $userGender = Auth::user()->gender;

        // Ambil item sesuai gender user
        $items = Item::with(['variants.size'])
            ->whereIn('type', [$userGender, 'All'])
            ->get();

        // Ambil ukuran user berdasarkan item_id
        $userSizes = Auth::user()->userItemSizes()
            ->with('itemVariant.size')
            ->get()
            ->mapWithKeys(function ($uis) {
                return [$uis->itemVariant->item_id => $uis->itemVariant->size];
            });

        // Ambil enum dari kolom 'type' (kecuali annual)
        $types = array_filter(
            RequestModel::getEnumValues('type'),
            fn($type) => $type !== 'annual'
        );

        return view('cabin_crew.request_form', compact('items', 'userSizes', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:annual,approval,buyer',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'proof_image' => 'required_if:type,approval|image|max:2048',
        ]);

        $selectedItems = collect($validated['items'])->filter(fn($data) => !empty($data['selected']));

        if ($selectedItems->isEmpty()) {
            return redirect()->back()
                ->withErrors(['items' => 'You must select at least one item.'])
                ->withInput();
        }

        foreach ($validated['items'] as $itemId => $data) {
            if (isset($data['selected']) && $data['selected']) {
                $quantity = $data['quantity'] ?? 1;
                if ($quantity > 2) {
                    return redirect()->back()
                        ->withErrors(['quantity' => "Maksimal request untuk item ID $itemId adalah 2"])
                        ->withInput();
                }
            }
        }

        $user = Auth::user();
        $proofImagePath = null;

        if ($request->hasFile('proof_image')) {
            $filename = time() . '_' . $request->file('proof_image')->getClientOriginalName();
            $request->file('proof_image')->move(public_path('images'), $filename);
            $proofImagePath = $filename;
        }

        $newRequest = RequestModel::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'proof_image_path' => $proofImagePath,
            'status' => 'pending',
        ]);

        $userSizes = $user->userItemSizes()
            ->with(['itemVariant.size'])
            ->get()
            ->mapWithKeys(function ($uis) {
                return [$uis->itemVariant->item_id => (object)[
                    'size_label' => optional($uis->itemVariant->size)->size_label,
                    'variant_id' => $uis->item_variant_id
                ]];
            });

        foreach ($validated['items'] as $itemId => $data) {
            if (isset($data['selected']) && $data['selected']) {
                $userSize = $userSizes->get($itemId);

                $sizeLabel = $userSize?->size_label;
                $variantId = $userSize?->variant_id;

                $variant = ItemVariant::find($variantId);

                if (!$variant) {
                    return redirect()->back()
                        ->withErrors(['variant' => "Item variant tidak ditemukan untuk item ID $itemId dan size $sizeLabel"])
                        ->withInput();
                }

                RequestItem::create([
                    'request_id' => $newRequest->id,
                    'item_id' => $itemId,
                    'item_variant_id' => $variant->id,
                    'size_label' => $sizeLabel,
                    'quantity' => $data['quantity'] ?? 1,
                ]);
            }
        }

        return redirect()->route('requests.create')->with('success', 'Request submitted successfully!');
    }

    public function storeOtherSize(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:annual,approval,buyer',
            'description' => 'nullable|string',
            'proof_image' => 'required_if:type,approval|image|max:2048',
            'items' => 'required|array',
        ]);

        $selectedItems = collect($validated['items'])->filter(fn($data) => !empty($data['selected']));

        if ($selectedItems->isEmpty()) {
            return redirect()->back()
                ->withErrors(['items' => 'You must select at least one item.'])
                ->withInput();
        }

        foreach ($selectedItems as $itemId => $data) {
            if (($data['quantity'] ?? 1) > 2) {
                return redirect()->back()
                    ->withErrors(['quantity' => "Maximum quantity for item $itemId is 2."])
                    ->withInput();
            }

            if (!isset($data['size_id']) || !$data['size_id']) {
                return redirect()->back()
                    ->withErrors(["size" => "Size must be selected for item $itemId."])
                    ->withInput();
            }
        }

        $user = Auth::user();
        $proofImagePath = null;

        if ($request->hasFile('proof_image')) {
            $filename = time() . '_' . $request->file('proof_image')->getClientOriginalName();
            $request->file('proof_image')->move(public_path('images'), $filename);
            $proofImagePath = $filename;
        }

        $newRequest = RequestModel::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'proof_image_path' => $proofImagePath,
            'status' => 'pending',
        ]);

        foreach ($selectedItems as $itemId => $data) {
            $variant = ItemVariant::where('item_id', $itemId)
                ->where('size_id', $data['size_id'])
                ->first();

            if (!$variant) {
                return redirect()->back()
                    ->withErrors(['variant' => "Variant not found for item ID $itemId with selected size."])
                    ->withInput();
            }

            $sizeLabel = optional($variant->size)->size_label;

            RequestItem::create([
                'request_id' => $newRequest->id,
                'item_id' => $itemId,
                'item_variant_id' => $variant->id,
                'size_label' => $sizeLabel,
                'quantity' => $data['quantity'] ?? 1,
                 'custom_size' => true, // 🚩 tanda dari form custom
            ]);
        }

        return redirect()->route('requests.create')->with('success', 'Request with other size submitted successfully!');
    }
    public function createOtherSize()
{
    $userGender = Auth::user()->gender;

    $items = \App\Models\Item::with(['variants.size'])
        ->whereIn('type', [$userGender, 'All'])
        ->get();

    $types = array_filter(
        \App\Models\Request::getEnumValues('type'),
        fn($type) => $type !== 'annual'
    );

    return view('cabin_crew.request_other_size', compact('items', 'types'));
}

}
