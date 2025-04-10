<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Post(
 *     path="/api/v1/auth/login",
 *     summary="Login user",
 *     description="Authenticates the user and returns a JWT token.",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="your.jwt.token"),
 *             @OA\Property(property="token_type", type="string", example="bearer"),
 *             @OA\Property(property="expires_in", type="integer", example=3600)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Email or password is incorrect")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", example={"Email is required",}),
 *             @OA\Property(property="password", type="string", example={"Password is required","Password must be at least 8 characters"})
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Something went wrong")
 *         )
 *     )
 * )
 */

class LoginController extends Controller
{
    private  $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();
        try {
            $response = $this->userRepository->login($data);
            if ($response['error'])
                return ResponseFormatter::error([], $response['message'], 401);
            return ResponseFormatter::success($response['data'], 'Login Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
