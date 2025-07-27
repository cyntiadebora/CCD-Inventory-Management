<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserItemSize extends Model
{
    protected $fillable = [
        'user_id',
        'item_variant_id', // ← Hanya menyimpan relasi ke item_variant
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke ItemVariant
    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    // Accessor untuk item (bukan relasi)
    public function getItemAttribute()
    {
        return $this->itemVariant?->item;
    }

    // Accessor untuk size (bukan relasi)
    public function getSizeAttribute()
    {
        return $this->itemVariant?->size;
    }
}
