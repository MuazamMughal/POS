<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'name', 'sku', 'barcode', 'price', 'stock')->get();
        return Inertia::render('POS/Index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([ 
            'items' => 'required|array',
            'payment_method' => 'required|string',
            'total' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);

        $sale = Sale::create([
            'invoice_no' => 'INV-' . time(),
            'total' => $data['total'],
            'grand_total' => $data['grand_total'],
            'payment_method' => $data['payment_method'],
        ]);

        foreach ($data['items'] as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);

            // Decrease stock
            $product = Product::find($item['id']);
            $product->decrement('stock', $item['quantity']);
        }

        return redirect()->route('pos.index')->with('success', 'Sale recorded successfully');
    }
}
