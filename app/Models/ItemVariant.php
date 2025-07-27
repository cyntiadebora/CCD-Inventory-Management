<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ItemVariant extends Model
{
    public $incrementing = false;      // ID bukan auto increment
    protected $keyType = 'string';    // ID bertipe string (UUID)

    protected $fillable = [
        'item_id',
        'size_id',
        'variant_code',
        'min_stock',
        'max_stock',
        'current_stock', 
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
    public function stockTransactions()
{
    return $this->hasMany(StockTransaction::class);
}

}
