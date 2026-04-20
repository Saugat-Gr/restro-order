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
  router.get(route("orders.edit", order.id));
};
</script>

<template>
  <CContainer fluid class="py-4 border shadow-lg rounded-4 p-4">
    <!-- PAGE HEADER -->
    <CCard class="mb-4 border-0 ">
      <CCardBody class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 fw-semibold">Order Search</h5>
          <div class="text-muted small">Filter and track restaurant orders</div>
        </div>
      </CCardBody>
    </CCard>

    <!-- FILTERS -->
    <CCard class="mb-4 border-0 shadow-sm">
      <CCardBody>
        <CRow class="g-3 align-items-center">
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
              <span class="input-group-text">
                <CIcon :icon="cilSearch" />
              </span>
              <CFormInput
                v-model="filterData.searchTerm"
                placeholder="Search by order number..."
              />
            </CInputGroup>
          </CCol>
        </CRow>
      </CCardBody>
    </CCard>

    <!-- RESULTS -->
    <CRow class="g-4">
      <CCol v-for="order in props.orders.data" :key="order.id" lg="4">
        <CCard class="border-0 shadow-lg h-100">
          <!-- HEADER -->
          <CCardBody class="pb-2">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-semibold fs-5 text-primary">
                  {{ order.order_number }}
                </div>
              </div>

              <CBadge
                shape="rounded-pill"
                :color="
                  order.status === 'pending'
                    ? 'warning'
                    : order.status === 'completed'
                    ? 'success'
                    : 'secondary'
                "
              >
                {{ $capitalize(order.status) }}
              </CBadge>
            </div>
          </CCardBody>

          <!-- BODY -->
          <CCardBody class="pt-2 small">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Table</span>
              <span class="fw-medium">
                {{ order.table?.table_number || "-" }}
              </span>
            </div>

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Total</span>
              <span class="text-success fw-semibold">
                {{ formatCurrency(order.total_amount) }}
              </span>
            </div>

            <div class="d-flex justify-content-between">
              <span class="text-muted">Time</span>
              <span class="small">
                {{ new Date(order.created_at).toLocaleString() }}
              </span>
            </div>
          </CCardBody>

          <!-- FOOTER -->
          <CCardBody class="pt-0 d-flex justify-content-end">
            <Link :href="route('orders.edit', order)">
              <CButton color="dark" variant="outline" size="sm">
                <CIcon icon="cil-pencil" class="me-1" />
                Edit
              </CButton>
            </Link>
          </CCardBody>
        </CCard>
      </CCol>

      <!-- PAGINATION -->
      <div
        v-if="props.orders.links && props.orders.links.length > 0"
        class="d-flex  align-items-center justify-end mt-4 gap-2"
      >
        <!-- PREVIOUS -->
        <CButton
          size="sm"
          variant="outline"
          color="primary"
          :disabled="!props.orders.prev_page_url"
          @click="
            router.get(
              props.orders.prev_page_url,
              {},
              { preserveState: true, preserveScroll: true }
            )
          "
        >
           Previous
        </CButton>


        <!-- NEXT -->
        <CButton
          size="sm"
          variant="outline"
          color="primary"
          :disabled="!props.orders.next_page_url"
          @click="
            router.get(
              props.orders.next_page_url,
              {},
              { preserveState: true, preserveScroll: true }
            )
          "
        >
          Next 
        </CButton>
      </div>

      <!-- EMPTY STATE -->
      <CCol v-if="props.orders.data.length === 0">
        <CCard class="border-0 shadow-sm text-center py-5">
          <CCardBody>
            <div class="text-muted mb-2">No orders found</div>
            <div class="small text-muted">
              Try adjusting filters or search term
            </div>
          </CCardBody>
        </CCard>
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
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
}
</style>