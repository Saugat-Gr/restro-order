<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted } from "vue"; // ← added computed
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
  CTableCaption,
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
} from "@coreui/vue";
import CIcon from "@coreui/icons-vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  menu_items: Object, // Full paginator object from Laravel
  app: Object,
  filters: Object,
  flash: Object,
});

console.log(props.flash);

onMounted(() => {
  if (props.flash.success) {
    toastr.success(props.flash.success, "Success");
  }

  if (props.flash.error) {
    toastr.error(props.flash.error, "Error");
  }
});

// Use computed so it automatically updates when props change
const menuItems = computed(
  () => props.menu_items?.data ?? props.menu_items ?? []
);

// Pagination:
const paginationRange = computed(() => {
  const current = props.menu_items?.current_page || 1;
  const lastPage = props.menu_items?.last_page || 1;
  const delta = 1; // Show 1 page before/after current

  const range = [];

  // Always show page 1
  range.push(1);

  // Show pages around current page
  for (
    let i = Math.max(2, current - delta);
    i <= Math.min(lastPage - 1, current + delta);
    i++
  ) {
    range.push(i);
  }

  // Always show last page
  if (lastPage !== 1) {
    range.push(lastPage);
  }

  return range.filter((page, index, self) => self.indexOf(page) === index); // Remove duplicates
});

// ✅ Computed for total pages check
const hasPagination = computed(() => props.menu_items?.last_page > 1);

const filters = ref({
  search: props.filters?.search || "",
  status: props.filters?.status || "",
  stock: props.filters?.stock || "",
  perPage: Number(props.filters?.perPage) || 10,
});

// Debounced apply filters
const applyFilters = debounce(() => {
  router.get(route("menu.menu-items.index"), filters.value, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 300);

watch(
  filters,
  () => {
    applyFilters();
  },
  { deep: true }
);

// Reset Filters
const resetFilters = () => {
  filters.value = { search: "", status: "", stock: "", perPage: 10 };
};

// ====================== CRUD Actions ======================
const deleteForm = useForm({ _method: "delete" });
const statusForm = useForm({ _method: "patch", status: null });
const stockForm = useForm({ _method: "patch", is_in_stock: null });

const toggleStatus = (item, newStatus) => {
  statusForm.status = newStatus;
  statusForm.post(route("menu.menu-items.update", item.id), {
    preserveScroll: true,
    onSuccess: () =>
      router.reload({ only: ["menu_items"], preserveScroll: true }),
    onError: (errors) => {
      console.error("Status update failed:", errors);
      alert("Failed to update status");
    },
  });

  if (newStatus === "inactive") toggleStock(item, false);
};

const toggleStock = (item, newStock) => {
  stockForm.is_in_stock = newStock ? 1 : 0;
  stockForm.post(route("menu.menu-items.update", item.id), {
    preserveScroll: true,
    onSuccess: () =>
      router.reload({ only: ["menu_items"], preserveScroll: true }),
    onError: (errors) => {
      console.error("Stock update failed:", errors);
      alert("Failed to update stock status");
    },
  });
};

const deleteItem = (item) => {
  if (!confirm("Are you sure you want to delete this item?")) return;
  deleteForm.delete(route("menu.menu-items.destroy", item.id));
};

// Page change handler
const backwardPage = (page) => {
  if (props.menu_items.current_page === 1) {
    return;
  }

  router.get(
    route("menu.menu-items.index"),
    { ...filters.value, page },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
};

const forwardPage = (page) => {
  if (props.menu_items.current_page >= props.menu_items.last_page) {
    return;
  }

  router.get(
    route("menu.menu-items.index"),
    { ...filters.value, page },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
};
</script>

<template>
  <Head>
    <title>{{ app.title }}</title>
  </Head>

  <CContainer class="border rounded-3 shadow-lg mt-4 p-4">
    <!-- Filters -->
    <div class="d-flex justify-center mb-5">
      <div class="col-6">
        <CInputGroup>
          <CFormInput
            v-model="filters.search"
            placeholder="Search by name or description..."
          />
        </CInputGroup>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <CButton color="secondary" @click="resetFilters">Reset Filters</CButton>

      <div class="d-flex gap-2">
        <CDropdown>
          <CDropdownToggle color="secondary">
            {{
              filters.stock
                ? filters.stock
                    .replace(/_/g, " ")
                    .replace(/\b\w/g, (l) => l.toUpperCase())
                : "All Stock"
            }}
          </CDropdownToggle>
          <CDropdownMenu>
            <CDropdownItem
              :active="filters.stock === ''"
              @click="filters.stock = ''"
              >All Stock</CDropdownItem
            >
            <CDropdownItem
              :active="filters.stock === 'in_stock'"
              @click="filters.stock = 'in_stock'"
              >In Stock</CDropdownItem
            >
            <CDropdownItem
              :active="filters.stock === 'out_of_stock'"
              @click="filters.stock = 'out_of_stock'"
              >Out of Stock</CDropdownItem
            >
          </CDropdownMenu>
        </CDropdown>
        <CDropdown>
          <CDropdownToggle color="secondary">
            {{ filters.status ? $capitalize(filters.status) : "All Status" }}
          </CDropdownToggle>
          <CDropdownMenu>
            <CDropdownItem
              :active="filters.status === ''"
              @click="filters.status = ''"
              >All Status</CDropdownItem
            >
            <CDropdownItem
              :active="filters.status === 'active'"
              @click="filters.status = 'active'"
              >Active</CDropdownItem
            >
            <CDropdownItem
              :active="filters.status === 'inactive'"
              @click="filters.status = 'inactive'"
              >Inactive</CDropdownItem
            >
            <CDropdownItem
              :active="filters.status === 'draft'"
              @click="filters.status = 'draft'"
              >Draft</CDropdownItem
            >
          </CDropdownMenu>
        </CDropdown>
        <Link href="/menu/menu-items/create">
          <CButton color="primary" class="text-white"
            >+ Create New Item</CButton
          >
        </Link>
      </div>
    </div>

    <!-- Table -->
    <CTable caption="top" hover bordered>
      <CTableCaption>List of Menu Items</CTableCaption>
      <CTableHead>
        <CTableRow>
          <CTableHeaderCell>#</CTableHeaderCell>
          <CTableHeaderCell>Item Name</CTableHeaderCell>
          <CTableHeaderCell>Status</CTableHeaderCell>
          <CTableHeaderCell>Image</CTableHeaderCell>
          <CTableHeaderCell>Category</CTableHeaderCell>
          <CTableHeaderCell>In Stock</CTableHeaderCell>
          <CTableHeaderCell width="180">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>
      <CTableBody>
        <CTableRow v-for="(item, index) in menuItems" :key="item.id">
          <CTableHeaderCell>
            {{
              (props.menu_items?.current_page - 1) *
                props.menu_items?.per_page +
              index +
              1
            }}
          </CTableHeaderCell>
          <CTableDataCell class="fw-medium">{{
            item.item_name
          }}</CTableDataCell>

          <!-- Status -->
          <CTableDataCell>
            <CDropdown>
              <CFormSwitch
                size="lg"
                :modelValue="item.status === 'active'"
                @update:modelValue="
                  (val) => toggleStatus(item, val ? 'active' : 'inactive')
                "
              />
              <CDropdownMenu>
                <CDropdownItem
                  @click="toggleStatus(item, 'active')"
                  :active="item.status === 'active'"
                  >Active</CDropdownItem
                >
                <CDropdownItem
                  @click="toggleStatus(item, 'inactive')"
                  :active="item.status === 'inactive'"
                  >Inactive</CDropdownItem
                >
              </CDropdownMenu>
            </CDropdown>
          </CTableDataCell>

          <!-- Image -->
          <CTableDataCell>
            <CImage
              v-if="item.image"
              :src="`/storage/${item.image}`"
              width="50"
              style="border-radius: 6px; object-fit: cover"
            />
            <span v-else class="text-muted small">No image</span>
          </CTableDataCell>

          <CTableDataCell>{{
            item.menu_item_category?.name || "—"
          }}</CTableDataCell>

          <!-- Stock -->
          <CTableDataCell>
            <CDropdown>
              <CDropdownToggle
                :color="item.is_in_stock ? 'success' : 'danger'"
                class="text-white"
                size="sm"
                caret
              >
                {{ item.is_in_stock ? "In Stock" : "Out of Stock" }}
              </CDropdownToggle>
              <CDropdownMenu>
                <CDropdownItem
                  @click="toggleStock(item, true)"
                  :active="item.is_in_stock === true"
                  >In Stock</CDropdownItem
                >
                <CDropdownItem
                  @click="toggleStock(item, false)"
                  :active="item.is_in_stock === false"
                  >Out of Stock</CDropdownItem
                >
              </CDropdownMenu>
            </CDropdown>
          </CTableDataCell>

          <!-- Actions -->
          <CTableDataCell>
            <div class="d-flex gap-2">
              <Link :href="route('menu.menu-items.edit', item.id)">
                <CButton color="secondary" size="sm" variant="outline"
                  ><CIcon name="cil-pencil"
                /></CButton>
              </Link>
              <CButton
                color="danger"
                size="sm"
                variant="outline"
                @click="deleteItem(item)"
                ><CIcon name="cil-trash"
              /></CButton>
              <Link :href="route('menu.menu-items.show', item.id)">
                <CButton color="info" size="sm" variant="outline">Show</CButton>
              </Link>
            </div>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>

    <!-- Pagination -->
    <!-- Pagination -->
    <div
      v-if="hasPagination"
      class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3"
    >
      <div class="text-muted small">
        Showing {{ props.menu_items.from }} to {{ props.menu_items.to }} of
        {{ props.menu_items.total }} entries
      </div>

      <div class="d-flex align-items-center gap-3">
        <!-- ✅ Manual Pagination like your example -->
        <CPagination aria-label="Page navigation example">
          <!-- Previous Button -->
          <CPaginationItem
            :disabled="props.menu_items.current_page === 1"
            @click="backwardPage(props.menu_items.current_page - 1)"
          >
            Previous
          </CPaginationItem>

          <!-- Next Button -->
          <CPaginationItem
            :disabled="
              props.menu_items.current_page === props.menu_items.last_page
            "
            @click="forwardPage(props.menu_items.current_page + 1)"
          >
            Next
          </CPaginationItem>
        </CPagination>

        <!-- Per page selector -->
        <div class="d-flex align-items-center gap-2">
          Items:
          <CFormSelect v-model="filters.perPage">
            <option :value="10">10 per page</option>
            <option :value="15">15 per page</option>
            <option :value="25">25 per page</option>
            <option :value="50">50 per page</option>
          </CFormSelect>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="menuItems.length === 0" class="text-center py-5 text-muted">
      No menu items.
    </div>
  </CContainer>
</template>