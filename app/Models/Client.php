<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'zip_code',
        'address',
        'number',
        'district',
        'city',
        'state',
        'tag',
        'process',
        'situation',
        'financial',
        'action',
        'court',
        'stick',
        'term',
        'user_id',
        'responsible',
        'date_fulfilled',
        'greeting',
        'confirmed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function photos()
    {
        return $this->hasMany(ClientPhotos::class);
    }

    public function financial() 
    {
        return $this->hasOne(Financial::class);
    }

    protected $casts = [
        'term' => 'datetime'
    ];

}
