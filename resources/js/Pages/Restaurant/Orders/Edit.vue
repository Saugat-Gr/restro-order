<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
  CContainer,
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
  CCardFooter,
  CButton,
  CFormSelect,
  CInputGroup,
  CFormInput,
  CTable,
  CTableHead,
  CTableBody,
  CTableRow,
  CTableHeaderCell,
  CTableDataCell,
  CImage,
} from "@coreui/vue";
import CIcon from "@coreui/icons-vue";
import { cilSave, cilArrowLeft, cilPlus, cilTrash } from "@coreui/icons";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { removeUnderScore } from "@/utils/format";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  order: Object,
  tables: Array,
  categories: Array,
  order_statuses: Array,
});

console.log(props.order);


const categories = computed(() =>
  props.categories.map((cat) => ({
    ...cat,
    menuItems: cat.menu_items || [],
  }))
);

// Local state
const activeCategoryId = ref(categories.value?.[0]?.id || null);
const searchTerm = ref("");

const localItems = ref(
  props.order.order_items.map((item) => ({
    id: item.id,
    menu_item_id: item.menu_item_id,
    item_name: item.item_name,
    item_price: parseFloat(item.item_price),
    quantity: item.quantity,
  }))
);

// Form
const form = useForm({
  table_id: props.order.table_id,
  status: props.order.status,
  items: [],
});

// Filtered menu
const filteredMenuItems = computed(() => {
  if (!activeCategoryId.value) return [];

  const cat = categories.value.find((c) => c.id === activeCategoryId.value);
  if (!cat) return [];

  return cat.menuItems.filter((item) => {
    const matchesSearch =
      !searchTerm.value ||
      item.item_name.toLowerCase().includes(searchTerm.value.toLowerCase());

    // fallback-safe checks
    const inStock = item.is_in_stock ?? true;
    const active = item.status ? item.status === "active" : true;

    return matchesSearch && inStock && active;
  });
});

// Cart logic
const addToOrder = (menuItem) => {
  const existing = localItems.value.find((i) => i.menu_item_id === menuItem.id);

  if (existing) {
    existing.quantity++;
  } else {
    localItems.value.push({
      menu_item_id: menuItem.id,
      item_name: menuItem.item_name,
      item_price: parseFloat(menuItem.price),
      quantity: 1,
    });
  }
};

const increaseQty = (index) => localItems.value[index].quantity++;

const decreaseQty = (index) => {
  if (localItems.value[index].quantity > 1) {
    localItems.value[index].quantity--;
  } else {
    localItems.value.splice(index, 1);
  }
};

const removeItem = (index) => localItems.value.splice(index, 1);

// Total
const orderTotal = computed(() =>
  localItems.value.reduce(
    (sum, item) => sum + item.item_price * item.quantity,
    0
  )
);

// Save
const saveOrder = () => {
  form.items = localItems.value.map((item) => ({
    menu_item_id: item.menu_item_id,
    quantity: item.quantity,
  }));

  form.put(route("orders.update", props.order.id), {
    preserveScroll: true,
  });
};
</script>


<template>
  <CContainer fluid class="mt-4">
    <CRow class="mb-4">
      <CCol class="d-flex justify-content-between align-items-center">
        <h2 class="fw-semibold">Edit Order #{{ props.order.order_number }}</h2>
        <CButton color="primary" :href="route('orders.index')">
          <CIcon :icon="cilArrowLeft" class="me-2" /> Back to Orders
        </CButton>
      </CCol>
    </CRow>

    <CRow class="g-4">
      <!-- Sidebar -->
      <CCol lg="5">
        <CCard class="shadow-sm mb-4">
          <CCardHeader class="bg-light">
            <strong>Order Information</strong>
          </CCardHeader>

          <CCardBody>
            <div class="mb-3">
              <label class="form-label">Table</label>
              <CFormSelect v-model="form.table_id">
                <option value="">Takeaway / No Table</option>
                <option
                  v-for="table in tables"
                  :key="table.id"
                  :value="table.id"
                >
                  Table {{ table.table_number }} — {{ table.capacity }} seats
                </option>
              </CFormSelect>
            </div>

            <div class="mb-4">
              <label class="form-label">Status</label>
              <CFormSelect v-model="form.status">
                <option
                  v-for="status in order_statuses"
                  :key="status"
                  :value="status"
                >
                  {{ $capitalize(removeUnderScore(status)) }}
                </option>
              </CFormSelect>
            </div>
          </CCardBody>
        </CCard>

        <CCard class="shadow-sm">
          <CCardHeader class="bg-light">
            <strong>Add Items to Order</strong>
          </CCardHeader>

          <CCardBody>
            <CInputGroup class="mb-3">
              <CFormInput v-model="searchTerm" placeholder="Search menu..." />
            </CInputGroup>

            <div class="d-flex gap-2 mb-3 flex-wrap">
              <CButton
                v-for="cat in categories"
                :key="cat.id"
                :color="activeCategoryId === cat.id ? 'primary' : 'secondary'"
                variant="outline"
                size="sm"
                @click="activeCategoryId = cat.id"
              >
                {{ cat.name }}
              </CButton>
            </div>

            <div style="max-height: 400px; overflow-y: auto">
              <div
                v-for="item in filteredMenuItems"
                :key="item.id"
                class="d-flex justify-content-between align-items-center p-2 border-bottom"
                style="cursor: pointer"
                @click="addToOrder(item)"
              >
                <div class="flex gap-2">
                  <CImage :src="`/storage/${item.image}`" width="50px"></CImage>
                  <div class="flex row">
                    <strong>{{ $capitalize(item.item_name) }}</strong>
                    <div class="text-success small">
                      Rs. {{ parseFloat(item.price).toFixed(2) }}
                    </div>
                  </div>
                </div>

                <CButton color="primary" size="sm" variant="outline">
                  <CIcon :icon="cilPlus" />
                </CButton>
              </div>
            </div>
          </CCardBody>
        </CCard>
      </CCol>

      <!-- Order Items -->
      <CCol lg="7">
        <CCard class="shadow-sm">
          <CCardHeader class="bg-light d-flex justify-content-between">
            <strong>Items ({{ localItems.length }})</strong>
            <strong class="text-success">
              Rs. {{ orderTotal.toFixed(2) }}
            </strong>
          </CCardHeader>

          <CCardBody class="p-0">
            <CTable hover bordered responsive class="mb-0">
              <CTableHead>
                <CTableRow>
                  <CTableHeaderCell>Item</CTableHeaderCell>
                  <CTableHeaderCell class="text-end">Price</CTableHeaderCell>
                  <CTableHeaderCell class="text-center">Qty</CTableHeaderCell>
                  <CTableHeaderCell class="text-end">Subtotal</CTableHeaderCell>
                  <CTableHeaderCell />
                </CTableRow>
              </CTableHead>

              <CTableBody>
                <CTableRow v-for="(item, index) in localItems" :key="index">
                  <CTableDataCell>{{ item.item_name }}</CTableDataCell>

                  <CTableDataCell class="text-end">
                    Rs. {{ item.item_price.toFixed(2) }}
                  </CTableDataCell>

                  <CTableDataCell class="text-center">
                    <CButton size="sm" @click="decreaseQty(index)">−</CButton>
                    <span class="px-2">{{ item.quantity }}</span>
                    <CButton size="sm" @click="increaseQty(index)">+</CButton>
                  </CTableDataCell>

                  <CTableDataCell class="text-end">
                    Rs. {{ (item.item_price * item.quantity).toFixed(2) }}
                  </CTableDataCell>

                  <CTableDataCell>
                    <CButton
                      size="sm"
                      color="danger"
                      class="text-white"
                      @click="removeItem(index)"
                    >
                      <CIcon :icon="cilTrash" />
                    </CButton>
                  </CTableDataCell>
                </CTableRow>

                <CTableRow v-if="localItems.length === 0">
                  <CTableDataCell colspan="5" class="text-center py-4">
                    No items added
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
            </CTable>
          </CCardBody>

          <CCardFooter>
            <CButton
              color="primary"
              class="w-100"
              :disabled="form.processing || localItems.length === 0"
              @click="saveOrder"
            >
              <CIcon :icon="cilSave" class="me-2" />
              {{ form.processing ? "Saving..." : "Save Changes" }}
            </CButton>
          </CCardFooter>
        </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>