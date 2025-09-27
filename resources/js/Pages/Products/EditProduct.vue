
<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  sku: '',
  price: '',
  stock: ''
})

const submit = () => {
  form.post('/products')
}
</script>

<template>
    <DashboardLayout>
      <h1 class="text-2xl font-bold mb-4">Add Product</h1>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label>Name</label>
          <input v-model="form.name" type="text" class="border p-2 w-full" required />
        </div>
        <div>
          <label>SKU</label>
          <input v-model="form.sku" type="text" class="border p-2 w-full" required />
        </div>
        <div>
          <label>Price</label>
          <input v-model="form.price" type="number" step="0.01" class="border p-2 w-full" required />
        </div>
        <div>
          <label>Stock</label>
          <input v-model="form.stock" type="number" class="border p-2 w-full" required />
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
      </form>
    </DashboardLayout>
  </template>
  