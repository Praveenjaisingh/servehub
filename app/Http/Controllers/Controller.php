<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

abstract class Controller
{
    /**
     * Central place that converts an exception thrown by a Service
     * into a JSON response. Keeps every controller's catch block
     * to a single line.
     */
    protected function handleException(Throwable $e)
    {
        return match (true) {
            $e instanceof ValidationException => api_error(
                'Validation failed.',
                422,
                $e->errors()
            ),
            $e instanceof NotFoundHttpException => api_error($e->getMessage() ?: 'Resource not found.', 404),
            $e instanceof AuthorizationException => api_error($e->getMessage() ?: 'This action is unauthorized.', 403),
            default => api_error($e->getMessage() ?: 'Something went wrong.', 500),
        };
    }
}
