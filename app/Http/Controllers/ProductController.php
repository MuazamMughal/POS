<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);


        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
      

        return Inertia::render('Products/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'units' => $units,
            ]);
    }

    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);
      
        try {
            DB::beginTransaction();
            Product::create($validated);
            DB::commit();
            return back()->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    // public function edit(Product $product)
    // {
    //     $categories = Category::all();
    //     $brands = Brand::all();

    //     return Inertia::render('Products/Edit', [
    //         'product' => $product,
    //         'categories' => $categories,
    //         'brands' => $brands,
    //     ]);
    // }

    // public function update(Request $request, Product $product)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string',
    //         'sku' => 'required|unique:products,sku,' . $product->id,
    //         'price' => 'required|numeric',
    //         'stock' => 'required|integer',
    //         'category_id' => 'nullable|exists:categories,id',
    //         'brand_id' => 'nullable|exists:brands,id',
    //     ]);

    //     $product->update($validated);

    //     return redirect()->route('products.index');
    // }

    // public function destroy(Product $product)
    // {
    //     $product->delete();
    //     return redirect()->route('products.index');
    // }
}
