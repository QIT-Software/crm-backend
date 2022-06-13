<?php

namespace App\Http\Middleware;

use App\People;
use Closure;

class PermissionMiddleware
{
    public function handle($request, Closure $next)
    {
        $user_id = app()->session_id; // it will come from session in the future
        $permissions = People::selectUsersPermissionById($user_id);
        if ($permissions[0]->admin === 1) {
            return $next($request);
        }

        if ($permissions[0]->edit === 1 && str_contains($request->path(), 'delete')){
            return redirect('/accounts');
        }
        elseif ($permissions[0]->edit === 0 && (str_contains($request->path(), 'delete') || str_contains($request->path(), 'update') || str_contains($request->path(), 'add'))){
            return redirect('/accounts');
        }
        else {
            return $next($request);
        }
    }
}
