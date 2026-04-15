<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CIcon from "@coreui/icons-vue";
import {
  CButton,
  CCol,
  CContainer,
  CDropdown,
  CDropdownItem,
  CDropdownMenu,
  CDropdownToggle,
  CFormInput,
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
import { router, useForm, usePage, Head, Link } from "@inertiajs/vue3";
import ConfirmDialog from "@/Components/ComponentDialog.vue";
import { computed, reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import { Inertia } from "@inertiajs/inertia";

defineOptions({
  layout: AuthenticatedLayout,
});

const page = usePage();
const user = page.props.auth.user;

const canCreate = user.permissions.includes('create-table');

const props = defineProps({
  tables: Array,
  statuses: Array,
  filters: Array,
});

console.log(props.tables);

const tables = computed(() => props.tables.data);
console.log(props.tables);
const statuses = props.statuses;

/**
 * FORMS
 */
const form = useForm({
  _method: "patch",
  status: props.filters.status ?? "",
});

const deleteForm = useForm({
  _method: "delete",
});

const filters = reactive({
  status: "",
  table_number: "",
});

/**
 * STATE
 */
const showDeleteModal = ref(false);
const tableToDelete = ref(null);

/**
 * FUNCTIONS
 */

// ✅ Update status (fixed)
const updateStatus = (table, status) => {
  form.status = status;

  form.post(route("tables.update", table.id), {
    preserveScroll: true,
    onSuccess: () => Inertia.reload([tables]),
  });
};

// ✅ Apply filter
const addFilterStatus = (status) => {
  filters.status = status;
};

// ✅ Reset filters
const resetFilters = () => {
  filters.status = "";
  filters.table_number = "";
};

// ✅ Debounced filtering
const applyFilters = debounce(() => {
  router.get(route("tables.index"), filters, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 300);

// Watch filters
watch(
  () => [filters.status, filters.table_number],
  () => {
    applyFilters();
  },
  { deep: true }
);

// ✅ Delete modal
const openDeleteModal = (table) => {
  tableToDelete.value = table;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  deleteForm.delete(route("tables.destroy", tableToDelete.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      tableToDelete.value = null;
    },
  });
};
</script>

<template>
  <CContainer class="p-5 border rounded-1 shadow-lg">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <CButton color="secondary" @click="resetFilters()">
          Reset Filters
        </CButton>
      </div>

      <div class="flex-grow-1 d-flex align-items-center justify-center">
        <CCol lg="4">
          <CFormInput
            placeholder="Search Table Number..."
            v-model="filters.table_number"
          ></CFormInput>
        </CCol>
      </div>

      <div class="flex items-center gap-2">
        <!-- STATUS FILTER -->
        <CDropdown>
          <CDropdownToggle color="secondary">
            {{ filters.status ? $capitalize(filters.status) : "Select Status" }}
          </CDropdownToggle>
          <CDropdownMenu>
            <CDropdownItem
              v-for="status in statuses"
              :key="status"
              @click="addFilterStatus(status)"
              :active="status === filters.status"
            >
              {{ $capitalize(status) }}
            </CDropdownItem>
          </CDropdownMenu>
        </CDropdown>

        <!-- ADD TABLE -->
        <Link :href="route('tables.create')" v-if="canCreate">
          <CButton color="primary">
            <CIcon name="cil-plus" />
            Add Table
          </CButton>
        </Link>
      </div>
    </div>

    <!-- TABLE -->
    <CTable caption="top" hover bordered>
      <CTableCaption>Tables</CTableCaption>

      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Table Number</CTableHeaderCell>
          <CTableHeaderCell>Capacity</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
          <CTableHeaderCell>Assigned To</CTableHeaderCell>
          <CTableHeaderCell>Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow v-for="(table, index) in tables" :key="table.id">
          <CTableDataCell
            >{{   (props.tables.current_page - 1) * props.tables.per_page + index + 1
            }}</CTableDataCell
          >

          <CTableDataCell>
            {{ table.table_number }}
          </CTableDataCell>

          <CTableDataCell>
            {{ table.capacity }}
          </CTableDataCell>

          <CTableDataCell>
            <CFormSelect
              :model-value="table.status"
              @change="(e) => updateStatus(table, e.target.value)"
            >
              <option v-for="status in statuses" :key="status" :value="status">
                {{ $capitalize(status) }}
              </option>
            </CFormSelect>
          </CTableDataCell>

          <CTableDataCell>
            {{ table?.user?.name || "NONE" }}
          </CTableDataCell>

          <!-- ACTIONS -->
          <CTableDataCell>
            <Link :href="route('tables.edit', table.id)">
              <CButton color="primary" variant="outline">
                <CIcon name="cil-pencil" />
              </CButton>
            </Link>

            <CButton
              color="danger"
              variant="outline"
              class="ml-2 hover:text-white"
              @click="openDeleteModal(table)"
            >
              <CIcon name="cil-trash" />
            </CButton>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>

    <CPagination aria-label="Page navigation" v-if="props.tables.current_page < props.tables.last_page">
      <Link :href="props.tables.prev_page_url">
        <CPaginationItem :disabled="!props.tables.prev_page_url">
          Previous Page
        </CPaginationItem>
      </Link>

      <Link :href="props.tables.next_page_url">
        <CPaginationItem :disabled="!props.tables.next_page_url">
          Next Page
        </CPaginationItem>
      </Link>
    </CPagination>
    <!-- EMPTY STATE -->
    <div v-if="tables.length === 0" class="text-center text-muted my-5">
      <h1>No Tables</h1>
    </div>
  </CContainer>

  <!-- DELETE CONFIRM -->
  <ConfirmDialog
    v-model:visible="showDeleteModal"
    :message="`Are you sure you want to delete ${tableToDelete?.table_number}?`"
    @confirm="confirmDelete"
    @cancel="showDeleteModal = false"
  />
</template>