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
import Revenue from "./Pratials/Revenue.vue";
import RestroPerformance from "./Pratials/RestroPerformance.vue";
import NewRestaurant from "./Pratials/NewRestaurant.vue";
import { ref } from "vue";

const props = defineProps({
  revenue_data: Object,
  performance_data: Object,
  new_restaurant_data: Object,
});

defineOptions({ layout: AuthenticatedLayout });

// Active tab state
const activeTab = ref("revenue");
</script>

<template>
  <CContainer class="py-4">
    <!-- Header -->
    <CRow class="mb-4">
      <CCol>
        <h4 class="fw-bold">Dashboard Overview</h4>
        <p class="text-medium-emphasis mb-0">
          Switch between revenue and restaurant performance insights
        </p>
      </CCol>
    </CRow>

    <!-- Toggle Buttons -->
    <CRow class="mb-4">
      <CCol class="d-flex justify-content-center">
        <CButtonGroup>
          <CButton
            color="success"
            variant="outline"
            :active="activeTab === 'revenue'"
            @click="activeTab = 'revenue'"
          >
            <CIcon icon="cil-dollar" class="me-2" />
            Revenue
          </CButton>

          <CButton
            color="success"
            variant="outline"
            :active="activeTab === 'performance'"
            @click="activeTab = 'performance'"
          >
            <CIcon icon="cil-chart-line" class="me-2" />
            Performance
          </CButton>

             <CButton
            color="success"
            variant="outline"
            :active="activeTab === 'new_restaurants'"
            @click="activeTab = 'new_restaurants'"
          >
            <CIcon icon="cil-building" class="me-2" />
            New Restaurants
          </CButton>
        </CButtonGroup>
      </CCol>
    </CRow>

    <!-- Content Card -->
    <CRow>
      <CCol>
        <CCard class="shadow-sm">
          <CCardBody>
            <div v-if="activeTab === 'new_restaurants'">
              <NewRestaurant :new_restaurant_data="new_restaurant_data" />
            </div>

            <!-- Revenue View -->
            <div v-if="activeTab === 'revenue'">
              <Revenue :revenue_data="revenue_data" />
            </div>

            <!-- Performance View -->
            <div v-if="activeTab === 'performance'">
              <RestroPerformance :performance_data="performance_data" />
            </div>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>