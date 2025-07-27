<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockTransaction extends Model
{
    protected $fillable = [
        'item_variant_id',
        'transaction_type',
        'quantity',
        'transaction_date',
        'description',
        'request_id', // ✅ Tambahkan ini!
    ];

    // Relasi ke ItemVariant
    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class);
    }
    // Relasi ke Request
     public function request()
{
    return $this->belongsTo(\App\Models\Request::class, 'request_id');
}

    // UUID otomatis saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }
 
}
