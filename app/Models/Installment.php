<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','financial_id','value','date','active','image'];

    public function financial() {
        return $this->belongsTo(Financial::class);
    }
}
