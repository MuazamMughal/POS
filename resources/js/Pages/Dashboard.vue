<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <DashboardLayout>
            <h2 class="text-2xl font-bold">Welcome to POS System</h2>
            <p class="mt-2 text-gray-600">Choose a module from the sidebar.</p>
            </DashboardLayout>
        </template>

       
    </AuthenticatedLayout>
</template>
