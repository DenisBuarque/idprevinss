<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','lead_id','term','audiencia','hour','tag','address','comments'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
