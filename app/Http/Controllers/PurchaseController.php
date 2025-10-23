<?php
namespace App\Http\Controllers;

use App\Models\{Purchase, PurchaseItem, Supplier, Product};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->get();

        return Inertia::render('Purchases/Index', [
            'purchases' => $purchases
        ]);
    }

    public function create()
    {
        return Inertia::render('Purchases/CreatePurchase', [
            'suppliers' => Supplier::all(),
            'products' => Product::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $total = collect($validated['items'])->sum(fn($item) => $item['quantity'] * $item['purchase_price']);
            
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'invoice_no' => $validated['invoice_no'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => $total,
                'paid_amount' => 0,
                'due_amount' => $total,
            ]);

            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['purchase_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $subtotal,
                ]);

                // Increase product stock
                Product::where('id', $item['product_id'])->increment('stock', $item['quantity']);
            }
            Supplier::where('id', $validated['supplier_id'])->increment('balance_due', $total);
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase added successfully!');
    }

    public function Show(Purchase $purchase)
    {
        $purchase->load('supplier', 'items.product');
        $items = $purchase->items->map(function ($item) {
            return [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'purchase_price' => $item->purchase_price,
                'subtotal' => $item->subtotal,
            ];
        });
        return Inertia::render('Purchases/ShowPurchase', [
            'purchase' => $purchase,
            'items' => $items,
        ]);
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }
            $purchase->delete();
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}