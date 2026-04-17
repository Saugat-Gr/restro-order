<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

import {
  CContainer,
  CCard,
  CCardBody,
  CRow,
  CCol,
  CAvatar,
  CButton,
  CBadge,
  CCardHeader,
  CCardFooter,
  CListGroup,
  CListGroupItem,
} from "@coreui/vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  show_restaurant: Object,
});

const restaurant = props.show_restaurant;
</script>

<template>
  <Head>
    <title>{{ restaurant?.name || "Restaurant Details" }}</title>
  </Head>

  <CContainer class="mt-5">
    <CCard class="shadow-lg border-0 rounded-3 overflow-hidden">
      <!-- Header with Back Button -->
      <CCardHeader class="d-flex justify-content-between align-items-center bg-light">
        <h5 class="mb-0">Restaurant Details</h5>
        <Link :href="route('restaurants.index')">
          <CButton color="primary" variant="outline" size="sm">
            <i class="bi bi-arrow-left me-2" />
            Back
          </CButton>
        </Link>
      </CCardHeader>

      <CCardBody>
        <!-- Top Section: Avatar + Name -->
        <div class="d-flex align-items-center gap-4 mb-5">
          <CAvatar
            v-if="restaurant.logo"
            :src="`/storage/${restaurant.logo}`"
            size="2xl"
            shape="rounded"
          />
          <CAvatar v-else color="secondary" text-color="white" size="2xl" shape="rounded">
            {{ restaurant.name?.charAt(0) }}
          </CAvatar>

          <div>
            <h3 class="fw-bold mb-1">{{ restaurant.name }}</h3>
            <small class="text-medium-emphasis">Managed by: {{ restaurant.user.name || "N/A" }}</small>
          </div>
        </div>

        <!-- Info Grid -->
        <CRow class="g-4">
          <CCol md="6">
            <CListGroup flush>
              <CListGroupItem class="d-flex justify-content-between align-items-center">
                <span>Email</span>
                <span class="fw-semibold">{{ restaurant.email || "-" }}</span>
              </CListGroupItem>
              <CListGroupItem class="d-flex justify-content-between align-items-center">
                <span>Phone</span>
                <span class="fw-semibold">{{ restaurant.phone || "-" }}</span>
              </CListGroupItem>
            </CListGroup>
          </CCol>

          <CCol md="6">
            <CListGroup flush>
              <CListGroupItem class="d-flex justify-content-between align-items-center">
                <span>Status</span>
                <CBadge
                  :color="restaurant.status === 'active' ? 'success' : 'danger'"
                  shape="rounded-pill"
                  class="py-2 px-3"
                >
                  {{ restaurant.status.toUpperCase() }}
                </CBadge>
              </CListGroupItem>
              <CListGroupItem class="d-flex justify-content-between align-items-center">
                <span>Owner</span>
                <span class="fw-semibold">{{ restaurant.user.name || "Not Assigned" }}</span>
              </CListGroupItem>
            </CListGroup>
          </CCol>

          <CCol md="12">
            <CListGroup flush>
              <CListGroupItem>
                <div class="fw-semibold mb-1">Address</div>
                <small class="text-medium-emphasis">{{ restaurant.address || "-" }}</small>
              </CListGroupItem>
            </CListGroup>
          </CCol>
        </CRow>
      </CCardBody>
    </CCard>
  </CContainer>
</template>