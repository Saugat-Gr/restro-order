<script setup>
import { useForm, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

import {
  CButton,
  CContainer,
  CForm,
  CModal,
  CModalBody,
  CModalHeader,
  CModalTitle,
  CModalFooter,
  CTable,
  CTableBody,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
  CTableDataCell,
  CFormInput,
  CFormLabel,
  CCard,
  CCardBody,
} from "@coreui/vue";
import CIcon from "@coreui/icons-vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
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

const hasCategories = computed(() => props.categories?.length > 0);
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
        <CFormInput v-model="editForm.name" />
      </CForm>
    </CModalBody>

    <CModalFooter>
      <CButton color="light" @click="closeModal">Cancel</CButton>
      <CButton color="warning" @click="updateCategory" :disabled="editForm.processing">
        Update
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
        <CFormInput v-model="createForm.name" placeholder="e.g. Drinks, Snacks" />
      </CForm>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <CButton color="light" @click="closeModal">Cancel</CButton>
        <CButton color="primary" type="submit" :disabled="createForm.processing">
          Create
        </CButton>
      </div>
    </CModalBody>
  </CModal>

  <!-- DELETE -->
  <CModal backdrop="static" :visible="isDeleteModalOpen" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Delete Category</CModalTitle>
    </CModalHeader>

    <CModalBody>
      Are you sure you want to delete <strong>{{ deleteForm.name }}</strong>?
    </CModalBody>

    <CModalFooter>
      <CButton color="light" @click="closeModal">Cancel</CButton>
      <CButton color="danger" @click="deleteCategory">Delete</CButton>
    </CModalFooter>
  </CModal>

  <CContainer class="mt-4">
    <CCard class="shadow-lg border-0 rounded-4 p-3">
      <CCardBody>
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-0">Categories</h4>
            <small class="text-medium-emphasis">
              Manage your menu categories
            </small>
          </div>

          <CButton
            v-if="canCreate"
            color="primary"
            @click="isCreateModalOpen = true"
          >
            <CIcon name="cil-plus" class="me-2" />
            Create Category
          </CButton>
        </div>

        <!-- TABLE -->
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
              <CTableDataCell>{{ index + 1 }}</CTableDataCell>

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

        <!-- EMPTY STATE (UI ONLY) -->
        <div
          v-if="!hasCategories"
          class="text-center py-5 text-medium-emphasis"
        >
          <CIcon name="cil-list" size="xl" class="mb-2" />
          <div>No categories found</div>
        </div>
      </CCardBody>
    </CCard>
  </CContainer>
</template>