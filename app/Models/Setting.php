<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = [
        'picklist' => 'array'
    ];
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];
}
