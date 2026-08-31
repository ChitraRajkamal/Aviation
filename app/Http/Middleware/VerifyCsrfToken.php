<?php

namespace App\Http\Middleware;

class VerifyCsrfToken
{
    protected $except = [
        'admin-assets/*'
    ];
}
