<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
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
} from "@coreui/vue";
import CIcon from "@coreui/icons-vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  menu_items: Array,
  app: Object,
});

const deleteForm = useForm({ _method: "delete" });

// Create forms with initial empty data + _method
const statusForm = useForm({
  _method: "patch",
  status: null, // placeholder
});

const stockForm = useForm({
  _method: "patch",
  is_in_stock: null,
});

const toggleStatus = (item, newStatus) => {
  statusForm.status = newStatus; // Set the value first

  statusForm.post(route("menu.menu-items.update", item.id), {
    preserveScroll: true,
    onSuccess: () => {
      router.reload({ only: ["menu_items"], preserveScroll: true });
    },
    onError: (errors) => {
      console.error("Status update failed:", errors);
      alert("Failed to update status");
    },
  });

  if (newStatus === "inactive") {
    toggleStock(item, 0);
  } else {
    toggleStock(item, 1);
  }
};

const toggleStock = (item, newStock) => {
  stockForm.is_in_stock = newStock ? 1 : 0;

  stockForm.post(route("menu.menu-items.update", item.id), {
    preserveScroll: true,
    onSuccess: () => {
      router.reload({ only: ["menu_items"], preserveScroll: true });
    },
    onError: (errors) => {
      console.error("Stock update failed:", errors);
      alert("Failed to update stock status");
    },
  });
};

const deleteItem = (item) => {
  if (!confirm("Delete this item?")) return;
  deleteForm.delete(route("menu.menu-items.destroy", item.id));
};
</script>

<template>
  <Head>
    <title>{{ app.title }}</title>
  </Head>

  <CContainer class="border rounded-3 shadow-lg mt-4 p-4">
    <Link href="/menu/menu-items/create">
      <CButton color="primary" class="float-end text-white mb-3">
        + Create New Item
      </CButton>
    </Link>

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
        <CTableRow v-for="(item, index) in props.menu_items" :key="item.id">
          <CTableHeaderCell>{{ index + 1 }}</CTableHeaderCell>
          <CTableDataCell class="fw-medium">{{
            item.item_name
          }}</CTableDataCell>

          <!-- Status Dropdown -->
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
                  class="hover:cursor-pointer"
                  @click="toggleStatus(item, 'active')"
                  :active="item.status === 'active'"
                >
                  Active
                </CDropdownItem>
                <CDropdownItem
                  class="hover:cursor-pointer"
                  @click="toggleStatus(item, 'inactive')"
                  :active="item.status === 'inactive'"
                >
                  Inactive
                </CDropdownItem>
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

          <!-- In Stock Dropdown -->
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
                  class="hover:cursor-pointer"
                  @click="toggleStock(item, true)"
                  :active="item.is_in_stock === true"
                >
                  In Stock
                </CDropdownItem>
                <CDropdownItem
                  class="hover:cursor-pointer"
                  @click="toggleStock(item, false)"
                  :active="item.is_in_stock === false"
                >
                  Out of Stock
                </CDropdownItem>
              </CDropdownMenu>
            </CDropdown>
          </CTableDataCell>

          <CTableDataCell>
            <div class="d-flex gap-2">
              <Link :href="route('menu.menu-items.edit', item.id)">
                <CButton color="secondary" size="sm" variant="outline">
                  <CIcon name="cil-pencil" />
                </CButton>
              </Link>

              <CButton
                color="danger"
                size="sm"
                variant="outline"
                @click="deleteItem(item)"
              >
                <CIcon name="cil-trash" />
              </CButton>

              <Link :href="route('menu.menu-items.show', item.id)">
                <CButton color="info" size="sm" variant="outline">Show</CButton>
              </Link>
            </div>
          </CTableDataCell>
        </CTableRow>
      </CTableBody>
    </CTable>
  </CContainer>
</template>

<style scoped>
.form-switch .form-check-input {
  width: 2.5em;
  height: 1.3em;
}

.form-switch .form-check-input:checked {
  background-position: right center;
}
</style>