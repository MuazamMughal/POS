<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return Inertia::render('Customers/Index', [
            'customers' => $customers
        ]);
    }
    public function create()
    {
        return Inertia::render('Customers/CreateCustomers');
    }


    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers',
            'email' => 'nullable|email',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }


   public function edit(Customer $customer)
    {
        $customer = Customer::findOrFail($customer->id);
        return Inertia::render('Customers/EditCustomers', [
            'customer' => $customer
        ]);
    }
   public function update(Request $request, Customer $customer)
    {
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }
   public function destroy(Customer $customer)
    {
        
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
