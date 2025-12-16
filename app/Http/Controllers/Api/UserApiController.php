<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserApiController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v2/users",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="role", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="is_active", in="query", @OA\Schema(type="boolean")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        // Only admins and superadmins can list users
        if (!in_array($request->user()->role, ['admin', 'superadmin'])) {
            return $this->forbiddenResponse('You do not have permission to view users');
        }

        $perPage = $request->get('per_page', 15);
        $query = User::with('company');

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Regular admins can only see users from their company
        if ($request->user()->role === 'admin') {
            $query->where('company_id', $request->user()->company_id);
        }

        $users = $query->paginate($perPage);
        
        return $this->paginatedResponse($users, 'Users retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v2/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="role", type="string", enum={"user", "admin", "superadmin"}),
     *             @OA\Property(property="company_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully")
     * )
     */
    public function store(Request $request)
    {
        // Only admins and superadmins can create users
        if (!in_array($request->user()->role, ['admin', 'superadmin'])) {
            return $this->forbiddenResponse('You do not have permission to create users');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin,superadmin',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        // Regular admins can only create users for their company
        if ($request->user()->role === 'admin') {
            $data['company_id'] = $request->user()->company_id;
        }

        $user = User::create($data);

        return $this->successResponse(
            $user->load('company'),
            'User created successfully',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v2/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function show(Request $request, $id)
    {
        $user = User::with('company')->find($id);

        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        // Users can only see themselves, admins can see their company users
        if ($request->user()->role === 'user' && $user->id !== $request->user()->id) {
            return $this->forbiddenResponse('You do not have permission to view this user');
        }

        if ($request->user()->role === 'admin' && $user->company_id !== $request->user()->company_id) {
            return $this->forbiddenResponse('You do not have permission to view this user');
        }

        return $this->successResponse($user, 'User retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/v2/users/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        // Permission checks
        if ($request->user()->role === 'user' && $user->id !== $request->user()->id) {
            return $this->forbiddenResponse('You do not have permission to update this user');
        }

        if ($request->user()->role === 'admin' && $user->company_id !== $request->user()->company_id) {
            return $this->forbiddenResponse('You do not have permission to update this user');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|in:user,admin,superadmin',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $validator->validated();

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Only superadmins can change roles
        if (isset($data['role']) && $request->user()->role !== 'superadmin') {
            unset($data['role']);
        }

        $user->update($data);

        return $this->successResponse(
            $user->load('company'),
            'User updated successfully'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User deleted successfully")
     * )
     */
    public function destroy(Request $request, $id)
    {
        // Only admins and superadmins can delete users
        if (!in_array($request->user()->role, ['admin', 'superadmin'])) {
            return $this->forbiddenResponse('You do not have permission to delete users');
        }

        $user = User::find($id);

        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        // Cannot delete yourself
        if ($user->id === $request->user()->id) {
            return $this->errorResponse('You cannot delete yourself', 400);
        }

        // Regular admins can only delete users from their company
        if ($request->user()->role === 'admin' && $user->company_id !== $request->user()->company_id) {
            return $this->forbiddenResponse('You do not have permission to delete this user');
        }

        $user->delete();

        return $this->successResponse(null, 'User deleted successfully');
    }
}
