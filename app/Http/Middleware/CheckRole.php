<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles){

        $role = explode('|', $roles);
        if(in_array(Auth::user()->id_level,$role)){
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Hak akses tidak diijinkan!',
            'code' => '405',
            'data'=> null
        ], 200);
    }
}
