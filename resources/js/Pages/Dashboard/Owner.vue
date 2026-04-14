<script setup>
import {
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
  CWidgetStatsF,
} from "@coreui/vue";

import { CChartLine, CChartDoughnut } from "@coreui/vue-chartjs";

import { formatCurrency } from "@/utils/format"; // create this helper if needed
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CIcon from "@coreui/icons-vue";
import { cibOcaml } from "@coreui/icons";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  kpis: Object,
  sales_trend: Object,
  top_items: Array,
  recent_orders: Array,
  table_stats: Object,
});

// Prepare Doughnut data for Top Items
const topItemsChart = {
  labels: props.top_items.map((item) => item.item_name),
  datasets: [
    {
      data: props.top_items.map((item) => item.revenue),
      backgroundColor: ["#4f46e5", "#22c55e", "#eab308", "#ef4444", "#8b5cf6"],
    },
  ],
};
</script>

<template>
  <div>
    <h1 class="mb-4">Restaurant Dashboard</h1>

    <!-- KPI Cards -->
    <CRow class="mb-4">
      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="primary"
          :value="formatCurrency(kpis.revenue_today)"
          title="Revenue Today"
        >
          <template #icon>
             <CIcon name="cil-cash" ></CIcon>
          </template>
        </CWidgetStatsF>
      </CCol>
      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="success"
          :value="formatCurrency(kpis.revenue_month)"
          title="This Month"
        >
         <template #icon>
          <CIcon name="cil-cash"></CIcon></template>
        </CWidgetStatsF>
      </CCol>
      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="info"
          :value="kpis.orders_today.toString()"
          title="Orders Today"
        >
         <template #icon>
           <CIcon name="cil-cart"></CIcon>
         </template>
        </CWidgetStatsF>
      </CCol>
      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="warning"
          :value="formatCurrency(kpis.avg_order_value)"
          title="Avg Order Value"
        >
          <template #icon>
              <CIcon name="cil-cart"></CIcon>
          </template>
        </CWidgetStatsF>
      </CCol>
    </CRow>

    <CRow>
      <!-- Sales Trend -->
      <CCol lg="8">
        <CCard>
          <CCardHeader>
            <strong>Daily Sales Trend (Last 30 Days)</strong>
          </CCardHeader>
          <CCardBody>
            <CChartLine :data="sales_trend" />
          </CCardBody>
        </CCard>
      </CCol>

      <!-- Table Utilization -->
      <CCol lg="4">
        <CCard>
          <CCardHeader>
            <strong>Table Utilization</strong>
          </CCardHeader>
          <CCardBody class="text-center">
            <h1 class="display-4">{{ table_stats.occupancy_rate }}%</h1>
            <p class="text-muted">
              {{ table_stats.occupied }} / {{ table_stats.total }} tables
              booked
            </p>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>

    <CRow class="mt-4">
      <!-- Top Menu Items -->
      <CCol md="6">
        <CCard>
          <CCardHeader><strong>Top 5 Selling Items</strong></CCardHeader>
          <CCardBody>
            <CChartDoughnut :data="topItemsChart" />
          </CCardBody>
        </CCard>
      </CCol>

      <!-- Recent Orders -->
      <CCol md="6">
        <CCard>
          <CCardHeader><strong>Recent Orders</strong></CCardHeader>
          <CCardBody>
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Table</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in recent_orders" :key="order.id">
                  <td>{{ order.order_number }}</td>
                  <td>{{ order.table?.table_number ?? "Takeaway" }}</td>
                  <td>{{ formatCurrency(order.total_amount) }}</td>
                  <td>
                    <span
                      :class="`badge bg-${
                        order.status === 'completed' ? 'success' : 'warning'
                      }`"
                    >
                      {{ order.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
</template>