<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserInterface;
use Illuminate\Http\Request;
use Throwable;

/**
 * Admin-only user management endpoints.
 */
class UserController extends Controller
{
    public function __construct(
        private readonly UserInterface $userService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userService->list($request->only(['role', 'search', 'per_page']));

            return api_success($users, 'Users fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        try {
            $user = $this->userService->find($id);

            return api_success($user, 'User fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $data = $request->validate([
                'name'    => ['sometimes', 'string', 'max:255'],
                'phone'   => ['sometimes', 'nullable', 'string', 'max:20'],
                'address' => ['sometimes', 'nullable', 'string', 'max:255'],
                'avatar'  => ['sometimes', 'nullable', 'string'],
                'password'=> ['sometimes', 'string', 'min:8'],
            ]);

            $user = $this->userService->update($id, $data);

            return api_success($user, 'User updated successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        try {
            $data = $request->validate([
                'status' => ['required', 'in:active,suspended'],
            ]);

            $user = $this->userService->updateStatus($id, $data['status']);

            return api_success($user, 'User status updated successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->userService->delete($id);

            return api_success(null, 'User deleted successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
