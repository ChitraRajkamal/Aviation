<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationRole extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function staff_count()
    {
        return $this->hasMany(User::class, 'role_id')->where([
            ['status', 1],
            ['role', 'organization']
        ])->count();
    }
}
