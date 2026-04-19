<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { watch } from "vue";

import { CContainer, CRow, CCol, CCard, CCardBody, CButton } from "@coreui/vue";

const page = usePage();
const user = page.props.auth.user;

const link =
  user?.role === "staff"
    ? "/orders/create"
    : user?.role === "owner"
    ? "/dashboard"
    : "/super-admin/dashboard";

watch(
  () => page.props.flash,
  (flash) => {
    if (flash?.success) toastr.success(flash.success);
    if (flash?.error) toastr.error(flash.error);
  },
  { immediate: true }
);

const features = [
  { icon: "🍽️", title: "Tables", desc: "Live table tracking" },
  { icon: "🧾", title: "Orders", desc: "Fast order processing" },
  { icon: "📊", title: "Reports", desc: "Sales insights" },
  { icon: "👨‍🍳", title: "Staff", desc: "Role management" },
];
</script>

<template>
  <Head title="Restaurant System" />

  <div class="min-vh-100 d-flex flex-column bg-body">
    <!-- HEADER -->
    <header class="border-bottom bg-body">
      <CContainer
        class="d-flex justify-content-between align-items-center py-3"
      >
        <div class="fw-bold fs-5 text-primary">RestoSystem</div>

        <div>
          <Link v-if="user" :href="link">
            <CButton color="primary" size="sm">Dashboard</CButton>
          </Link>

          <Link v-else :href="route('login')">
            <CButton color="secondary" size="sm">Login</CButton>
          </Link>
        </div>
      </CContainer>
    </header>

    <!-- HERO -->
    <section class="flex-grow-1 d-flex align-items-center py-5">
      <CContainer>
        <CRow class="align-items-center g-5">
          <!-- TEXT -->
          <CCol lg="6">
            <h1 class="fw-bold display-5">
              Restaurant Management<br />
              <span class="text-primary">System</span>
            </h1>

            <p class="text-body-secondary mt-3 fs-6">
              A centralized platform to manage tables, orders, staff, and
              business performance in real time.
            </p>

            <div class="mt-4">
              <Link v-if="user" :href="link">
                <CButton color="primary"> Go to Dashboard </CButton>
              </Link>
            </div>
          </CCol>

          <!-- VISUAL CARD (IMPORTANT FIX) -->
          <CCol lg="6">
            <div class="hero-visual">
              <CCard class="border-0 shadow-sm bg-body-tertiary">
                <CCardBody class="p-5 text-center">
                  <div class="display-6 mb-2">🍽️</div>
                  <h5 class="fw-semibold">Live Dashboard Preview</h5>
                  <p class="text-body-secondary mb-0">
                    Orders • Tables • Analytics • Staff
                  </p>
                </CCardBody>
              </CCard>
            </div>
          </CCol>
        </CRow>
      </CContainer>
    </section>

    <!-- FEATURES -->
    <section class="py-5 bg-body-tertiary">
      <CContainer>
        <h4 class="text-center fw-bold mb-4">Core Features</h4>

        <CRow class="g-3">
          <CCol v-for="(f, i) in features" :key="i" md="3">
            <CCard class="border-0 shadow-sm bg-body h-100 feature-card">
              <CCardBody class="text-center">
                <div class="fs-3 mb-2">{{ f.icon }}</div>
                <h6 class="fw-semibold">{{ f.title }}</h6>
                <p class="text-body-secondary small mb-0">{{ f.desc }}</p>
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </CContainer>
    </section>

    <!-- FOOTER -->
    <footer class="border-top bg-body py-3">
      <CContainer class="text-center text-body-secondary small">
        © {{ new Date().getFullYear() }} RestoSystem
      </CContainer>
    </footer>
  </div>
</template>

<style scoped>
h1,
h2,
h3,
h4 {
  letter-spacing: -0.02em;
}

/* subtle lift instead of flat UI */
.feature-card {
  transition: 0.2s ease;
}

.feature-card:hover {
  transform: translateY(-4px);
}

/* hero visual depth */
.hero-visual {
  position: relative;
}

.hero-visual::before {
  content: "";
  position: absolute;
  inset: -10px;
  background: var(--cui-primary);
  opacity: 0.08;
  border-radius: 16px;
  z-index: -1;
  filter: blur(20px);
}
</style>