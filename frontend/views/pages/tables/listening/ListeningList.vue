<template>
  <div>
    <!-- Add Button -->
    <VBtn class="mb-4 ml-4" size="small" title="Add" type="Add" color="secondary" @click="openAddModal">
      <i class="ri-add-circle-line"></i> Add Listening
    </VBtn>

    <VTable>
      <thead>
        <tr>
          <th class="text-uppercase text-center">Title</th>
          <th class="text-uppercase text-center">Transcript</th>
          <th class="text-uppercase text-center">Audio</th>
          <th class="text-uppercase text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="listening in listenings" :key="listening.id">
          <td class="text-center">{{ listening.title }}</td>
          <td class="text-center">{{ listening.transcript }}</td>
          <td class="text-center">
            <audio :src="`${BASE_URL}/storage/${listening.audio_url}`" controls></audio>
          </td>
          <td class="text-center">
            <VBtn size="small" title="Edit" type="edit" color="warning" @click="openEditModal(listening)">
              <i class="ri-edit-fill"></i>
            </VBtn>&nbsp;
            <VBtn size="small" title="Delete" type="delete" color="error" @click="openDeleteModal(listening)">
              <i class="ri-delete-bin-line"></i>
            </VBtn>
          </td>
        </tr>
      </tbody>
    </VTable>

    <VPagination v-model="currentPage" :length="totalPages" @page-change="onPageChange" />

    <!-- Add Modal -->
    <VDialog v-model="addModal" max-width="800px">
      <VCard>
        <VCardTitle>Add New Listening</VCardTitle>
        <VCardText>
          <VForm ref="addForm">
            <VRow>
              <VCol cols="12">
                <VTextField
                  v-model="newListening.title"
                  label="Title"
                  :rules="[v => !!v || 'Title is required']"
                  required
                />
              </VCol>
            </VRow>
            <VRow>
              <VCol cols="12">
                <VFileInput
                  v-model="newListening.audio"
                  label="Audio File"
                  accept="audio/*"
                />
              </VCol>
            </VRow>
            <VRow>
              <VCol cols="12">
                <VTextField
                  v-model="newListening.transcript"
                  label="Transcript"
                />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
        <VCardActions>
          <VCol cols="12" class="d-flex gap-4">
            <VBtn color="error" text @click="closeAddModal">Cancel</VBtn>
            <VBtn color="success" text @click="createListening">Save</VBtn>
          </VCol>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Edit Modal -->
    <VDialog v-model="editModal" max-width="800px">
      <VCard>
        <VCardTitle>Edit Listening</VCardTitle>
        <VCardText>
          <VForm ref="editForm">
            <VRow>
              <VCol cols="12">
                <VTextField
                  v-model="editListening.title"
                  label="Title"
                  :rules="[v => !!v || 'Title is required']"
                  required
                />
              </VCol>
            </VRow>
            <VRow>
              <VCol cols="12">
                <VFileInput
                  v-model="editListening.audio"
                  label="Audio File"
                  accept="audio/mpeg, audio/mp3, audio/wav, audio/aac"
                />
              </VCol>
            </VRow>
            <VRow>
              <VCol cols="12">
                <VTextField
                  v-model="editListening.transcript"
                  label="Transcript"
                />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
        <VCardActions>
          <VCol cols="12" class="d-flex gap-4">
            <VBtn color="error" text @click="closeEditModal">Cancel</VBtn>
            <VBtn color="success" text @click="updateListening">Save</VBtn>
          </VCol>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Delete Modal -->
    <VDialog v-model="deleteModal" max-width="500px">
      <VCard>
        <VCardTitle>Delete Listening</VCardTitle>
        <VCardText>Are you sure you want to delete this listening?</VCardText>
        <VCardActions>
          <VCol cols="12">
            <VBtn color="error" text @click="closeDeleteModal">Cancel</VBtn>
            <VBtn color="success" text @click="deleteListening">Delete</VBtn>
          </VCol>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>

<script setup lang="ts">
import { BASE_URL } from '@/config/apiConfig';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const listenings = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);

const addModal = ref(false);
const editModal = ref(false);
const deleteModal = ref(false);

const newListening = ref({
  title: '',
  audio: null,
  transcript: '',
});
const editListening = ref({
  id: '',
  title: '',
  audio: null,
  transcript: '',
});
const listeningToDelete = ref(null);

const fetchListenings = async (page = 1) => {
  try {
    const token = localStorage.getItem('authToken');
    if (token) {
      const response = await axios.get(`${BASE_URL}/listening?page=${page}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      listenings.value = response.data.data; // Adjust if necessary
      totalPages.value = response.data.last_page;
      currentPage.value = response.data.current_page;
    } else {
      console.error('No auth token found');
    }
  } catch (error) {
    console.error('Failed to fetch listenings:', error);
  }
};

const openAddModal = () => {
  newListening.value = { title: '', audio: null, transcript: '' }; // Reset fields
  addModal.value = true;
};

const closeAddModal = () => {
  addModal.value = false;
};

const createListening = async () => {
  try {
    const token = localStorage.getItem('authToken');
    if (token) {
      const formData = new FormData();
      formData.append('title', newListening.value.title);
      if (newListening.value.audio) formData.append('audio', newListening.value.audio);
      formData.append('transcript', newListening.value.transcript);

      await axios.post(`${BASE_URL}/listening`, formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'multipart/form-data',
        },
      });

      fetchListenings(currentPage.value);
      closeAddModal();
    } else {
      console.error('No auth token found');
    }
  } catch (error) {
    console.error('Failed to create listening:', error);
  }
};

const openEditModal = (listening) => {
  editListening.value = { ...listening, audio: null }; // Reset audio to avoid unwanted data
  editModal.value = true;
};

const closeEditModal = () => {
  editModal.value = false;
};

const updateListening = async () => {
  try {
    const token = localStorage.getItem('authToken');
    if (token) {
      const formData = new FormData();
      formData.append('title', editListening.value.title);
      if (editListening.value.audio) formData.append('audio', editListening.value.audio);
      formData.append('transcript', editListening.value.transcript);

      await axios.post(`${BASE_URL}/listening/${editListening.value.id}`, formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'multipart/form-data',
        },
      });

      fetchListenings(currentPage.value);
      closeEditModal();
    } else {
      console.error('No auth token found');
    }
  } catch (error) {
    console.error('Failed to update listening:', error);
  }
};

const openDeleteModal = (listening) => {
  listeningToDelete.value = listening;
  deleteModal.value = true;
};

const closeDeleteModal = () => {
  deleteModal.value = false;
};

const deleteListening = async () => {
  try {
    const token = localStorage.getItem('authToken');
    if (token && listeningToDelete.value) {
      await axios.delete(`${BASE_URL}/listening/${listeningToDelete.value.id}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      fetchListenings(currentPage.value);
      closeDeleteModal();
    } else {
      console.error('No auth token found or delete listening ID missing');
    }
  } catch (error) {
    console.error('Failed to delete listening:', error);
  }
};

const onPageChange = (page) => {
  currentPage.value = page;
};

onMounted(() => {
  fetchListenings(currentPage.value);
});

watch(currentPage, (newPage) => fetchListenings(newPage));
</script>

<style scoped>
.text-uppercase {
  text-transform: uppercase;
}
</style>
