<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

import {
  CContainer,
  CRow,
  CCol,
  CCard,
  CCardBody,
  CButton,
  CForm,
  CFormInput,
  CFormCheck,
  CAlert
} from '@coreui/vue';
import AppFooter from '@/Components/AppFooter.vue';

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Log in" />

  <div class="min-vh-100 d-flex flex-column bg-body">
    <!-- HEADER -->
    <header class="border-bottom bg-body">
        
      <CContainer class="d-flex justify-content-between align-items-center py-3">
          <div class="fw-bold fs-5 text-primary">
          <Link :href="route('welcome')" class="text-decoration-none">
                RestoSystem
            </Link>
        </div>

        <Link :href="route('login')">
          <CButton color="secondary" size="sm">Login</CButton>
        </Link>
      </CContainer>
    </header>

    <!-- LOGIN SECTION -->
    <section class="flex-grow-1 d-flex align-items-center py-5">
      <CContainer>
        <CRow class="justify-content-center">
          <CCol md="6" lg="5">
            <CCard class="border-0 shadow-lg bg-body-tertiary">
              <CCardBody class="p-4">

                <h4 class="fw-bold mb-3 text-center">Login</h4>

                <CAlert v-if="status" color="success">
                  {{ status }}
                </CAlert>

                <CForm @submit.prevent="submit">
                  <!-- Email -->
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <CFormInput
                      type="email"
                      v-model="form.email"
                      required
                      autofocus
                      autocomplete="username"
                    />
                    <div v-if="form.errors.email" class="text-danger small mt-1">
                      {{ form.errors.email }}
                    </div>
                  </div>

                  <!-- Password -->
                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <CFormInput
                      type="password"
                      v-model="form.password"
                      required
                      autocomplete="current-password"
                    />
                    <div v-if="form.errors.password" class="text-danger small mt-1">
                      {{ form.errors.password }}
                    </div>
                  </div>

                  <!-- Remember -->
                  <div class="mb-3">
                    <CFormCheck
                      label="Remember me"
                      v-model="form.remember"
                    />
                  </div>

                  <!-- Actions -->
                  <div class="d-flex justify-content-between align-items-center">
                    <Link
                      v-if="canResetPassword"
                      :href="route('password.request')"
                      class="text-decoration-none small"
                    >
                      Forgot password?
                    </Link>

                    <CButton
                      type="submit"
                      color="primary"
                      :disabled="form.processing"
                    >
                      Log in
                    </CButton>
                  </div>
                </CForm>

              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </CContainer>
    </section>

    <AppFooter/>
  </div>
</template>