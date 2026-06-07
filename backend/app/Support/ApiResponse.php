<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Request successful.',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => (object) $meta,
        ], $status);
    }

    protected function error(
        string $message = 'Something went wrong.',
        int $status = 400,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => (object) $errors,
        ], $status);
    }

    protected function paginated(
        AbstractPaginator $paginator,
        string $message = 'Data fetched successfully.'
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }

    protected function resource(
        JsonResource $resource,
        string $message = 'Data fetched successfully.',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource,
            'meta' => (object) [],
        ], $status);
    }
}