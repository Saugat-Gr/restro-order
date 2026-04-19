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

const canCreate = user.permissions.includes("create-table");
const canUpdate = user.permissions.includes("update-table");

const props = defineProps({
  tables: Object,
  statuses: Array,
  filters: Array,
});

console.log(props.tables);

const tables = computed(() => props.tables.data);
console.log(props.tables);
const statuses = props.statuses;


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


const showDeleteModal = ref(false);
const tableToDelete = ref(null);


const updateStatus = (table, status) => {
  form.status = status;

  form.post(route("tables.update", table.id), {
    preserveScroll: true,
  });
};

const addFilterStatus = (status) => {
  filters.status = status;
};

const resetFilters = () => {
  filters.status = "";
  filters.table_number = "";
};


const applyFilters = debounce(() => {
  router.get(route("tables.index"), filters, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 300);


watch(
  () => [filters.status, filters.table_number],
  () => {
    applyFilters();
  },
  { deep: true }
);

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
  <CContainer class=" border rounded-4 shadow-lg mt-4 p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

      <!-- LEFT FILTERS -->
      <div class="d-flex align-items-center gap-2 flex-wrap">

        <!-- STATUS FILTER -->
        <CDropdown>
          <CDropdownToggle color="secondary" variant="outline">
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

        <!-- RESET -->
        <CButton color="secondary" variant="outline" @click="resetFilters">
          Reset Filters
        </CButton>

      </div>

      <div class="flex-grow-1 d-flex align-items-center justify-center">
        <CCol lg="4">
          <CFormInput
            placeholder="Search Table Number..."
            v-model="filters.table_number"
          />
        </CCol>
      </div>

      <div>
        <Link :href="route('tables.create')" v-if="canCreate">
          <CButton class="d-flex align-items-center gap-2 px-3" color="primary">
            <CIcon name="cil-plus" />
            Add Table
          </CButton>
        </Link>
      </div>

    </div>

    <CTable hover responsive align="middle" class="mb-0">

      <CTableCaption class="text-medium-emphasis">
        Tables
      </CTableCaption>

      <CTableHead>
        <CTableRow class="text-medium-emphasis">
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Table Number</CTableHeaderCell>
          <CTableHeaderCell>Capacity</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
          <CTableHeaderCell>Assigned To</CTableHeaderCell>
          <CTableHeaderCell v-if="canUpdate" class="text-end">
            Actions
          </CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow v-for="(table, index) in tables" :key="table.id">

          <!-- Index -->
          <CTableDataCell>
            {{
              (props.tables.current_page - 1) *
                props.tables.per_page +
              index +
              1
            }}
          </CTableDataCell>

          <!-- Table Number -->
          <CTableDataCell class="fw-semibold">
            {{ table.table_number }}
          </CTableDataCell>

          <!-- Capacity -->
          <CTableDataCell>
            {{ table.capacity }}
          </CTableDataCell>

          <!-- Status -->
          <CTableDataCell>
            <CFormSelect
              :model-value="table.status"
              @change="(e) => updateStatus(table, e.target.value)"
              size="sm"
            >
              <option v-for="status in statuses" :key="status" :value="status">
                {{ $capitalize(status) }}
              </option>
            </CFormSelect>
          </CTableDataCell>

          <!-- Assigned -->
          <CTableDataCell>
            <span class="text-medium-emphasis">
              {{ table?.user?.name || "NONE" }}
            </span>
          </CTableDataCell>

          <!-- Actions -->
          <CTableDataCell v-if="canUpdate" class="text-end">
            <div class="d-flex justify-content-end gap-2">

              <Link :href="route('tables.edit', table.id)">
                <CButton size="sm" color="secondary" variant="outline">
                  <CIcon name="cil-pencil" />
                </CButton>
              </Link>

              <CButton
                size="sm"
                color="danger"
                variant="outline"
                @click="openDeleteModal(table)"
              >
                <CIcon name="cil-trash" />
              </CButton>

            </div>
          </CTableDataCell>

        </CTableRow>
      </CTableBody>
    </CTable>

    <div
  class="d-flex justify-content-end mt-4"
  v-if="props.tables.current_page <= props.tables.last_page"
>
  <CPagination aria-label="Page navigation">

    <Link :href="props.tables.prev_page_url">
      <CPaginationItem
      color="light"
      :disabled="!props.tables.prev_page_url"
      >
        Previous Page
      </CPaginationItem>
    </Link>

    <Link :href="props.tables.next_page_url">
      <CPaginationItem
        color="light"
        :disabled="!props.tables.next_page_url"
      >
        Next Page
      </CPaginationItem>
    </Link>

  </CPagination>
</div>

    <div v-if="tables.length === 0" class="text-center text-muted my-5">
      <h5 class="mb-0">No Tables Found</h5>
    </div>

  </CContainer>

  <ConfirmDialog
    v-model:visible="showDeleteModal"
    :message="`Are you sure you want to delete ${tableToDelete?.table_number}?`"
    @confirm="confirmDelete"
    @cancel="showDeleteModal = false"
  />
</template>