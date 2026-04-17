<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  CCol,
  CContainer,
  CFormInput,
  CFormSelect,
  CInputGroup,
  CRow,
  CCard,
  CCardBody,
  CBadge,
  CButton,
} from "@coreui/vue";

import CIcon from "@coreui/icons-vue";
import { cilSearch } from "@coreui/icons";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import { router, useForm, Link } from "@inertiajs/vue3";
import { formatCurrency, removeUnderScore } from "@/utils/format";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  tables: Array,
  statuses: Array,
  orders: Array,
  filters: Object,
});

const filterData = useForm({
  table: props.filters?.table || "",
  searchTerm: props.filters?.searchTerm || "",
  status: props.filters?.status || "",
});

const search = debounce(() => {
  filterData.get(route("orders.search"), {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 400);

watch(
  () => [filterData.table, filterData.searchTerm, filterData.status],
  () => search()
);

const edit = (order) => {
    router.get(route('orders.edit', order.id));
  
};

</script>
<template>
  <CContainer fluid class="py-4">

    <!-- HEADER -->
    <div class="mb-4">
      <h4 class="fw-semibold mb-1">Order Search</h4>
      <div class="text-muted small">
        Filter and track restaurant orders in real time
      </div>
    </div>

    <!-- FILTER CARD -->
    <CCard class="mb-4 border-0 shadow-sm">
      <CCardBody>
        <CRow class="g-3">

          <CCol md="3">
            <CFormSelect v-model="filterData.table">
              <option value="">All Tables</option>
              <option
                v-for="table in props.tables"
                :key="table.id"
                :value="table.table_number"
              >
                Table {{ table.table_number }}
              </option>
            </CFormSelect>
          </CCol>

          <CCol md="3">
            <CFormSelect v-model="filterData.status">
              <option value="">All Status</option>
              <option
                v-for="status in props.statuses"
                :key="status"
                :value="status"
              >
                {{ $capitalize(removeUnderScore(status)) }}
              </option>
            </CFormSelect>
          </CCol>

          <CCol md="6">
            <CInputGroup>
              <CFormInput
                v-model="filterData.searchTerm"
                placeholder="Search order number..."
              />
              <span class="input-group-text">
                <CIcon :icon="cilSearch" />
              </span>
            </CInputGroup>
          </CCol>

        </CRow>
      </CCardBody>
    </CCard>

    <!-- RESULTS -->
    <CRow class="g-4">

      <CCol v-for="order in props.orders" :key="order.id" lg="4">

        <CCard class="border-0 shadow-md order-card h-100">

          <!-- HEADER -->
          <CCardBody class="pb-2">

            <div class="d-flex justify-content-between align-items-start">

              <div>
                <div class="text-muted small">Order</div>
                <div class="fw-bold text-primary">
                  {{ order.order_number }}
                </div>
              </div>

              <CBadge
                class="px-2 py-1"
                :color="
                  order.status === 'pending'
                    ? 'warning'
                    : order.status === 'completed'
                    ? 'success'
                    : 'secondary'
                "
              >
                {{ order.status }}
              </CBadge>

            </div>

          </CCardBody>

          <hr class="my-0">

          <!-- BODY -->
          <CCardBody class="pt-2 pb-2 small text-muted">

            <div class="d-flex justify-content-between mb-1">
              <span>Table</span>
              <span class="text-dark">
                {{ order.table?.table_number || '-' }}
              </span>
            </div>

            <div class="d-flex justify-content-between mb-1">
              <span>Total</span>
              <span class="text-success fw-semibold">
                {{ formatCurrency(order.total_amount) }}
              </span>
            </div>

            <div class="d-flex justify-content-between">
              <span>Time</span>
              <span>
                {{ new Date(order.created_at).toLocaleString() }}
              </span>
            </div>

          </CCardBody>

          <!-- FOOTER -->
          <CCardBody class="pt-0">

            <div class="d-flex justify-content-end">

              <Link :href="route('orders.edit', order)">
                <CButton color="dark" variant="outline" size="sm">
                  <CIcon name="cil-pencil" />
                </CButton>
              </Link>

            </div>

          </CCardBody>

        </CCard>

      </CCol>

      <!-- EMPTY -->
      <CCol v-if="props.orders.length === 0" class="text-center py-5">
        <div class="text-muted">No orders found</div>
      </CCol>

    </CRow>

  </CContainer>
</template>

<style scoped>
.order-card {
  transition: all 0.2s ease;
}

.order-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}
</style>