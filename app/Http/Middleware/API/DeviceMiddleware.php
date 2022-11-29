<?php

namespace App\Http\Middleware\API;

use Closure;

use App\Helpers\EnvChecker;

class DeviceMiddleware
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
        $env = new EnvChecker;

        $key = $request->header('App-Key');
        $message = 'Unauthorize Device.';

        if (!$env->isDev() && $key != config('mobile.key')) {
            return response()->json([
                'message' => $message,
            ], 403);
        }

        return $next($request);
    }
}
