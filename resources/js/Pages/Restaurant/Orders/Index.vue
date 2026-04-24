<script setup>
import { ref } from "vue";
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
  CModal,
  CModalHeader,
  CTable,
  CTableBody,
  CTableCaption,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from "@coreui/vue";
import { Link, useForm } from "@inertiajs/vue3";
import ComponentDialog from "@/Components/ComponentDialog.vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  orders: Array,
  order_statuses: Array,
  payment_methods: Array,
});

// missing refs (FIX)
const showCompleteModal = ref(false);
const selectedOrderId = ref(null);
const selectedStatus = ref("");

const showCancelModal = ref(false);
const selectedCancelOrderId = ref(null);

// shared form
const updateStatus = useForm({
  _method: "patch",
  status: "",
  payment_method: null,
});

const cancelForm = useForm({
  _method: "patch",
  status: "cancelled",
});
const confirmComplete = () => {
  updateStatus.status = selectedStatus.value;

  updateStatus.post(route("orders.update", selectedOrderId.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      showCompleteModal.value = false;
    },
  });
};

const confirmCancel = () => {
  if (!selectedCancelOrderId.value) return;

  cancelForm.post(route("orders.update", selectedCancelOrderId.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      showCancelModal.value = false;
      cancelForm.reset();
    },
  });
};

// update function per order
const update = (orderId, status) => {
  updateStatus.status = status;

  if (status === "completed") {
    selectedOrderId.value = orderId;
    selectedStatus.value = status;
    showCompleteModal.value = true;
    return;
  }

  if (status === "cancelled") {
    selectedCancelOrderId.value = orderId;
    selectedStatus.value = status;
    showCancelModal.value = true;
    return;
  }

  updateStatus.post(route("orders.update", orderId), {
    preserveScroll: true,
    preserveState: true,
  });
};
</script>
<template>
  <CModal :visible="showCompleteModal" @close="showCompleteModal = false">
    <CModalHeader>
      <strong>Confirm Completion</strong>
    </CModalHeader>

    <div class="p-4">Define the payment method:</div>

    <CDropdown>
      <CDropdownToggle
        size="sm"
        variant="outline"
        class="rounded-pill px-3"
        :disabled="updateStatus.processing"
      >
        {{
          updateStatus.payment_method
            ? $capitalize(removeUnderScore(updateStatus.payment_method))
            : "Payment Method"
        }}
      </CDropdownToggle>

      <CDropdownMenu>
        <CDropdownItem
          v-for="method in payment_methods"
          :key="method"
          :selected="method === updateStatus.payment_method"
          @click="updateStatus.payment_method = method"
        >
          {{ $capitalize(removeUnderScore(method)) }}
        </CDropdownItem>
      </CDropdownMenu>
    </CDropdown>

    <div class="d-flex justify-content-end gap-2 p-3">
      <CButton
        color="secondary"
        variant="outline"
        @click="showCompleteModal = false"
      >
        Cancel
      </CButton>

      <CButton
        color="success"
        :disabled="updateStatus.processing"
        @click="confirmComplete"
      >
        Done
      </CButton>
    </div>
  </CModal>

  <CModal :visible="showCancelModal" @close="showCancelModal = false">
    <CModalHeader>
      <strong>Confirm Cancel</strong>
    </CModalHeader>

    <div class="d-flex justify-content-end gap-2 p-3">
      <CButton
        color="secondary"
        variant="outline"
        @click="showCancelModal = false"
      >
        Cancel Confirm
      </CButton>

      <CButton
        color="success"
        :disabled="updateStatus.processing"
        @click="confirmCancel"
      >
        Confirm
      </CButton>
    </div>
  </CModal>

  <CContainer class="mt-4">
    <div class="border-0 rounded-4 shadow-lg p-4">
      <!-- HEADER -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-0 fw-semibold">Orders</h4>
          <small class="text-medium-emphasis">
            Recent 10 orders overview
          </small>
        </div>
      </div>

      <!-- TABLE -->
      <CTable hover responsive align="middle" class="mb-0">
        <CTableHead>
          <CTableRow class="text-medium-emphasis">
            <CTableHeaderCell>#</CTableHeaderCell>
            <CTableHeaderCell>Order</CTableHeaderCell>
            <CTableHeaderCell>Status</CTableHeaderCell>
            <CTableHeaderCell>Amount</CTableHeaderCell>
            <CTableHeaderCell>Created By</CTableHeaderCell>
            <CTableHeaderCell>Table</CTableHeaderCell>
            <CTableHeaderCell class="text-end">Actions</CTableHeaderCell>
          </CTableRow>
        </CTableHead>

        <CTableBody>
          <CTableRow v-for="(order, index) in orders" :key="order.id">
            <!-- INDEX -->
            <CTableHeaderCell class="text-medium-emphasis">
              {{ index + 1 }}
            </CTableHeaderCell>

            <!-- ORDER NUMBER -->
            <CTableDataCell>
              <div class="fw-semibold">
                {{ order.order_number }}
              </div>
            </CTableDataCell>

            <!-- STATUS (same logic, cleaner UI) -->
            <CTableDataCell>
              <CDropdown>
                <CDropdownToggle
                  size="sm"
                  :color="
                    order.status === 'completed'
                      ? 'success'
                      : order.status === 'cancelled'
                      ? 'danger'
                      : 'primary'
                  "
                  variant="outline"
                  class="rounded-pill px-3"
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
                    :disabled="
                      order.status === 'completed' ||
                      order.status === 'cancelled'
                    "
                  >
                    {{ $capitalize(removeUnderScore(status)) }}
                  </CDropdownItem>
                </CDropdownMenu>
              </CDropdown>
            </CTableDataCell>

            <!-- AMOUNT -->
            <CTableDataCell class="fw-semibold">
              {{ formatCurrency(order.total_amount) }}
            </CTableDataCell>

            <!-- USER -->
            <CTableDataCell class="text-medium-emphasis">
              {{ order.user.name }}
            </CTableDataCell>

            <!-- TABLE -->
            <CTableDataCell>
              <span class="text-medium-emphasis">
                {{ order.table?.table_number || "TAKE-AWAY" }}
              </span>
            </CTableDataCell>

            <!-- ACTIONS -->
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

      <!-- EMPTY STATE -->
      <div
        v-if="orders.length === 0"
        class="text-center py-5 text-medium-emphasis"
      >
        No orders found
      </div>
    </div>
  </CContainer>
</template>