<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //protected $appends = ['is_enrolled', 'is_attended'];
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'type_id');
    }
}
