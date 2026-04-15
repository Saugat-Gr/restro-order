<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
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
import { formatCurrency } from "@/utils/format";

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

// ✅ Debounced request
const filterData = debounce(() => {
  filters.get(route("transactions.index"), {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  });
}, 300);

// ✅ Cleaner watch
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
  <CContainer>
    <div class="d-flex justify-between align-items-center gap-3 mb-3">
      
      <!-- Per Page -->
      <div>
        <CFormLabel>Items Per Page</CFormLabel>
        <CFormSelect v-model="filters.perPage">
          <option
            v-for="value in perPageValues"
            :key="value"
            :value="String(value)"
          >
            {{ value }}
          </option>
        </CFormSelect>
      </div>

      <!-- Search -->
      <div class="flex-grow-1 text-center">
        <CFormInput
          class="w-50 mx-auto"
          placeholder="Search Transaction..."
          v-model="filters.searchTerm"
        />
      </div>

      <!-- Transaction Method -->
      <div>
        <CFormLabel>Transaction Method</CFormLabel>
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

    <!-- Table -->
    <CTable hover bordered>
      <CTableCaption>Transactions</CTableCaption>

      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Transaction Number</CTableHeaderCell>
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
          <CTableDataCell>
            {{
              (props.transactions.current_page - 1) *
                props.transactions.per_page +
              index +
              1
            }}
          </CTableDataCell>

          <CTableDataCell>
            {{ transaction.transaction_number }}
          </CTableDataCell>

          <CTableDataCell>
            {{ transaction.user.name }}
          </CTableDataCell>

          <CTableDataCell>
            {{ $capitalize(transaction.payment_method) }}
          </CTableDataCell>

          <CTableDataCell>
            {{ formatCurrency(transaction.amount) }}
          </CTableDataCell>

          <CTableDataCell>
            {{ $capitalize(transaction.status) }}
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>

    <!-- Pagination -->
    <div v-if="props.transactions.last_page > 1" class="mt-3">
      <CPagination >

        <!-- Previous -->
        <Link
          v-if="props.transactions.prev_page_url"
          :href="props.transactions.prev_page_url"
        >
          <CPaginationItem>
            Previous
          </CPaginationItem>
        </Link>

        <!-- Next -->
        <Link
          v-if="props.transactions.next_page_url"
          :href="props.transactions.next_page_url"
        >
          <CPaginationItem>
            Next
          </CPaginationItem>
        </Link>

      </CPagination>
    </div>

  </CContainer>
</template>