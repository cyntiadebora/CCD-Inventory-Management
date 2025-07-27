<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Size;
use App\Models\UserItemSize;
use App\Models\Item;
use App\Models\ItemVariant;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $cabinCrews = User::where('role', 'cabin_crew')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('join_date', 'asc')
            ->get();

        return view('admin.users.index', [
            'cabinCrews' => $cabinCrews,
            'judul' => 'Cabin Crew List',
        ]);
    }


    public function activeUsers()
    {
        $cabinCrews = User::where('role', 'cabin_crew')->where('status', 'active')->get();
        return view('admin.users.index', [
            'cabinCrews' => $cabinCrews,
            'judul' => 'Cabin Crew List (Active)',
        ]);
    }

    public function create()
    {
        $items = Item::with('variants.size')->get();
        $sizes = Size::all();
        return view('admin.users.create', compact('items', 'sizes'));
    }

    public function show($id)
    {
        $crew = User::with('userItemSizes.itemVariant.item', 'userItemSizes.itemVariant.size')->findOrFail($id);

        $nonSizeItems = $crew->userItemSizes
            ->filter(fn($uis) => $uis->item && !$uis->item->has_size)
            ->map(fn($uis) => $uis->item->name)
            ->unique()
            ->values();

        $sizes = Size::all();
        return view('admin.users.show', compact('crew', 'nonSizeItems', 'sizes'));
    }

    public function edit($id)
    {
        $crew = User::with(['userItemSizes.itemVariant.item', 'userItemSizes.itemVariant.size'])->findOrFail($id);
        $items = Item::all();
        $sizes = Size::all();
        return view('admin.users.edit', compact('crew', 'items', 'sizes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_number' => 'nullable|string|unique:users,id_number',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'status' => 'required|in:active,inactive',
            'gender' => 'required|in:male,female',
            'base' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'rank' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'password' => [
                'required',
                'string',
                'min:5',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validatedData['photo'] = $filename;
        }

        $validatedData['role'] = 'cabin_crew';
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if (!empty($item['item_id'])) {
                    $itemVariant = ItemVariant::where('item_id', $item['item_id'])
                        ->where(function ($query) use ($item) {
                            if (!empty($item['size_id'])) {
                                $query->where('size_id', $item['size_id']);
                            } else {
                                $query->whereNull('size_id');
                            }
                        })
                        ->first();

                    if ($itemVariant) {
                        UserItemSize::create([
                            'user_id' => $user->id,
                            'item_variant_id' => $itemVariant->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('users.show', $user->id)->with('success', 'Cabin crew berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'id_number' => 'nullable|string|unique:users,id_number,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive',
            'gender' => 'required|in:male,female',
            'base' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'rank' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => [
                    'string',
                    'min:5',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                ],
            ]);
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validatedData['photo'] = $filename;
        }

        $user->update($validatedData);

        $user->userItemSizes()->delete();

        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                if (!empty($itemData['item_id'])) {
                    $variant = ItemVariant::where('item_id', $itemData['item_id'])
                        ->when($itemData['size_id'] ?? null, fn($q) => $q->where('size_id', $itemData['size_id']))
                        ->first();

                    if ($variant) {
                        $user->userItemSizes()->create([
                            'item_variant_id' => $variant->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully.');
    }

    public function updateItems(Request $request, $id)
    {
        $request->validate([
            'sizes' => 'required|array',
            'sizes.*' => 'required|exists:sizes,id',
        ]);

        foreach ($request->sizes as $userItemSizeId => $newSizeId) {
            $userItemSize = UserItemSize::find($userItemSizeId);
            if ($userItemSize && $userItemSize->user_id == $id) {
                $userItemSize->size_id = $newSizeId;
                $userItemSize->save();
            }
        }

        return redirect()->route('users.show', $id)->with('success', 'Ukuran item berhasil diperbarui.');
    }

    public function addItems(Request $request, $id)
    {
        $request->validate([
            'item_variant_ids' => 'required|array',
            'item_variant_ids.*' => 'exists:item_variants,id',
        ]);

        foreach ($request->item_variant_ids as $variantId) {
            $exists = UserItemSize::where('user_id', $id)->where('item_variant_id', $variantId)->exists();
            if (!$exists) {
                UserItemSize::create([
                    'user_id' => $id,
                    'item_variant_id' => $variantId,
                ]);
            }
        }

        return redirect()->route('admin.users.show', $id)->with('success', 'Item berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->photo && file_exists(public_path('images/' . $user->photo))) {
            unlink(public_path('images/' . $user->photo));
        }

        $user->userItemSizes()->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Cabin Crew berhasil dihapus.');
    }
    public function editSelf()
    {
        $admin = auth()->user(); // ambil admin yang sedang login
        return view('admin.profile.edit', compact('admin'));
    }
    public function updateSelf(Request $request)
{
    $admin = auth()->user();

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $admin->id,
        'gender' => 'required|in:male,female',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'password' => [
            'nullable',
            'string',
            'min:5',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ],
    ]);

        // Jika ada upload foto baru
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validatedData['photo'] = $filename;
        }

        // Jika mengisi password baru
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        $admin->update($validatedData);

        return redirect()->route('admin.personal-profile')->with('success', 'Profil berhasil diperbarui.');
    }


}
