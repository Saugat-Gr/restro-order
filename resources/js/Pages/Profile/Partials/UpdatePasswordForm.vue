<script setup>
import {
  CCard, CCardBody, CCardHeader,
  CForm, CFormInput, CFormLabel, CFormFeedback,
  CButton, CAlert
} from '@coreui/vue'

import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <CCard>
    <CCardHeader>
      <strong>Update Password</strong>
      <div class="text-body-secondary small">
        Ensure your account is using a long, random password.
      </div>
    </CCardHeader>

    <CCardBody>
      <CForm @submit.prevent="updatePassword">

        <div class="mb-3">
          <CFormLabel>Current Password</CFormLabel>
          <CFormInput
            type="password"
            v-model="form.current_password"
            :invalid="!!form.errors.current_password"
          />
          <CFormFeedback invalid>
            {{ form.errors.current_password }}
          </CFormFeedback>
        </div>

        <div class="mb-3">
          <CFormLabel>New Password</CFormLabel>
          <CFormInput
            type="password"
            v-model="form.password"
            :invalid="!!form.errors.password"
          />
          <CFormFeedback invalid>
            {{ form.errors.password }}
          </CFormFeedback>
        </div>

        <div class="mb-3">
          <CFormLabel>Confirm Password</CFormLabel>
          <CFormInput
            type="password"
            v-model="form.password_confirmation"
            :invalid="!!form.errors.password_confirmation"
          />
          <CFormFeedback invalid>
            {{ form.errors.password_confirmation }}
          </CFormFeedback>
        </div>

        <div class="d-flex gap-3 align-items-center">
          <CButton type="submit" color="primary" :disabled="form.processing">
            Save
          </CButton>

          <CAlert v-if="form.recentlySuccessful" color="success" class="mb-0 py-1 px-2">
            Saved
          </CAlert>
        </div>

      </CForm>
    </CCardBody>
  </CCard>
</template>