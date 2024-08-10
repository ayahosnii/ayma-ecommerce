<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router'; // Import useRouter if you want to redirect after success
import { useToast } from 'vue-toast-notification';

const $toast = useToast();

const name = ref('');
const slug = ref('');
const description = ref('');
const isActive = ref(true);
const parentId = ref(null);
const image = ref(null); // Add image ref to store the uploaded file
const router = useRouter();

const handleImageChange = (event) => {
  image.value = event.target.files[0]; // Store the selected file in the image ref
};

const handleSubmit = async () => {
  const token = localStorage.getItem('authToken');

  try {
    // Create a FormData object to handle file uploads
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('slug', slug.value);
    formData.append('description', description.value);
    formData.append('is_active', isActive.value);
    formData.append('parent_id', parentId.value);

    if (image.value) {
      formData.append('image', image.value); // Append the image file to the formData
    }

    const response = await axios.post('http://127.0.0.1:8000/api/categories', formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data', // Important for file uploads
      },
    });

    $toast.success('Category added successfully!');
    console.log('Category added successfully:', response.data);
    
    // Optionally redirect after success
    // router.push('/categories'); // Uncomment this if you want to navigate to categories list

  } catch (error) {
    $toast.error('Error adding category: ' + (error.response?.data?.message || error.message));
    console.error('Error adding category:', error);
  }
};
</script>

<template>
  <VForm @submit.prevent="handleSubmit">
    <VRow>
      <VCol cols="12">
        <VTextField
          v-model="name"
          label="Name"
          placeholder="Category Name"
        />
      </VCol>

      <VCol cols="12">
        <VTextField
          v-model="slug"
          label="Slug"
          placeholder="category-slug"
        />
      </VCol>

      <VCol cols="12">
        <VTextField
          v-model="description"
          label="Description"
          placeholder="Category Description"
          type="textarea"
        />
      </VCol>

      <VCol cols="12">
        <VCheckbox
          v-model="isActive"
          label="Active"
          true-value="true"
          false-value="false"
        />
      </VCol>

      <VCol cols="12">
        <VTextField
          v-model="parentId"
          label="Parent ID"
          placeholder="Parent Category ID (Optional)"
          type="number"
        />
      </VCol>

      <!-- New Image Upload Field -->
      <VCol cols="12">
        <VFileInput
          label="Upload Image"
          accept="image/*"
          @change="handleImageChange"
        />
      </VCol>

      <VCol
        cols="12"
        class="d-flex gap-4"
      >
        <VBtn type="submit">
          Add
        </VBtn>
      </VCol>
    </VRow>
  </VForm>
</template>
