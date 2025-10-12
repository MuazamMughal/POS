<script setup>
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Success!',
    },
    message: {
        type: String,
        default: 'Operation completed successfully!',
    },
    buttonText: {
        type: String,
        default: 'OK',
    },
});

const emit = defineEmits(['close', 'confirmed']);

const close = () => {
    emit('close');};

const confirm = () => {
    emit('confirmed');
    close();
};
</script>

<template>
    <Modal :show="show" @close="close">
        <div class="px-6 py-4">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="mt-3 text-lg font-medium text-gray-900">
                    {{ title }}
                </h3>
                
                <!-- Message -->
                <div class="mt-2 text-sm text-gray-500">
                    <p>{{ message }}</p>
                </div>
                
                <!-- Action Button -->
                <div class="mt-6">
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm"
                        @click="confirm"
                    >
                        {{ buttonText }}
                    </button>
                </div>
            </div>
        </div>
    </Modal>
</template>
