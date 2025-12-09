<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies
     * Superadmin only
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        if (!$request->user()->isSuperadmin()) {
            return response()->json([
                'message' => 'Unauthorized. Superadmin access required.'
            ], 403);
        }
        
        $query = Company::withCount(['users', 'assets']);
        
        // Apply filters
        if ($request->has('is_active')) {
            $status = $request->boolean('is_active') ? 'active' : 'inactive';
            $query->where('status', $status);
        }
        
        if ($request->has('subscription_status')) {
            $query->where('subscription_status', $request->subscription_status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nit', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $companies = $query->paginate($perPage);
        
        return CompanyResource::collection($companies);
    }

    /**
     * Display the specified company
     */
    public function show(Request $request, int $id): CompanyResource|JsonResponse
    {
        // Users can only see their own company, unless superadmin
        if (!$request->user()->isSuperadmin() && $request->user()->company_id != $id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $company = Company::withCount(['users', 'assets'])->find($id);
        
        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ], 404);
        }
        
        return new CompanyResource($company);
    }

    /**
     * Get statistics for a company
     */
    public function stats(Request $request, int $id): JsonResponse
    {
        // Users can only see their own company stats, unless superadmin
        if (!$request->user()->isSuperadmin() && $request->user()->company_id != $id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $company = Company::with(['users', 'assets'])->find($id);
        
        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ], 404);
        }
        
        return response()->json([
            'company_id' => $company->id,
            'company_name' => $company->name,
            'statistics' => [
                'total_users' => $company->users()->count(),
                'active_users' => $company->users()->where('is_active', true)->count(),
                'total_assets' => $company->assets()->count(),
                'assets_by_status' => [
                    'available' => $company->assets()->where('status', 'available')->count(),
                    'in_use' => $company->assets()->where('status', 'in_use')->count(),
                    'in_maintenance' => $company->assets()->where('status', 'in_maintenance')->count(),
                    'retired' => $company->assets()->where('status', 'retired')->count(),
                ],
                'total_asset_value' => $company->assets()->sum('current_value'),
            ],
        ]);
    }
}
