<?php

namespace App\Http\Controllers\ServiceCategory;

use App\Http\Controllers\Controller;
use App\Services\ServiceCategory\ServiceCategoryInterface;
use Illuminate\Http\Request;
use Throwable;

class ServiceCategoryController extends Controller
{
    public function __construct(
        private readonly ServiceCategoryInterface $categoryService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $categories = $this->categoryService->list((bool) $request->boolean('active_only'));

            return api_success($categories, 'Service categories fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        try {
            $category = $this->categoryService->find($id);

            return api_success($category, 'Service category fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'        => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'icon'        => ['nullable', 'string'],
                'status'      => ['nullable', 'in:active,inactive'],
            ]);

            $category = $this->categoryService->create($data);

            return api_success($category, 'Service category created successfully.', 201);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $data = $request->validate([
                'name'        => ['sometimes', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'icon'        => ['nullable', 'string'],
                'status'      => ['nullable', 'in:active,inactive'],
            ]);

            $category = $this->categoryService->update($id, $data);

            return api_success($category, 'Service category updated successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->categoryService->delete($id);

            return api_success(null, 'Service category deleted successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
