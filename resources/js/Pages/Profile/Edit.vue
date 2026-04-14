<script setup>
import { Head } from '@inertiajs/vue3'
import {
  CContainer,
  CRow,
  CCol,
} from '@coreui/vue'

import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineOptions({
    layout: AuthenticatedLayout
})

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
})
</script>

<template>
  <Head title="Profile" />

  <CContainer class="py-4">
    <CRow class="g-4">

      <CCol xs="12">
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
        />
      </CCol>

      <CCol xs="12">
        <UpdatePasswordForm />
      </CCol>

      <CCol xs="12">
        <DeleteUserForm />
      </CCol>

    </CRow>
  </CContainer>
</template>