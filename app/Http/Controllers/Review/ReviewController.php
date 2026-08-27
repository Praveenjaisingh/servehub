<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Services\Review\ReviewInterface;
use Illuminate\Http\Request;
use Throwable;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewInterface $reviewService
    ) {
    }

    public function store(StoreReviewRequest $request)
    {
        try {
            $review = $this->reviewService->create($request->user(), $request->validated());

            return api_success($review, 'Review submitted successfully.', 201);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function forProvider(Request $request, int $providerId)
    {
        try {
            $reviews = $this->reviewService->forProvider($providerId, $request->only(['per_page']));

            return api_success($reviews, 'Reviews fetched successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $this->reviewService->delete($request->user(), $id);

            return api_success(null, 'Review deleted successfully.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
