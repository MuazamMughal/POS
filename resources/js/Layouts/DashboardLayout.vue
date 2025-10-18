<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex w-full h-screen bg-gray-100">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-lg">
        <Link href="/pos" class="block p-2 rounded hover:bg-gray-200">
        <div class="p-4 text-xl font-bold border-b">POS System</div>
        </Link>
        <nav class="p-4 space-y-2">
          <Link href="/dashboard" class="block p-2 rounded hover:bg-gray-200">Dashboard</Link>
          <Link href="/detailings" class="block p-2 rounded hover:bg-gray-200">Detailings</Link>
          <Link href="/products" class="block p-2 rounded hover:bg-gray-200">Products</Link>
          <Link href="/sales" class="block p-2 rounded hover:bg-gray-200">Sales</Link>
          <Link href="/customers" class="block p-2 rounded hover:bg-gray-200">Customers</Link>
          <Link href="/suppliers" class="block p-2 rounded hover:bg-gray-200">Suppliers</Link>
          <Link href="/purchases" class="block p-2 rounded hover:bg-gray-200">Purchases</Link>
          <Link href="/reports" class="block p-2 rounded hover:bg-gray-200">Reports</Link>
          <Link href="/settings" class="block p-2 rounded hover:bg-gray-200">Settings</Link>
        </nav>
      </aside>
  
      <!-- Main Content -->
      <div class="flex-1 flex flex-col">
        <!-- Topbar -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
          <h1 class="text-xl font-semibold">Dashboard</h1>
          <div>
            <Link href="/profile" class="px-4 py-2 bg-gray-200 rounded">Profile</Link>
          </div>
        </header>
  
        <!-- Page Content -->
        <main class="p-6 flex-1 overflow-y-auto">
          <slot />
        </main>
      </div>
    </div>
  </template>