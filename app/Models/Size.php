<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'catogory',    // misal 'clothing', 'shoe', dll
        'size_label',   // misal 'S', 'M', '36', dll
    ];

    public function itemVariants()
    {
        return $this->hasMany(ItemVariant::class, 'size_id', 'id');
    }
}
