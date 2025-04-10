<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Post(
 *     path="/api/v1/auth/register",
 *     summary="Register",
 *     description="Register user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email", "password","password_confirmation"},
 *             @OA\Property(property="name", type="string", example="User"),
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User Registered",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="User"),
 *             @OA\Property(property="email", type="string", example="user@example.com"),
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

class RegisterController extends Controller
{

    private  $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {

        $data = $request->validated();
        try {
            $user = $this->userRepository->create($data);
            return ResponseFormatter::success($user, 'User Registered', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
