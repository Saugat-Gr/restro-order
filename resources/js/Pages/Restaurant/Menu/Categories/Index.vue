<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

import {
  CButton,
  CContainer,
  CForm,
  CHeader,
  CModal,
  CModalBody,
  CModalHeader,
  CModalTitle,
  CModalFooter,
  CTable,
  CTableBody,
  CTableCaption,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
  CTableDataCell,
  CFormInput,
  CFormLabel,
} from "@coreui/vue";

defineOptions({
  layout: AuthenticatedLayout,
});

defineProps({
  categories: Array,
});

const page = usePage();

const user = page.props.auth.user;

const canCreate = user.permissions.includes('create-category');

/* ---------------- FORMS ---------------- */
const createForm = useForm({
  name: "",
});

const editForm = useForm({
  id: null,
  name: "",
});

const deleteForm = useForm({
  id: null,
  name: "",
});

/* ---------------- STATE ---------------- */
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);

/* ---------------- CREATE ---------------- */
const createCategory = () => {
  createForm.post(route("menu.category.store"), {
    onSuccess: () => createForm.reset(),
  });
};

/* ---------------- EDIT ---------------- */
const openEditModal = (category) => {
  editForm.id = category.id;
  editForm.name = category.name;
  isEditModalOpen.value = true;
};

const updateCategory = () => {
  editForm.patch(route("menu.category.update", editForm.id), {
    onSuccess: () => closeModal(),
  });
};

/* ---------------- DELETE ---------------- */
const openDeleteModal = (category) => {
  deleteForm.id = category.id;
  deleteForm.name = category.name;
  isDeleteModalOpen.value = true;
};

const deleteCategory = () => {
  router.delete(route("menu.category.destroy", deleteForm.id), {
    onSuccess: () => closeModal(),
  });
};

/* ---------------- MODAL ---------------- */
const closeModal = () => {
  isEditModalOpen.value = false;
  isDeleteModalOpen.value = false;

  editForm.reset();
  deleteForm.reset();
};
</script>

<template>
  <!-- CREATE -->
  <CContainer class="border rounded-3 shadow-lg p-4" v-if="canCreate">
    <CHeader class="d-flex justify-content-center border-none">
      <h4 class="text-dark">Create Menu Category</h4>
    </CHeader>

    <CForm @submit.prevent="createCategory">
      <div class="mb-3">
        <CFormLabel>Category Name</CFormLabel>
        <CFormInput
          v-model="createForm.name"
          :invalid="createForm.errors.name"
        />
        <div v-if="createForm.errors.name" class="invalid-feedback d-block">
          {{ createForm.errors.name }}
        </div>
      </div>

      <CButton color="primary" type="submit" :disabled="createForm.processing">
        {{ createForm.processing ? "Creating..." : "Create Category" }}
      </CButton>
    </CForm>
  </CContainer>

  <!-- EDIT MODAL -->
  <CModal backdrop="static" :visible="isEditModalOpen" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Edit Category</CModalTitle>
    </CModalHeader>

    <CModalBody>
      <CForm @submit.prevent="updateCategory">
        <div class="mb-3">
          <CFormLabel>Category Name</CFormLabel>
          <CFormInput
            v-model="editForm.name"
            :invalid="editForm.errors.name"
          />
          <div v-if="editForm.errors.name" class="invalid-feedback d-block">
            {{ editForm.errors.name }}
          </div>
        </div>
      </CForm>
    </CModalBody>

    <CModalFooter>
      <CButton color="secondary" @click="closeModal">Cancel</CButton>

      <CButton
        color="warning"
        @click="updateCategory"
        :disabled="editForm.processing"
      >
        {{ editForm.processing ? "Updating..." : "Update" }}
      </CButton>
    </CModalFooter>
  </CModal>

  <!-- DELETE MODAL -->
  <CModal backdrop="static" :visible="isDeleteModalOpen" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Delete Category</CModalTitle>
    </CModalHeader>

    <CModalBody>
      <p>
        Are you sure you want to delete:
        <strong>{{ deleteForm.name }}</strong> ?
      </p>
    </CModalBody>

    <CModalFooter>
      <CButton color="secondary" @click="closeModal">Cancel</CButton>

      <CButton color="danger" @click="deleteCategory">
        Delete
      </CButton>
    </CModalFooter>
  </CModal>

  <!-- TABLE -->
  <CContainer class="border rounded-3 shadow-lg mt-4 p-4">
    <CTable caption="top">
      <CTableCaption>List of Categories</CTableCaption>

      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Name</CTableHeaderCell>
          <CTableHeaderCell>Items</CTableHeaderCell>
          <CTableHeaderCell v-if="canCreate">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow v-for="(category, index) in categories" :key="category.id">
          <CTableHeaderCell>{{ index + 1 }}</CTableHeaderCell>
          <CTableDataCell>{{ category.name }}</CTableDataCell>
          <CTableDataCell>{{ category.menu_items_count }}</CTableDataCell>

          <CTableDataCell v-if="canCreate">
            <CButton
              color="danger"
              class="me-2"
              @click="openDeleteModal(category)"
            >
              Delete
            </CButton>

            <CButton color="warning" @click="openEditModal(category)">
              Edit
            </CButton>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>
  </CContainer>
</template>