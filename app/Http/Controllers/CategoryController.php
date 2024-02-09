<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use JustSteveKing\StatusCode\Http;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::orderBy('name')->get()
        )->additional([
            'status_code' => Http::OK,
            'message' => 'OK'
        ]);
    }

    public function store(CategoryRequest $request)
    {
        return response()->json([
            'status_code' => Http::CREATED,
            'message' => 'Category created successfully.',
            'data' => CategoryResource::make(
                Category::create($request->validated())
            )
        ], Http::CREATED());
    }

    public function show(Category $category)
    {
        return response()->json([
            'status_code' => Http::OK,
            'message' => 'OK',
            'data' => CategoryResource::make($category)
        ], Http::OK());
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json([
            'status_code' => Http::OK,
            'message' => 'Category updated successfully.',
            'data' => CategoryResource::make($category)
        ], Http::OK());
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status_code' => Http::OK,
            'message' => 'Category deleted successfully.',
        ], Http::OK());
    }
}
