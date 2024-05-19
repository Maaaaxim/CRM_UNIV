<?php

namespace App\Http\Middleware;

use Closure;

class CheckIP
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

        $allowed_ips = ['111.111.11.11'];

        if (!in_array($request->ip(), $allowed_ips)) {
            return redirect('https://www.google.com');
        }
        return $next($request);
    }

}
