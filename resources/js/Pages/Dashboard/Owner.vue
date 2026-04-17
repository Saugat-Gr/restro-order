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
    <!-- HEADER -->
    <div class="mb-4">
      <h3 class="fw-semibold mb-1">Restaurant Dashboard</h3>
      <div class="text-muted small">
        Real-time overview of revenue, orders, and operations
      </div>
    </div>

    <!-- ================= KPI ================= -->
    <CRow class="g-4 mb-3">
      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="primary"
          :value="formatCurrency(kpis.revenue_today)"
          class="shadow-lg"
          title="Today Revenue"
        >
          <template #icon>
            <CIcon name="cil-cash" />
          </template>
        </CWidgetStatsF>
      </CCol>

      <CCol sm="6" lg="3">
        <CWidgetStatsF
          class="shadow-lg"
          color="success"
          :value="formatCurrency(kpis.revenue_month)"
          title="Monthly Revenue"
        >
          <template #icon>
            <CIcon name="cil-graph" />
          </template>
        </CWidgetStatsF>
      </CCol>

      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="info"
          class="shadow-lg"
          :value="kpis.orders_today.toString()"
          title="Orders Today"
        >
          <template #icon>
            <CIcon name="cil-cart" />
          </template>
        </CWidgetStatsF>
      </CCol>

      <CCol sm="6" lg="3">
        <CWidgetStatsF
          class="shadow-lg"
          color="warning"
          :value="formatCurrency(kpis.avg_order_value)"
          title="Avg Order Value"
        >
          <template #icon>
            <CIcon name="cil-speedometer" />
          </template>
        </CWidgetStatsF>
      </CCol>
    </CRow>

    <!-- ================= MAIN INSIGHT ROW ================= -->
    <CRow class="g-4">
      <!-- SALES TREND -->
      <CCol lg="8">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">Sales Performance</div>
            <div class="text-muted small">Last 30 days revenue trend</div>
          </CCardHeader>

          <CCardBody>
            <CChartLine :data="sales_trend" />
          </CCardBody>
        </CCard>
      </CCol>

      <!-- TABLE STATUS -->
      <CCol lg="4">
        <CCard class="border-0 shadow-lg h-100 text-center">
          <CCardHeader class="border-bottom text-start">
            <div class="fw-semibold">Table Utilization</div>
            <div class="text-muted small">Current occupancy status</div>
          </CCardHeader>

          <CCardBody class="d-flex flex-column justify-content-center">
            <div class="display-5 fw-bold text-primary mb-2">
              {{ table_stats.occupancy_rate }}%
            </div>

            <div class="text-muted">
              {{ table_stats.occupied }} / {{ table_stats.total }} tables
              occupied
            </div>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>

    <!-- ================= BOTTOM INSIGHTS ================= -->
    <CRow class="g-4 mt-1">
      <!-- TOP ITEMS -->
      <CCol md="6">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">Top Selling Items</div>
            <div class="text-muted small">Revenue contribution breakdown</div>
          </CCardHeader>

          <CCardBody>
            <CChartDoughnut :data="topItemsChart" />
          </CCardBody>
        </CCard>
      </CCol>

      <!-- RECENT ORDERS -->
      <CCol md="6">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">Recent Orders</div>
            <div class="text-muted small">Latest activity in the system</div>
          </CCardHeader>

          <CCardBody class="p-0">
            <table class="table table-hover table-sm mb-0">
              <thead class="table-light">
                <tr>
                  <th>Order</th>
                  <th>Table</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="order in recent_orders" :key="order.id">
                  <td class="fw-medium">
                    {{ order.order_number }}
                  </td>

                  <td>
                    {{ order.table?.table_number ?? "Takeaway" }}
                  </td>

                  <td class="text-success fw-semibold">
                    {{ formatCurrency(order.total_amount) }}
                  </td>

                  <td>
                    <span
                      class="badge"
                      :class="
                        order.status === 'completed'
                          ? 'bg-success'
                          : order.status === 'pending'
                          ? 'bg-warning text-dark'
                          : 'bg-secondary'
                      "
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