
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
