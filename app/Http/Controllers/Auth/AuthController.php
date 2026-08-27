<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthInterface;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthInterface $authService
    ) {
    }

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());

            return api_success($result, 'Registration successful.', 201);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->login($request->validated());

            return api_success($result, 'Login successful.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());

            return api_success(null, 'Logged out successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $this->authService->profile($request->user());

            return api_success($user, 'Profile fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
