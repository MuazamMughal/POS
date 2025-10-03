<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useForm, router } from '@inertiajs/vue3';

// Forms for each section
const categoryForm = useForm({
    name: ''
});

const brandForm = useForm({
    name: ''
});

const unitForm = useForm({
    name: ''
});

// Submit handlers for each form
const submitCategory = async () => {
    try {
        const response = await axios.post('/detailings/category', {
            name: categoryForm.name
        });
        
        if (response.data.success) {
            categoryForm.reset();
            alert(response.data.message || 'Category added successfully!');
        } else {
            throw new Error(response.data.message || 'Failed to add category');
        }
    } catch (error) {
        console.error('Error adding category:', error);
        alert(error.response?.data?.message || 'Failed to add category. Please try again.');
    }
};

const submitBrand = async () => {
    try {
        const response = await axios.post('/detailings/brand', {
            name: brandForm.name
        });
        
        if (response.data.success) {
            brandForm.reset();
            alert(response.data.message || 'Brand added successfully!');
        } else {
            throw new Error(response.data.message || 'Failed to add brand');
        }
    } catch (error) {
        console.error('Error adding brand:', error);
        alert(error.response?.data?.message || 'Failed to add brand. Please try again.');
    }
};

const submitUnit = async () => {
    try {
        const response = await axios.post('/detailings/unit', {
            name: unitForm.name
        });
        
        if (response.data.success) {
            unitForm.reset();
            alert(response.data.message || 'Unit added successfully!');
        } else {
            throw new Error(response.data.message || 'Failed to add unit');
        }
    } catch (error) {
        console.error('Error adding unit:', error);
        alert(error.response?.data?.message || 'Failed to add unit. Please try again.');
    }
};

</script>

<template>
  <DashboardLayout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
      <!-- Category Section -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Add Category</h2>
        <form @submit.prevent="submitCategory">
          <div class="mb-4">
            <label for="category-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="categoryForm.name"
              type="text"
              id="category-name"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              :class="{ 'border-red-500': categoryForm.errors.name }"
              required
            />
            <p v-if="categoryForm.errors.name" class="mt-1 text-sm text-red-600">
              {{ categoryForm.errors.name }}
            </p>
          </div>
          <button
            type="submit"
            class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
            :disabled="categoryForm.processing"
          >
            <span v-if="categoryForm.processing">Adding...</span>
            <span v-else>Add Category</span>
          </button>
        </form>
      </div>
      
      <!-- Brand Section -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Add Brand</h2>
        <form @submit.prevent="submitBrand">
          <div class="mb-4">
            <label for="brand-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="brandForm.name"
              type="text"
              id="brand-name"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              :class="{ 'border-red-500': brandForm.errors.name }"
              required
            />
            <p v-if="brandForm.errors.name" class="mt-1 text-sm text-red-600">
              {{ brandForm.errors.name }}
            </p>
          </div>
          <button
            type="submit"
            class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
            :disabled="brandForm.processing"
          >
            <span v-if="brandForm.processing">Adding...</span>
            <span v-else>Add Brand</span>
          </button>
        </form>
      </div>
      
      <!-- Unit Section -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Add Unit</h2>
        <form @submit.prevent="submitUnit">
          <div class="mb-4">
            <label for="unit-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="unitForm.name"
              type="text"
              id="unit-name"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              :class="{ 'border-red-500': unitForm.errors.name }"
              required
            />
            <p v-if="unitForm.errors.name" class="mt-1 text-sm text-red-600">
              {{ unitForm.errors.name }}
            </p>
          </div>
          <button
            type="submit"
            class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
            :disabled="unitForm.processing"
          >
            <span v-if="unitForm.processing">Adding...</span>
            <span v-else>Add Unit</span>
          </button>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>
  

  
  <style>
  /* Assuming Tailwind is imported globally, no need for additional styles */
  </style>