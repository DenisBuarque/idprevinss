<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','file','action_id'];

    public function action() 
    {
        return $this->belongsTo(Action::class);
    }
}
