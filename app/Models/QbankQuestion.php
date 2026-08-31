<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QbankQuestion extends BaseModel
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];
}
