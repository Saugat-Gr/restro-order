<script setup>
import {
  CCard, CCardBody, CCardHeader,
  CButton, CModal, CModalHeader, CModalBody, CModalFooter,
  CFormInput, CFormFeedback, CAlert
} from '@coreui/vue'

import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const visible = ref(false)

const form = useForm({
  password: '',
})

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    onSuccess: () => (visible.value = false),
  })
}
</script>

<template>
  <CCard>
    <CCardHeader>
      <strong>Delete Account</strong>
    </CCardHeader>

    <CCardBody>
      <CAlert color="danger">
        Once deleted, your account cannot be recovered.
      </CAlert>

      <CButton color="danger" @click="visible = true">
        Delete Account
      </CButton>
    </CCardBody>
  </CCard>

  <!-- Modal -->
  <CModal :visible="visible" @close="visible = false">
    <CModalHeader>Confirm Deletion</CModalHeader>

    <CModalBody>
      <p>Enter password to confirm:</p>

      <CFormInput
        type="password"
        v-model="form.password"
        :invalid="!!form.errors.password"
      />
      <CFormFeedback invalid>
        {{ form.errors.password }}
      </CFormFeedback>
    </CModalBody>

    <CModalFooter>
      <CButton color="secondary" @click="visible = false">
        Cancel
      </CButton>

      <CButton color="danger" @click="deleteUser" :disabled="form.processing">
        Delete
      </CButton>
    </CModalFooter>
  </CModal>
</template>