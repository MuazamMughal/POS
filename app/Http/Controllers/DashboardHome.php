<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ItemService;

class DashboardHome extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        return Inertia::render('Dashboard');
    }

    public function detailings()
    {
        return Inertia::render("Detailings/Index");
    }

    public function storeCategory(Request $request)
    {
        return $this->itemService->store($request, 'category');
    }

    public function storeBrand(Request $request)
    {
        return $this->itemService->store($request, 'brand');
    }

    public function storeUnit(Request $request)
    {
        return $this->itemService->store($request, 'unit');
    }
}

