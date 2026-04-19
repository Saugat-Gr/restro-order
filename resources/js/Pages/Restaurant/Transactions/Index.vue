<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  CButton,
  CContainer,
  CFormInput,
  CFormLabel,
  CFormSelect,
  CPagination,
  CPaginationItem,
  CTable,
  CTableBody,
  CTableCaption,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from "@coreui/vue";
import { Link, useForm } from "@inertiajs/vue3";
import { computed, watch } from "vue";
import debounce from "lodash/debounce";
import { formatCurrency, removeUnderScore } from "@/utils/format";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  transactions: Object,
  transaction_methods: Object,
});

const filters = useForm({
  perPage: "10",
  transaction_method: "cash",
  searchTerm: "",
});

const perPageValues = [10, 20, 30, 40];

const transactions = computed(() => props.transactions.data);

const filterData = debounce(() => {
  filters.get(route("transactions.index"), {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  });
}, 300);

watch(
  () => ({
    perPage: filters.perPage,
    method: filters.transaction_method,
    search: filters.searchTerm,
  }),
  filterData,
  { deep: true }
);
</script>
<template>
  <CContainer class="border rounded-4 shadow-lg mt-4 p-4">
    <div
      class="d-flex justify-content-between align-items-center gap-3 mb-4 flex-wrap"
    >
      <div class="flex-grow-1 text-center">
        <CFormInput
          class="w-50 mx-auto"
          placeholder="Search Transaction..."
          v-model="filters.searchTerm"
        />
      </div>

      <div>
        <CFormLabel class="text-medium-emphasis">Transaction Method</CFormLabel>
        <CFormSelect v-model="filters.transaction_method">
          <option
            v-for="transaction in props.transaction_methods"
            :key="transaction"
            :value="transaction"
          >
            {{ transaction }}
          </option>
        </CFormSelect>
      </div>
    </div>

    <CTable hover responsive align="middle" class="mb-0">
      <CTableCaption class="text-medium-emphasis"> Transactions </CTableCaption>

      <CTableHead>
        <CTableRow class="text-medium-emphasis">
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Transaction</CTableHeaderCell>
          <CTableHeaderCell>Processed By</CTableHeaderCell>
          <CTableHeaderCell>Method</CTableHeaderCell>
          <CTableHeaderCell>Amount</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow
          v-for="(transaction, index) in transactions"
          :key="transaction.id"
        >
          <!-- Index -->
          <CTableDataCell>
            {{
              (props.transactions.current_page - 1) *
                props.transactions.per_page +
              index +
              1
            }}
          </CTableDataCell>

          <!-- Number -->
          <CTableDataCell class="fw-semibold">
            {{ transaction.transaction_number }}
          </CTableDataCell>

          <!-- User -->
          <CTableDataCell>
            {{ transaction?.user?.name || "USER" }}
          </CTableDataCell>

          <!-- Method -->
          <CTableDataCell>
            <span class="text-medium-emphasis">
              {{ removeUnderScore($capitalize(transaction.payment_method)) }}
            </span>
          </CTableDataCell>

          <!-- Amount -->
          <CTableDataCell class="fw-medium">
            {{ formatCurrency(transaction.amount) }}
          </CTableDataCell>

          <!-- Status -->
          <CTableDataCell>
            {{ $capitalize(transaction.status) }}
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>

    <div
      v-if="props.transactions.last_page >= 1"
      class="mt-4 d-flex justify-content-end align-items-center flex-wrap gap-3"
    >
      <CPagination class="mb-0">
        <CPaginationItem
          color="light"
          :disabled="!props.transactions.prev_page_url"
        >
          <Link
            v-if="props.transactions.prev_page_url"
            :href="props.transactions.prev_page_url"
            class="text-decoration-none"
          >
            Previous
          </Link>
          <span v-else>Previous</span>
        </CPaginationItem>

        <CPaginationItem
          color="light"
          :disabled="!props.transactions.next_page_url"
        >
          <Link
            v-if="props.transactions.next_page_url"
            :href="props.transactions.next_page_url"
            class="text-decoration-none"
          >
            Next
          </Link>
          <span v-else>Next</span>
        </CPaginationItem>
      </CPagination>

      <div class="d-flex align-items-center gap-2">
        <CFormLabel class="mb-0 text-medium-emphasis"> Items: </CFormLabel>

        <CFormSelect v-model="filters.perPage" style="width: 100px">
          <option
            v-for="value in perPageValues"
            :key="value"
            :value="String(value)"
          >
            {{ value }} per page
          </option>
        </CFormSelect>
      </div>
    </div>
  </CContainer>
</template>