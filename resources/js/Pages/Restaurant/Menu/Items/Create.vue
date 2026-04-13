<script setup>
import { Head, useForm, usePage, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import {
  CButton,
  CContainer,
  CForm,
  CFormFeedback,
  CFormInput,
  CFormLabel,
  CFormSelect,
  CHeader,
} from "@coreui/vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const page = usePage();

const categories = page.props.categories;
const statuses = page.props.statuses;

const form = useForm({
  item_name: "",
  description: "",
  image: null,
  price: "",
  status: "",
  menu_item_category_id: "",
});

const createItem = () => {
  console.log(form);
  form.post(route("menu.menu-items.store"));
};
</script>

<template>
  <Head>
    <title>{{ page.props.app.title }}</title>
  </Head>

  <CContainer class="border rounded-3 shadow-lg p-5">
    
    <Link href="/menu/items">
      <CButton class="float-end" color="primary">Back</CButton>
    </Link>

    <CHeader class="border-none justify-center fw-bold">
      Create an Item
    </CHeader>

    <!-- ✅ submit handled here -->
    <CForm @submit.prevent="createItem">
      <div class="mb-3">
        <CFormLabel>Item Name</CFormLabel>
        <CFormInput
          type="text"
          v-model="form.item_name"
          :invalid="!!form.errors.item_name"
        />
        <CFormFeedback invalid>
          {{ form.errors.item_name }}
        </CFormFeedback>
      </div>

      <div class="mb-3">
        <CFormLabel>Price</CFormLabel>
        <CFormInput
          type="number"
          v-model="form.price"
          :invalid="!!form.errors.price"
        />
        <CFormFeedback invalid>
          {{ form.errors.price }}
        </CFormFeedback>
      </div>

      <div class="mb-3">
        <CFormLabel>Image</CFormLabel>
        <CFormInput
          type="file"
          @change="(e) => (form.image = e.target.files[0])"
          :invalid="!!form.errors.image"
        />
        <CFormFeedback invalid>
          {{ form.errors.image }}
        </CFormFeedback>
      </div>

      <div class="mb-3">
        <CFormLabel>Category</CFormLabel>
        <CFormSelect
          v-model="form.menu_item_category_id"
          :invalid="!!form.errors.menu_item_category_id"
        >
          <option disabled value="" selected>Select Category</option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </CFormSelect>
        <CFormFeedback invalid>
          {{ form.errors.menu_item_category_id }}
        </CFormFeedback>
      </div>

      <div class="mb-3">
        <CFormLabel>Status</CFormLabel>
        <CFormSelect v-model="form.status" :invalid="!!form.errors.status">
          <option disabled value="" selected>Select Status</option>
          <option v-for="status in statuses" :key="status" :value="status">
            {{ status.toUpperCase() }}
          </option>
        </CFormSelect>
        <CFormFeedback invalid>
          {{ form.errors.status }}
        </CFormFeedback>
      </div>

      <div class="mb-3">
        <CFormLabel>Description</CFormLabel>
        <vue-easymde
          v-model="form.description"
          :invalid="!!form.errors.description"
        />
        <CFormFeedback invalid>
          {{ form.errors.description }}
        </CFormFeedback>
      </div>

      <div class="mb-3">
        <CButton
          type="submit"
          color="success"
          class="text-white float-end form-control"
        >
          Create
        </CButton>
      </div>
    </CForm>
  </CContainer>
</template>