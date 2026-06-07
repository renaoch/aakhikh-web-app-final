<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\RoleAuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"))
            ->when($request->role, fn($q, $r) => $q->where('role', $r))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role'      => ['sometimes', 'string', \Illuminate\Validation\Rule::enum(UserRole::class)],
            'is_active' => 'boolean',
            'name'      => 'sometimes|string|max:100',
        ]);

        $oldRole = $user->role?->value;
        $user->update($validated);

        // Audit log if role changed
        if (isset($validated['role']) && $oldRole !== $validated['role']) {
            RoleAuditLog::create([
                'user_id'    => $user->id,
                'changed_by' => $request->user()?->id,
                'old_role'   => $oldRole,
                'new_role'   => $validated['role'],
            ]);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Super admin cannot be deleted.',
                'errors'  => (object) [],
            ], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted.']);
    }
}
