<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RequestItem;


class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'status',
        'approved_by',
         'proof_image_path', 
         'admin_message',
    ];

    // ✅ Relasi ke detail item dalam request
    //public function items()
    //{
      //  return $this->hasMany(RequestItem::class);
    //}

    // ✅ Relasi ke user yang mengajukan
   // ✅ Relasi ke detail item dalam request
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    // ✅ Relasi ke user yang mengajukan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relasi ke user yang menyetujui
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ✅ Untuk enum helper
    public static function getEnumValues($column)
    {
        $result = \DB::select("SHOW COLUMNS FROM requests WHERE Field = '{$column}'");

        $type = $result[0]->Type;

        preg_match('/^enum\((.*)\)$/', $type, $matches);

        return collect(explode(',', $matches[1]))->map(function ($value) {
            return trim($value, "'");
        })->toArray();
    }
}
