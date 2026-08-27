<?php

namespace App\Services\Service;

use App\Models\Service;
use App\Models\User;
use App\Repositories\ProviderProfile\ProviderProfileContract;
use App\Repositories\Service\ServiceContract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceService implements ServiceInterface
{
    protected $serviceContract;
    protected $providerProfileContract;

    public function __construct(ServiceContract $serviceContract, ProviderProfileContract $providerProfileContract)
    {
        $this->serviceContract = $serviceContract;
        $this->providerProfileContract = $providerProfileContract;
    }

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->serviceContract->paginate($filters, $filters['per_page'] ?? 15);
    }

    public function find(int $id)
    {
        $service = $this->serviceContract->findWithProvider($id);

        if (! $service) {
            throw new NotFoundHttpException('Service not found.');
        }

        return $service;
    }

    public function create(User $provider, array $data)
    {
        $profile = $this->providerProfileContract->findByUserId($provider->id);

        if (! $profile) {
            throw ValidationException::withMessages([
                'provider' => ['You must create a provider profile before adding services.'],
            ]);
        }

        $data['provider_id'] = $profile->id;
        $data['slug'] = generate_unique_slug(Service::class, $data['title']);
        $data['status'] = $data['status'] ?? 'active';

        return $this->serviceContract->create($data);
    }

    public function update(User $provider, int $id, array $data)
    {
        $service = $this->authorizeOwnership($provider, $id);

        if (isset($data['title']) && $data['title'] !== $service->title) {
            $data['slug'] = generate_unique_slug(Service::class, $data['title']);
        }

        return $this->serviceContract->update($service, $data);
    }

    public function delete(User $provider, int $id): bool
    {
        $service = $this->authorizeOwnership($provider, $id);

        return $this->serviceContract->delete($service);
    }

    public function myServices(User $provider, array $filters): LengthAwarePaginator
    {
        $profile = $this->providerProfileContract->findByUserId($provider->id);

        if (! $profile) {
            throw new NotFoundHttpException('Provider profile not found.');
        }

        return $this->serviceContract->forProvider($profile->id, $filters, $filters['per_page'] ?? 15);
    }

    /**
     * Ensures the authenticated provider owns the service being modified.
     */
    private function authorizeOwnership(User $provider, int $serviceId): Service
    {
        $service = $this->serviceContract->findWithProvider($serviceId);

        if (! $service) {
            throw new NotFoundHttpException('Service not found.');
        }

        if ($service->provider->user_id !== $provider->id) {
            throw new AuthorizationException('You are not authorized to modify this service.');
        }

        return $service;
    }
}
