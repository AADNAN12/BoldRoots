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
        'login', // Exclusion temporaire pour déboguer le problème de Page Expired
        'logout', // Exclusion temporaire pour déboguer le problème de Page Expired lors de la déconnexion
    ];
}
