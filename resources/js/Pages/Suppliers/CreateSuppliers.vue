<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const form = ref({
  name: '',
  company_name: '',
  phone: '',
  email: '',
  address: '',
  balance_due: 0
})

const errors = ref({})
const processing = ref(false)

function submit() {
  processing.value = true
  router.post('/suppliers/store', form.value, {
    onSuccess: () => {
      form.value = { 
        name: '', 
        company_name: '', 
        phone: '', 
        email: '', 
        address: '',
        balance_due: 0
      }
    },
    onError: (err) => {
      errors.value = err
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Add New Supplier</h1>
      <a href="/suppliers" class="px-4 py-2 text-gray-600 hover:text-gray-800">
        &larr; Back to Suppliers
      </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Supplier Name <span class="text-red-500">*</span></label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.name }"
              placeholder="Enter supplier name"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <div>
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
            <input
              id="company_name"
              v-model="form.company_name"
              type="text"
              class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.company_name }"
              placeholder="Company name"
            />
            <p v-if="errors.company_name" class="mt-1 text-sm text-red-600">{{ errors.company_name }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
              required
              class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.phone }"
              placeholder="e.g. 03001234567"
            />
            <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.email }"
              placeholder="supplier@example.com"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>
        </div>

        <div>
          <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
          <textarea
            id="address"
            v-model="form.address"
            rows="3"
            class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-red-500': errors.address }"
            placeholder="Full supplier address"
          ></textarea>
          <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="balance_due" class="block text-sm font-medium text-gray-700 mb-1">Balance Due (Rs.)</label>
            <input
              id="balance_due"
              v-model.number="form.balance_due"
              type="number"
              min="0"
              step="0.01"
              class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.balance_due }"
              placeholder="0.00"
            />
            <p v-if="errors.balance_due" class="mt-1 text-sm text-red-600">{{ errors.balance_due }}</p>
          </div>
        </div>

        <div class="flex justify-end space-x-4 pt-4">
          <a
            href="/suppliers"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </a>
          <button
            type="submit"
            :disabled="processing"
            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="processing" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Saving...
            </span>
            <span v-else>Save Supplier</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>