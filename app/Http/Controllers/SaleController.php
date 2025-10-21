<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // Validate the incoming request
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'customer' => 'nullable|array',
            'customer.id' => 'nullable|exists:customers,id',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|array',
            'discount.type' => 'required|in:percentage,fixed',
            'discount.value' => 'required|numeric|min:0',
            'discount.reason' => 'nullable|string',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment' => 'required|array',
            'payment.method' => 'required|in:cash,credit_card,debit_card,bank_transfer',
            'payment.amount' => 'required|numeric|min:' . $request->input('total'),
            'payment.change' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Create the sale record
            $sale = Sale::create([
                'invoice_no' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'customer_id' => $validated['customer']['id'] ?? null,
                'subtotal' => $validated['subtotal'],
                'discount_type' => $validated['discount']['type'],
                'discount_value' => $validated['discount']['value'],
                'discount_reason' => $validated['discount']['reason'] ?? null,
                'tax_amount' => $validated['tax'],
                'total' => $validated['total'],
                'payment_method' => $validated['payment']['method'],
                'amount_paid' => $validated['payment']['amount'],
                'change_returned' => $validated['payment']['change'],
                'notes' => $validated['notes'] ?? null,
                'sold_by' => Auth::id(),
            ]);

            // Process each item in the cart
            foreach ($validated['items'] as $item) {
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                ]);

                // Update product stock
                $product = Product::findOrFail($item['id']);
                
                // Check if there's enough stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
                
                $product->decrement('stock', $item['quantity']);
            }

            // If everything is successful, commit the transaction
            DB::commit();

            // Return success response for Inertia
            return back()->with([
                'success' => true,
                'message' => 'Sale completed successfully',
                'data' => [
                    'sale_id' => $sale->id,
                    'invoice_no' => $sale->invoice_no,
                ]
            ]);

        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            
            // Log the error
            \Log::error('Sale processing failed: ' . $e->getMessage());
            
            // Return error response
            return back()->with([
                'success' => false,
                'message' => 'Failed to process sale: ' . $e->getMessage(),
            ]);
        }
    }


    public function Sales()
    {
        $sales = Sale::latest()->get();
        return Inertia::render('Sales/Index', [
            'sales' => $sales
        ]);
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function show(Sale $sale)
    {
        $sale = Sale::findOrFail($sale->id);
        $saleItems = SaleItem::where('sale_id', $sale->id)
        ->with('product')
        ->get();
           
  

        return Inertia::render('Sales/SaleView', [
            'sale' => $sale,
            'saleItems' => $saleItems
        ]);
    }
}
