<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import type { BreadcrumbItem } from '@/types';
import { useToast } from 'vue-toastification';
import Modal from '@/Components/Modal.vue';
import EditModal from '@/Components/EditModal.vue';

interface User {
  id: number;
  name: string;
  email: string;
  role: 'citizen' | 'admin' | 'lgu';
  created_at: string;
  updated_at: string;
}

const props = defineProps<{
  users: User[];
  filters: {
    name?: string;
    email?: string;
    role?: string;
  };
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'User Management',
    href: '/admin/users',
  },
];

// Forms
const form = useForm({
  id: null as number | null,
  name: '',
  email: '',
  password: '',
  role: 'citizen' as 'citizen' | 'admin' | 'lgu',
});

const searchForm = useForm({
  name: props.filters.name || '',
  email: props.filters.email || '',
  role: props.filters.role || '',
});

// State
const isEditing = ref(false);
const showDeleteModal = ref(false);
const userToDelete = ref<number | null>(null);
const toast = useToast();

// Initialize search form with existing filters on component mount
onMounted(() => {
  searchForm.name = props.filters.name || '';
  searchForm.email = props.filters.email || '';
  searchForm.role = props.filters.role || '';
});

// Form Methods
const resetForm = () => {
  form.reset();
  form.id = null;
  isEditing.value = false;
};

const submitForm = () => {
  if (isEditing.value && form.id) {
    form.put(`/admin/users/${form.id}`, {
      onSuccess: () => {
        resetForm();
        toast.success('User updated successfully!', { timeout: 3000 });
      },
      onError: () => {
        toast.error('Failed to update user. Please check the form.', { timeout: 3000 });
      },
    });
  } else {
    form.post('/admin/users', {
      onSuccess: () => {
        resetForm();
        toast.success('User created successfully!', { timeout: 3000 });
      },
      onError: () => {
        toast.error('Failed to create user. Please check the form.', { timeout: 3000 });
      },
    });
  }
};

const editUser = (user: User) => {
  form.id = user.id;
  form.name = user.name;
  form.email = user.email;
  form.password = '';
  form.role = user.role;
  isEditing.value = true;
};

// Delete Methods
const deleteUser = (id: number) => {
  userToDelete.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  if (userToDelete.value) {
    form.delete(`/admin/users/${userToDelete.value}`, {
      onSuccess: () => {
        searchForm.get('/admin/users', {
          preserveState: false,
          preserveScroll: true,
        });
        toast.success('User deleted successfully!', { timeout: 3000 });
        showDeleteModal.value = false;
        userToDelete.value = null;
      },
      onError: () => {
        toast.error('Failed to delete user.', { timeout: 3000 });
        showDeleteModal.value = false;
        userToDelete.value = null;
      },
    });
  }
};

const cancelDelete = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

// Role Update Method
const updateRole = (id: number, role: string) => {
  useForm({ role }).patch(`/admin/users/${id}/role`, {
    onSuccess: () => {
      searchForm.get('/admin/users', {
        preserveState: false,
        preserveScroll: true,
      });
      toast.success(`User role updated to ${role} successfully!`, { timeout: 3000 });
    },
    onError: () => {
      toast.error('Failed to update user role.', { timeout: 3000 });
    },
  });
};

// Search Methods
const search = () => {
  searchForm.get('/admin/users', {
    preserveState: false,
    preserveScroll: true,
  });
};

const resetSearch = () => {
  searchForm.reset();
  searchForm.get('/admin/users', {
    preserveState: false,
    preserveScroll: true,
  });
};
</script>

<template>
  <!-- Delete Confirmation Modal -->
  <Modal
    v-if="showDeleteModal"
    title="Confirm Deletion"
    message="Are you sure you want to delete this user? This action cannot be undone."
    @confirm="confirmDelete"
    @cancel="cancelDelete"
  />

  <Head title="User Management" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-2xl font-bold">User Management</h1>

      <!-- Form for Creating/Editing Users -->
      <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold">{{ isEditing ? 'Edit User' : 'Create User' }}</h2>
        <form @submit.prevent="submitForm" class="mt-4 space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium">Name</label>
            <input
              v-model="form.name"
              id="name"
              type="text"
              class="mt-1 w-full rounded-md border p-2"
              :class="{ 'border-red-500': form.errors.name }"
              required
            />
            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input
              v-model="form.email"
              id="email"
              type="email"
              class="mt-1 w-full rounded-md border p-2"
              :class="{ 'border-red-500': form.errors.email }"
              required
            />
            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium">
              Password {{ isEditing ? '(Leave blank to keep current password)' : '' }}
            </label>
            <input
              v-model="form.password"
              id="password"
              type="password"
              class="mt-1 w-full rounded-md border p-2"
              :class="{ 'border-red-500': form.errors.password }"
              :required="!isEditing"
            />
            <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
          </div>

          <div>
            <label for="role" class="block text-sm font-medium">Role</label>
            <select
              v-model="form.role"
              id="role"
              class="mt-1 w-full rounded-md border p-2"
              :class="{ 'border-red-500': form.errors.role }"
              required
            >
              <option value="citizen">Citizen</option>
              <option value="lgu">LGU</option>
            </select>
            <p v-if="form.errors.role" class="text-sm text-red-500">{{ form.errors.role }}</p>
          </div>

          <div class="flex gap-2">
            <button
              type="submit"
              class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Processing...' : (isEditing ? 'Update User' : 'Create User') }}
            </button>
            <button
              v-if="isEditing"
              type="button"
              class="rounded-md bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400"
              @click="resetForm"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>

      <!-- Search Filters -->
      <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold">Search Users</h2>
        <form @submit.prevent="search" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-4">
          <div>
            <label for="search-name" class="block text-sm font-medium">Name</label>
            <input
              v-model="searchForm.name"
              id="search-name"
              type="text"
              placeholder="Search by name..."
              class="mt-1 w-full rounded-md border p-2"
            />
          </div>
          <div>
            <label for="search-email" class="block text-sm font-medium">Email</label>
            <input
              v-model="searchForm.email"
              id="search-email"
              type="email"
              placeholder="Search by email..."
              class="mt-1 w-full rounded-md border p-2"
            />
          </div>
          <div>
            <label for="search-role" class="block text-sm font-medium">Role</label>
            <select
              v-model="searchForm.role"
              id="search-role"
              class="mt-1 w-full rounded-md border p-2"
            >
              <option value="">All Roles</option>
              <option value="citizen">Citizen</option>
              <option value="lgu">LGU</option>
            </select>
          </div>
          <div class="flex items-end gap-2">
            <button
              type="submit"
              class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
              :disabled="searchForm.processing"
            >
              {{ searchForm.processing ? 'Searching...' : 'Search' }}
            </button>
            <button
              type="button"
              class="rounded-md bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400"
              @click="resetSearch"
              :disabled="searchForm.processing"
            >
              Reset
            </button>
          </div>
        </form>
      </div>

      <!-- User List -->
      <div class="mt-6 rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold">Users ({{ users.length }} found)</h2>
        <div v-if="users.length === 0" class="mt-4 text-center text-gray-500">
          No users found matching your search criteria.
        </div>
        <div v-else class="mt-4 overflow-x-auto">
          <table class="w-full table-auto">
            <thead>
              <tr class="bg-gray-50">
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Role</th>
                <th class="px-4 py-2 text-left">Created At</th>
                <th class="px-4 py-2 text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                <td class="border px-4 py-2">{{ user.name }}</td>
                <td class="border px-4 py-2">{{ user.email }}</td>
                <td class="border px-4 py-2">
                  <select
                    :value="user.role"
                    @change="updateRole(user.id, ($event.target as HTMLSelectElement).value)"
                    class="rounded-md border p-1"
                  >
                    <option value="citizen">Citizen</option>
                    <option value="lgu">LGU</option>
                  </select>
                </td>
                <td class="border px-4 py-2">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                <td class="border px-4 py-2 text-center">
                  <button
                    class="mr-2 rounded-md bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600"
                    @click="editUser(user)"
                  >
                    Edit
                  </button>
                  <button
                    class="rounded-md bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                    @click="deleteUser(user.id)"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>