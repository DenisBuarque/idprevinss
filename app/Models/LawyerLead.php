<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawyerLead extends Model
{
    use HasFactory;

    protected $table = 'lawyer_lead';
    protected $fillable = ['lawyer_id','lead_id'];
}
