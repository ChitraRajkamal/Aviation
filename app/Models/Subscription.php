<?php

namespace App\Models;

class Subscription extends BaseModel
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
