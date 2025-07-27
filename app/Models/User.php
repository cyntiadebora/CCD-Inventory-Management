<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'id_number',
        'role',
        'status',
        'password',
        'gender',
        'base',
        'join_date',
        'rank',
        'batch',
        'photo',
        ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Perbaikan: properti $casts, bukan method
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke user_item_sizes (ukuran item milik user).
     */
    
   // App\Models\User.php
public function userItemSizes()
{
    return $this->hasMany(UserItemSize::class, 'user_id', 'id');
}



    /**
     * Relasi ke semua request yang diajukan user.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Relasi ke permintaan terakhir user.
     */
    public function latestRequest()
    {
        return $this->hasOne(Request::class)->latestOfMany();
    }
    
}
