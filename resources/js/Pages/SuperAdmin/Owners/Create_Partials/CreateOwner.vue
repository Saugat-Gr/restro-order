<script setup>
import { useForm } from "@inertiajs/vue3";

const emit = defineEmits(["created"]);

const form = useForm({
  avatar: null,
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  phone: "",
});

const submit = () => {
  form.post("/register", {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};

const handleFileChange = (e) => {
  form.avatar = e.target.files[0];
};
</script>

<template>
  <CForm @submit.prevent="submit">
    <div class="mb-4">
      <h4 class="mb-1">Create Owner</h4>
      <p class="text-medium-emphasis mb-0">
        Add a user who will manage restaurants
      </p>
    </div>

    <CRow class="g-3">
      <!-- Name -->
      <CCol md="6">
        <div class="mb-3">
          <CFormLabel>Full Name</CFormLabel>
          <CFormInput
            v-model="form.name"
            type="text"
            placeholder="Enter full name"
            :invalid="!!form.errors.name"
          />
          <div class="text-danger small">{{ form.errors.name }}</div>
        </div>
      </CCol>

      <!-- Email -->
      <CCol md="6">
        <div class="mb-3">
          <CFormLabel>Email Address</CFormLabel>
          <CFormInput
            v-model="form.email"
            type="email"
            placeholder="Enter email"
            :invalid="!!form.errors.email"
          />
          <div class="text-danger small">{{ form.errors.email }}</div>
        </div>
      </CCol>

      <!-- Password -->
      <CCol class="col-12">
        <div class="mb-3">
          <CFormLabel>Password</CFormLabel>
          <CFormInput
            v-model="form.password"
            type="password"
            placeholder="Enter password"
            :invalid="!!form.errors.password"
          />
          <div class="text-danger small">{{ form.errors.password }}</div>
        </div>
      </CCol>

      <CCol class="col-12">
        <div class="mb-3">
          <CFormLabel>Confirm Password</CFormLabel>
          <CFormInput
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm password"
            :invalid="!!form.errors.password_confirmation"
          />

          <div class="text-danger small">
            {{ form.errors.password_confirmation }}
          </div>
        </div>
      </CCol>

      <!-- Avatar -->
      <CCol md="12">
        <div class="mb-3">
          <CFormLabel>Avatar</CFormLabel>
          <CFormInput
            type="file"
            @change="handleFileChange"
            :invalid="!!form.errors.avatar"
          />
          <div class="text-danger small">{{ form.errors.avatar }}</div>
        </div>
      </CCol>

      <!-- Submit -->
      <CCol md="12">
        <div class="d-grid mt-2">
          <CButton
            color="primary"
            size="lg"
            :disabled="form.processing"
            type="submit"
          >
            Create Owner →
          </CButton>
        </div>
      </CCol>
    </CRow>
  </CForm>
</template>