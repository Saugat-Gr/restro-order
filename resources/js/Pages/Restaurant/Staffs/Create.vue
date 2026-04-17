<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  avatar: null,
});

const previewUrl = ref(null);

const handleFileChange = (e) => {
  const file = e.target.files[0];
  form.avatar = file;

  if (file) {
    previewUrl.value = URL.createObjectURL(file);
  }
};

const removeAvatar = () => {
  form.avatar = null;
  previewUrl.value = null;
};

const submit = () => {
  form.post(route("staffs.store"), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      removeAvatar();
      form.reset();
    },
  });
};
</script>

<template>
  <CContainer class="py-4">
    <CRow class="justify-content-center">
      <CCol lg="7">
        <!-- Header -->
        <div class="mb-4">
          <h3 class="fw-bold mb-1">Create Staff Member</h3>
          <p class="text-muted mb-0">
            Add a new staff account to your restaurant dashboard
          </p>
        </div>

        <!-- Card -->
        <CCard class="shadow-lg border-0 rounded-4">
          <CCardBody class="p-4">
            <CForm @submit.prevent="submit">
              <!-- Avatar Section -->
              <div class="text-center mb-4">
                <div class="position-relative d-inline-block">
                  <img
                    v-if="previewUrl"
                    :src="previewUrl"
                    class="rounded-circle border shadow-sm"
                    width="110"
                    height="110"
                    style="object-fit: cover"
                  />

                  <div
                    v-else
                    class="rounded-circle bg-light border d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 110px; height: 110px"
                  >
                    <span class="text-muted">No Image</span>
                  </div>

                  <button
                    v-if="previewUrl"
                    type="button"
                    class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle"
                    style="transform: translate(25%, -25%)"
                    @click="removeAvatar"
                  >
                    ✕
                  </button>
                </div>

                <div class="mt-3">
                  <CFormInput
                    type="file"
                    accept="image/*"
                    @change="handleFileChange"
                    :invalid="!!form.errors.avatar"
                  />
                  <div v-if="form.errors.avatar" class="text-danger small mt-1">
                    {{ form.errors.avatar }}
                  </div>
                </div>
              </div>

              <CRow class="g-3">
                <!-- Name -->
                <CCol md="6">
                  <CFormLabel class="fw-semibold">Full Name</CFormLabel>
                  <CInputGroup>
                    <CFormInput
                      v-model="form.name"
                      placeholder="CTS Staff"
                      :invalid="!!form.errors.name"
                    />
                  </CInputGroup>
                  <div v-if="form.errors.name" class="text-danger small">
                    {{ form.errors.name }}
                  </div>
                </CCol>

                <!-- Email -->
                <CCol md="6">
                  <CFormLabel class="fw-semibold">Email</CFormLabel>
                  <CInputGroup>
                    <CFormInput
                      v-model="form.email"
                      type="email"
                      placeholder="ctsstaff@email.com"
                      :invalid="!!form.errors.email"
                    />
                  </CInputGroup>
                  <div v-if="form.errors.email" class="text-danger small">
                    {{ form.errors.email }}
                  </div>
                </CCol>

                <!-- Password -->
                <CCol md="6">
                  <CFormLabel class="fw-semibold">Password</CFormLabel>
                  <CInputGroup>
                    <CFormInput
                      v-model="form.password"
                      type="password"
                      :invalid="!!form.errors.password"
                    />
                  </CInputGroup>
                  <div v-if="form.errors.password" class="text-danger small">
                    {{ form.errors.password }}
                  </div>
                </CCol>

                <!-- Confirm Password -->
                <CCol md="6">
                  <CFormLabel class="fw-semibold">Confirm Password</CFormLabel>
                  <CInputGroup>
                    <CFormInput
                      v-model="form.password_confirmation"
                      type="password"
                    />
                  </CInputGroup>
                </CCol>
              </CRow>

              <!-- Actions -->
              <div class="d-flex justify-content-end mt-4 gap-2">
                <CButton
                  color="secondary"
                  variant="outline"
                  type="button"
                  @click="form.reset()"
                >
                  Reset
                </CButton>

                <CButton
                  color="primary"
                  type="submit"
                  :disabled="form.processing"
                  class="px-4"
                >
                  <span v-if="form.processing">Saving...</span>
                  <span v-else>Create Staff</span>
                </CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>