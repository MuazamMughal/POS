<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  suppliers: Array,
  products: Array
})

const form = ref({
  supplier_id: '',
  invoice_no: '',
  purchase_date: new Date().toISOString().split('T')[0], // Default to today
  items: []
})

const totalAmount = computed(() => {
  return form.value.items.reduce((sum, item) => {
    return sum + (parseFloat(item.quantity || 0) * parseFloat(item.purchase_price || 0))
  }, 0).toFixed(2)
})

function addItem() {
  form.value.items.push({ 
    product_id: '', 
    quantity: 1, 
    purchase_price: 0,
    subtotal: 0
  })
}

function removeItem(index) {
  form.value.items.splice(index, 1)
}

function updateSubtotal(item) {
  item.subtotal = (parseFloat(item.quantity || 0) * parseFloat(item.purchase_price || 0)).toFixed(2)
}

function submit() {
  router.post('/purchases/store', {
    ...form.value,
    total_amount: totalAmount.value,
    due_amount: totalAmount.value // Assuming full amount is due initially
  })
}
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">New Purchase</h1>
      <a 
        href="/purchases" 
        class="px-4 py-2 text-gray-600 hover:text-gray-800"
      >
        &larr; Back to Purchases
      </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Supplier and Invoice Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
            <select 
              v-model="form.supplier_id" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Select Supplier</option>
              <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                {{ supplier.name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Invoice No <span class="text-red-500">*</span></label>
            <input 
              v-model="form.invoice_no" 
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="INV-001"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
            <input 
              v-model="form.purchase_date" 
              type="date" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <!-- Products Table -->
        <div class="mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-800">Products</h2>
            <button 
              type="button" 
              @click="addItem"
              class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              + Add Product
            </button>
          </div>

          <div v-if="form.items.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in form.items" :key="index" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <select 
                      v-model="item.product_id" 
                      required
                      class="w-full px-3 py-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option value="">Select Product</option>
                      <option v-for="product in products" :key="product.id" :value="product.id">
                        {{ product.name }}
                      </option>
                    </select>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <input 
                      v-model.number="item.quantity" 
                      type="number" 
                      min="1" 
                      @input="updateSubtotal(item)"
                      class="w-20 px-3 py-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <input 
                      v-model.number="item.purchase_price" 
                      type="number" 
                      min="0" 
                      step="0.01" 
                      @input="updateSubtotal(item)"
                      class="w-24 px-3 py-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    Rs. {{ item.subtotal || '0.00' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button 
                      type="button" 
                      @click="removeItem(index)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Remove
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="bg-gray-50">
                  <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-700">Total Amount</td>
                  <td class="px-6 py-4 font-medium text-gray-900">Rs. {{ totalAmount }}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div v-else class="text-center py-8 text-gray-500">
            No products added. Click "Add Product" to get started.
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 pt-4">
          <a 
            href="/purchases" 
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </a>
          <button 
            type="submit" 
            :disabled="form.items.length === 0"
            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Save Purchase
          </button>
        </div>
      </form>
    </div>
  </div>
</template>