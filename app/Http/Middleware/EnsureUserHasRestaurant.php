<?php

namespace App\Http\Middleware;

use App\Enums\Status;
use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRestaurant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->roles->pluck('name')->contains('super-admin')) {
            return $next($request);
        }

        if (!$user->restaurant_id) {
            return redirect()->route('restaurant.create');
        }

     

        return $next($request);
    }
}
