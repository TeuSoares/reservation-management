<?php

namespace App\Support\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait HttpResponse
{
    protected function successResponse(string $message = 'Success', int $status = 200, array|object|null $data = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse(array $errors, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => $errors,
        ], $status);
    }

    protected function paginatedResponse(object $pagination, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $pagination->data,
            'meta' => [
                'current_page' => $pagination->current_page,
                'last_page' => $pagination->last_page,
                'per_page' => $pagination->per_page,
                'total' => $pagination->total,
            ],
        ], $status);
    }

    protected function noContentResponse(): Response
    {
        return response()->noContent();
    }
}
