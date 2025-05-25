<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED));
    }
}
