<?php

namespace App\Http\Controllers\Availability;

use App\Http\Controllers\Controller;
use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Services\Availability\AvailabilityInterface;
use Illuminate\Http\Request;
use Throwable;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityInterface $availabilityService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $slots = $this->availabilityService->list($request->user());

            return api_success($slots, 'Availability fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function store(StoreAvailabilityRequest $request)
    {
        try {
            $slots = $this->availabilityService->setAvailability($request->user(), $request->validated()['slots']);

            return api_success($slots, 'Availability saved successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $this->availabilityService->delete($request->user(), $id);

            return api_success(null, 'Availability slot removed successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
