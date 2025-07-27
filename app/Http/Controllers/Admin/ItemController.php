<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('variants.size')->get();
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $sizeByCategory = Size::all()->groupBy('category');
        $categories = $sizeByCategory->keys();

        return view('admin.items.create', [
            'sizeByCategory' => $sizeByCategory,
            'categories' => $categories,
            'oldVariants' => old('variants', []),
            'oldSizeType' => old('size_type'),
        ]);
    }

    public function store(Request $request)
    {
        $hasSize = $request->boolean('has_size');

        $rules = [
            'code' => 'required|string|unique:items,code|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Male,Female,All',
            'has_size' => 'required|boolean',
            'photo' => 'nullable|image|max:2048',
        ];

        if ($hasSize) {
            $rules['category'] = 'required|string';
            $rules['variants'] = 'required|array|min:1';
            $rules['variants.*.size'] = 'required|string';
            $rules['variants.*.variant_code'] = 'required|string|distinct|unique:item_variants,variant_code';
            $rules['variants.*.min_stock'] = 'nullable|integer|min:0';
            $rules['variants.*.max_stock'] = 'nullable|integer|gte:variants.*.min_stock';
        } else {
            $rules['min_stock'] = 'required|integer|min:0';
            $rules['max_stock'] = 'required|integer|gte:min_stock';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $validated['photo'] = $filename;
        }

        $item = Item::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'has_size' => $hasSize,
            'photo' => $validated['photo'] ?? null,
        ]);

        if ($hasSize) {
            foreach ($validated['variants'] as $variant) {
                $size = Size::firstOrCreate([
                    'category' => $validated['category'],
                    'size_label' => $variant['size'],
                ]);

                ItemVariant::create([
                    'item_id' => $item->id,
                    'size_id' => $size->id,
                    'variant_code' => $variant['variant_code'],
                    'min_stock' => $variant['min_stock'] ?? 0,
                    'max_stock' => $variant['max_stock'] ?? 0,
                ]);
            }
        } else {
            ItemVariant::create([
                'item_id' => $item->id,
                'size_id' => null,
                'variant_code' => null,
                'min_stock' => $validated['min_stock'],
                'max_stock' => $validated['max_stock'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Item created successfully!');
    }

    public function edit(Item $item)
    {
        $item->load('variants.size');
        $sizeByCategory = Size::all()->groupBy('category');
        $categories = $sizeByCategory->keys();

        return view('admin.items.edit', [
            'item' => $item,
            'categories' => $categories,
            'sizeByCategory' => $sizeByCategory,
        ]);
    }

   public function update(Request $request, Item $item)
{
    $hasSize = $request->boolean('has_size');

    $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string|in:Male,Female,All',
        'has_size' => 'nullable|boolean',
        'photo' => 'nullable|image|max:2048',
    ];

    if ($hasSize) {
        $rules['category'] = 'nullable|string|max:255';
        $rules['variants'] = 'required|array';
        $rules['variants.*.id'] = 'nullable|exists:item_variants,id';
        $rules['variants.*.variant_code'] = 'required|string|distinct';
        $rules['variants.*.size'] = 'required_if:variants.*.id,null|string';
        $rules['variants.*.min_stock'] = 'nullable|integer|min:0';
        $rules['variants.*.max_stock'] = 'nullable|integer|gte:variants.*.min_stock';
    } else {
        $variantId = optional($item->variants->first())->id;
        $rules['variant_code'] = 'nullable|string|unique:item_variants,variant_code,' . ($variantId ?? 'NULL');
        $rules['min_stock'] = 'nullable|integer|min:0';
        $rules['max_stock'] = 'nullable|integer|gte:min_stock';
    }

    $validated = $request->validate($rules);

    $item->name = $validated['name'];
    $item->type = $validated['type'];
    $item->has_size = $hasSize;

    if ($request->hasFile('photo')) {
        if ($item->photo && file_exists(public_path('images/' . $item->photo))) {
            unlink(public_path('images/' . $item->photo));
        }

        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $item->photo = $filename;
    }

    $item->save();

    if ($hasSize) {
        $inputVariantIds = collect($validated['variants'])->pluck('id')->filter()->all();
        $existingVariantIds = $item->variants()->pluck('id')->all();
        $toDelete = array_diff($existingVariantIds, $inputVariantIds);
        if (!empty($toDelete)) {
            ItemVariant::whereIn('id', $toDelete)->delete();
        }

        foreach ($validated['variants'] as $variantData) {
            $variant = isset($variantData['id']) ? ItemVariant::find($variantData['id']) : null;

            if ($variant) {
                $variant->variant_code = $variantData['variant_code'];
                $variant->min_stock = $variantData['min_stock'] ?? 0;
                $variant->max_stock = $variantData['max_stock'] ?? 0;
                $variant->save();
            } else {
                $size = Size::firstOrCreate([
                    'size_label' => $variantData['size'],
                    'category' => $validated['category'] ?? null,
                ]);

                ItemVariant::create([
                    'item_id' => $item->id,
                    'size_id' => $size->id,
                    'variant_code' => $variantData['variant_code'],
                    'min_stock' => $variantData['min_stock'] ?? 0,
                    'max_stock' => $variantData['max_stock'] ?? 0,
                ]);
            }
        }
    } else {
        $variant = $item->variants()->first();

        if ($variant) {
            $variant->update([
                'variant_code' => $validated['variant_code'] ?? null,
                'min_stock' => $validated['min_stock'] ?? 0,
                'max_stock' => $validated['max_stock'] ?? 0,
            ]);
        } else {
            ItemVariant::create([
                'item_id' => $item->id,
                'size_id' => null,
                'variant_code' => $validated['variant_code'] ?? null,
                'min_stock' => $validated['min_stock'] ?? 0,
                'max_stock' => $validated['max_stock'] ?? 0,
            ]);
        }
    }

    return redirect()->route('dashboard')->with('success', 'Item berhasil diupdate.');
}

    public function destroy(Item $item)
    {
        $item->variants()->delete();

        if ($item->photo && file_exists(public_path('images/' . $item->photo))) {
            unlink(public_path('images/' . $item->photo));
        }

        $item->delete();

        return redirect()->route('dashboard')->with('success', 'Item berhasil dihapus.');
    }

    public function show(Item $item)
    {
        return view('admin.items.show', compact('item'));
    }

    public function showDetail($id)
    {
        $item = Item::with('variants.size')->findOrFail($id);
        return response()->json($item);
    }
    public function getSizes($itemId)
{
    $variants = ItemVariant::with('size')
                ->where('item_id', $itemId)
                ->get();

    return response()->json($variants);
}
public function reorder()
{
    // Ambil semua item dan variannya
    $items = Item::with('variants.size')->get();

    // Filter item yang memiliki current stock di bawah atau sama dengan min_stock
    $reorderItems = $items->filter(function ($item) {
        foreach ($item->variants as $variant) {
            if ($variant->current_stock <= $variant->min_stock) {
                return true;
            }
        }
        return false;
    });

    return view('admin.items.reorder', compact('reorderItems'));
}


}
