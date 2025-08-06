<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'thanh-toan-thanh-cong',
        'thanh-toan-thanh-cong/*',
        'api/thanh-toan-thanh-cong',
        'api/thanh-toan-thanh-cong/*',
    ];
} 