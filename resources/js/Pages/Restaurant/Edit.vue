<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { CFormLabel, CHeader } from "@coreui/vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm, Head, usePage } from "@inertiajs/vue3";

const page = usePage();

defineOptions({
  layout: AuthenticatedLayout,
});

// Inertia form (cleaner)
const form = useForm({
  name: page.props.restaurant.name || "",
  address: page.props.restaurant.address || "",
  phone: page.props.restaurant.phone || "",
  email: page.props.restaurant.email || "",
  logo: null,
});

// Submit handler
const submit = () => {
  console.log("Submitting form with data:", form);
  form.post(`/restaurant/${page.props.restaurant.id}`, {
    forceFormData: true,
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      Inertia.reload({ only: ["restaurant"] });
    },
    onError: (errors) => {
      console.error("Form submission errors:", errors);
    },
  });
};
</script>

<template>
  <Head>
    <title>{{ page.props.app.title }}</title>
  </Head>

  <CContainer fluid class="bg-grey text-dark rounded-3 shadow-2xl p-4 mt-4">
    <h1 class="mb-4">General</h1>
    <p>Enter the general information about your restaurant</p>

    <div class="border rounded-3 p-4">
      <CForm @submit.prevent="submit">
        <div class="mb-3">
          <CFormLabel for="name">Restaurant Name</CFormLabel>
          <CFormInput
            type="text"
            v-model="form.name"
            class="form-control"
            id="name"
          />
        </div>

        <div class="mb-3">
          <CFormLabel for="phone">Phone Number</CFormLabel>
          <CFormInput
            type="text"
            v-model="form.phone"
            class="form-control"
            id="phone"
          />
        </div>

        <div class="mb-3">
          <CFormLabel for="address">Address</CFormLabel>
          <CFormInput
            type="text"
            v-model="form.address"
            class="form-control"
            id="address"
          />
        </div>

        <div class="mb-3">
          <CFormLabel for="email">Email</CFormLabel>
          <CFormInput
            type="email"
            v-model="form.email"
            class="form-control"
            id="email"
          />
        </div>

        <div class="mb-3">
          <CFormLabel for="logo">Company Logo</CFormLabel>
          <CFormInput
            type="file"
            ref="logoInput"
            class="form-control"
            id="logo"
            @change="form.logo = $event.target.files[0]"
          />
          <div v-if="form.errors.logo" class="text-danger mt-1">
            {{ form.errors.logo }}
          </div>
        </div>

        <div>
          <CButton type="submit" color="primary"> Save</CButton>
        </div>
      </CForm>
    </div>
  </CContainer>
</template>