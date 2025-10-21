<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  sale: {
    type: Object,
    required: true
  },
  saleItems: {
    type: Array,
    required: true,
    default: () => []
  }
});

// Compute items with proper structure for display
const items = computed(() => {
  return props.saleItems.map(item => ({
    ...item,
    product: item.product || { name: 'Unknown Product' },
    total: item.total || (item.price * item.quantity)
  }));
});

const formatDate = (dateString) => {
  const options = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(dateString).toLocaleDateString('en-US', options);
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-PK', {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
};

const printInvoice = () => {
  const printContents = document.querySelector('.print-section').innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  location.reload();
};

const goBack = () => {
  router.visit(route('sales.index'));
};
</script>

<template>
  <DashboardLayout>
    <div class="p-6 max-w-5xl mx-auto">
      <!-- Header with actions -->
      <div class="flex justify-between items-center mb-6">
        <button 
          @click="goBack"
          class="flex items-center text-gray-600 hover:text-gray-800"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
          Back to Sales
        </button>
        <div class="space-x-3">
          <button 
            @click="printInvoice"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Print Invoice
          </button>
        </div>
      </div>

      <!-- Invoice -->
      <div class="bg-white rounded-lg shadow-lg print-section overflow-hidden print:shadow-none">
        <!-- Invoice Header -->
        <div class="p-6 border-b">
          <div class="flex justify-between items-start">
            <div>
              <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
              <p class="text-gray-600">#{{ sale.invoice_no }}</p>
            </div>
            <div class="text-right">
              <p class="text-gray-600">Date: {{ formatDate(sale.created_at) }}</p>
              <p class="text-gray-600">Status: <span class="font-medium text-green-600">Paid</span></p>
            </div>
          </div>
        </div>

        <!-- Customer & Business Info -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8 border-b">
          <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Bill To:</h2>
            <p class="font-medium">{{ sale.customer?.name || 'Walk-in Customer' }}</p>
            <p v-if="sale.customer?.phone" class="text-gray-600">{{ sale.customer.phone }}</p>
            <p v-if="sale.customer?.email" class="text-gray-600">{{ sale.customer.email }}</p>
          </div>
          <div class="md:text-right">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Business Info:</h2>
            <p class="font-medium">Your Business Name</p>
            <p class="text-gray-600">123 Business St, City</p>
            <p class="text-gray-600">Phone: (123) 456-7890</p>
            <p class="text-gray-600">Email: business@example.com</p>
          </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(item, index) in saleItems" :key="index" class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                      <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ item.product?.name || 'Product' }}</div>
                      <div v-if="item.product?.sku" class="text-xs text-gray-500">SKU: {{ item.product.sku }}</div>
                      <div v-else class="text-xs text-gray-400">No SKU</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="text-sm text-gray-900">Rs. {{ formatCurrency(item.product?.price) }}</div>
                  <div v-if="item.discount_amount > 0" class="text-xs text-red-600">
                    -Rs. {{ formatCurrency(item.product?.discount) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="px-2 py-1 text-sm bg-gray-100 rounded-full">
                    {{ item.quantity }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="text-gray-900">Rs. {{ formatCurrency(item.total_price) }}</div>
                  <div v-if="item.product?.tax > 0" class="text-xs text-gray-500">
                    (Tax: Rs. {{ formatCurrency(item.product?.tax) }})
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Totals -->
        <div class="bg-gray-50 p-6">
          <div class="max-w-md ml-auto space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-600">Subtotal:</span>
              <span>Rs. {{ formatCurrency(sale.subtotal) }}</span>
            </div>
            
            <div v-if="sale.discount_value > 0" class="flex justify-between">
              <span class="text-gray-600">
                Discount {{ sale.discount_type === 'percentage' ? `(${sale.discount_value}%)` : '' }}:
              </span>
              <span class="text-red-600">- Rs. {{ formatCurrency(sale.discount_amount || 0) }}</span>
            </div>
            
            <div class="flex justify-between">
              <span class="text-gray-600">Tax ({{ sale.tax_rate || 0 }}%):</span>
              <span>Rs. {{ formatCurrency(sale.tax_amount || 0) }}</span>
            </div>
            
            <div class="border-t border-gray-200 my-2"></div>
            
            <div class="flex justify-between text-lg font-bold">
              <span>Total:</span>
              <span>Rs. {{ formatCurrency(sale.total) }}</span>
            </div>
            
            <div class="pt-4 mt-4 border-t border-gray-200">
              <div class="flex justify-between">
                <span class="text-gray-600">Amount Paid:</span>
                <span>Rs. {{ formatCurrency(sale.amount_paid) }}</span>
              </div>
              <div class="flex justify-between font-medium">
                <span>Change:</span>
                <span>Rs. {{ formatCurrency(sale.change_returned || 0) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment & Notes -->
        <div class="p-6 border-t">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-sm font-medium text-gray-500">Payment Method</h3>
              <p class="mt-1 text-sm text-gray-900 capitalize">{{ sale.payment_method || 'N/A' }}</p>
            </div>
            <div v-if="sale.notes">
              <h3 class="text-sm font-medium text-gray-500">Notes</h3>
              <p class="mt-1 text-sm text-gray-900">{{ sale.notes }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Thank You Note -->
      <!-- <div class="mt-8 text-center text-gray-500 text-sm">
        <p>Thank you for your business!</p>
        <p class="mt-1">For any inquiries, please contact our support team.</p>
      </div> -->
    </div>
  </DashboardLayout>
</template>

<style scoped>
@media print {
  @page {
    margin: 0;
    size: auto;
  }
  body {
    padding: 1cm;
  }
  .print\:hidden {
    display: none;
  }
}
</style>