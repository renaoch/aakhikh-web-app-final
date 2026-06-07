<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;

class HealthController extends Controller
{
    use ApiResponse;

    public function __invoke()
    {
        return $this->success([
            'app' => config('app.name'),
            'environment' => config('app.env'),
            'timestamp' => now()->toIso8601String(),
        ], 'API is healthy.');
    }
}