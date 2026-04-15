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
        <div
          class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3"
        >
          <h2 class="mb-0 fw-semibold">Create New Order</h2>

          <CInputGroup style="max-width: 320px">
            <CFormInput
              v-model="searchTerm"
              placeholder="Search menu items..."
              class="ps-4"
            />
            <div class="input-group-text">
              <CIcon :icon="cilSearch" />
            </div>
          </CInputGroup>
        </div>

        <!-- Categories -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
          <CButton
            v-for="category in categories"
            :key="category.id"
            :color="activeCategoryId === category.id ? 'primary' : 'light'"
            size="sm"
            class="px-4 py-2 fw-medium"
            @click="activeCategoryId = category.id"
          >
            {{ category.name }}
          </CButton>
        </div>

        <!-- Menu Items -->
        <CRow class="g-4">
          <CCol
            v-for="item in filteredMenuItems"
            :key="item.id"
            sm="6"
            md="4"
            lg="3"
          >
            <CCard
              class="h-100 menu-card shadow-lg border-0 overflow-hidden"
              style="cursor: pointer; transition: all 0.2s ease"
              @click="addToCart(item)"
            >
              <div class="position-relative">
                <CImage
                  v-if="item.image"
                  :src="`/storage/${item.image}`"
                  class="card-img-top"
                  style="height: 180px; object-fit: cover"
                />
                <div
                  v-else
                  class="bg-light d-flex align-items-center justify-content-center"
                  style="height: 180px"
                >
                  <span class="text-muted">No Image</span>
                </div>

                <CBadge
                  v-if="!item.is_in_stock"
                  color="danger"
                  class="position-absolute top-2 end-2"
                >
                  Out of Stock
                </CBadge>
              </div>

              <CCardBody class="text-center pt-3 pb-4">
                <h5 class="fw-semibold mb-1">{{ item.item_name }}</h5>
                <p
                  v-if="item.description"
                  class="text-muted small mb-3 line-clamp-2"
                >
                  {{ item.description }}
                </p>
                <div class="text-success fw-bold fs-5 mb-3">
                  Rs. {{ parseFloat(item.price).toFixed(2) }}
                </div>

                <CButton
                  color="primary"
                  size="sm"
                  variant="outline"
                  class="w-100"
                >
                  <CIcon :icon="cilPlus" class="me-1" /> Add to Order
                </CButton>
              </CCardBody>
            </CCard>
          </CCol>

          <!-- Empty state for current category -->
          <CCol v-if="filteredMenuItems.length === 0" cols="12">
            <div class="text-center py-5 text-muted">
              <p class="fs-5 mb-1">No available items in this category</p>
              <small v-if="searchTerm">Try clearing the search term</small>
            </div>
          </CCol>
        </CRow>
      </CCol>

      <!-- CART SECTION -->
      <CCol lg="4">
        <CCard class="shadow" style="top: 20px">
          <CCardHeader class="border-0 py-3">
            <strong class="fs-5">Current Order</strong>
          </CCardHeader>

          <CCardBody style="max-height: 65vh; overflow-y: auto" class="pe-3">
            <CFormSelect v-model="selectedTableId" class="mb-4">
              <option value="">Takeaway / No Table Assigned</option>
              <option v-for="table in tables" :key="table.id" :value="table.id">
                Table {{ table.table_number }} — {{ table.capacity }} seats
              </option>
            </CFormSelect>

            <CFormSelect v-model="selectedStaffId" class="mb-4" v-if="canAssign">
              <option value="">Assign Staff / No Staff Assigned</option>
              <option v-for="staff in props.staffs" :key="staff.id" :value="staff.id">
                 {{ staff.name }}
              </option>
            </CFormSelect>

            <div v-if="cart.length === 0" class="text-center text-muted py-5">
              <p>Your cart is empty</p>
              <small>Click items from the menu to add them</small>
            </div>

            <div v-else>
              <div
                v-for="(item, index) in cart"
                :key="index"
                class="mb-4 pb-4 border-bottom"
              >
                <div class="d-flex justify-content-between">
                  <div>
                    <strong>{{ item.item_name }}</strong>
                    <div class="text-muted small">
                      Rs. {{ item.item_price.toFixed(2) }}
                    </div>
                  </div>
                  <CButton
                    color="danger"
                    variant="ghost"
                    size="sm"
                    @click="removeItem(index)"
                  >
                    <CIcon :icon="cilTrash" />
                  </CButton>
                </div>

                <div class="d-flex align-items-center mt-3">
                  <CButton size="sm" @click="decreaseQty(index)">−</CButton>
                  <span class="mx-4 fw-medium">{{ item.quantity }}</span>
                  <CButton size="sm" @click="increaseQty(index)">+</CButton>

                  <div class="ms-auto text-success fw-bold">
                    Rs. {{ (item.item_price * item.quantity).toFixed(2) }}
                  </div>
                </div>
              </div>
            </div>
          </CCardBody>

          <CCardFooter class="border-0 pt-3">
            <div class="d-flex justify-content-between fs-5 mb-3">
              <strong>Total</strong>
              <strong class="text-primary"
                >Rs. {{ cartTotal.toFixed(2) }}</strong
              >
            </div>

            <CButton
              color="success"
              class="w-100 py-3 fw-semibold"
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

<style scoped>
.menu-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12) !important;
}
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>