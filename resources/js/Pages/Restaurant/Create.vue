<script setup>
import { useForm, Head } from "@inertiajs/vue3";

// Inertia form (cleaner)
const form = useForm({
  name: "",
  address: "",
  phone: "",
  email: "",
  logo: null
});

// Submit handler
const submit = () => {
  form.post(route("restaurant.store"));
};
</script>

<template>
  <Head>
    <title>{{ "Create Restaurant" }}</title>
  </Head>

  <CRow class="w-full justify-content-center mt-5">
    <CCol xs="12" sm="10" md="6" lg="5">
      <CCard class="shadow-2xl border-0">
        <CCardHeader class="text-center">
          <h4 class="mb-1">🏪 Create Your Restaurant</h4>
          <small class="text-medium-emphasis">
            Set up your restaurant to start managing orders
          </small>
        </CCardHeader>

        <CCardBody>
          <CForm @submit.prevent="submit">
            <!-- Name -->
            <div class="mb-3">
              <CFormLabel>Restaurant Name</CFormLabel>
              <CFormInput
                v-model="form.name"
                placeholder="e.g. Himalayan Bites"
                :invalid="form.errors.name"
              />
              <div class="text-danger small">{{ form.errors.name }}</div>
            </div>

            <!-- Address -->
            <div class="mb-3">
              <CFormLabel>Address</CFormLabel>
              <CFormInput
                v-model="form.address"
                placeholder="e.g. Kathmandu, Nepal"
                :invalid="form.errors.address"
              />
              <div class="text-danger small">{{ form.errors.address }}</div>
            </div>

            <!-- Phone -->
            <div class="mb-4">
              <CFormLabel>Phone</CFormLabel>
              <CFormInput
                v-model="form.phone"
                placeholder="+977-98XXXXXXXX"
                :invalid="form.errors.phone"
              />
              <div class="text-danger small">{{ form.errors.phone }}</div>
            </div>

            <!-- Email -->
            <div class="mb-4">
              <CFormLabel>Email</CFormLabel>
              <CFormInput
                v-model="form.email"
                type="email"
                placeholder="e.g. hello@yourrestaurant.com"
                :invalid="form.errors.email"
              />
              <div class="text-danger small">{{ form.errors.email }}</div>
            </div>

            <!-- Logo -->
            <div class="mb-4">
              <CFormLabel>Logo</CFormLabel>
              <CFormInput
                type="file"
                @change="e => form.logo = e.target.files[0]"
                :invalid="form.errors.logo"
              />
              <div class="text-danger small">{{ form.errors.logo }}</div>
            </div>

            <!-- Submit -->
            <div class="d-grid">
              <CButton
                color="primary"
                type="submit"
                :disabled="form.processing"
              >
                {{ form.processing ? "Creating..." : "Create Restaurant" }}
              </CButton>
            </div>
          </CForm>
        </CCardBody>
      </CCard>

      <!-- Info Alert -->
      <CAlert color="info" class="mt-3 text-center">
        You must create a restaurant before accessing the dashboard.
      </CAlert>
    </CCol>
  </CRow>
</template>