<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Services\Service\ServiceInterface;
use Illuminate\Http\Request;
use Throwable;

class ServiceController extends Controller
{
    public function __construct(
        private readonly ServiceInterface $serviceService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $services = $this->serviceService->list(
                $request->only(['category_id', 'search', 'min_price', 'max_price', 'per_page'])
            );

            return api_success($services, 'Services fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(int $id)
    {
        try {
            $service = $this->serviceService->find($id);

            return api_success($service, 'Service fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function store(StoreServiceRequest $request)
    {
        try {
            $service = $this->serviceService->create($request->user(), $request->validated());

            return api_success($service, 'Service created successfully.', 201);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(UpdateServiceRequest $request, int $id)
    {
        try {
            $service = $this->serviceService->update($request->user(), $id, $request->validated());

            return api_success($service, 'Service updated successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $this->serviceService->delete($request->user(), $id);

            return api_success(null, 'Service deleted successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function mine(Request $request)
    {
        try {
            $services = $this->serviceService->myServices($request->user(), $request->only(['search', 'per_page']));

            return api_success($services, 'Your services fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
