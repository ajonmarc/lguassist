<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';

defineProps<{
  message: string;
  type: 'success' | 'error';
}>();

const isVisible = ref(false);

onMounted(() => {
  isVisible.value = true;
  setTimeout(() => {
    isVisible.value = false;
  }, 3000); // Auto-hide after 3 seconds
});
</script>

<template>
  <div
    v-if="isVisible"
    :class="[
      'fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white transition-opacity duration-300',
      type === 'success' ? 'bg-green-600' : 'bg-red-600'
    ]"
  >
    {{ message }}
  </div>
</template>