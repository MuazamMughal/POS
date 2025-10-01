<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    default: () => []
  },
  brands: {
    type: Array,
    default: () => []
  },
  units: {
    type: Array,
    default: () => []
  }
})

const form = useForm({
  name: props.product?.name || '',
  sku: props.product?.sku || '',
  price: props.product?.price || '',
  stock: props.product?.stock || 0,
  category_id: props.product?.category_id || null,
  brand_id: props.product?.brand_id || null,
  unit_id: props.product?.unit_id || null,
  tax: props.product?.tax || 0,
  discount: props.product?.discount || 0,
  barcode: props.product?.barcode || '',
  _method: 'PUT'
})

const submit = () => {
  form.post('/products/update/' + props.product.id, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Product updated successfully')
    },
    onError: (errors) => {
      console.error('Error updating product:', errors)
      alert('Failed to update product. Please check the form for errors.')
    }
  })
}
</script>

<template>
  <DashboardLayout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
      <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
      
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
              <input v-model="form.name" type="text" class="w-full border rounded-md p-2" required />
              <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
              <input v-model="form.sku" type="text" class="w-full border rounded-md p-2" required />
              <div v-if="form.errors.sku" class="text-red-500 text-sm mt-1">{{ form.errors.sku }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
              <input v-model="form.barcode" type="text" class="w-full border rounded-md p-2" />
              <div v-if="form.errors.barcode" class="text-red-500 text-sm mt-1">{{ form.errors.barcode }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select v-model="form.category_id" class="w-full border rounded-md p-2">
                <option :value="null">Select Category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <div v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
              <input v-model="form.price" type="number" step="0.01" min="0" class="w-full border rounded-md p-2" required />
              <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
              <input v-model="form.stock" type="number" min="0" class="w-full border rounded-md p-2" required />
              <div v-if="form.errors.stock" class="text-red-500 text-sm mt-1">{{ form.errors.stock }}</div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tax (%)</label>
                <input v-model="form.tax" type="number" min="0" max="100" step="0.01" class="w-full border rounded-md p-2" />
                <div v-if="form.errors.tax" class="text-red-500 text-sm mt-1">{{ form.errors.tax }}</div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
                <input v-model="form.discount" type="number" min="0" max="100" step="0.01" class="w-full border rounded-md p-2" />
                <div v-if="form.errors.discount" class="text-red-500 text-sm mt-1">{{ form.errors.discount }}</div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <select v-model="form.brand_id" class="w-full border rounded-md p-2">
                  <option :value="null">Select Brand</option>
                  <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                    {{ brand.name }}
                  </option>
                </select>
                <div v-if="form.errors.brand_id" class="text-red-500 text-sm mt-1">{{ form.errors.brand_id }}</div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <select v-model="form.unit_id" class="w-full border rounded-md p-2">
                  <option :value="null">Select Unit</option>
                  <option v-for="unit in units" :key="unit.id" :value="unit.id">
                    {{ unit.name }}
                  </option>
                </select>
                <div v-if="form.errors.unit_id" class="text-red-500 text-sm mt-1">{{ form.errors.unit_id }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-end space-x-4 pt-4 border-t">
          <Link :href="route('products.index')" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Cancel
          </Link>
          <button 
            type="submit" 
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
            :disabled="form.processing"
          >
            <span v-if="form.processing">Saving...</span>
            <span v-else>Save Changes</span>
          </button>
        </div>
      </form>
    </div>
  </DashboardLayout>
</template>
  