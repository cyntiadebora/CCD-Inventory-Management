<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    public $incrementing = false;      // Karena ID UUID string, bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'code',
        'type',
        'has_size',
       
        'photo',
        // 'min_stock' dan 'max_stock' sudah pindah ke ItemVariant
    ];

    // Tambahkan atribut virtual 'total_stock'
    protected $appends = ['total_stock', 'label_sizes'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relasi satu Item ke banyak ItemVariant
    public function variants()
    {
        return $this->hasMany(ItemVariant::class, 'item_id', 'id');
    }

    // Attribute dynamic untuk total stok dari semua variant
    public function getTotalStockAttribute()
    {
        // Pastikan 'variants' sudah di-load untuk menghindari query berulang
        return $this->variants->sum('current_stock');
    }

    // Contoh pengecekan stok minimum per variant
    public function isBelowMinStock()
    {
        return $this->variants->contains(fn($variant) => $variant->current_stock < $variant->min_stock);
    }

    // Contoh pengecekan stok maksimum per variant
    public function isAboveMaxStock()
    {
        return $this->variants->contains(fn($variant) => $variant->current_stock > $variant->max_stock);
    }
    public function userItemSizes()
{
    return $this->hasMany(UserItemSize::class, 'item_id', 'id');
}
// Di model Item.php
public function getLabelSizesAttribute()
{
    return $this->variants
                ->pluck('size.label')
                ->filter()
                ->unique()
                ->values();
}
// app/Models/Item.php
public function getCategoryForSizeAttribute()
{
    // Ambil kategori size dari variant pertama jika ada
    return optional($this->variants->first()?->size)->category;
}

public function displayCode()
{
    if ($this->has_size) {
        return $this->variants->pluck('variant_code')->join(', ');
    }

    return $this->code;
}



}
