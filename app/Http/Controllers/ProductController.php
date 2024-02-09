<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use JustSteveKing\StatusCode\Http;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::query()
                ->orderBy('updated_at')
                ->orderBy('created_at', 'desc')
                ->get()
        )->additional([
            'status_code' => Http::OK,
            'message' => 'OK'
        ]);
    }

    public function store(ProductRequest $request)
    {
        $photoFileName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $photoFileName = uniqid().'-'.now()->timestamp.$file->getClientOriginalName();
            $file->storeAs('public/uploads', $photoFileName);
        }

        return response()->json([
            'status_code' => Http::CREATED,
            'message' => 'Product created successfully.',
            'data' => ProductResource::make(
                Product::create(array_replace($request->validated(), [
                    'image' => $photoFileName,
                ]))
            )
        ], Http::CREATED());
    }

    public function show(Product $product)
    {
        return response()->json([
            'status_code' => Http::OK,
            'message' => 'OK',
            'data' => ProductResource::make($product)
        ], Http::OK());
    }

    public function update(ProductRequest $request, Product $product)
    {
        $photoFileName = null;
        if ($request->hasFile('new_image')) {
            //Delete old image
            Storage::disk('public')->delete("/uploads/{$product->image}");

            //Store new_image in public folder
            $file = $request->file('new_image');
            $photoFileName = uniqid().'-'.now()->timestamp.$file->getClientOriginalName();
            $file->storeAs('public/uploads', $photoFileName);
        }

        $product->update(array_replace($request->validated(), [
           'image' => $photoFileName,
        ]));

        return response()->json([
            'status_code' => Http::OK,
            'message' => 'Product updated successfully.',
            'data' => ProductResource::make($product)
        ], Http::OK());
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status_code' => Http::OK,
            'message' => 'Product deleted successfully.',
        ], Http::OK());
    }
}
