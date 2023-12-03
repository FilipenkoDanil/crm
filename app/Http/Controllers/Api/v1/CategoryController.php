<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Category\StoreCategoryRequest;
use App\Http\Requests\v1\Category\UpdateCategoryRequest;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use App\Services\v1\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        return response()->json(CategoryResource::collection(Category::mainParents()->get()));
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return response()->json(Category::create($request->validated()));
    }

    public function show($slug): JsonResponse
    {
        return response()->json(new CategoryResource(Category::show($slug)));
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        return $this->categoryService->update($request, $category);
    }

    public function destroy(Category $category): JsonResponse
    {
        return $this->categoryService->delete($category);
    }
}