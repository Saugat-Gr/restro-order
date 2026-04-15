<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
  CContainer,
  CRow,
  CCol,
  CCard,
  CCardBody,
  CCardHeader,
  CButton,
  CBadge,
} from "@coreui/vue";
import { usePage } from "@inertiajs/vue3";
import { formatCurrency } from "@/utils/format";
defineOptions({
  layout: AuthenticatedLayout,
});

const page = usePage();
const menu_item = page.props.menu_item;

</script>

<template>
  <CContainer class="py-5 d-flex justify-content-center">
    <CCard class="shadow-lg" style="max-width: 700px; width: 100%">
      <CRow class="g-0">
        <CCol md="5">
          <img
            :src="`/storage/${menu_item.image}`"
            class="img-fluid rounded-start"
            :alt="menu_item.item_name"
          />
        </CCol>
        <CCol md="7">
          <CCardBody class="d-flex flex-column justify-content-between h-100">
            <div>
              <h3 class="fw-bold">{{ menu_item.item_name }}</h3>
              <p class="text-muted mb-2">{{ menu_item.description }}</p>
              <div class="mb-3">
                <CBadge color="info" class="me-2">{{
                  menu_item.category
                }}</CBadge>
                <CBadge
                  :color="menu_item.status === 'active' ? 'success' : 'danger'"
                >
                  {{ menu_item.is_in_stock ? "Available" : "Un-Available" }}
                </CBadge>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <span class="fs-4 fw-bold">{{ formatCurrency(menu_item.price) }}</span>
              <Link :href="route('menu.menu-items.index')">
                <CButton color="primary" size="lg">Go BACK </CButton>
              </Link>
            </div>
          </CCardBody>
        </CCol>
      </CRow>
    </CCard>
  </CContainer>
</template>

<style scoped>
img.img-fluid {
  height: 100%;
  object-fit: cover;
}
CCard {
  border-radius: 12px;
  transition: transform 0.3s, box-shadow 0.3s;
}
CCard:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}
</style>