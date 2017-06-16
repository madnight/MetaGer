<?php

namespace App\Http\Middleware;

use Closure;

class RefererCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $refererCorrect = env('referer_check');
        $referer        = $request->server('HTTP_REFERER');
        if ($refererCorrect !== $referer && "https://metager.de/admin/count" !== $referer) {
            abort(403, 'Unauthorized');
        } else {
            return $next($request);
        }
    }
}
