<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  CButton,
  CContainer,
  CForm,
  CFormInput,
  CFormLabel,
  CFormFeedback,
  CHeader,
} from "@coreui/vue";
import { Inertia } from "@inertiajs/inertia";

defineOptions({
  layout: AuthenticatedLayout,
});

const page = usePage();
const restaurant = page.props.restaurant;

// ✅ Form (logo included directly — best practice)
const form = useForm({
  _method: "patch",
  name: restaurant.name || "",
  address: restaurant.address || "",
  phone: restaurant.phone || "",
  email: restaurant.email || "",
  logo: null,
});

// ✅ Submit
const submit = () => {
  form
    .transform((data) => {
      if (!data.logo) {
        delete data.logo;
      }
      return data;
    })
    .post(`/restaurant/${restaurant.id}`, {
      forceFormData: true,
      preserveScroll: true,
      onSuccess: () => {
        Inertia.reload({ only: ["restaurant"] });
        form.logo = null;
      },
      onError: (errors) => {
        console.error("Submission errors:", errors);
      },
    });
};
</script>
<template>
  <Head>
    <title>Edit Restaurant - {{ page.props.app?.title || "Restaurant" }}</title>
  </Head>

  <CContainer class="py-4">

    <!-- HEADER -->
    <div class="mb-4">
      <h3 class="fw-bold mb-1">Restaurant Settings</h3>
      <div class="text-muted">
        Manage your restaurant profile and branding in one place
      </div>
    </div>

    <!-- SINGLE FORM SURFACE -->
    <CForm @submit.prevent="submit">

      <CCard class="border-0 shadow-lg rounded-3 overflow-hidden">

        <!-- SECTION: GENERAL INFO -->
        <div class="p-4 border-bottom">
          <h6 class="fw-semibold mb-3">General Information</h6>

          <CRow class="g-3">

            <CCol md="12">
              <CFormLabel>Restaurant Name</CFormLabel>
              <CFormInput
                v-model="form.name"
                :invalid="!!form.errors.name"
              />
              <CFormFeedback invalid>
                {{ form.errors.name }}
              </CFormFeedback>
            </CCol>

            <CCol md="6">
              <CFormLabel>Phone</CFormLabel>
              <CFormInput
                v-model="form.phone"
                :invalid="!!form.errors.phone"
              />
              <CFormFeedback invalid>
                {{ form.errors.phone }}
              </CFormFeedback>
            </CCol>

            <CCol md="6">
              <CFormLabel>Email</CFormLabel>
              <CFormInput
                type="email"
                v-model="form.email"
                :invalid="!!form.errors.email"
              />
              <CFormFeedback invalid>
                {{ form.errors.email }}
              </CFormFeedback>
            </CCol>

            <CCol md="12">
              <CFormLabel>Address</CFormLabel>
              <CFormInput
                v-model="form.address"
                :invalid="!!form.errors.address"
              />
              <CFormFeedback invalid>
                {{ form.errors.address }}
              </CFormFeedback>
            </CCol>

          </CRow>
        </div>

        <!-- SECTION: BRANDING -->
        <div class="p-4 border-bottom text-center">
          <h6 class="fw-semibold mb-3 text-start">Branding</h6>

          <div v-if="restaurant.logo" class="mb-3">
            <div class="text-muted small mb-2">Current Logo</div>

            <div class="p-3 bg-light border rounded-3 shadow-sm d-inline-block">
              <img
                :src="`/storage/${restaurant.logo}`"
                style="max-height: 110px; object-fit: contain"
              />
            </div>
          </div>

          <CFormLabel class="d-block mb-2 fw-semibold text-start">
            Upload New Logo
          </CFormLabel>

          <CFormInput
            type="file"
            accept="image/*"
            @change="(e) => (form.logo = e.target.files?.[0] || null)"
            :invalid="!!form.errors.logo"
          />

          <CFormFeedback invalid>
            {{ form.errors.logo }}
          </CFormFeedback>

          <div class="text-muted small mt-2 text-start">
            Recommended: square PNG logo for best quality
          </div>
        </div>

        <!-- SECTION: ACTION BAR (INSIDE SAME FORM) -->
        <div class="p-4 d-flex justify-content-end align-items-center gap-3 bg-light">

          <div class="text-muted small">
            Make sure all details are correct before saving
          </div>

          <CButton
            type="submit"
            color="primary"
            size="lg"
            class="px-4 fw-semibold"
            :disabled="form.processing"
          >
            {{ form.processing ? "Saving..." : "Save Changes" }}
          </CButton>

        </div>

      </CCard>

    </CForm>
  </CContainer>
</template>