<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SiteSetting::allAsArray());
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings'         => 'required|array',
            'settings.*.key'   => 'required|string|max:100',
            'settings.*.value' => 'nullable|string',
            'settings.*.group' => 'nullable|string|max:50',
        ]);

        foreach ($data['settings'] as $setting) {
            SiteSetting::set(
                $setting['key'],
                $setting['value'] ?? null,
                $setting['group'] ?? 'general',
            );
        }

        return response()->json(SiteSetting::allAsArray());
    }
}
