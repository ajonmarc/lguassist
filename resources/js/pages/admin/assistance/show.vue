<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import Modal from '@/Components/Modal.vue'; //using this as my delete confirmation modal

defineProps<{
    assistance: {
        id: string;
        name: string;
        deadline: string;
        description: string | null;
        number_of_questions: number;
        survey_instructions: string | null;
        target_audience: string | null;
        user: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assistance List',
        href: '/admin/assistance_list',
    },
    {
        title: 'Assistance Details',
        href: '',
    },
];
</script>

<template>
    <Head title="Assistance Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <h1 class="text-2xl font-bold">{{ assistance.name }}</h1>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-lg font-semibold">Details</h2>
                        <p><strong>Created By:</strong> {{ assistance.user }}</p>
                        <p><strong>Deadline:</strong> {{ assistance.deadline }}</p>
                        <p><strong>Number of Questions:</strong> {{ assistance.number_of_questions }}</p>
                        <p><strong>Target Audience:</strong> {{ assistance.target_audience || 'N/A' }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Description</h2>
                        <p>{{ assistance.description || 'No description provided' }}</p>
                        <h2 class="text-lg font-semibold mt-4">Survey Instructions</h2>
                        <p>{{ assistance.survey_instructions || 'No instructions provided' }}</p>
                    </div>
                </div>
            </div>
            <Link
                href="/admin/assistance_list"
                   class="rounded-md bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400"
            >
                Back to List
            </Link>
        </div>
    </AppLayout>
</template>