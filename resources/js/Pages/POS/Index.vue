<script setup>
import { useCartStore } from '@/Stores/cart'
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'



const props = defineProps({ 
  products: Array,
  customers: {
    type: Array,
    default: () => []
  }
})
const ShowReasonModal = ref(false)
const cart = useCartStore()
const search = ref('')
const showPaymentModal = ref(false)
const paymentMethod = ref('cash')
const amountPaid = ref('')
const discountValue = ref('')
const discountType = ref('')
if(discountValue.value){
  discountType.value = 'percentage'
}else{
  discountType.value = ''
}
const selectedCustomer = ref(null)
const orderNotes = ref('')
const discountReason = ref('')
const TransactionCompletModal = ref(false)

// Filter products based on search
const filtered = computed(() => 
  props.products.filter(p =>
    p.name.toLowerCase().includes(search.value.toLowerCase()) ||
    p.sku.toLowerCase().includes(search.value.toLowerCase()) ||
    (p.barcode && p.barcode.toLowerCase().includes(search.value.toLowerCase()))
  )
)

// Format currency
const formatCurrency = (value) => {
  return 'Rs. ' + parseFloat(value).toFixed(2)
}

// Handle quantity update
const updateQuantity = (item, change) => {
  const newQty = item.quantity + change
  if (newQty > 0) {
    cart.updateQuantity(item.id, newQty)
  } else {
    cart.removeItem(item.id)
  }
}

// Apply discount
const applyDiscount = () => {
  
  if (discountValue.value) {
    cart.applyDiscount(parseFloat(discountValue.value), discountType.value,discountReason.value)

  }

}

const SaveReason = () => {
  applyDiscount()
  ShowReasonModal.value = false
}
const removeDiscount = () => {
  cart.removeDiscount()
}

// Process payment
const processPayment = () => {
  const orderData = {
    items: cart.items,
    customer: selectedCustomer.value,
    subtotal: cart.subtotal,
    discount: cart.discount,
    tax: cart.taxAmount,
    total: cart.total,
    payment: {
      method: paymentMethod.value,
      amount: parseFloat(amountPaid.value) || 0,
      change: cart.changeDue
    },
    notes: orderNotes.value
  };

  // Show loading state

  
  // Close payment modal immediately when form is submitted
  showPaymentModal.value = false;

  // Use Inertia's router for better SPA experience
  router.post(route('sales.store'), orderData, {
    onSuccess: () => {
      // Clear the cart and reset form
      cart.clearCart();
      amountPaid.value = '';
      orderNotes.value = '';
      cart.discount = null;
      selectedCustomer.value = null;
      showPaymentModal.value = false;
      
      
      // Show success modal
     
      
      // Hide success modal after 3 seconds
    
    },
    onError: (errors) => {
      // Show error message
      let errorMessage = 'Payment processing failed. Please try again.';
      if (errors) {
        errorMessage = Object.values(errors).flat().join(' ');
      }
      alert(errorMessage);
      
      // Reopen payment modal on error
      showPaymentModal.value = true;
    },
    onFinish: () => {
      TransactionCompletModal.value = true;
    }
  });
};

// Focus search on component mount
onMounted(() => {
  const searchInput = document.querySelector('input[type="search"]')
  if (searchInput) searchInput.focus()
})
</script>

<template>
  <div class="p-6 grid grid-cols-3 gap-6 h-screen">
    <!-- Product Grid -->
    <div class="col-span-2">
      <div class="flex gap-2 mb-4">
        <input
          v-model="search"
          type="search"
          placeholder="Search by name, SKU or scan barcode..."
          class="flex-1 border p-2 rounded"
          autofocus
        />
        <select 
          v-model="selectedCustomer"
          class="border p-2 rounded"
        >
          <option :value="null">Walk-in Customer</option>
          <option 
            v-for="customer in customers" 
            :key="customer.id" 
            :value="customer"
          >
            {{ customer.name }}
          </option>
        </select>
      </div>

      <div class="grid grid-cols-4 gap-3 overflow-y-auto h-[calc(100vh-200px)] pr-2">
        <div 
          v-for="product in filtered" 
          :key="product.id" 
          class="p-3 border rounded hover:bg-gray-50 cursor-pointer transition-colors"
          @click="cart.addItem(product)"
        >
          <p class="font-bold truncate">{{ product.name }}</p>
          <p class="text-sm text-gray-600">{{ product.sku }}</p>
          <p class="text-sm font-semibold text-green-600 mt-1">{{ formatCurrency(product.price) }}</p>
          <div v-if="product.stock !== undefined" class="text-xs text-gray-500">
            Stock: {{ product.stock }}
          </div>
        </div>
      </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="border-l pl-6 flex flex-col">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Order #{{ Math.floor(1000 + Math.random() * 9000) }}</h2>
        <button 
          @click="cart.clearCart()" 
          class="text-red-500 text-sm hover:underline"
          :disabled="!cart.items.length"
        >
          Clear All
        </button>
      </div>

      <!-- Cart Items -->
      <div class="flex-1 overflow-y-auto pr-2">
        <div v-if="!cart.items.length" class="text-gray-500 text-center mt-10">
          <p>Your cart is empty</p>
          <p class="text-sm">Add items to get started</p>
        </div>

        <div v-else>
          <div v-for="item in cart.items" :key="item.id" class="border-b py-3">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-medium">{{ item.name }}</p>
                <p class="text-sm text-gray-600">{{ item.sku || 'N/A' }}</p>
              </div>
              <p class="font-medium">{{ formatCurrency(item.total) }}</p>
            </div>
            <div class="flex items-center justify-between mt-2">
              <div class="flex items-center border rounded">
                <button 
                  @click.stop="updateQuantity(item, -1)" 
                  class="px-2 py-1 hover:bg-gray-100"
                >
                  -
                </button>
                <span class="px-2">{{ item.quantity }}</span>
                <button 
                  @click.stop="cart.addItem(item)" 
                  class="px-2 py-1 hover:bg-gray-100"
                >
                  +
                </button>
              </div>
              <button 
                @click.stop="cart.removeItem(item.id)" 
                class="text-red-500 hover:text-red-700"
              >
                Remove
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="border-t pt-4 mt-4">
        <!-- Discount -->
        <div class="flex gap-2 mb-3">
          <select v-model="discountType" class="border p-2 rounded w-1/3">
            <option value="percentage">%</option>
            <option value="fixed">Fixed</option>
          </select>
          <input 
            v-model="discountValue"
            type="number" 
            :placeholder="discountType === 'percentage' ? 'Discount %' : 'Discount Amount'"
            class="border p-2 rounded flex-1"
            @keyup.enter="applyDiscount"
          />
          <button v-if="!cart.discount.value"
            @click="ShowReasonModal = true"
            class="bg-orange-500 text-white px-3 rounded hover:bg-orange-600"
            :disabled="!cart.items.length"
          >
            Reason
          </button>
          <!-- <button 
            @click="applyDiscount"
            class="bg-blue-500 text-white px-3 rounded hover:bg-blue-600"
            :disabled="!discountValue"
          >
            Apply
          </button> -->
          <button v-if="cart.discount.value"
            @click="removeDiscount"
            class="bg-red-500 text-white px-3 rounded hover:bg-red-600"
            :disabled="!cart.discount.value"
          >
            Remove
          </button>
        
        </div>

        <!-- Totals -->
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span>Subtotal:</span>
            <span>{{ formatCurrency(cart.subtotal) }}</span>
          </div>
          
          <div v-if="cart.discount.value > 0" class="flex justify-between text-red-500">
            <span>Discount ({{ cart.discount.type === 'percentage' ? cart.discount.value + '%' : 'Fixed' }}):</span>
            <span>-{{ formatCurrency(cart.discountAmount) }}</span>
          </div>
          
          <div class="flex justify-between">
            <span>Tax ({{ (cart.taxRate * 100) }}%):</span>
            <span>{{ formatCurrency(cart.taxAmount) }}</span>
          </div>
          
          <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg">
            <span>Total:</span>
            <span>{{ formatCurrency(cart.total) }}</span>
          </div>
        </div>

        <!-- Checkout Button -->
        <button
          @click="showPaymentModal = true"
          class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium text-lg"
          :disabled="!cart.items.length"
          :class="{ 'opacity-50 cursor-not-allowed': !cart.items.length }"
        >
          Pay {{ formatCurrency(cart.total) }}
        </button>
      </div>
    </div>

    <!-- Reason Modal -->
    <div v-if="ShowReasonModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-4">Reason</h3>
        <input type="text" 
        v-model="discountReason"
        @keyup.enter="applyDiscount" class="w-full border p-2 rounded mb-4">
        <div class="flex justify-end gap-2">
          <button @click="ShowReasonModal = false" class="bg-blue-500 text-white px-3 rounded hover:bg-blue-600">Close</button>
      <button @click="SaveReason" class="bg-green-500 text-white px-3 rounded hover:bg-green-600">Apply Discount</button>
        </div>
      </div>

    </div>

    <!-- Payment Modal -->
    <div v-if="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-4">Complete Payment</h3>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
            <select v-model="paymentMethod" class="w-full border p-2 rounded">
              <option value="cash">Cash</option>
              <option value="credit_card">Credit Card</option>
              <option value="debit_card">Debit Card</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
            <input
              v-model="amountPaid"
              type="number"
              min="0"
              step="0.01"
              class="w-full border p-2 rounded"
              placeholder="Enter amount"
              @input="cart.setAmountPaid($event.target.value)"
            />
          </div>
          
          <div v-if="cart.payment.amountPaid > 0" class="space-y-2">
            <div class="flex justify-between">
              <span>Change Due:</span>
              <span :class="{ 'text-green-600 font-medium': cart.changeDue > 0 }">
                {{ formatCurrency(cart.changeDue) }}
              </span>
            </div>
            
            <div v-if="cart.changeDue < 0" class="text-red-500 text-sm">
              Additional {{ formatCurrency(-cart.changeDue) }} required
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes</label>
            <textarea
              v-model="orderNotes"
              class="w-full border p-2 rounded"
              rows="2"
              placeholder="Any special instructions?"
            ></textarea>
          </div>
          
          <div class="flex justify-end gap-2 pt-4">
            <button
              @click="showPaymentModal = false"
              class="px-4 py-2 border rounded hover:bg-gray-100"
            >
              Cancel
            </button>
            <button
              @click="processPayment"
              :disabled="!cart.isPaid"
              class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
            >
              Complete Sale
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="TransactionCompletModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg w-full max-w-md p-6">
          <h3 class="text-xl font-bold mb-4">Transaction Completed</h3>
          <p class="text-gray-600 mb-4">Thank you for your purchase!</p>
          <div class="flex justify-end gap-2">
            <button @click="TransactionCompletModal = false" class="bg-blue-500 text-white px-3 rounded hover:bg-blue-600">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Fade transition for modals */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
