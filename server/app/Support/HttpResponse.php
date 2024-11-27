<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class HttpResponse
{
    public static function data(array|object $data, int $status = 200): JsonResponse
    {
        return response()->json(['data' => $data], $status);
    }

    public static function message(string $message, int $status = 200, array|object $data = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error(array $errors, int $status = 400): JsonResponse
    {
        return response()->json(['errors' => $errors], $status);
    }

    public static function paginated(object $pagination, int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $pagination->data,
            'meta' => [
                'current_page' => $pagination->current_page,
                'last_page' => $pagination->last_page,
                'per_page' => $pagination->per_page,
                'total' => $pagination->total,
            ],
        ], $status);
    }

    public static function noContent(): Response
    {
        return response()->noContent();
    }
}
