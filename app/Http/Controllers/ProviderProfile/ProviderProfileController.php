<?php

namespace App\Http\Controllers\ProviderProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\StoreProviderProfileRequest;
use App\Http\Requests\Provider\UpdateProviderProfileRequest;
use App\Services\ProviderProfile\ProviderProfileInterface;
use Illuminate\Http\Request;
use Throwable;

class ProviderProfileController extends Controller
{
    public function __construct(
        private readonly ProviderProfileInterface $providerProfileService
    ) {
    }

    public function store(StoreProviderProfileRequest $request)
    {
        try {
            $profile = $this->providerProfileService->createOrUpdate($request->user(), $request->validated());

            return api_success($profile, 'Provider profile saved successfully.', 201);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(UpdateProviderProfileRequest $request)
    {
        try {
            $profile = $this->providerProfileService->createOrUpdate($request->user(), $request->validated());

            return api_success($profile, 'Provider profile updated successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function me(Request $request)
    {
        try {
            $profile = $this->providerProfileService->findByUser($request->user());

            return api_success($profile, 'Provider profile fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        try {
            $profile = $this->providerProfileService->find($id);

            return api_success($profile, 'Provider profile fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function index(Request $request)
    {
        try {
            $profiles = $this->providerProfileService->search(
                $request->only(['city', 'skill', 'per_page'])
            );

            return api_success($profiles, 'Providers fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function verify(int $id)
    {
        try {
            $profile = $this->providerProfileService->verify($id);

            return api_success($profile, 'Provider verified successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->providerProfileService->delete($request->user());

            return api_success(null, 'Provider profile deleted successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
