<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CitizenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        

        if (Auth::user()?->role !== UserRole::Citizen){
            abort(403);
        }

        return $next($request);
    }
}
