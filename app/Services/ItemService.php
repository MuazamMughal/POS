<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Support\Str;

class ItemService
{
    public function store($request, $type)
    {
        // Map types to their correct table names
        $tableNames = [
            'category' => 'categories',
            'brand' => 'brands',
            'unit' => 'units'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:' . $tableNames[$type] . ',name',
        ]);

        try {
            $model = $this->getModel($type);
            
            // Create the record
            $item = $model::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ]);

            // Return the created item
            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' created successfully!',
                'data' => $item
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ' . $type,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getModel($type)
    {
        $models = [
            'category' => Category::class,
            'brand' => Brand::class,
            'unit' => Unit::class,
        ];

        if (!array_key_exists($type, $models)) {
            throw new \InvalidArgumentException("Invalid model type: {$type}");
        }

        return $models[$type];
    }
}
