<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CIcon from "@coreui/icons-vue";
import { CButton, CContainer, CTableDataCell } from "@coreui/vue";
import { Link, usePage } from "@inertiajs/vue3";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  orders: Array,
});

console.log(props.orders);
</script>


<template>
  <CContainer class="shadow-lg p-5 rounded-2">
    <CTable caption="top" hover bordered>
      <CTableCaption> Recent 10 Orders</CTableCaption>
      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Order Number</CTableHeaderCell>
          <CTableHeaderCell>Order Status</CTableHeaderCell>
          <CTableHeaderCell>Amount</CTableHeaderCell>
          <CTableHeaderCell>Created By</CTableHeaderCell>
          <CTableHeaderCell>Assigned Table</CTableHeaderCell>
          <CTableHeaderCell width="180">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>
      <CTableBody>
        <CTableRow v-for="(order, index) in orders" :key="order.id">
          <CTableHeaderCell>
            <!-- {{
              (props.menu_items?.current_page - 1) *
                props.menu_items?.per_page +
              index +
              1
            }} -->
              {{ index + 1 }}
          </CTableHeaderCell>
          <CTableDataCell class="fw-medium">{{ order.order_number }}</CTableDataCell>

          <!-- Status -->
          <CTableDataCell>
            <span
              :class="[`bg-${order.status_color}`, 'text-white', 'p-1 rounded']"
            >
              {{ order.status.toUpperCase() }}
            </span>
          </CTableDataCell>

          <CTableDataCell>{{ order.total_amount }}</CTableDataCell>

          <!-- Stock -->
          <CTableDataCell>
            {{ order.user.name }}
          </CTableDataCell>

          <CTableDataCell>
            {{ order.table?.table_number || "TAKE-AWAY" }}
          </CTableDataCell>

          <!-- Actions -->
          <CTableDataCell>
            <Link :href="route('orders.edit', order.id)">
              <CButton color="secondary" size="sm" variant="outline"
                ><CIcon name="cil-pencil"
              /></CButton>
            </Link>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>
  </CContainer>
</template>