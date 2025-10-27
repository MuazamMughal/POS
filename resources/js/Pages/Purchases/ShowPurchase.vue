
<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  purchase: Object,
  items: Array
})

const dueAmount = computed(() => {
  return parseFloat(props.purchase.total_amount) - parseFloat(props.purchase.paid_amount || 0)
})

function formatDate(dateString) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}

const printInvoice = () => {
  const printContents = document.querySelector('.print-section').innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  location.reload();
};
// Set up print styles
onMounted(() => {
  const style = document.createElement('style')
  style.id = 'print-styles'
  style.textContent = `
    @page {
      size: A4;
      margin: 20mm;
    }
    @media print {
      body {
        font-size: 12pt;
        -webkit-print-color-adjust: exact;
      }
      .print\\:hidden {
        display: none !important;
      }
      .print\\:block {
        display: block !important;
      }
      .print\\:grid {
        display: grid !important;
      }
    }
  `
  document.head.appendChild(style)
})
</script>

<style>
@media print {
  @page {
    margin: 0;
  }
  body {
    padding: 1cm;
  }
  .no-print {
    display: none !important;
  }
  .print\:hidden {
    display: none !important;
  }
  .print\:block {
    display: block !important;
  }
}
</style>

<template>
  <div class="p-6 print:p-0">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6 print:hidden">
      <h1 class="text-2xl font-bold text-gray-800">Purchase #{{ purchase.id }}</h1>
      <div class="space-x-3">
        <button 
            @click="printInvoice"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Print Invoice
          </button>
        <a 
          :href="`/purchases/${purchase.id}/edit`"
          class="px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a 
          href="/purchases" 
          class="px-4 py-2 text-gray-600 hover:text-gray-800"
        >
          <i class="fas fa-arrow-left mr-2"></i> Back to Purchases
        </a>
      </div>
    </div>
    <div class="print-section">
    <!-- Print Header (only visible when printing) -->
    <div class="hidden print-section print:block mb-8">
      <div class="flex justify-between items-center border-b-2 border-gray-300 pb-4">
        <div>
          <h1 class="text-2xl font-bold">Purchase Receipt</h1>
          <p class="text-gray-600">#{{ purchase.invoice_no }}</p>
        </div>
        <div class="text-right">
          <p class="text-lg font-semibold">Your Company Name</p>
          <p>123 Business Street</p>
          <p>City, Country</p>
          <p>Phone: (123) 456-7890</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white   rounded-lg shadow-md p-6 print:shadow-none print:border print:border-gray-200">
      <!-- Purchase Info -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 print:grid-cols-3 print:gap-4">
        <div>
          <h3 class="text-sm font-medium text-gray-500 mb-1">Supplier</h3>
          <p class="text-gray-900 font-medium">{{ purchase.supplier.name }}</p>
          <p v-if="purchase.supplier.phone" class="text-gray-600 text-sm">{{ purchase.supplier.phone }}</p>
        </div>

        <div>
          <h3 class="text-sm font-medium text-gray-500 mb-1">Invoice No</h3>
          <p class="text-gray-900 font-medium">{{ purchase.invoice_no }}</p>
        </div>

        <div>
          <h3 class="text-sm font-medium text-gray-500 mb-1">Date</h3>
          <p class="text-gray-900">{{ formatDate(purchase.purchase_date) }}</p>
        </div>
      </div>

      <!-- Products Table -->
      <div class="mb-8">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50 print:bg-gray-100">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(item, index) in items" :key="index" class="hover:bg-gray-50 print:hover:bg-white">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ item.product.name }}</div>
                  <div v-if="item.product.code" class="text-xs text-gray-500">SKU: {{ item.product.code }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                  {{ item.quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                  Rs. {{ parseFloat(item.purchase_price).toFixed(2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                  Rs. {{ parseFloat(item.subtotal).toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Summary -->
        <div class="mt-6 w-full md:w-1/2 ml-auto">
          <div class="bg-gray-50 p-4 rounded-md">
            <div class="flex justify-between py-2 text-sm">
              <span class="text-gray-600">Subtotal:</span>
              <span class="font-medium">Rs. {{ parseFloat(purchase.total_amount).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between py-2 text-sm">
              <span class="text-gray-600">Paid Amount:</span>
              <span class="font-medium">Rs. {{ parseFloat(purchase.paid_amount || 0).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between py-2 text-lg font-bold border-t border-gray-200 mt-2 pt-2">
              <span>Balance Due:</span>
              <span :class="dueAmount > 0 ? 'text-red-600' : 'text-green-600'">
                Rs. {{ dueAmount.toFixed(2) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment & Notes -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 print:grid-cols-2 print:gap-4">
        <div>
          <h3 class="text-sm font-medium text-gray-500 mb-2">Payment Method</h3>
          <p class="text-gray-900 capitalize">{{ purchase.payment_method || 'Not specified' }}</p>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500 mb-2">Notes</h3>
          <p class="text-gray-900 whitespace-pre-line">{{ purchase.notes || 'No notes' }}</p>
        </div>
      </div>


      </div>

      <!-- Print Footer
      <div class="hidden print:block mt-12 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
        <p>Thank you for your business!</p>
        <p class="mt-1">Generated on {{ new Date().toLocaleDateString() }} at {{ new Date().toLocaleTimeString() }}</p>
      </div> -->
    </div>
  </div>
</template>
