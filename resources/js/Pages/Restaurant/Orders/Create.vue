<script setup>
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
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
  CBadge,
  CImage,
} from "@coreui/vue";
import CIcon from "@coreui/icons-vue";
import { cilPlus, cilTrash, cilSearch } from "@coreui/icons";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { formatCurrency } from "@/utils/format";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  tables: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  },

  staffs:{
     type: Object,
  }
});


const page = usePage();
const user = page.props.auth.user;

const canAssign = user.permissions.includes("assign-to-table");

// State
const selectedTableId = ref(null);
const selectedStaffId = ref(null);

const activeCategoryId = ref(
  props.categories.find((cat) => cat.menu_items?.length)?.id ||
    props.categories?.[0]?.id ||
    null
);

const searchTerm = ref("");
const cart = ref([]);

// Filtered Menu Items
const filteredMenuItems = computed(() => {
  if (!activeCategoryId.value) return [];

  const activeCategory = props.categories.find(
    (cat) => cat.id === activeCategoryId.value
  );

  if (!activeCategory || !Array.isArray(activeCategory.menu_items)) {
    return [];
  }

  return activeCategory.menu_items.filter((item) => {
    const matchesSearch =
      !searchTerm.value ||
      item.item_name.toLowerCase().includes(searchTerm.value.toLowerCase());

    const isAvailable = item.is_in_stock === true && item.status === "active";

    return matchesSearch && isAvailable;
  });
});

// Cart Functions
const addToCart = (menuItem) => {
  if (!menuItem.is_in_stock || menuItem.status !== "active") return;

  const existingItem = cart.value.find(
    (item) => item.menu_item_id === menuItem.id
  );

  if (existingItem) {
    existingItem.quantity++;
  } else {
    cart.value.push({
      menu_item_id: menuItem.id,
      item_name: menuItem.item_name,
      item_price: parseFloat(menuItem.price),
      quantity: 1,
    });
  }
};

const increaseQty = (index) => {
  cart.value[index].quantity++;
};

const decreaseQty = (index) => {
  if (cart.value[index].quantity > 1) {
    cart.value[index].quantity--;
  } else {
    cart.value.splice(index, 1);
  }
};

const removeItem = (index) => {
  cart.value.splice(index, 1);
};

const cartTotal = computed(() =>
  cart.value.reduce((sum, item) => sum + item.item_price * item.quantity, 0)
);

// Order Form
const orderForm = useForm({
  table_id: null,
  staff_id: null,
  items: [],
});

const placeOrder = () => {
  if (cart.value.length === 0) return;

  orderForm.table_id = selectedTableId.value;
  orderForm.staff_id = selectedStaffId.value;
  orderForm.items = cart.value.map((item) => ({
    menu_item_id: item.menu_item_id,
    quantity: item.quantity,
  }));

  orderForm.post(route("orders.store"), {
    preserveScroll: true,
    onSuccess: () => {
      cart.value = [];
      selectedTableId.value = null;
      selectedStaffId.value = null;
    },
  });
};
</script>
<template>
  <CContainer fluid class="mt-4">
    <CRow class="g-4">
      <!-- MENU SECTION -->
      <CCol lg="8">
        <!-- Header -->
        <div class="mb-4">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
              <h2 class="mb-1 fw-semibold">Create New Order</h2>
              <div class="text-muted small">
                Select items from menu and build customer order
              </div>
            </div>

            <CInputGroup style="max-width: 320px">
              <span class="input-group-text ">
                <CIcon :icon="cilSearch" />
              </span>
              <CFormInput
                v-model="searchTerm"
                placeholder="Search menu items..."
              />
            </CInputGroup>
          </div>
        </div>

        <!-- Categories -->
        <div class="mb-4">
          <div class="d-flex gap-2 flex-wrap">
            <CButton
              v-for="category in categories"
              :key="category.id"
              :color="activeCategoryId === category.id ? 'primary' : 'light'"
              size="sm"
              class="px-3 py-2 fw-medium rounded-pill"
              @click="activeCategoryId = category.id"
            >
              {{ category.name }}
            </CButton>
          </div>
        </div>

        <!-- Menu Items -->
        <CRow class="g-3">
          <CCol
            v-for="item in filteredMenuItems"
            :key="item.id"
            sm="6"
            md="4"
            lg="3"
          >
            <CCard
              class="h-100 border-0 shadow-lg overflow-hidden"
              style="cursor: pointer;"
              @click="addToCart(item)"
            >
              <div class="position-relative">
                <CImage
                  v-if="item.image"
                  :src="`/storage/${item.image}`"
                  class="w-100"
                  style="height: 170px; object-fit: cover"
                />
                <div
                  v-else
                  class="bg-light d-flex align-items-center justify-content-center"
                  style="height: 170px"
                >
                  <span class="text-muted small">No Image</span>
                </div>

                <CBadge
                  v-if="!item.is_in_stock"
                  color="danger"
                  class="position-absolute top-0 end-0 m-2"
                >
                  Out of Stock
                </CBadge>
              </div>

              <CCardBody class="p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-semibold mb-0 text-truncate">
                    {{ item.item_name }}
                  </h6>
                </div>

                <p
                  v-if="item.description"
                  class="text-muted small mb-2"
                  style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                >
                  {{ item.description }}
                </p>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="text-success fw-semibold">
                    {{ formatCurrency(parseFloat(item.price).toFixed(2)) }}
                  </div>
                </div>

                <CButton
                  color="primary"
                  size="sm"
                  variant="outline"
                  class="w-100"
                >
                  <CIcon :icon="cilPlus" class="me-1" />
                  Add
                </CButton>
              </CCardBody>
            </CCard>
          </CCol>

          <!-- Empty state -->
          <CCol v-if="filteredMenuItems.length === 0" cols="12">
            <div class="text-center py-5 text-muted">
              <div class="mb-1 fw-medium">No items found</div>
              <small v-if="searchTerm">Try adjusting your search</small>
              <small v-else>Switch category to view items</small>
            </div>
          </CCol>
        </CRow>
      </CCol>

      <!-- CART SECTION -->
      <CCol lg="4">
        <CCard class="border-0 shadow-lg rounded-2 sticky-top z-1" style="top: 20px">
          <CCardHeader class="border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
              <strong class="fs-6 mb-0">Current Order</strong>
              <span class="text-muted small">{{ cart.length }} items</span>
            </div>
          </CCardHeader>

          <CCardBody class="p-3" style="max-height: 65vh; overflow-y: auto">
            <CFormSelect v-model="selectedTableId" class="mb-3">
              <option value="">Takeaway / No Table</option>
              <option v-for="table in tables" :key="table.id" :value="table.id">
                Table {{ table.table_number }} — {{ table.capacity }} seats
              </option>
            </CFormSelect>

            <CFormSelect
              v-model="selectedStaffId"
              class="mb-3"
              v-if="canAssign"
            >
              <option value="">Assign Staff (Optional)</option>
              <option v-for="staff in props.staffs" :key="staff.id" :value="staff.id">
                {{ staff.name }}
              </option>
            </CFormSelect>

            <div v-if="cart.length === 0" class="text-center text-muted py-5">
              <div class="mb-1">Cart is empty</div>
              <small>Add items from menu</small>
            </div>

            <div v-else class="d-flex flex-column gap-3">
              <div
                v-for="(item, index) in cart"
                :key="index"
                class="border rounded p-2"
              >
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div class="pe-2">
                    <div class="fw-semibold small">
                      {{ item.item_name }}
                    </div>
                    <div class="text-muted small">
                      {{ formatCurrency(item.item_price.toFixed(2)) }}
                    </div>
                  </div>

                  <CButton
                    color="danger"
                    variant="ghost"
                    size="sm"
                    class="p-1"
                    @click="removeItem(index)"
                  >
                    <CIcon :icon="cilTrash" />
                  </CButton>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                  <div class="btn-group btn-group-sm">
                    <CButton @click="decreaseQty(index)">−</CButton>
                    <CButton disabled class="px-3  text-dark border">
                      {{ item.quantity }}
                    </CButton>
                    <CButton @click="increaseQty(index)">+</CButton>
                  </div>

                  <div class="fw-semibold text-success small">
                    {{ formatCurrency((item.item_price * item.quantity).toFixed(2)) }}
                  </div>
                </div>
              </div>
            </div>
          </CCardBody>

          <CCardFooter class=" border-top p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="fw-semibold">Total</span>
              <span class="fw-bold text-primary fs-6">
                {{ formatCurrency(cartTotal.toFixed(2)) }}
              </span>
            </div>

            <CButton
              color="success"
              class="w-100 py-2 fw-semibold"
              :disabled="cart.length === 0"
              @click="placeOrder"
            >
              Place Order
            </CButton>
          </CCardFooter>
        </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>