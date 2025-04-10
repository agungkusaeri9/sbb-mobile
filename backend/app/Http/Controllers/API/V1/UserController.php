<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $userRepository;
    private $imageUploadService;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->imageUploadService = new ImageUploadService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request('page', 1);
        $limit = request('limit', 10);
        $search = request('search', null);
        $result = $this->userRepository->getAll($search, $limit, $page);
        return ResponseFormatter::success($result['data'], 'Users Fetched Successfully', 200, $result['pagination']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        try {
            if (request()->file('image'))
                $data['image'] = request()->file('image')->store('users', 'public');
            $user = $this->userRepository->create($data);
            return ResponseFormatter::success($user, 'User Created Successfully', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user)
                return ResponseFormatter::error(null, 'User Not Found', 404);
            return ResponseFormatter::success($user, 'User Fetched Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = request()->only('name', 'email');
            if (request('password'))
                $data['password'] = bcrypt(request('password'));
            $user = $this->userRepository->findById($id);
            if ($request->file('image')) {
                Storage::disk('public')->delete($user->image);
                $data['image'] = $this->imageUploadService->handleImageUpload($request->file('image'), 'users');
            }
            $user = $this->userRepository->update($id, $data);
            return ResponseFormatter::success($user, 'User Updated Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user)
                return ResponseFormatter::error(null, 'User Not Found', 404);
            if ($user->image)
                Storage::disk('public')->delete($user->image);
            $this->userRepository->delete($id);
            return ResponseFormatter::success(null, 'User Deleted Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    public function delete_array()
    {
        $validator = validator(request()->all(), [
            'ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors(), 422);
        }

        try {
            $ids = request('ids');
            $delete = $this->userRepository->delete_array($ids);
            return ResponseFormatter::success($delete, 'Users Deleted Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }
}
