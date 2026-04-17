<script setup>
import { computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  CContainer,
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
} from "@coreui/vue";
import { CChart } from "@coreui/vue-chartjs";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  performance_data: Object,
});

const performance = computed(() => props.performance_data.performance);

const labels = computed(() => performance.value.labels);

const completed = computed(() => performance.value.completed);

const cancelled = computed(() => performance.value.cancelled);


</script>

<template>
  <CContainer class="py-4">
    <h4 class="mb-4 mt-5 text-primary">Performance Analytics</h4>

    <CRow class="g-4">
      <!-- Completed Orders -->
      <CCol lg="6">
        <CCard class="shadow-lg border-0 h-100">
          <CCardHeader>
            <strong>Completed Orders by Restaurant</strong>
          </CCardHeader>
          <CCardBody class="chart-container" style="height: 500px"> 
            <CChart
              type="bar"
              :data="{ labels: labels, datasets: completed.datasets }"
             
            />
          </CCardBody>
        </CCard>
      </CCol>

      <CCol lg="6">
        <CCard class="shadow-lg border-0 h-100">
          <CCardHeader>
            <strong>Cancelled Orders by Restaurant</strong>
          </CCardHeader>
          <CCardBody class="chart-container" style="height: 500px">  
            <CChart
              type="bar"
              :data="{ labels: labels, datasets: cancelled.datasets }"
            />
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>