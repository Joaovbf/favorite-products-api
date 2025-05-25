<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\SecurityScheme(
 *     securityScheme="basicAuth",
 *     type="http",
 *     scheme="basic"
 * )
 */
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
     *     tags={"Authentication"},
     *     summary="Generate API token using Basic Authentication",
     *     description="Authenticates user with Basic Auth and returns a Bearer token for subsequent API calls",
     *     security={{"basicAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully generated API token",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcdef123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function getToken(Request $request)
    {
        $token = $request->user()->createToken('token');

        return ['token' => $token->plainTextToken];
    }
}
