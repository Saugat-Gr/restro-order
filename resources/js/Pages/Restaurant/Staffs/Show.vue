<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineProps } from "vue";
import {
  CContainer,
  CCard,
  CCardHeader,
  CCardBody,
  CRow,
  CCol,
  CButton,
  CBadge,
  CImage,
} from "@coreui/vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  staff: Object, 
});
</script>

<template>
  <CContainer class="py-4">
    <!-- HEADER -->
    <CCard class="shadow-lg border-0 rounded-4">
      <CCardHeader class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Staff Details</h4>
        <CButton :href="route('staffs.index')" color="secondary">Back</CButton>
      </CCardHeader>

      <CCardBody>
        <CRow class="mb-4">
          <CCol md="4" class="text-center">
            <!-- Avatar -->
            <CImage
              v-if="staff.avatar"
              :src="`/storage/${staff.avatar}`"
              rounded
              width="150"
              height="150"
              class="shadow-sm"
              style="object-fit: cover"
            />

            <div
              v-else
              class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm mx-auto"
              style="width: 150px; height: 150px; font-size: 3rem"
            >
              {{ staff.name.charAt(0).toUpperCase() }}
            </div>
          </CCol>

          <CCol md="8">
            <h3 class="fw-bold">{{ staff.name }}</h3>
            <p class="text-muted mb-1">ID: #{{ staff.id }}</p>
            <p class="text-muted mb-1">Email: {{ staff.email }}</p>
            <p class="text-muted mb-1">
              Restaurant:
              <span v-if="staff.restaurant">{{ staff.restaurant.name }}</span>
              <span v-else class="text-secondary">Not assigned</span>
            </p>

            <!-- STATUS -->
            <p class="mb-1">
              Status:
              <CBadge
                :color="staff.status === 'active' ? 'success' : 'secondary'"
              >
                {{ staff.status }}
              </CBadge>
            </p>

            <!-- JOIN DATE -->
            <p class="text-muted">
              Joined: {{ new Date(staff.created_at).toLocaleDateString() }}
            </p>
          </CCol>
        </CRow>
      </CCardBody>
    </CCard>
  </CContainer>
</template>