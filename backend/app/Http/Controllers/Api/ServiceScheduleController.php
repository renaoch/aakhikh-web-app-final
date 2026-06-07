<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            ServiceSchedule::active()->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'day_of_week' => 'required|integer|between:0,6',
            'time'        => 'required|date_format:H:i',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        return response()->json(ServiceSchedule::create($data), 201);
    }

    public function update(Request $request, ServiceSchedule $serviceSchedule): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'day_of_week' => 'sometimes|integer|between:0,6',
            'time'        => 'sometimes|date_format:H:i',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $serviceSchedule->update($data);

        return response()->json($serviceSchedule);
    }

    public function destroy(ServiceSchedule $serviceSchedule): JsonResponse
    {
        $serviceSchedule->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
