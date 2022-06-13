<?php

namespace App\Http\Middleware;

use Closure;

class CheckSessionMiddleware
{
    public function handle($request, Closure $next)

    {
        if (!isset(app()->session_id)) {
            return redirect('https://erp.azercosmos.az');
        }

        return $next($request);
    }
}
