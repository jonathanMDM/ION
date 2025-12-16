<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v2/categories",
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        $categories = Category::withCount('assets')->paginate($perPage);
        
        return $this->paginatedResponse($categories, 'Categories retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v2/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created successfully")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $category = Category::create($validator->validated());

        return $this->successResponse($category, 'Category created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v2/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get category by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function show($id)
    {
        $category = Category::withCount('assets')->with('subcategories')->find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        return $this->successResponse($category, 'Category retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/v2/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update category",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Category updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $category->update($validator->validated());

        return $this->successResponse($category, 'Category updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete category",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Category deleted successfully")
     * )
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        if ($category->assets()->count() > 0) {
            return $this->errorResponse('Cannot delete category with associated assets', 400);
        }

        $category->delete();

        return $this->successResponse(null, 'Category deleted successfully');
    }
}
