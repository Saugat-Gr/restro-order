<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import debounce from "lodash/debounce";

import {
  CButton,
  CContainer,
  CTable,
  CTableBody,
  CTableHead,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
  CDropdown,
  CDropdownToggle,
  CDropdownMenu,
  CDropdownItem,
  CFormSwitch,
  CFormInput,
  CFormSelect,
  CImage,
  CInputGroup,
  CPagination,
  CPaginationItem,
} from "@coreui/vue";
import CIcon from "@coreui/icons-vue";
import { removeUnderScore } from "@/utils/format";

defineOptions({ layout: AuthenticatedLayout });

const page = usePage();
const user = page.props.auth.user;

const canCreate = user.permissions.includes("create-menu-item");
const canUpdate = user.permissions.includes("update-menu-item");

const props = defineProps({
  menu_items: Object,
  app: Object,
  filters: Object,
});

const menuItems = computed(
  () => props.menu_items?.data ?? props.menu_items ?? []
);

const hasPagination = computed(() => props.menu_items?.last_page > 1);

const filters = ref({
  search: props.filters?.search || "",
  status: props.filters?.status || "",
  stock: props.filters?.stock || "",
  perPage: Number(props.filters?.perPage) || 10,
});

const applyFilters = debounce(() => {
  router.get(route("menu.menu-items.index"), filters.value, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 300);

watch(filters, applyFilters, { deep: true });

const resetFilters = () => {
  filters.value = { search: "", status: "", stock: "", perPage: 10 };
};


const deleteForm = useForm({ _method: "delete" });
const statusForm = useForm({ _method: "patch", status: null });
const stockForm = useForm({ _method: "patch", is_in_stock: null });

const toggleStatus = (item, status) => {
  statusForm.status = status;
  statusForm.post(route("menu.menu-items.update", item.id), {
    preserveScroll: true,
    onSuccess: () => router.reload({ only: ["menu_items"] }),
  });

  if (status === "inactive") toggleStock(item, false);
};

const toggleStock = (item, stock) => {
  stockForm.is_in_stock = stock ? 1 : 0;
  stockForm.post(route("menu.menu-items.update", item.id), {
    preserveScroll: true,
    onSuccess: () => router.reload({ only: ["menu_items"] }),
  });
};

const deleteItem = (item) => {
  if (confirm("Delete this item?")) {
    deleteForm.delete(route("menu.menu-items.destroy", item.id));
  }
};

const changePage = (page) => {
  router.get(
    route("menu.menu-items.index"),
    { ...filters.value, page },
    { preserveState: true, preserveScroll: true }
  );
};
</script>

<template>
  <Head>
    <title>{{ app.title }}</title>
  </Head>

  <CContainer class="border rounded-4 shadow-lg mt-4 p-4">
    <!-- HEADER -->
    <div
      class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3"
    >
      <!-- Filters -->
      <div class="d-flex gap-2 flex-wrap">
        <CDropdown>
          <CDropdownToggle color="secondary" variant="outline">
            {{ $capitalize(removeUnderScore(filters.stock)) || "Stock" }}
          </CDropdownToggle>
          <CDropdownMenu>
            <CDropdownItem @click="filters.stock = ''">All</CDropdownItem>
            <CDropdownItem @click="filters.stock = 'in_stock'"
              >In Stock</CDropdownItem
            >
            <CDropdownItem @click="filters.stock = 'out_of_stock'"
              >Out of Stock</CDropdownItem
            >
          </CDropdownMenu>
        </CDropdown>

        <CDropdown>
          <CDropdownToggle color="secondary" variant="outline">
            {{ filters.status || "Status" }}
          </CDropdownToggle>
          <CDropdownMenu>
            <CDropdownItem @click="filters.status = ''">All</CDropdownItem>
            <CDropdownItem @click="filters.status = 'active'"
              >Active</CDropdownItem
            >
            <CDropdownItem @click="filters.status = 'inactive'"
              >Inactive</CDropdownItem
            >
          </CDropdownMenu>
        </CDropdown>

        <CButton color="secondary" variant="outline" @click="resetFilters">
          Reset
        </CButton>
      </div>

      <!-- Search -->
      <div class="col-md-4">
        <CInputGroup>
          <CFormInput v-model="filters.search" placeholder="Search..." />
        </CInputGroup>
      </div>

      <!-- Create -->
      <Link href="/menu/menu-items/create" v-if="canCreate">
        <CButton color="primary">
          <CIcon name="cil-plus" class="me-2" />
          Create
        </CButton>
      </Link>
    </div>

    <!-- TABLE -->
    <CTable hover responsive align="middle">
      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Image</CTableHeaderCell>
          <CTableHeaderCell>Name</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
          <CTableHeaderCell>Category</CTableHeaderCell>
          <CTableHeaderCell>Stock</CTableHeaderCell>
          <CTableHeaderCell class="text-end">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>

      <CTableBody>
        <CTableRow v-for="(item, index) in menuItems" :key="item.id">
          <!-- Index -->
          <CTableHeaderCell>
            {{
              (props.menu_items.current_page - 1) * props.menu_items.per_page +
              index +
              1
            }}
          </CTableHeaderCell>

          <!-- Image -->
          <CTableDataCell class="text-center">
            <CImage
              v-if="item.image"
              :src="`/storage/${item.image}`"
              width="45"
              height="45"
              class="rounded"
            />
            <span v-else class="text-muted small">—</span>
          </CTableDataCell>

          <!-- Name -->
          <CTableDataCell class="fw-semibold">
            {{ item.item_name }}
          </CTableDataCell>

          <!-- Status -->
          <CTableDataCell>
            <label class="switch m-0">
              <input
                type="checkbox"
                :checked="item.status === 'active'"
                :disabled="!canUpdate"
                @change="
                  (e) =>
                    toggleStatus(item, e.target.checked ? 'active' : 'inactive')
                "
              />
              <span class="slider"></span>
            </label>
          </CTableDataCell>

          <!-- Category -->
          <CTableDataCell>
            {{ item.menu_item_category?.name || "—" }}
          </CTableDataCell>

          <!-- Stock -->
          <CTableDataCell>
            <CDropdown>
              <CDropdownToggle
                size="sm"
                :color="item.is_in_stock ? 'success' : 'danger'"
                class="text-white"
              >
                {{ item.is_in_stock ? "In Stock" : "Out of Stock" }}
              </CDropdownToggle>
              <CDropdownMenu>
                <CDropdownItem @click="toggleStock(item, true)"
                  >In Stock</CDropdownItem
                >
                <CDropdownItem @click="toggleStock(item, false)"
                  >Out of Stock</CDropdownItem
                >
              </CDropdownMenu>
            </CDropdown>
          </CTableDataCell>

          <!-- Actions -->
          <CTableDataCell class="text-end">
            <div class="d-flex justify-content-end gap-2">
              <Link
                :href="route('menu.menu-items.edit', item.id)"
                v-if="canUpdate"
              >
                <CButton size="sm" color="secondary" variant="outline">
                  <CIcon name="cil-pencil" />
                </CButton>
              </Link>

              <CButton
                size="sm"
                color="danger"
                variant="outline"
                @click="deleteItem(item)"
              >
                <CIcon name="cil-trash" />
              </CButton>

              <Link :href="route('menu.menu-items.show', item.id)">
                <CButton size="sm" color="primary" variant="outline">
                  <i class="bi bi-eye"></i>
                </CButton>
              </Link>
            </div>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>

    <!-- PAGINATION -->
    <div
      v-if="hasPagination"
      class="d-flex justify-content-between align-items-center mt-4"
    >
      <div class="text-muted small">
        Showing {{ props.menu_items.from }} to {{ props.menu_items.to }} of
        {{ props.menu_items.total }}
      </div>

      <div class="d-flex align-items-center gap-3">
        <CPagination>
          <CPaginationItem
            :disabled="props.menu_items.current_page === 1"
            @click="changePage(props.menu_items.current_page - 1)"
          >
            Prev
          </CPaginationItem>

          <CPaginationItem
            :disabled="
              props.menu_items.current_page === props.menu_items.last_page
            "
            @click="changePage(props.menu_items.current_page + 1)"
          >
            Next
          </CPaginationItem>
        </CPagination>

        <CFormSelect v-model="filters.perPage">
          <option :value="10">10</option>
          <option :value="25">25</option>
          <option :value="50">50</option>
        </CFormSelect>
      </div>
    </div>

    <!-- EMPTY -->
    <div v-if="menuItems.length === 0" class="text-center py-5 text-muted">
      No data found.
    </div>
  </CContainer>
</template>


<style scoped>
.switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background-color: #ccc;
  transition: 0.3s;
  border-radius: 50px;
}

.slider::before {
  content: "";
  position: absolute;
  height: 18px;
  width: 18px;
  left: 3px;
  top: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #321fdb; /* CoreUI primary */
}

input:checked + .slider::before {
  transform: translateX(22px);
}
</style>