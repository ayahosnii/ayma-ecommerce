<template>
  <div>
    <v-data-table
      item-key="id"
      class="elevation-1"
      :headers="headers"
      :items="categories"
      :loading="loading"
      loading-text="Loading... Please wait"
    >
      <template v-slot:item="{ item }">
        <tr :key="item.id">
          <td>{{ item.name }}</td>
          <td>{{ item.slug }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.is_active ? 'Active' : 'Inactive' }}</td>
          <td class="text-center">
            <v-btn size="small" color="warning" @click="editCategory(item.id)">Edit</v-btn>&nbsp;
            <v-btn size="small" color="error" @click="deleteCategory(item.id)">Delete</v-btn>
          </td>
        </tr>
      </template>
    </v-data-table>
    <v-pagination
      v-model="currentPage"
      :length="totalPages"
      @input="onPageChange"
    />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { fetchCategoriesList } from './fetchCategoriesList.ts';

const headers = ref([
  { text: 'Name', value: 'name' },
  { text: 'Slug', value: 'slug' },
  { text: 'Description', value: 'description' },
  { text: 'Status', value: 'is_active' },
  { text: 'Action', value: 'action', sortable: false }
]);

const {
  categories,
  currentPage,
  totalPages,
  loading,
  onPageChange
} = fetchCategoriesList();

const editCategory = (id: number) => {
  // Handle edit action, e.g., navigating to an edit page or opening a modal
  console.log('Editing category with ID:', id);
  // Example: navigate to edit page
  // router.push(`/categories/edit/${id}`);
};

const deleteCategory = async (id: number) => {
  if (confirm('Are you sure you want to delete this category?')) {
    try {
      await fetch(`/api/categories/${id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      // Re-fetch the category list to reflect the deletion
      onPageChange(currentPage.value);
      console.log('Category deleted successfully');
    } catch (error) {
      console.error('Error deleting category:', error);
    }
  }
};
</script>


<style src="./CategoriesTable.css" scoped></style>
