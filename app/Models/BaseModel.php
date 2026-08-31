<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (lms_is_organization() && lms_organization_id() > 0 &&
            !in_array(static::class, ['App\Models\QbankQuestion'])) {
                $model->organization_id = lms_organization_id();
            }
            if (lms_user_id() > 0) {
                $model->created_by_id = lms_user_id();
            }
        });

        static::updating(function ($model) {
            if (lms_user_id() > 0) {
                // p(static::class); 
                // App\Models\Exam
                $model->updated_by_id = lms_user_id();
            }
        });
    }
}
