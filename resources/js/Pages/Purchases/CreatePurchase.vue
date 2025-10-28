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

   
  </div>
</template>