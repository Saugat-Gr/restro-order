<script setup>
import {
  CCard,
  CCardBody,
  CCardHeader,
  CForm,
  CFormInput,
  CFormLabel,
  CFormFeedback,
  CButton,
  CAlert,
} from "@coreui/vue";
import { Inertia } from "@inertiajs/inertia";

import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
});

const user = usePage().props.auth.user;

console.log(user);
const previewUrl = ref(null);

const handleFileChange = (e) => {
   const file = e.target.files[0];
   form.avatar = file;

   if(file){
     previewUrl.value = URL.createObjectURL(file);
   }
}

const form = useForm({
  name: user.name,
  email: user.email,
  avatar: null,
  _method: 'patch'
});

const submit = () => {
  form.post(route("profile.update"), {
    forceFormData: true,
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
       Inertia.reload({only: ['user']})
    }
  });
};
</script>
<template>
  <CCard>
    <CCardHeader>
      <strong>Profile Information</strong>
    </CCardHeader>

    <CCardBody>
      <CForm @submit.prevent="submit">
        <div class="mb-3">
          <CFormLabel>Name</CFormLabel>
          <CFormInput v-model="form.name" :invalid="!!form.errors.name" />
          <CFormFeedback invalid>{{ form.errors.name }}</CFormFeedback>
        </div>

        <div class="mb-3">
          <CFormLabel>Email</CFormLabel>
          <CFormInput
            v-model="form.email"
            type="email"
            :invalid="!!form.errors.email"
            class="form-control"
          />
          <CFormFeedback invalid>{{ form.errors.email }}</CFormFeedback>
        </div>

        <!-- Avatar UI Improved -->
        <div class="mb-3">
          <CFormLabel>Avatar</CFormLabel>

          <div class="d-flex align-items-center gap-3">
            <img
              :src="previewUrl || `/storage/${user.avatar}`"
              width="80"
              height="80"
              class="rounded-circle"
            />

            <!-- File Input -->
            <div class="flex-grow-1">
              <CFormInput
                type="file"
                @change="handleFileChange"
                :invalid="!!form.errors.avatar"
              />
              <CFormFeedback invalid>{{ form.errors.avatar }}</CFormFeedback>
            </div>
          </div>
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
          <CAlert
            v-if="form.recentlySuccessful"
            color="success"
            class="mb-0 py-1 px-2"
          >
            Saved
          </CAlert>
        </div>
      </CForm>
    </CCardBody>
  </CCard>
</template>