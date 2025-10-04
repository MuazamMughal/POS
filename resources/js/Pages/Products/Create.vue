<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  categories: Array,
  brands: Array,
  units: Array
})

const form = useForm({
  name: '',
  sku: '',
  barcode: '',
  price: '',
  stock: '',
  category_id: null,
  brand_id: null,
  unit_id: null,
  tax: '',
  discount: '',
})



const validateForm = () => {
  if (form.name.length < 3) {
    form.errors.name = 'Name must be at least 3 characters long';
  } else {
    form.errors.name = '';
  }
  form.errors.sku = '';
  form.errors.price = '';
  form.errors.stock = '';
  form.errors.category_id = '';
  form.errors.brand_id = '';
  form.errors.unit_id = '';
  form.errors.tax = '';
  form.errors.discount = '';
}
const submit = () => {
  validateForm();
  if (form.errors.name || form.errors.sku || form.errors.price || form.errors.stock || form.errors.category_id || form.errors.brand_id || form.errors.unit_id || form.errors.tax || form.errors.discount) {
    return;
  }
  form.post('/products/store', {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      form.reset()
      success('Product created successfully')
      show('Product created successfully')
    }
  })
}
</script>

<template>
  <DashboardLayout>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-lg">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Add New Product</h1>
      
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
          <input 
            v-model="form.name" 
            type="text" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Enter product name"
            required
          />
          <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- SKU -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
          <input 
            v-model="form.sku" 
            type="text" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Enter unique SKU"
            required
          />
          <p v-if="form.errors.sku" class="text-red-500 text-sm mt-1">{{ form.errors.sku }}</p>
        </div>

        <!-- Barcode -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Barcode (Optional)</label>
          <input 
            v-model="form.barcode" 
            type="text" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Enter barcode"
          />
          <p v-if="form.errors.barcode" class="text-red-500 text-sm mt-1">{{ form.errors.barcode }}</p>
        </div>

        <!-- Price -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
            <input 
              v-model="form.price" 
              type="number" 
              step="0.01" 
              min="0"
              class="w-full p-3 pl-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="0.00"
              required
            />
          </div>
          <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
        </div>

        <!-- Stock -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
          <input 
            v-model="form.stock" 
            type="number" 
            min="0"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Enter stock quantity"
            required
          />
          <p v-if="form.errors.stock" class="text-red-500 text-sm mt-1">{{ form.errors.stock }}</p>
        </div>

        <!-- Category -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select 
            v-model="form.category_id"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          >
            <option :value="null" disabled>Select a category</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</p>
        </div>

        <!-- Brand -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
          <select 
            v-model="form.brand_id"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          >
            <option :value="null" disabled>Select a brand</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.id">
              {{ brand.name }}
            </option>
          </select>
          <p v-if="form.errors.brand_id" class="text-red-500 text-sm mt-1">{{ form.errors.brand_id }}</p>
        </div>

        <!-- Unit -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
          <select 
            v-model="form.unit_id"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          >
            <option :value="null" disabled>Select a unit</option>
            <option v-for="unit in units" :key="unit.id" :value="unit.id">
              {{ unit.name }}
            </option>
          </select>
          <p v-if="form.errors.unit_id" class="text-red-500 text-sm mt-1">{{ form.errors.unit_id }}</p>
        </div>

        <!-- Tax -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tax (%)</label>
          <div class="relative">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
            <input 
              v-model="form.tax" 
              type="number" 
              step="0.01" 
              min="0"
              max="100"
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="0.00"
            />
          </div>
          <p v-if="form.errors.tax" class="text-red-500 text-sm mt-1">{{ form.errors.tax }}</p>
        </div>

        <!-- Discount -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
          <div class="relative">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
            <input 
              v-model="form.discount" 
              type="number" 
              step="0.01" 
              min="0"
              max="100"
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="0.00"
            />
          </div>
          <p v-if="form.errors.discount" class="text-red-500 text-sm mt-1">{{ form.errors.discount }}</p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
          <button 
            type="button" 
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
            @click="form.reset()"
          >
            Reset
          </button>
          <button 
            type="submit" 
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Saving...' : 'Save Product' }}
          </button>
        </div>
      </form>
    </div>
  </DashboardLayout>
</template>

<style scoped>
input:focus, select:focus {
  outline: none;
}
</style>