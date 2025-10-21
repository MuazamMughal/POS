<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { router } from '@inertiajs/vue3'

defineProps({
  sales: {
    type: Array,
    required: true
  }
})

const formatDate = (dateString) => {
  const options = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }
  return new Date(dateString).toLocaleDateString('en-US', options)
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-PK', {
    style: 'currency',
    currency: 'PKR',
    minimumFractionDigits: 2
  }).format(amount).replace('PKR', 'Rs.')
}

const viewSale = (id) => {
  router.get(`/sales/${id}`)
}

const destroy = (id) => {
  if (confirm('Are you sure you want to delete this sale?')) {
    router.delete(`/sales/${id}`)
  }
}
</script>

<template>
  <DashboardLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sales History</h1>
        <a href="/pos" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          + New Sale
        </a>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="sale in sales" :key="sale.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ sale.invoice_no }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(sale.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ sale.customer?.name || 'Walk-in Customer' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                  {{ sale.items?.length || 0 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                  {{ formatCurrency(sale.total) }}
                </td>
                <td class="flex gap-2 px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button 
                    @click="viewSale(sale.id)" 
                    class="text-blue-600 hover:text-blue-900"
                  >
                    View
                  </button>
                  <button 
                    @click="destroy(sale.id)" 
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
              <tr v-if="sales.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                  No sales records found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Add any custom styles here if needed */
</style>