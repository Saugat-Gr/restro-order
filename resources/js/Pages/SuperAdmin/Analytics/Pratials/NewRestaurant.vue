<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";

import {
  CContainer,
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
  CWidgetStatsF,
} from "@coreui/vue";

import { CChart } from "@coreui/vue-chartjs";
import CIcon from "@coreui/icons-vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  new_restaurant_data: Object,
});

const data = computed(() => props.new_restaurant_data.new_restaurants);

const weekly = computed(() => data.value.weekly);
const monthly = computed(() => data.value.monthly);
const total = computed(() => data.value.total);
</script>

<template>
  <CContainer class="py-4">

    <!-- Header Widget -->
    <CRow class="mb-4">
      <CCol lg="4" md="6">
        <CWidgetStatsF
          class="shadow-lg"
          color="warning"
          title="Total Restaurants"
          :value="total"
        >
          <template #icon>
            <CIcon icon="cil-restaurant" size="xl" />
          </template>
        </CWidgetStatsF>
      </CCol>
    </CRow>

    <!-- Weekly Chart -->
    <CCard class="mb-4 shadow-lg border-0">
      <CCardHeader>
        <strong>Weekly New Restaurants</strong>
      </CCardHeader>

      <CCardBody>
        <CChart
          type="line"
          :data="{
            labels: weekly.labels,
            datasets: weekly.datasets
          }"
        />
      </CCardBody>
    </CCard>

    <!-- Monthly Chart -->
    <CCard class="shadow-lg border-0">
      <CCardHeader>
        <strong>Monthly New Restaurants</strong>
      </CCardHeader>

      <CCardBody>
        <CChart
          type="line"
          :data="{
            labels: monthly.labels,
            datasets: monthly.datasets
          }"
        />
      </CCardBody>
    </CCard>

  </CContainer>
</template>