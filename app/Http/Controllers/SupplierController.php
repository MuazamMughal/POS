<?php

namespace App\Http\Controllers;

use App\Models\Supplier;    
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return Inertia::render('Suppliers/Index', [
            'suppliers' => $suppliers
        ]);
    }
    public function create()
    {
        return Inertia::render('Suppliers/CreateSuppliers');
    }
    public function store(Request $request)
    {
        
        $request->validate([
             'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|unique:suppliers',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'balance_due' => 'required|numeric',
        ]);
        Supplier::create($request->all());
        return redirect()->route('suppliers.index');
    }
    public function edit(Supplier $supplier)
    {
        return Inertia::render('Suppliers/EditSuppliers', [
            'supplier' => $supplier
        ]);
    }   
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'balance_due' => 'required|numeric',
        ]);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index');
    }   
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index');
    }
}
