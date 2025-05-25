<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function __construct()
    {
        try {
            Auth::onceBasic();
        } catch (\Exception $_) {
            abort(response()->json([
                'message' => 'Invalid credentials'
            ], 401));
        }
    }

    public function getToken(Request $request)
    {
        $token = $request->user()->createToken('token');

        return ['token' => $token->plainTextToken];
    }
}
