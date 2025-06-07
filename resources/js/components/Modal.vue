<script setup lang="ts">
defineProps<{
  title: string;
  message: string;
}>();

defineEmits<{
  (e: 'confirm'): void;
  (e: 'cancel'): void;
}>();
</script>

<template>
  <!-- Option 1: Lighter gray background with lower opacity -->
  <div class="fixed inset-0 backdrop-blur-sm bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
      <h2 class="text-lg font-semibold text-gray-800">{{ title }}</h2>
      <p class="mt-2 text-gray-600">{{ message }}</p>
      <div class="mt-6 flex justify-end gap-4">
        <button
          @click="$emit('cancel')"
          class="rounded-md bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300"
        >
          Cancel
        </button>
        <button
          @click="$emit('confirm')"
          class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>