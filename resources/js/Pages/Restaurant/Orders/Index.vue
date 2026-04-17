<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { formatCurrency, removeUnderScore } from "@/utils/format";
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
  <CContainer class="border rounded-4 shadow-lg mt-4 p-4">
    <CTable hover responsive align="middle" class="mb-0">
      <CTableCaption class="text-medium-emphasis">
        Recent 10 Orders
      </CTableCaption>

      <CTableHead>
        <CTableRow class="text-medium-emphasis">
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Order</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
          <CTableHeaderCell>Amount</CTableHeaderCell>
          <CTableHeaderCell>Created By</CTableHeaderCell>
          <CTableHeaderCell>Table</CTableHeaderCell>
          <CTableHeaderCell class="text-end" width="120">
            Actions
          </CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow v-for="(order, index) in orders" :key="order.id">
          <!-- Index -->
          <CTableHeaderCell>
            {{ index + 1 }}
          </CTableHeaderCell>

          <!-- Order Number -->
          <CTableDataCell class="fw-semibold">
            {{ order.order_number }}
          </CTableDataCell>

          <!-- Status -->
          <CTableDataCell>
            <CDropdown>
              <CDropdownToggle
                size="sm"
                color="primary"
                variant="outline"
                :disabled="updateStatus.processing"
              >
                {{ $capitalize(removeUnderScore(order.status)) }}
              </CDropdownToggle>

              <CDropdownMenu>
                <CDropdownItem
                  v-for="status in order_statuses"
                  :key="status"
                  :active="order.status === status"
                  @click="update(order.id, status)"
                  :disabled="order.status === 'completed' || order.status === 'cancelled'"
                >
                  {{ $capitalize(removeUnderScore(status)) }}
                </CDropdownItem>
              </CDropdownMenu>
            </CDropdown>
          </CTableDataCell>

          <!-- Amount -->
          <CTableDataCell class="fw-medium">
            {{ formatCurrency(order.total_amount) }}
          </CTableDataCell>

          <!-- User -->
          <CTableDataCell>
            {{ order.user.name }}
          </CTableDataCell>

          <!-- Table -->
          <CTableDataCell>
            <span class="text-medium-emphasis">
              {{ order.table?.table_number || "TAKE-AWAY" }}
            </span>
          </CTableDataCell>

          <!-- Actions -->
          <CTableDataCell class="text-end">
            <Link :href="route('orders.edit', order.id)">
              <CButton size="sm" color="secondary" variant="outline">
                <CIcon name="cil-pencil" />
              </CButton>
            </Link>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>
  </CContainer>
</template>