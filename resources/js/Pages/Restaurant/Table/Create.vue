<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  CButton,
  CCol,
  CContainer,
  CDropdownHeader,
  CForm,
  CFormFeedback,
  CFormInput,
  CFormLabel,
  CFormSelect,
  CHeader,
  CRow,
} from "@coreui/vue";
import { useForm, usePage } from "@inertiajs/vue3";

defineOptions({
  layout: AuthenticatedLayout,
});

const page = usePage();

const tableStatuses = page.props.tableStatuses;

const form = useForm({
  table_number: "",
  capacity: "",
  status: "",
});

const createTable = () => {
      form.post(route("tables.store"));
}

</script>


<template>
  <CContainer class="d-flex justify-center mt-5">
    <CCol class="col-6 shadow-lg p-3 border rounded-lg">
      <CForm @submit.prevent="createTable">
        <CHeader class="justify-center border-none mb-4">
          <h1 class="fw-bold">Create Table</h1>
        </CHeader>
        <CRow>
          <CCol class="col-6">
            <CFormLabel>Table Name/Number</CFormLabel>
            <CFormInput
              type="text"
              v-model="form.table_number"
              :invalid="!!form.errors.table_number"
            />
            <CFormFeedback invalid>
              {{ form.errors.table_number }}
            </CFormFeedback>
          </CCol>

          <CCol class="col-6">
            <CFormLabel>Capacity</CFormLabel>
            <CFormInput type="number" v-model="form.capacity" :invalid="!!form.errors.capacity"/>
            <CFormFeedback invalid>
                 {{ form.errors.capacity }}
            </CFormFeedback>
          </CCol>
          
        </CRow>

        <CRow>
          <CCol class="col-12 mt-2">
            <CFormLabel>Status</CFormLabel>
            <CFormSelect v-model="form.status" :invalid="!!form.errors.status">
              <option value="" disabled selected>Select Status</option>
              <option
                v-for="status in tableStatuses"
                :value="status"
                :key="status"
              >
                {{ status[0].toUpperCase() + status.slice(1) }}
              </option>
            </CFormSelect>
            <CFormFeedback invalid>
                {{ form.errors.status }}
            </CFormFeedback>
          </CCol>
        </CRow>

        <div class="mt-4 float-end">
          <CButton color="secondary" class="mr-2">Cancel</CButton>
          <CButton color="primary" type="submit"> Create Table </CButton>
        </div>
      </CForm>
    </CCol>
  </CContainer>
</template>