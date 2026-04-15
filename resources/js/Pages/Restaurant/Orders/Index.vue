<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CIcon from "@coreui/icons-vue";
import {
  CButton,
  CContainer,
  CDropdown,
  CDropdownItem,
  CDropdownMenu,
  CDropdownToggle,
  CTable,
  CTableBody,
  CTableCaption,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from "@coreui/vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineOptions({
  layout: AuthenticatedLayout,
});



const props = defineProps({
  orders: Array,
  order_statuses: Array,
});

// shared form
const updateStatus = useForm({
  _method: "patch",
  status: "",
});

// update function per order
const update = (orderId, status) => {
  updateStatus.status = status;

  updateStatus.post(route("orders.update", orderId), {
    preserveScroll: true,
    preserveState: true,
  });
};
</script>

<template>
  <CContainer class="shadow-lg p-5 rounded-2">
    <CTable caption="top" hover bordered>
      <CTableCaption>Recent 10 Orders</CTableCaption>

      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Order Number</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
          <CTableHeaderCell>Amount</CTableHeaderCell>
          <CTableHeaderCell>Created By</CTableHeaderCell>
          <CTableHeaderCell>Table</CTableHeaderCell>
          <CTableHeaderCell width="180">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow v-for="(order, index) in orders" :key="order.id">
          <!-- Index -->
          <CTableHeaderCell>
            {{ index + 1 }}
          </CTableHeaderCell>

          <!-- Order Number -->
          <CTableDataCell class="fw-medium">
            {{ order.order_number }}
          </CTableDataCell>

          <!-- ✅ Status Dropdown -->
          <CTableDataCell>
            <CDropdown class="w-full">
              <CDropdownToggle
                color="secondary"
                size="sm"
                :disabled="updateStatus.processing"
              >
                {{ $capitalize(order.status) }}
              </CDropdownToggle>

              <CDropdownMenu>
                <CDropdownItem
                  v-for="status in order_statuses"
                  :key="status"
                  :active="order.status === status"
                  @click="update(order.id, status)"
                  :disabled="order.status === 'completed'"
                >
                  {{ $capitalize(status) }}
                </CDropdownItem>
              </CDropdownMenu>
            </CDropdown>
          </CTableDataCell>

          <!-- Amount -->
          <CTableDataCell>
            {{ order.total_amount }}
          </CTableDataCell>

          <!-- User -->
          <CTableDataCell>
            {{ order.user.name }}
          </CTableDataCell>

          <!-- Table -->
          <CTableDataCell>
            {{ order.table?.table_number || "TAKE-AWAY" }}
          </CTableDataCell>

          <!-- Actions -->
          <CTableDataCell>
            <Link :href="route('orders.edit', order.id)">
              <CButton color="secondary" size="sm" variant="outline">
                <CIcon name="cil-pencil" />
              </CButton>
            </Link>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>
  </CContainer>
</template>