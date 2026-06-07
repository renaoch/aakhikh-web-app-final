<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoleAuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::latest()->paginate(20));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:super_admin,admin,editor,media,member',
        ]);

        $oldRole = $user->role;

        $user->update(['role' => $validated['role']]);

        RoleAuditLog::create([
            'user_id'    => $user->id,
            'changed_by' => $request->user()->id,
            'old_role'   => $oldRole,
            'new_role'   => $validated['role'],
            'changed_at' => now(),
        ]);

        return response()->json(['message' => 'Role updated.', 'user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->forceDelete();
        return response()->json(['message' => 'User permanently deleted.']);
    }
}