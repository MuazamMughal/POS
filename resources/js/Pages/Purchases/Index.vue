<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({ 
  purchases: Array 
})

function deletePurchase(id) {
  if (confirm('Are you sure you want to delete this purchase?')) {
    router.delete(`/purchases/${id}`)
  }
}
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Purchases</h1>
      <a 
        href="/purchases/create" 
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        + New Purchase
      </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="purchase in purchases" :key="purchase.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ purchase.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ purchase.invoice_no }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ purchase.supplier?.name || '-' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ new Date(purchase.purchase_date).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
              Rs. {{ parseFloat(purchase.total_amount).toFixed(2) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm" 
                :class="purchase.due_amount > 0 ? 'text-red-600' : 'text-green-600'">
              Rs. {{ parseFloat(purchase.due_amount).toFixed(2) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <a 
                  :href="`/purchases/${purchase.id}/show`" 
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  View
                </a>
                <a 
                  :href="`/purchases/${purchase.id}/edit`" 
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  Edit
                </a>
                <button 
                  @click="deletePurchase(purchase.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="purchases.length === 0">
            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
              No purchases found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>