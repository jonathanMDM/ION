<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetApiController extends ApiController
{
    /**
     * Display a listing of assets
     * 
     * @OA\Get(
     *     path="/api/assets",
     *     tags={"Assets"},
     *     summary="Get list of assets",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Asset::with(['category', 'subcategory', 'location', 'user']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $assets = $query->paginate($perPage);

        return $this->paginatedResponse($assets, 'Assets retrieved successfully');
    }

    /**
     * Store a newly created asset
     * 
     * @OA\Post(
     *     path="/api/assets",
     *     tags={"Assets"},
     *     summary="Create a new asset",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","code","category_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="subcategory_id", type="integer"),
     *             @OA\Property(property="location_id", type="integer"),
     *             @OA\Property(property="serial_number", type="string"),
     *             @OA\Property(property="model", type="string"),
     *             @OA\Property(property="brand", type="string"),
     *             @OA\Property(property="purchase_date", type="string", format="date"),
     *             @OA\Property(property="purchase_price", type="number"),
     *             @OA\Property(property="status", type="string", enum={"available", "in_use", "maintenance", "retired"}),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Asset created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:assets,code',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'location_id' => 'nullable|exists:locations,id',
            'serial_number' => 'nullable|string',
            'model' => 'nullable|string',
            'brand' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,in_use,maintenance,retired',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $asset = Asset::create($validator->validated());

        return $this->successResponse(
            $asset->load(['category', 'subcategory', 'location']),
            'Asset created successfully',
            201
        );
    }

    /**
     * Display the specified asset
     * 
     * @OA\Get(
     *     path="/api/assets/{id}",
     *     tags={"Assets"},
     *     summary="Get asset by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Asset ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Asset not found")
     * )
     */
    public function show($id)
    {
        $asset = Asset::with(['category', 'subcategory', 'location', 'user', 'maintenances'])->find($id);

        if (!$asset) {
            return $this->notFoundResponse('Asset not found');
        }

        return $this->successResponse($asset, 'Asset retrieved successfully');
    }

    /**
     * Update the specified asset
     * 
     * @OA\Put(
     *     path="/api/assets/{id}",
     *     tags={"Assets"},
     *     summary="Update asset",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Asset ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="location_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asset updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Asset not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return $this->notFoundResponse('Asset not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:assets,code,' . $id,
            'category_id' => 'sometimes|required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'location_id' => 'nullable|exists:locations,id',
            'serial_number' => 'nullable|string',
            'model' => 'nullable|string',
            'brand' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,in_use,maintenance,retired',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $asset->update($validator->validated());

        return $this->successResponse(
            $asset->load(['category', 'subcategory', 'location']),
            'Asset updated successfully'
        );
    }

    /**
     * Remove the specified asset
     * 
     * @OA\Delete(
     *     path="/api/assets/{id}",
     *     tags={"Assets"},
     *     summary="Delete asset",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Asset ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asset deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Asset not found")
     * )
     */
    public function destroy($id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return $this->notFoundResponse('Asset not found');
        }

        $asset->delete();

        return $this->successResponse(null, 'Asset deleted successfully');
    }
}
