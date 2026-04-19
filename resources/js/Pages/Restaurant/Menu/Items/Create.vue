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

  <CContainer class="py-4">

    <!-- Card Form -->
    <div class="bg-white border rounded-4 shadow-lg p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fw-bold mb-0">Create Item</h4>
          <small class="text-medium-emphasis">Add a new menu item</small>
        </div>

        <Link href="/menu/menu-items">
          <CButton color="primary">Back</CButton>
        </Link>
      </div>

      <CForm @submit.prevent="createItem">
        <!-- Basic Info -->
        <div class="mb-4">
          <h6 class="fw-semibold mb-3 text-medium-emphasis">Basic Info</h6>

          <div class="mb-3">
            <CFormLabel>Item Name</CFormLabel>
            <CFormInput
              v-model="form.item_name"
              :invalid="!!form.errors.item_name"
              placeholder="e.g. Chicken Burger"
            />
            <CFormFeedback invalid>
              {{ form.errors.item_name }}
            </CFormFeedback>
          </div>
        </div>

        <!-- Pricing & Category -->
        <div class="mb-4">
          <h6 class="fw-semibold mb-3 text-medium-emphasis">
            Pricing & Classification
          </h6>

          <div class="row g-3">
            <div class="col-md-4">
              <CFormLabel>Price</CFormLabel>
              <CFormInput
                type="number"
                v-model="form.price"
                :invalid="!!form.errors.price"
                placeholder="0.00"
              />
              <CFormFeedback invalid>
                {{ form.errors.price }}
              </CFormFeedback>
            </div>

            <div class="col-md-4">
              <CFormLabel>Category</CFormLabel>
              <CFormSelect
                v-model="form.menu_item_category_id"
                :invalid="!!form.errors.menu_item_category_id"
              >
                <option selected value="">Select</option>
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

            <div class="col-md-4">
              <CFormLabel>Status</CFormLabel>
              <CFormSelect
                v-model="form.status"
                :invalid="!!form.errors.status"
              >
                <option selected value="">Select</option>
                <option
                  v-for="status in statuses"
                  :key="status"
                  :value="status"
                >
                  {{ status.toUpperCase() }}
                </option>
              </CFormSelect>
              <CFormFeedback invalid>
                {{ form.errors.status }}
              </CFormFeedback>
            </div>
          </div>
        </div>

        <!-- Media -->
        <div class="mb-4">
          <h6 class="fw-semibold mb-3 text-medium-emphasis">Media</h6>

          <CFormLabel>Upload Image</CFormLabel>
          <CFormInput
            type="file"
            @change="(e) => (form.image = e.target.files[0])"
            :invalid="!!form.errors.image"
          />
          <CFormFeedback invalid>
            {{ form.errors.image }}
          </CFormFeedback>
        </div>

        <!-- Description -->
        <div class="mb-4">
          <h6 class="fw-semibold mb-3 text-medium-emphasis">Description</h6>

          <vue-easymde
            v-model="form.description"
            :invalid="!!form.errors.description"
          />
          <CFormFeedback invalid>
            {{ form.errors.description }}
          </CFormFeedback>
        </div>

        <!-- Actions -->
        <div
          class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top"
        >
          <small class="text-medium-emphasis">
            Make sure all fields are filled correctly
          </small>

          <CButton
            type="submit"
            color="primary"
            class="px-4"
            :disabled="form.processing"
          >
            {{ form.processing ? "Creating..." : "Create Item" }}
          </CButton>
        </div>
      </CForm>
    </div>
  </CContainer>
</template>