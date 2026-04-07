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

defineOptions({ layout: AuthenticatedLayout });

const page = usePage();
const item = page.props.item;
const categories = page.props.categories;
const statuses = page.props.statuses;

const form = useForm({
  _method: "patch",
  item_name: item.item_name ?? "",
  description: item.description ?? "",
  price: item.price ?? 0,
  status: item.status ?? "",
  menu_item_category_id: item.menu_item_category_id ?? "",
});

const updateItem = () => {
  const data = { ...form.data() }; 

  if (form.image) {
    data.image = form.image;
  }

  form.post(route("menu.menu-items.update", item.id), {
    data,
    forceFormData: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Edit Item" />

  <CContainer class="border rounded-3 shadow-lg p-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <CHeader class="h3 fw-bold m-0">Edit Item</CHeader>
      <Link href="/menu/items">
        <CButton color="primary">Back</CButton>
      </Link>
    </div>

    <CForm @submit.prevent="updateItem">
      <div class="row">
        <div class="col-md-6 mb-3">
          <CFormLabel>Item Name *</CFormLabel>
          <CFormInput
            v-model="form.item_name"
            :invalid="form.errors.item_name"
            placeholder="Enter item name"
          />
          <CFormFeedback invalid>{{ form.errors.item_name }}</CFormFeedback>
        </div>

        <div class="col-md-6 mb-3">
          <CFormLabel>Price *</CFormLabel>
          <CFormInput
            type="number"
            step="0.01"
            v-model="form.price"
            :invalid="form.errors.price"
            placeholder="0.00"
          />
          <CFormFeedback invalid>{{ form.errors.price }}</CFormFeedback>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <CFormLabel>Category *</CFormLabel>
          <CFormSelect
            v-model="form.menu_item_category_id"
            :invalid="form.errors.menu_item_category_id"
          >
            <option value="">Select Category</option>
            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </CFormSelect>
          <CFormFeedback invalid>{{
            form.errors.menu_item_category_id
          }}</CFormFeedback>
        </div>

        <div class="col-md-6 mb-3">
          <CFormLabel>Status *</CFormLabel>
          <CFormSelect v-model="form.status" :invalid="form.errors.status">
            <option value="">Select Status</option>
            <option v-for="status in statuses" :key="status" :value="status">
              {{ status }}
            </option>
          </CFormSelect>
          <CFormFeedback invalid>{{ form.errors.status }}</CFormFeedback>
        </div>
      </div>

      <div class="mb-3">
        <CFormLabel>Description</CFormLabel>
        <vue-easymde v-model="form.description" />
        <CFormFeedback invalid>{{ form.errors.description }}</CFormFeedback>
      </div>

      <div class="mb-3">
        <CFormLabel>Image (Optional)</CFormLabel>
        <div v-if="item.image" class="mt-2">
          Current Image: <br />
          <img
            :src="`/storage/${item.image}`"
            alt="Item Image"
            style="max-height: 120px"
          />
        </div>
        <CFormInput
          type="file"
          accept="image/*"
          @change="
            (e) => {
              form.image = e.target.files[0] || null;
              console.log('File selected:', form.image);
            }
          "
          :invalid="form.errors.image"
        />
        <CFormFeedback invalid>{{ form.errors.image }}</CFormFeedback>
      </div>

      <div class="d-flex gap-2">
        <CButton
          type="submit"
          color="success"
          :disabled="form.processing"
          class="text-white"
        >
          {{ form.processing ? "Updating..." : "Update Item" }}
        </CButton>
      </div>
    </CForm>
  </CContainer>
</template>