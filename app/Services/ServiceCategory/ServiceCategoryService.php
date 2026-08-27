<?php

namespace App\Services\ServiceCategory;

use App\Models\ServiceCategory;
use App\Repositories\ServiceCategory\ServiceCategoryContract;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceCategoryService implements ServiceCategoryInterface
{
    protected $serviceCategoryContract;

    public function __construct(ServiceCategoryContract $serviceCategoryContract)
    {
        $this->serviceCategoryContract = $serviceCategoryContract;
    }

    public function list(bool $onlyActive = false): Collection
    {
        return $this->serviceCategoryContract->all($onlyActive);
    }

    public function find(int $id)
    {
        $category = $this->serviceCategoryContract->find($id);

        if (! $category) {
            throw new NotFoundHttpException('Service category not found.');
        }

        return $category;
    }

    public function create(array $data)
    {
        $data['slug'] = generate_unique_slug(ServiceCategory::class, $data['name']);

        return $this->serviceCategoryContract->create($data);
    }

    public function update(int $id, array $data)
    {
        $category = $this->find($id);

        if (isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = generate_unique_slug(ServiceCategory::class, $data['name']);
        }

        return $this->serviceCategoryContract->update($category, $data);
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);

        return $this->serviceCategoryContract->delete($category);
    }
}
