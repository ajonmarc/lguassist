<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import Modal from '@/Components/Modal.vue'; //im suing this for delete modal


defineProps<{
    assistances: Array<{
        id: string;
        name: string;
        route: string;
        color: string;
        hoverColor: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assistance List',
        href: '/admin/assistance_list',
    },
];
</script>

<template>
    <Head title="Assistance List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Assistance List</h1>
                <Link
                    href="/admin/assistance/create"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
                >
                    <Plus class="w-4 h-4" />
                    Create Assistance
                </Link>
            </div>

            <!-- Assistance Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
                <Link
                    v-for="assistance in assistances"
                    :key="assistance.id"
                    :href="assistance.route"
                    :class="[
                        'block rounded-2xl p-8 text-white font-bold text-xl transition-all duration-200 transform hover:scale-105 shadow-lg',
                        assistance.color,
                        assistance.hoverColor
                    ]"
                >
                    <div class="flex items-center justify-center h-24">
                        <span class="text-center leading-tight">
                            {{ assistance.name }}
                        </span>
                    </div>
                </Link>
            </div>

            <!-- Empty State -->
            <div v-if="assistances.length === 0" class="text-center py-12">
                <div class="text-gray-400 text-lg">No assistance types available</div>
                <Link
                    href="/admin/assistance/create"
                    class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
                >
                    Add First Assistance
                </Link>
            </div>
        </div>
    </AppLayout>
</template>