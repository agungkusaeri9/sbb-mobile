<?php


namespace App\Repositories\Eloquent;

use App\Http\Controllers\API\V1\ResponseFormatter;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    public function getAll($search = null, $limit = 10, $page = 1)
    {
        $query = User::whereNotNull('id');
        if (isset($search)) {
            $query->where('name', 'like', '%' . $search . '%')->orWHere('email', 'like', '%' . $search . '%');
        }
        $users = $query->orderBy('name', 'ASC')->paginate($limit, ['*'], 'page', $page);
        return [
            'data' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
            ]
        ];
    }

    public function findById($id)
    {
        $user = User::find($id);
        if (!$user)
            return null;
        return new UserResource($user);
    }

    public function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return $user;
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        if (!$user)
            throw new \Exception("User not found.");
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return $user;
    }

    public function login($data)
    {
        if (! $token = Auth::attempt($data)) {
            return [
                'error' => true,
                'message' => 'Email or password is wrong'
            ];
        }
        return $this->respondWithToken($token);
    }
    public function respondWithToken($token)
    {
        return [
            'error' => false,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 99999999,
                'user' => Auth::user()
            ]
        ];
    }

    public function getProfile()
    {
        $user = Auth::user();
        if (!$user)
            throw new \Exception("User not found.");
        return new UserResource($user);
    }

    public function updateProfile($data)
    {
        $user = Auth::user();
        if (!$user)
            throw new \Exception("User not found.");
        if (isset($data['password']))
            $data['password'] = bcrypt($data['password']);
        $user->update($data);
        return $user;
    }

    public function delete_array($arr)
    {
        User::whereIn('id', $arr)->delete();
        return true;
    }
}
