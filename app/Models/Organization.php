<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    protected $casts = [
        'organization_permissions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_count()
    {
        return User::where([
            ['organization_id', $this->id]
        ])->count();
    }
}
