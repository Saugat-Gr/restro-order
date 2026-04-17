<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CIcon from "@coreui/icons-vue";
import {
  CContainer,
  CWidgetStatsF,
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
  CButton,
  CButtonGroup,
  CSpinner,
} from "@coreui/vue";
import { CChart } from "@coreui/vue-chartjs";
import { router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  revenue_data: Object,
  selected_months: Number,
});

const loading = ref(false);


const revenues = computed(() => props.revenue_data.revenues);
const selectedMonths = computed(() => props.selected_months);
console.log(selectedMonths);


const weekly = computed(() => revenues.value.weekly);
const monthly = computed(() => revenues.value.monthly);


function changeRange(months) {
  if (months === selectedMonths.value) return; 

  loading.value = true;

  router.get(
    route("superadmin.analytics"),
    { months },
    {
      preserveState: true,
      preserveScroll: true,
      only: ["revenue_data", "selected_months"],
      onFinish: () => {
        loading.value = false;
      },
      onError: () => {
        loading.value = false;
      },
    }
  );
}
</script>

<template>
  <CContainer class="py-4">

    <CRow class="mb-4">
      <CCol lg="4" md="6">
        <CWidgetStatsF
          class="shadow-lg"
          color="success"
          title="Total Revenue"
          :value="revenues.revenue_value"
        >
          <template #icon>
            <CIcon icon="cib-cashapp" size="xl" />
          </template>
        </CWidgetStatsF>
      </CCol>
    </CRow>

    <CCard class="mb-4 shadow-lg border-0">
      <CCardHeader>
        <strong>Weekly Revenue</strong>
      </CCardHeader>

      <CCardBody>
        <CChart
          type="line"
          :data="{
            labels: weekly.labels,
            datasets: weekly.datasets,
          }"
        />
      </CCardBody>
    </CCard>

    <CCard class="shadow-lg border-0">
      <CCardHeader class="d-flex justify-content-between align-items-center">
        <strong>Monthly Revenue</strong>

        <div class="d-flex align-items-center gap-2">
          <CSpinner v-if="loading" size="sm" color="primary" />
          <CButtonGroup role="group" size="sm">
            <CButton
              :color="Number(selectedMonths) === 3 ? 'primary' : 'secondary'"
              @click="changeRange(3)"
            >
              3M
            </CButton>

            <CButton
              :color="Number(selectedMonths) === 6 ? 'primary' : 'secondary'"
              @click="changeRange(6)"
            >
              6M
            </CButton>

            <CButton
              :color="Number(selectedMonths) === 12 ? 'primary' : 'secondary'"
              @click="changeRange(12)"
            >
              12M
            </CButton>
          </CButtonGroup>
        </div>
      </CCardHeader>

      <CCardBody>
        <CChart
          type="line"
          :key="`monthly-chart-${selectedMonths}`"
          :data="{
            labels: monthly.labels,
            datasets: monthly.datasets,
          }"
        />
      </CCardBody>
    </CCard>
  </CContainer>
</template>