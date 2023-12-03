<?php

namespace App\Services\v1;

use App\Http\Requests\v1\Category\UpdateCategoryRequest;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        if ($category->id !== 1) {
            $category->update($request->validated());
            return response()->json(new CategoryResource($category));
        }

        return response()->json(['message' => 'This category cannot be updated.'], 422);

    }

    public function delete(Category $category): JsonResponse
    {
        if ($category->id !== 1) {
            DB::transaction(function () use ($category) {
                $category->products()->update(['category_id' => 1]);
                $category->subcategories()->update(['parent_id' => $category->parent_id]);
                $category->delete();
                return response()->json(['success' => true, 'message' => 'Category deleted.']);
            });
        }

        return response()->json(['success' => false, 'message' => 'This category cannot be deleted.'], 422);
    }
}
