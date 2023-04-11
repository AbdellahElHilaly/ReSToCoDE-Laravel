<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ApiResponceHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authentified
{

    use ApiResponceHandler;

    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){
            return $this->apiResponse(null, false, 'You are already logged in', 100);
        }
        return $next($request);
    }
}
