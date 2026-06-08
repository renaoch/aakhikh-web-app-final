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
        $schedules = ServiceSchedule::where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    public function show(ServiceSchedule $serviceSchedule): JsonResponse
    {
        return response()->json($serviceSchedule);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:255'],
            'livestream_url' => ['nullable', 'url'],
            'is_active' => ['sometimes', 'boolean'],
            'format' => ['sometimes', 'string', 'in:in_person,online,hybrid'],
        ]);

        $schedule = ServiceSchedule::create($data);

        return response()->json($schedule, 201);
    }

    public function update(Request $request, ServiceSchedule $serviceSchedule): JsonResponse
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'day_of_week' => ['sometimes', 'integer', 'between:0,6'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'livestream_url' => ['sometimes', 'nullable', 'url'],
            'is_active' => ['sometimes', 'boolean'],
            'format' => ['sometimes', 'string', 'in:in_person,online,hybrid'],
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