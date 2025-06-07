<template>
  <Modal :show="show" @close="$emit('close')">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Assistance</h2>
      <form @submit.prevent="handleSubmit">
        <!-- Form fields for editing assistance -->
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300" />
          </div>
          <!-- Add other fields as needed -->
        </div>
        <div class="flex justify-end gap-3 mt-6">
          <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
        </div>
      </form>
    </div>
  </Modal>
</template>