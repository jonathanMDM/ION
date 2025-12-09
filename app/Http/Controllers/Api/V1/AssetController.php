<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AssetController extends Controller
{
    /**
     * Display a listing of assets
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Asset::with(['category', 'subcategory', 'location', 'supplier', 'employee']);
        
        // Filter by company (unless superadmin)
        if (!$request->user()->isSuperadmin()) {
            $query->where('company_id', $request->user()->company_id);
        }
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('condition')) {
            $query->where('condition', $request->condition);
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $perPage = min($request->get('per_page', 15), 100); // Max 100 items per page
        $assets = $query->paginate($perPage);
        
        return AssetResource::collection($assets);
    }

    /**
     * Display the specified asset
     */
    public function show(Request $request, int $id): AssetResource|JsonResponse
    {
        $query = Asset::with(['category', 'subcategory', 'location', 'supplier', 'employee']);
        
        // Filter by company (unless superadmin)
        if (!$request->user()->isSuperadmin()) {
            $query->where('company_id', $request->user()->company_id);
        }
        
        $asset = $query->find($id);
        
        if (!$asset) {
            return response()->json([
                'message' => 'Asset not found'
            ], 404);
        }
        
        return new AssetResource($asset);
    }

    /**
     * Store a newly created asset
     * Admin only
     */
    public function store(Request $request): AssetResource|JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,in_use,in_maintenance,retired',
            'condition' => 'required|in:excellent,good,fair,poor',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'location_id' => 'required|exists:locations,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);
        
        $validated['company_id'] = $request->user()->company_id;
        
        $asset = Asset::create($validated);
        $asset->load(['category', 'subcategory', 'location', 'supplier', 'employee']);
        
        return new AssetResource($asset);
    }

    /**
     * Update the specified asset
     * Admin only
     */
    public function update(Request $request, int $id): AssetResource|JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }
        
        $query = Asset::query();
        
        // Filter by company (unless superadmin)
        if (!$request->user()->isSuperadmin()) {
            $query->where('company_id', $request->user()->company_id);
        }
        
        $asset = $query->find($id);
        
        if (!$asset) {
            return response()->json([
                'message' => 'Asset not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:available,in_use,in_maintenance,retired',
            'condition' => 'sometimes|required|in:excellent,good,fair,poor',
            'category_id' => 'sometimes|required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);
        
        $asset->update($validated);
        $asset->load(['category', 'subcategory', 'location', 'supplier', 'employee']);
        
        return new AssetResource($asset);
    }

    /**
     * Remove the specified asset
     * Admin only
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }
        
        $query = Asset::query();
        
        // Filter by company (unless superadmin)
        if (!$request->user()->isSuperadmin()) {
            $query->where('company_id', $request->user()->company_id);
        }
        
        $asset = $query->find($id);
        
        if (!$asset) {
            return response()->json([
                'message' => 'Asset not found'
            ], 404);
        }
        
        $asset->delete();
        
        return response()->json([
            'message' => 'Asset deleted successfully'
        ]);
    }
}
