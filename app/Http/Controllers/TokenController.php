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

    /**
     * @OA\Get(
     *     path="/api/token",
     *     tags={"Token"},
     *     summary="Using Basic HTTP auth generates API token",
     *     @OA\Response(
     *         response=200,
     *         description="Gives the API token"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="username or password are invalid"
     *     )
     * )
     */
    public function getToken(Request $request)
    {
        $token = $request->user()->createToken('token');

        return ['token' => $token->plainTextToken];
    }
}
