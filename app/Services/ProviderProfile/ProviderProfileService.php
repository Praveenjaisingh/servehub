<?php

namespace App\Services\ProviderProfile;

use App\Models\User;
use App\Repositories\ProviderProfile\ProviderProfileContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProviderProfileService implements ProviderProfileInterface
{
    protected $providerProfileContract;

    public function __construct(ProviderProfileContract $providerProfileContract)
    {
        $this->providerProfileContract = $providerProfileContract;
    }

    public function createOrUpdate(User $user, array $data)
    {
        $profile = $this->providerProfileContract->findByUserId($user->id);

        if ($profile) {
            return $this->providerProfileContract->update($profile, $data);
        }

        $data['user_id'] = $user->id;

        return $this->providerProfileContract->create($data);
    }

    public function find(int $id)
    {
        $profile = $this->providerProfileContract->find($id);

        if (! $profile) {
            throw new NotFoundHttpException('Provider profile not found.');
        }

        return $profile;
    }

    public function findByUser(User $user)
    {
        $profile = $this->providerProfileContract->findByUserId($user->id);

        if (! $profile) {
            throw new NotFoundHttpException('Provider profile not found. Please create one first.');
        }

        return $profile;
    }

    public function search(array $filters): LengthAwarePaginator
    {
        return $this->providerProfileContract->search($filters, $filters['per_page'] ?? 15);
    }

    public function verify(int $id)
    {
        $profile = $this->find($id);

        return $this->providerProfileContract->update($profile, ['is_verified' => true]);
    }

    public function delete(User $user): bool
    {
        $profile = $this->findByUser($user);

        return $this->providerProfileContract->delete($profile);
    }
}
