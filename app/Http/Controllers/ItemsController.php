<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    public function index()
    {
        $products = Items::latest()->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $products,
        ]);
    }

    // ✅ Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|integer',
            'image'       => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
        
            $imagePath = 'uploads/products/' . $filename;
        }

        $product = Items::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'is_cylinder' => $request->is_cylinder = true ? 1 : 0,
            'image'       => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ]);
    }

    public function show(Items $product)
    {
        return response()->json([
            'status' => true,
            'data' => $product,
        ]);
    }

    // ✅ Update product
    public function update(Request $request, Items $product)
    {
        $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'price'       => 'sometimes|required|integer',
            'is_cylinder' => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->only(['name', 'price', 'is_cylinder']));

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    // ✅ Delete product
    public function destroy(Items $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}
