<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'request_id',
        'item_id',
        'size_label',
        'quantity',
        'item_variant_id',
        'custom_size', // ✅ tambahkan ini!
    ];

    // ✅ Tambahkan ini supaya item_name dan size_label bisa digunakan di view
    protected $appends = ['item_name', 'size_label'];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    // ✅ nama item dari variant
    public function getItemNameAttribute()
    {
        return $this->itemVariant->item->name ?? 'Item not found';
    }

    // ✅ size label dari variant atau fallback dari kolom
    public function getSizeLabelAttribute()
    {
        return $this->itemVariant->size->size_label ?? $this->attributes['size_label'];
    }
    public function size()
{
    return $this->hasOneThrough(
        Size::class,
        ItemVariant::class,
        'id',             // foreign key di item_variants
        'id',             // foreign key di sizes
        'item_variant_id',// foreign key di request_items
        'size_id'         // foreign key di item_variants
    );
}

}

