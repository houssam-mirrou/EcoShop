<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|min:4'
        ]);

        $category = Category::create([
            'name'=>$request->name
        ]);

        return response()->json([
            'message'=>'The category has been successfully created',
            'category'=>$category
        ],201);
    }

    public function destroy(string $id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'message'=>'you\'ve successfully deleted this category'
        ],200);
    }
}
