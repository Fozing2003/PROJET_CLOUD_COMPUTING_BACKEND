<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
 protected function redirectTo(Request $request)
    {
        // ✅ POUR UNE API → PAS DE REDIRECTION
        if ($request->expectsJson()) {
            return null;
        }

        // ⚠️ Ne jamais rediriger vers login en API
        return null;
    }
}
