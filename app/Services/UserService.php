<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Gate;

class UserService {
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function store($data) {
        $user = $this->userRepository->store($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Tài khoản được tạo thành công',
            'user' => $user
        ], 200);
    }

    public function login($data, $routePrefix) {
        if ($this->userRepository->login($data, $routePrefix)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công.',
                'token' => $this->userRepository->createToken(),
            ], 200);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác.'
        ], 401);
    }

    public function logout() {
        try {
            if (!$this->userRepository->isLoggedIn()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Bạn đã đăng xuất rồi.'
                ], 400);
            }

            $this->userRepository->deleteToken();

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thành công.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAll() {
        $users = $this->userRepository->getAll();

        return response()->json([
            'user' => $users,
        ], 200);
    }

    public function getById($id) {
        $user = $this->userRepository->getById($id);

        $response = Gate::inspect("view", $user);

        if ($response->allowed()) {
            return response()->json([
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không thể xem hồ sơ của người dùng này!',
            ], 403);
        }
    }

    public function getByRole($type) {
        $users = $this->userRepository->getByRole($type);

        return response()->json([
            'users' => $users,
        ], 200);
    }

    public function profile() {
        $user = $this->userRepository->profile();
        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function update($data, $id) {
        $user = $this->userRepository->getById($id);

        $response = Gate::inspect("update", $user);

        if ($response->allowed()) {
            $this->userRepository->update($data, $user);
            return response()->json([
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không chỉnh sửa hồ sơ của người dùng này!',
            ], 403);
        }
    }

    public function setEmailVerified($id) {
        $user = $this->userRepository->getById($id);

        $this->userRepository->setEmailVerified($user);

        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 200);
    }

    public function switchStatus($id) {
        $user = $this->userRepository->getById($id);

        $response = Gate::inspect("view", $user);

        if ($response->allowed()) {
            $this->userRepository->switchStatus($user);

            return response()->json([
                'message' => 'success',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không thể chỉnh sửa hồ sơ của người dùng này!',
            ], 404);
        }
    }
}
