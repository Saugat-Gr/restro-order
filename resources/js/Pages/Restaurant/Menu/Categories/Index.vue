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
import CIcon from "@coreui/icons-vue";

defineOptions({
  layout: AuthenticatedLayout,
});

defineProps({
  categories: Array,
});

const page = usePage();

const user = page.props.auth.user;

const canCreate = user.permissions.includes("create-category");

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

const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isCreateModalOpen = ref(false);

const createCategory = () => {
  createForm.post(route("menu.category.store"), {
    onSuccess: () => {
      closeModal();
      createForm.reset();
    },
  });
};


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


const closeModal = () => {
  isEditModalOpen.value = false;
  isDeleteModalOpen.value = false;
  isCreateModalOpen.value = false;

  createForm.reset();
  editForm.reset();
  deleteForm.reset();
};
</script>
<template>

  <!-- EDIT -->
  <CModal backdrop="static" :visible="isEditModalOpen" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Edit Category</CModalTitle>
    </CModalHeader>

    <CModalBody>
      <CForm @submit.prevent="updateCategory">
        <CFormLabel>Category Name</CFormLabel>
        <CFormInput
          v-model="editForm.name"
          :invalid="editForm.errors.name"
          placeholder="Enter category name"
        />
        <div v-if="editForm.errors.name" class="invalid-feedback d-block">
          {{ editForm.errors.name }}
        </div>
      </CForm>
    </CModalBody>

    <CModalFooter>
      <CButton color="light" @click="closeModal">Cancel</CButton>

      <CButton
        color="warning"
        @click="updateCategory"
        :disabled="editForm.processing"
      >
        {{ editForm.processing ? "Updating..." : "Update" }}
      </CButton>
    </CModalFooter>
  </CModal>

  <!-- CREATE -->
  <CModal backdrop="static" :visible="isCreateModalOpen" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Create Category</CModalTitle>
    </CModalHeader>

    <CModalBody>
      <CForm @submit.prevent="createCategory">
        <CFormLabel>Category Name</CFormLabel>
        <CFormInput
          v-model="createForm.name"
          :invalid="createForm.errors.name"
          placeholder="e.g. Drinks, Snacks"
        />
        <div v-if="createForm.errors.name" class="invalid-feedback d-block">
          {{ createForm.errors.name }}
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <CButton color="light" @click="closeModal">Cancel</CButton>

          <CButton
            color="primary"
            type="submit"
            :disabled="createForm.processing"
          >
            {{ createForm.processing ? "Creating..." : "Create" }}
          </CButton>
        </div>
      </CForm>
    </CModalBody>
  </CModal>

  <!-- DELETE -->
  <CModal backdrop="static" :visible="isDeleteModalOpen" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Delete Category</CModalTitle>
    </CModalHeader>

    <CModalBody>
      <p class="mb-0">
        Are you sure you want to delete
        <strong>{{ deleteForm.name }}</strong
        >?
      </p>
    </CModalBody>

    <CModalFooter>
      <CButton color="light" @click="closeModal">Cancel</CButton>

      <CButton color="danger" @click="deleteCategory"> Delete </CButton>
    </CModalFooter>
  </CModal>


  <CContainer class="py-4">
    <!-- Card Table -->
    <div class=" border rounded-4 shadow-lg p-5">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fw-bold mb-0">Categories</h4>
          <small class="text-medium-emphasis">
            Manage your menu categories
          </small>
        </div>

        <CButton
          v-if="canCreate"
          color="primary"
          class="d-flex align-items-center gap-2"
          @click="isCreateModalOpen = true"
        >
          <CIcon name="cil-plus" />
          Create Category
        </CButton>
      </div>

      <CTable hover responsive align="middle" class="mb-0">
        <CTableHead>
          <CTableRow class="text-medium-emphasis">
            <CTableHeaderCell>#</CTableHeaderCell>
            <CTableHeaderCell>Name</CTableHeaderCell>
            <CTableHeaderCell>Items</CTableHeaderCell>
            <CTableHeaderCell v-if="canCreate" class="text-end">
              Actions
            </CTableHeaderCell>
          </CTableRow>
        </CTableHead>

        <CTableBody>
          <CTableRow v-for="(category, index) in categories" :key="category.id">
            <CTableHeaderCell>{{ index + 1 }}</CTableHeaderCell>

            <CTableDataCell class="fw-semibold">
              {{ category.name }}
            </CTableDataCell>

            <CTableDataCell>
              {{ category.menu_items_count }}
            </CTableDataCell>

            <CTableDataCell v-if="canCreate" class="text-end">
              <div class="d-flex justify-content-end gap-2">
                <CButton
                  color="secondary"
                  variant="outline"
                  size="sm"
                  @click="openEditModal(category)"
                >
                  <CIcon name="cil-pencil" />
                </CButton>

                <CButton
                  color="danger"
                  variant="outline"
                  size="sm"
                  @click="openDeleteModal(category)"
                >
                  <CIcon name="cil-trash" />
                </CButton>
              </div>
            </CTableDataCell>
          </CTableRow>
        </CTableBody>
      </CTable>
    </div>
  </CContainer>
</template>