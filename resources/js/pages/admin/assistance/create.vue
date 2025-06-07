<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification'; // Import useToast
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Assistance',
        href: '/admin/assistance/create',
    },
];

// Initialize Inertia form
const form = useForm({
    name: '',
    deadline: '',
    description: '',
    number_of_questions: 0,
    survey_instructions: '',
    target_audience: '',
});

const toast = useToast(); // Initialize toast

const submit = () => {
  form.post(route('admin.assistance.store'), {
    onSuccess: () => {
      form.reset();
      toast.success('Assistance created successfully!', {
        timeout: 3000,
      }); // Show success toast
    },
    onError: (errors) => {
      toast.error('Failed to create assistance. Please check the form.', {
        timeout: 3000,
      }); // Show error toast
    },
  });
};
</script>

<template>
    <Head title="Create Assistance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">Create Assistance</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Assistance Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Assistance Name</label>
                    <input
                        v-model="form.name"
                        id="name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.name }"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                    <input
                        v-model="form.deadline"
                        id="deadline"
                        type="datetime-local"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.deadline }"
                    />
                    <p v-if="form.errors.deadline" class="mt-1 text-sm text-red-600">{{ form.errors.deadline }}</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.description }"
                    ></textarea>
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- Number of Questions -->
                <div>
                    <label for="number_of_questions" class="block text-sm font-medium text-gray-700">Number of Questions</label>
                    <input
                        v-model="form.number_of_questions"
                        id="number_of_questions"
                        type="number"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.number_of_questions }"
                    />
                    <p v-if="form.errors.number_of_questions" class="mt-1 text-sm text-red-600">{{ form.errors.number_of_questions }}</p>
                </div>

                <!-- Survey Instructions -->
                <div>
                    <label for="survey_instructions" class="block text-sm font-medium text-gray-700">Survey Instructions</label>
                    <textarea
                        v-model="form.survey_instructions"
                        id="survey_instructions"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.survey_instructions }"
                    ></textarea>
                    <p v-if="form.errors.survey_instructions" class="mt-1 text-sm text-red-600">{{ form.errors.survey_instructions }}</p>
                </div>

                <!-- Target Audience -->
                <div>
                    <label for="target_audience" class="block text-sm font-medium text-gray-700">Target Audience</label>
                    <input
                        v-model="form.target_audience"
                        id="target_audience"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.target_audience }"
                    />
                    <p v-if="form.errors.target_audience" class="mt-1 text-sm text-red-600">{{ form.errors.target_audience }}</p>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Create Assistance
                    </button>

                     <Link
                href="/admin/assistance_list"
                class="rounded-md bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400"
            >
              Cancel
            </Link>
                    
                </div>
            </form>
        </div>
    </AppLayout>
</template>