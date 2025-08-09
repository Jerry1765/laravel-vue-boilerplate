<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\CacheKeyService;
use App\Traits\CacheTrait;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    use JsonResponseTrait, CacheTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAuthUser(Request $request): JsonResponse
    {
        $user = $request->user()->load('roles.permissions');

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'two_factor_enabled' => $user->two_factor_enabled,
            'two_factor_method' => $user->two_factor_method,
            'roles' => $user->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name
                ];
            }),
            'permissions' => $user->getAllPermissions()
        ]);
    }

    public function index(): JsonResponse
    {
        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles,
                'is_blocked' => $user->is_blocked,
                'updated_at' => $user->updated_at,
                'permissions' => $user->getAllPermissions()
            ];
        });

        $roles = Role::all();

        return response()->json([
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $user->syncRoles($validated['roles']);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return $this->errorResponse(['message' => 'Cannot update your own account.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_blocked' => 'required|boolean',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_blocked' => $validated['is_blocked']
        ]);

        if ($validated['is_blocked'] === false) {
            $cacheKey = CacheKeyService::instance()->getFailedAttemptsKey($validated['email']);
            $this->cacheForget($cacheKey);
        }

        $user->syncRoles($validated['roles']);

        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete your own account'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
                // Custom rule to check if the current password is correct
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Current password is incorrect.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'confirmed', // This requires a 'password_confirmation' field
                Password::min(8)->mixedCase()->numbers()->symbols(), // Enforce strong password
            ],
        ]);

        // Update the user's password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return $this->successResponse(['message' => 'Your password has been updated.']);
    }
}
