<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Manager;

class RedirectIfManagersExist
{
    public function handle(Request $request, Closure $next): mixed
    {
        // If managers already exist, redirect to login
        if (Manager::count() > 0) {
            return redirect()->route('manager.login');
        }

        return $next($request);
    }
}
