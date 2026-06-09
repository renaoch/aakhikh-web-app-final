<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MinistryTeam;

class MinistryTeamController extends Controller
{
    /**
     * GET /api/ministry-teams
     * Returns all active ministry teams with their members.
     */
    public function index()
    {
        $teams = MinistryTeam::with(['members' => function ($q) {
             $q->orderBy('display_order');
            }])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $teams,
        ]);
    }

    /**
     * GET /api/ministry-teams/{id}
     */
    public function show(int $id)
    {
        $team = MinistryTeam::with(['members' => function ($q) {
          $q->orderBy('display_order');
            }])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $team,
        ]);
    }
}
