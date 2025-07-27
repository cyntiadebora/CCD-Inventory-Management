<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StockLog extends Model
{
    use HasUuids;

    // Nama tabel (opsional kalau sudah sesuai konvensi Laravel)
    protected $table = 'stock_logs';

    // Primary key bertipe UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Kolom yang bisa diisi massal (mass assignment)
    protected $fillable = [
        'id',
        'item_variant_id',
        'log_date',
        'opening_stock',
        'stock_in',
        'stock_out',
        'closing_stock',
        'created_at',
        'updated_at',
    ];

    // Relasi ke ItemVariant (jika ingin digunakan)
    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class);
    }
}
