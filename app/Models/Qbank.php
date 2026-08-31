<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Qbank extends BaseModel
{
    protected $table = "qbank";
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function questions()
    {
        return $this->hasMany(QbankQuestion::class)->where('status', 1);
    }

    public function question_count()
    {
        return $this->hasMany(QbankQuestion::class)->where('status', 1)->count();
    }

    public function added_marks()
    {
        return $this->hasMany(QbankQuestion::class)->where('status', 1)->sum('marks');
    }
}
