<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MinistryTeam;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index(MinistryTeam $ministryTeam): JsonResponse
    {
        $members = TeamMember::where('team_id', $ministryTeam->id)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $members,
        ]);
    }

    public function store(Request $request, MinistryTeam $ministryTeam): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'photo_url' => ['nullable', 'url'],
            'email' => ['nullable', 'email', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['team_id'] = $ministryTeam->id;

        $member = TeamMember::create($validated);

        return response()->json([
            'success' => true,
            'data' => $member,
        ], 201);
    }

    public function show(MinistryTeam $ministryTeam, TeamMember $member): JsonResponse
    {
        abort_unless($member->team_id === $ministryTeam->id, 404);

        return response()->json([
            'success' => true,
            'data' => $member,
        ]);
    }

    public function update(Request $request, MinistryTeam $ministryTeam, TeamMember $member): JsonResponse
    {
        abort_unless($member->team_id === $ministryTeam->id, 404);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'role_title' => ['sometimes', 'string', 'max:255'],
            'photo_url' => ['sometimes', 'nullable', 'url'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'display_order' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $member->update($validated);

        return response()->json([
            'success' => true,
            'data' => $member,
        ]);
    }

    public function destroy(MinistryTeam $ministryTeam, TeamMember $member): JsonResponse
    {
        abort_unless($member->team_id === $ministryTeam->id, 404);

        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team member deleted successfully.',
        ]);
    }
}