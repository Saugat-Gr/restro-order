<script setup>
import {
  CCard, CCardBody, CCardHeader,
  CForm, CFormInput, CFormLabel, CFormFeedback,
  CButton, CAlert
} from '@coreui/vue'

import { Link, useForm, usePage } from '@inertiajs/vue3'

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
})

const user = usePage().props.auth.user

const form = useForm({
  name: user.name,
  email: user.email,
})
</script>

<template>
  <CCard>
    <CCardHeader>
      <strong>Profile Information</strong>
    </CCardHeader>

    <CCardBody>
      <CForm @submit.prevent="form.patch(route('profile.update'))">

        <div class="mb-3">
          <CFormLabel>Name</CFormLabel>
          <CFormInput v-model="form.name" :invalid="!!form.errors.name" />
          <CFormFeedback invalid>{{ form.errors.name }}</CFormFeedback>
        </div>

        <div class="mb-3">
          <CFormLabel>Email</CFormLabel>
          <CFormInput v-model="form.email" type="email" :invalid="!!form.errors.email" />
          <CFormFeedback invalid>{{ form.errors.email }}</CFormFeedback>
        </div>

        <div v-if="mustVerifyEmail && user.email_verified_at === null">
          <CAlert color="warning">
            Email not verified
            <Link
              :href="route('verification.send')"
              method="post"
              as="button"
              class="btn btn-link"
            >
              Resend
            </Link>
          </CAlert>

          <CAlert v-if="status === 'verification-link-sent'" color="success">
            Verification link sent
          </CAlert>
        </div>

        <div class="d-flex gap-3 align-items-center mt-3">
          <CButton type="submit" color="primary">Save</CButton>
          <CAlert v-if="form.recentlySuccessful" color="success" class="mb-0 py-1 px-2">
            Saved
          </CAlert>
        </div>

      </CForm>
    </CCardBody>
  </CCard>
</template>