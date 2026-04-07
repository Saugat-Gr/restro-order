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

  <CContainer fluid class="bg-grey rounded-3 shadow-2xl p-4 mt-4">
    <div class="border rounded-3 p-4">
      <CForm @submit.prevent="submit">
        <CHeader class="mb-5 text-2xl text-center fw-bold border-none">
          Edit the general information about your restaurant
        </CHeader>

        <!-- Name -->
        <div class="mb-3">
          <CFormLabel for="name">Restaurant Name</CFormLabel>
          <CFormInput
            id="name"
            v-model="form.name"
            :invalid="!!form.errors.name"
          />
          <CFormFeedback invalid>{{ form.errors.name }}</CFormFeedback>
        </div>

        <!-- Phone -->
        <div class="mb-3">
          <CFormLabel for="phone">Phone Number</CFormLabel>
          <CFormInput
            id="phone"
            type="tel"
            v-model="form.phone"
            :invalid="!!form.errors.phone"
          />
          <CFormFeedback invalid>{{ form.errors.phone }}</CFormFeedback>
        </div>

        <!-- Address -->
        <div class="mb-3">
          <CFormLabel for="address">Address</CFormLabel>
          <CFormInput
            id="address"
            v-model="form.address"
            :invalid="!!form.errors.address"
          />
          <CFormFeedback invalid>{{ form.errors.address }}</CFormFeedback>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <CFormLabel for="email">Email</CFormLabel>
          <CFormInput
            id="email"
            type="email"
            v-model="form.email"
            :invalid="!!form.errors.email"
          />
          <CFormFeedback invalid>{{ form.errors.email }}</CFormFeedback>
        </div>

        <!-- Logo -->
        <div class="mb-4">
          <CFormLabel for="logo">Company Logo (Optional)</CFormLabel>

          <!-- Current logo -->
          <div v-if="restaurant.logo" class="mb-3">
            <small class="text-muted d-block mb-1">Current Logo:</small>
            <img
              :src="`/storage/${restaurant.logo}`"
              alt="Current Logo"
              style="max-height: 100px; border-radius: 8px; object-fit: contain"
            />
          </div>

          <!-- File input -->
          <CFormInput
            id="logo"
            type="file"
            accept="image/*"
            @change="(e) => (form.logo = e.target.files?.[0] || null)"
            :invalid="!!form.errors.logo"
          />
          <CFormFeedback invalid>{{ form.errors.logo }}</CFormFeedback>
        </div>

        <!-- Submit -->
        <div class="mt-4">
          <CButton type="submit" color="primary" :disabled="form.processing">
            {{ form.processing ? "Saving..." : "Save Changes" }}
          </CButton>
        </div>
      </CForm>
    </div>
  </CContainer>
</template>