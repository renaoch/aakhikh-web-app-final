<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\RoleAuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('email', 'ilike', '%' . $request->search . '%');
            }))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 20));

        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'avatar'    => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ]);

        $user->update($data);

        return response()->json($user);
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $oldRole = $user->role->value;
        $user->update(['role' => $data['role']]);

        RoleAuditLog::create([
            'user_id'    => $user->id,
            'changed_by' => $request->user()->id,
            'old_role'   => $oldRole,
            'new_role'   => $data['role'],
            'changed_at' => now(),
        ]);

        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {
        abort_if($user->id === request()->user()->id, 403, 'Cannot delete yourself.');

        $user->delete();

        return response()->json(['message' => 'User deleted.']);
    }
}
