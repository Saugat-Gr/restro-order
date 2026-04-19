<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  CContainer,
  CCard,
  CCardBody,
  CRow,
  CCol,
  CButtonGroup,
  CButton,
} from "@coreui/vue";

import Revenue from "./Partials/Revenue.vue";
import Orders from "./Partials/Orders.vue";

import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  revenue_data: Object,
  orders_data: Object,
  months: Number,
});

// Tabs
const activeTab = ref("revenue");

// Shared months filter
const selectedMonths = ref(props.months || 6);

const changeMonths = (value) => {
  selectedMonths.value = value;

  router.get(
    route("analytics"),
    { month: value },
    {
      preserveState: true,
      replace: true,
    }
  );
};
</script>

<template>
  <CContainer class="py-4 border rounded-2 shadow-lg p-4">
    <!-- Header -->
    <CRow class="mb-4 align-items-center">
      <CCol>
        <h4 class="fw-bold">Analytics Dashboard</h4>
        <p class="text-medium-emphasis mb-0">
          Track your revenue and order performance
        </p>
      </CCol>

      <!-- Month Filter -->
      <CCol class="text-end">
        <CButtonGroup>
          <CButton
            v-for="m in [3, 6, 12]"
            :key="m"
            color="success"
            variant="outline"
            :active="selectedMonths === m"
            @click="changeMonths(m)"
          >
            {{ m }}M
          </CButton>
        </CButtonGroup>
      </CCol>
    </CRow>

    <!-- Tabs -->
    <CRow class="mb-4">
      <CCol class="d-flex justify-content-center">
        <CButtonGroup>
          <CButton
            color="primary"
            variant="outline"
            :active="activeTab === 'revenue'"
            @click="activeTab = 'revenue'"
          >
            Revenue
          </CButton>

          <CButton
            color="primary"
            variant="outline"
            :active="activeTab === 'orders'"
            @click="activeTab = 'orders'"
          >
            Orders
          </CButton>
        </CButtonGroup>
      </CCol>
    </CRow>

    <!-- Content -->
    <CRow>
      <CCol>
        <CCard class="shadow-sm">
          <CCardBody>
            <Revenue
              v-if="activeTab === 'revenue'"
              :data="revenue_data"
            />

            <Orders
              v-if="activeTab === 'orders'"
              :data="orders_data"
            />
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>