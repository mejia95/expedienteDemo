<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class PermisoImportacionesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if(Auth::user()->rol != 1 and Auth::user()->rol!=2 ){
            if(Auth::user()->status!=3 or Auth::user()->activo!=1 ){
                return redirect()->route('dashboard');
            }
            return redirect()->route('dashboard');
        }   
        return $next($request);
    }
}
