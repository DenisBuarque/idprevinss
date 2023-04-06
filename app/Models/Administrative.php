<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'rg',
        'cpf',
        'cep',
        'address',
        'number',
        'district',
        'city',
        'state',
        'email',
        'login',
        'inss',
        'situation',
        'requirements',
        'results',
        'benefits',
        'initial_date',
        'concessao_date',
        'fees',
        'payment',
        'reminder',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
