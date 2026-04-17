<script setup>
import {
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
  CWidgetStatsF,
} from "@coreui/vue";

import { CChartLine, CChartBar, CChartDoughnut } from "@coreui/vue-chartjs";

import { formatCurrency } from "@/utils/format";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CIcon from "@coreui/icons-vue";
import { cilBuilding, cilChart, cilPeople, cilMoney } from "@coreui/icons";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  kpis: Object,
  revenue_trend: Object,
  new_restaurants_trend: Object,
  top_restaurants: Array,
  recent_orders: Array,
});


const topRestaurantsChart = {
  labels: props.top_restaurants.map((r) => r.name),
  datasets: [
    {
      data: props.top_restaurants.map((r) => r.transactions_sum_amount || 0),
      backgroundColor: ["#4f46e5", "#22c55e", "#eab308", "#ef4444", "#8b5cf6", "#ec4899", "#14b8a6", "#f59e0b", "#6366f1", "#a855f7"],
    },
  ],
};
</script>

<template>
  <div>
    <!-- HEADER -->
    <div class="mb-4">
      <h3 class="fw-semibold mb-1">Platform Overview • Super Admin</h3>
      <div class="text-muted small">
        SaaS-wide performance • All restaurants aggregated
      </div>
    </div>


    <CRow class="g-4 mb-4">
      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="primary"
          :value="kpis.total_restaurants.toString()"
          class="shadow-lg"
          title="Total Restaurants"
        >
          <template #icon>
            <CIcon :icon="cilBuilding" />
          </template>
        </CWidgetStatsF>
      </CCol>

      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="success"
          :value="kpis.active_restaurants.toString()"
          class="shadow-lg"
          title="Active Restaurants"
        >
          <template #icon>
            <CIcon :icon="cilPeople" />
          </template>
        </CWidgetStatsF>
      </CCol>

      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="info"
          :value="formatCurrency(kpis.total_revenue)"
          class="shadow-lg"
          title="Total Platform Revenue"
        >
          <template #icon>
            <CIcon :icon="cilMoney" />
          </template>
        </CWidgetStatsF>
      </CCol>

      <CCol sm="6" lg="3">
        <CWidgetStatsF
          color="warning"
          :value="formatCurrency(kpis.revenue_this_month)"
          class="shadow-lg"
          title="Revenue This Month"
        >
          <template #icon>
            <CIcon :icon="cilChart" />
          </template>
        </CWidgetStatsF>
      </CCol>
    </CRow>


    <CRow class="g-4">
      <!-- Revenue Trend -->
      <CCol lg="8">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">Platform Revenue Trend</div>
            <div class="text-muted small">Last 30 days (all restaurants)</div>
          </CCardHeader>
          <CCardBody>
            <CChartLine :data="revenue_trend" />
          </CCardBody>
        </CCard>
      </CCol>

      <!-- New Restaurants -->
      <CCol lg="4">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">New Restaurants Growth</div>
            <div class="text-muted small">Last 6 months</div>
          </CCardHeader>
          <CCardBody>
            <CChartBar :data="new_restaurants_trend" />
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>



    <CRow class="g-4 mt-4">
      <!-- Top Restaurants by Revenue -->
      <CCol md="6">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">Top 10 Restaurants by Revenue</div>
            <div class="text-muted small">All-time completed transactions</div>
          </CCardHeader>
          <CCardBody>
            <CChartDoughnut :data="topRestaurantsChart" />
          </CCardBody>
        </CCard>
      </CCol>

      <!-- Recent Platform Activity -->
      <CCol md="6">
        <CCard class="border-0 shadow-lg h-100">
          <CCardHeader class="border-bottom">
            <div class="fw-semibold">Recent Completed Orders</div>
            <div class="text-muted small">Platform-wide latest activity</div>
          </CCardHeader>

          <CCardBody class="p-0">
            <table class="table table-hover table-sm mb-0">
              <thead class="table-light">
                <tr>
                  <th>Restaurant</th>
                  <th>Order #</th>
                  <th>Table</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in recent_orders" :key="order.id">
                  <td class="fw-medium">{{ order.restaurant?.name }}</td>
                  <td>{{ order.order_number }}</td>
                  <td>{{ order.table?.table_number ?? 'Takeaway' }}</td>
                  <td class="text-success fw-semibold">
                    {{ formatCurrency(order.total_amount) }}
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