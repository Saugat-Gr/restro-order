<script setup>
import { CRow, CCol, CCard, CCardBody } from "@coreui/vue";
import { CChartLine } from "@coreui/vue-chartjs";

const props = defineProps({
  data: Object,
});
</script>

<template>
  <CRow class="mb-3">
    <CCol>
      <h5 class="mb-0">Revenue Analytics</h5>
    </CCol>
  </CRow>

  <!-- KPI -->
  <CRow class="mb-4">
    <CCol md="4">
      <CCard class="shadow-sm text-center">
        <CCardBody>
          <h6 class="text-medium-emphasis">Total Revenue</h6>
          <h3 class="fw-bold text-success">
            {{ new Intl.NumberFormat().format(props.data.revenue.total) }}
          </h3>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>

  <!-- Charts -->
  <CRow>
    <CCol md="6">
      <CCard class="shadow-sm mb-4">
        <CCardBody>
          <h6 class="mb-3">Weekly Revenue</h6>
          <CChartLine :data="props.data.revenue.weekly" />
        </CCardBody>
      </CCard>
    </CCol>

    <CCol md="6">
      <CCard class="shadow-sm mb-4">
        <CCardBody>
          <h6 class="mb-3">Monthly Revenue (Last {{ props.data.revenue.monthly.labels.length }} months)</h6>
          <CChartLine :data="props.data.revenue.monthly" />
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>