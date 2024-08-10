<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(3);
        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        // Handle image upload if an image is provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // Create the category with the image path if available
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $imagePath,
            'position' => $request->position,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Handle image upload if an image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        // Update the category with the new data
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $category->image, // Ensure that the image is updated if a new one was uploaded
            'position' => $request->position,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete the image from storage if it exists
        if ($category->image) {
            \Storage::disk('public')->delete($category->image);
        }

        // Delete the category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
