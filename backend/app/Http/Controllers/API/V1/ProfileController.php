<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     summary="Get Profile",
     *     description="You can get your profile",
     *     tags={"Profile"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Profile Retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="User"),
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
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

    public function get()
    {
        try {
            $user = $this->userRepository->getProfile();
            return ResponseFormatter::success($user, 'Profile Retrieved Successfully', 200);
        } catch (\Throwable $th) {
            throw $th;
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        try {
            $user = $this->userRepository->updateProfile($data);
            return ResponseFormatter::success($user, 'Profile Updated Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }
}
