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
import { formatCurrency } from "@/utils/format";

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
      <h3 class="fw-bold mb-1">Order Search</h3>
      <small class="text-muted">Filter and track all restaurant orders</small>
    </div>

    <!-- FILTER BAR -->
    <CCard class="mb-4 shadow-sm border-0">
      <CCardBody>
        <CRow class="g-3">

          <!-- TABLE -->
          <CCol md="3">
            <CFormSelect v-model="filterData.table">
              <option value="">All Tables</option>
              <option
                v-for="table in props.tables"
                :key="table.id"
                :value="table.table_number"
              >
                {{ table.table_number }}
              </option>
            </CFormSelect>
          </CCol>

          <!-- STATUS -->
          <CCol md="3">
            <CFormSelect v-model="filterData.status">
              <option value="">All Status</option>
              <option
                v-for="status in props.statuses"
                :key="status"
                :value="status"
              >
                {{ status }}
              </option>
            </CFormSelect>
          </CCol>

          <!-- SEARCH -->
          <CCol md="6">
            <CInputGroup>
              <CFormInput
                v-model="filterData.searchTerm"
                placeholder="Search order number (e.g. 0002)"
              />
              <div class="input-group-text">
                <CIcon :icon="cilSearch" />
              </div>
            </CInputGroup>
          </CCol>

        </CRow>
      </CCardBody>
    </CCard>

    <!-- RESULTS -->
    <CRow class="g-4">
      <CCol
        v-for="order in props.orders"
        :key="order.id"
        lg="4"
      >
        <CCard class="order-card shadow-sm border-0 h-100">
          <CCardBody>

            <!-- TOP -->
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-bold text-primary mb-0">
                {{ order.order_number }}
              </h6>

              <CBadge
                :color="order.status === 'pending' ? 'warning'
                  : order.status === 'completed' ? 'success'
                  : 'secondary'"
              >
                {{ order.status }}
              </CBadge>
            </div>

            <!-- INFO -->
            <div class="text-muted small">
              <div>Table: {{ order.table?.table_number || '-' }}</div>
              <div>Total: {{formatCurrency(order.total_amount) }}</div>
              <div>
                {{ new Date(order.created_at).toLocaleString() }}
              </div>
            </div>

            <div class="float-end">
                <Link :href="route('orders.edit', order)">
                    <CButton color="dark" variant="outline">
                       <CIcon name="cil-pencil" ></CIcon>
                    </CButton>
                </Link>
            </div>

          </CCardBody>
        </CCard>
      </CCol>

      <!-- EMPTY STATE -->
      <CCol v-if="props.orders.length === 0" class="text-center py-5">
        <div class="text-muted">No orders found</div>
      </CCol>

    </CRow>
  </CContainer>
</template>

<style scoped>
.order-card {
  transition: all 0.25s ease;
  border-left: 4px solid #321fdb;
}

.order-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
</style>