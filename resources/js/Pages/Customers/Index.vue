<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { router } from '@inertiajs/vue3'
defineProps(
    { 
        customers: Array 
        }
    )
      const destroy = (id) => {
    if (confirm("Are you sure?")) {
      router.delete(`/customers/${id}`)
    }
  }
</script>

<template>
    <DashboardLayout>
  <div class="p-6">
    <div class=" flex justify-between items-center">
        <h1 class="text-xl font-bold">Customers</h1>
    <a href="/customers/create" class="px-4 py-2 bg-blue-500 text-white rounded">Add Customer</a>
    </div>
    
    <table class="w-full mt-4 border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">#</th>    
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Phone</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Credit Limit</th>
          <th class="p-2 border">Balance Due</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in customers" :key="c.id" class="border-t">
          <td class="pl-4 border">{{ c.id }}</td>
          <td class="p-2 border">{{ c.name }}</td>
          <td class="p-2 border">{{ c.phone }}</td>
          <td class="p-2 border">{{ c.email }}</td>
          <td class="p-2 border">Rs. {{ c.credit_limit }}</td>
          <td class="p-2 border">Rs. {{ c.balance_due }}</td>
          <td class="p-2 border">
            <a :href="`/customers/${c.id}/edit`" class="text-blue-500 mr-2">Edit</a>
            <button @click="destroy(c.id)" class="text-red-500">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </DashboardLayout>
</template>
