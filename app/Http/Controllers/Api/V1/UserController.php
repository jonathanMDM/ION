<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::with('company');
        
        // Filter by company (unless superadmin)
        if (!$request->user()->isSuperadmin()) {
            $query->where('company_id', $request->user()->company_id);
        }
        
        // Apply filters
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $users = $query->paginate($perPage);
        
        return UserResource::collection($users);
    }

    /**
     * Display the specified user
     */
    public function show(Request $request, int $id): UserResource|JsonResponse
    {
        $query = User::with('company');
        
        // Filter by company (unless superadmin)
        if (!$request->user()->isSuperadmin()) {
            $query->where('company_id', $request->user()->company_id);
        }
        
        $user = $query->find($id);
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        
        return new UserResource($user);
    }

    /**
     * Get authenticated user information
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('company'));
    }
}
