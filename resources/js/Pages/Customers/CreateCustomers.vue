<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const form = ref({
  name: '',
  phone: '',
  email: '',
  credit_limit: 0
})

const errors = ref({})
const processing = ref(false)

function submit() {
  processing.value = true
  router.post('/customers/store', form.value, {
    onSuccess: () => {
      form.value = { name: '', phone: '', email: '', credit_limit: 0 }
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
      <h1 class="text-2xl font-bold text-gray-800">Add New Customer</h1>
      <a href="/customers" class="px-4 py-2 text-gray-600 hover:text-gray-800">
        &larr; Back to Customers
      </a>
    </div>

    <div class= " bg-white rounded-lg shadow-md p-6 ">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Enter customer name"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
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
              placeholder="customer@example.com"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>
        </div>

        <div class="w-1/2">
          <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-1">Credit Limit (Rs.)</label>
          <input
            id="credit_limit"
            v-model.number="form.credit_limit"
            type="number"
            min="0"
            step="0.01"
            class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-red-500': errors.credit_limit }"
          />
          <p v-if="errors.credit_limit" class="mt-1 text-sm text-red-600">{{ errors.credit_limit }}</p>
        </div>

        <div class="flex justify-end space-x-4 pt-4">
          <a
            href="/customers"
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
            <span v-else>Save Customer</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
