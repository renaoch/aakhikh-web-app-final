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
        return response()->json(
            $ministryTeam->members()->get()
        );
    }

    public function store(Request $request, MinistryTeam $ministryTeam): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'avatar'    => 'nullable|url|max:500',
            'bio'       => 'nullable|string',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $member = $ministryTeam->members()->create($data);

        return response()->json($member, 201);
    }

    public function show(MinistryTeam $ministryTeam, TeamMember $member): JsonResponse
    {
        abort_if($member->ministry_team_id !== $ministryTeam->id, 404);

        return response()->json($member);
    }

    public function update(Request $request, MinistryTeam $ministryTeam, TeamMember $member): JsonResponse
    {
        abort_if($member->ministry_team_id !== $ministryTeam->id, 404);

        $data = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'role'      => 'sometimes|string|max:255',
            'avatar'    => 'nullable|url|max:500',
            'bio'       => 'nullable|string',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $member->update($data);

        return response()->json($member);
    }

    public function destroy(MinistryTeam $ministryTeam, TeamMember $member): JsonResponse
    {
        abort_if($member->ministry_team_id !== $ministryTeam->id, 404);

        $member->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
