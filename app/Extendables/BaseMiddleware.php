<?php

namespace App\Extendables;

use Closure;

class BaseMiddleware
{
    protected $permissions = [];

    public function __construct() {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user->hasAnyPermission($this->permissions)) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Permission denied.',
                ], 401);
            }

            return abort(401); 
        }

        return $next($request);
    }
}
