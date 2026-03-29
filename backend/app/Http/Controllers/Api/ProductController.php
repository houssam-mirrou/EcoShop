<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['product' => $product], 200);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:5',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $product = Product::findOrFail($id);

        $product->update($validated);

        return response()->json(['product' => $product], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:5',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'The product has been created successfully',
            'product' => $product
        ], 201);
    }
}
