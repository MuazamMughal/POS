 
  <script setup>
  import DashboardLayout from '@/Layouts/DashboardLayout.vue'
  import { Link, router } from '@inertiajs/vue3'
  
  defineProps({
    products: Object
  })
  
  const destroy = (id) => {
    if (confirm("Are you sure?")) {
      router.delete(`/products/${id}`)
    }
  }
  </script>
  
<template>
    <DashboardLayout>
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Products</h1>
        <Link href="/products/create" class="px-4 py-2 bg-blue-600 text-white rounded">+ Add Product</Link>
      </div>
  
      <table class="w-full border">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Price</th>
            <th class="p-2 border">Stock</th>
            <th class="p-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in products.data" :key="p.id">
            <td class="border p-2">{{ p.id }}</td>
            <td class="border p-2">{{ p.name }}</td>
            <td class="border p-2">{{ p.price }}</td>
            <td class="border p-2">{{ p.stock }}</td>
            <td class="border p-2">
              <Link :href="`/products/${p.id}/edit`" class="px-2 py-1 bg-yellow-400 text-white rounded mr-2">Edit</Link>
              <button @click="destroy(p.id)" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </DashboardLayout>
  </template>
 