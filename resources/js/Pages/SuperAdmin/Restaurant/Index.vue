<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { computed, watch } from "vue";
import debounce from "lodash/debounce";

import {
  CContainer,
  CTable,
  CTableBody,
  CTableHead,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
  CAvatar,
  CButton,
  CBadge,
  CCard,
  CCardBody,
  CFormSelect,
} from "@coreui/vue";

import CIcon from "@coreui/icons-vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  restaurants: Array,
  filters: Object,
  app: Object,
});

const restaurantsList = computed(() => props.restaurants ?? []);

const form = useForm({
  status: "",
  _method: "patch",
});

const filterRestaurant = useForm({
  status: props.filters?.status ?? "all",
});

const toggleRestaurantStatus = (restaurant) => {
  const newStatus =
    restaurant.status === "active" ? "inactive" : "active";

  form.status = newStatus;

  form.patch(route("restaurants.update", restaurant.id), {
    preserveScroll: true,
    onSuccess: () => {
      restaurant.status = newStatus;
    },
  });
};

const filterData = debounce(() => {
  filterRestaurant.get(route("restaurants.index"), {
    preserveState: true,
    replace: true,
  });
}, 300);

watch(
  () => filterRestaurant.status,
  () => {
    filterData();
  }
);

const showRestaurant = (restaurant) => {
  router.visit(route("restaurants.show", restaurant));
};

const deleteRestaurant = (id) => {
  if (confirm("Are you sure you want to delete this restaurant?")) {
    router.delete(route("restaurants.destroy", id), {
      preserveScroll: true,
    });
  }
};
</script>

<template>
  <Head>
    <title>{{ app?.title || "Restaurants" }}</title>
  </Head>

  <CContainer class="mt-4">
    <CCard class="shadow-lg border-0 rounded-4 p-4">
      <CCardBody>

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-0">Restaurants</h4>
            <small class="text-medium-emphasis">
              Manage all restaurants
            </small>
          </div>

          <Link :href="route('owners.create')">
            <CButton color="primary">
              <CIcon name="cil-plus" class="me-2" />
              Create Restaurant
            </CButton>
          </Link>
        </div>

        <!-- FILTER -->
        <div class="d-flex gap-3 mb-4">
          <CFormSelect v-model="filterRestaurant.status" class="w-auto">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </CFormSelect>
        </div>

        <!-- TABLE -->
        <CTable hover responsive align="middle" class="mb-0">
          <CTableHead>
            <CTableRow class="text-medium-emphasis">
              <CTableHeaderCell>#</CTableHeaderCell>
              <CTableHeaderCell>Restaurant</CTableHeaderCell>
              <CTableHeaderCell>Owner</CTableHeaderCell>
              <CTableHeaderCell>Address</CTableHeaderCell>
              <CTableHeaderCell>Status</CTableHeaderCell>
              <CTableHeaderCell class="text-end">Actions</CTableHeaderCell>
            </CTableRow>
          </CTableHead>

          <CTableBody>
            <CTableRow
              v-for="(restaurant, index) in restaurantsList"
              :key="restaurant.id"
            >
              <!-- Index -->
              <CTableDataCell>
                {{ index + 1 }}
              </CTableDataCell>

              <!-- Restaurant -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">
                  <CAvatar
                    v-if="restaurant.logo"
                    :src="`/storage/${restaurant.logo}`"
                  />
                  <CAvatar v-else color="secondary" text-color="white">
                    {{ restaurant.name.charAt(0) }}
                  </CAvatar>

                  <div>
                    <div class="fw-semibold">
                      {{ restaurant.name }}
                    </div>

                  </div>
                </div>
              </CTableDataCell>

              <!-- Owner -->
              <CTableDataCell>
                {{ restaurant.owner_name || "Not Assigned" }}
              </CTableDataCell>

              <!-- Address -->
              <CTableDataCell class="text-medium-emphasis">
                {{ restaurant.address }}
              </CTableDataCell>

              <!-- Status -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">
        

                  <!-- SAME SWITCH -->
                  <label class="switch mb-0">
                    <input
                      type="checkbox"
                      :checked="restaurant.status === 'active'"
                      @change="() => toggleRestaurantStatus(restaurant)"
                    />
                    <span class="slider"></span>
                  </label>
                </div>
              </CTableDataCell>

              <!-- Actions -->
              <CTableDataCell class="text-end">
                <CButton
                  color="info"
                  variant="outline"
                  size="sm"
                  class="me-2"
                  @click="showRestaurant(restaurant)"
                >
                  <i class="bi bi-eye"></i>
                </CButton>

                <CButton
                  color="danger"
                  variant="outline"
                  size="sm"
                  @click="deleteRestaurant(restaurant.id)"
                >
                  <i class="bi bi-trash"></i>
                </CButton>
              </CTableDataCell>
            </CTableRow>
          </CTableBody>
        </CTable>

        <!-- EMPTY -->
        <div
          v-if="restaurantsList.length === 0"
          class="text-center py-5 d-flex justify-content-center align-items-center gap-2"
        >
          <CIcon
            name="cil-restaurant"
            size="xl"
            class="text-medium-emphasis"
          />
          <h6 class="text-medium-emphasis">
            No restaurants found
          </h6>
        </div>

      </CCardBody>
    </CCard>
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
  background-color: #321fdb;
}

input:checked + .slider::before {
  transform: translateX(22px);
}
</style>