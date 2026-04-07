<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import tabletImg from "@/assets/app-images/tablet.webp";
import { CContainer, CRow, CCol, CCard, CCardBody } from "@coreui/vue";
defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});

const features = [
  {
    icon: "🍽️",
    title: "Table Management",
    desc: "Real-time table status, reservations & seating arrangements.",
  },
  {
    icon: "📱",
    title: "POS Integration",
    desc: "Seamless POS sync with inventory & payment processing.",
  },
  {
    icon: "📊",
    title: "Analytics",
    desc: "Revenue insights, peak hours & customer analytics.",
  },
  {
    icon: "👥",
    title: "Staff Management",
    desc: "Shift scheduling, performance tracking & permissions.",
  },
];

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
  <Head title="Restaurant SaaS - Order Management System" />

  <div class="landing-hero">
    <!-- HEADER -->
    <header class="header-premium">
      <CContainer>
        <div class="header-content d-flex justify-content-between w-100">
          <div class="logo d-flex align-items-center gap-2">
            <!-- SVG stays same -->
            <span class="logo-text">RestoSaaS</span>
          </div>

          <div class="header-actions d-flex align-items-center gap-2">
            <Link v-if="user" :href="route('dashboard')">
              <CButton color="outline-primary">Dashboard</CButton>
            </Link>

            <template v-else>
              <Link :href="route('login')">
                <CButton color="grey">Sign In</CButton>
              </Link>
            </template>
          </div>
        </div>
      </CContainer>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section">
      <CContainer>
        <div class="hero-content">
          <div class="hero-text">
            <h1 class="hero-title">
              Premium Restaurant<br />
              <span class="gradient-text">Order Management</span>
            </h1>

            <p class="hero-subtitle">
              Streamline your restaurant operations with our SaaS platform.
            </p>

            <div class="hero-stats">
              <div class="stat">
                <div class="stat-number">10K+</div>
                <div class="stat-label">Restaurants</div>
              </div>
              <div class="stat">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">Uptime</div>
              </div>
              <div class="stat">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
              </div>
            </div>

            <div class="hero-buttons">
              <Link :href="route('register')">
                <CButton color="warning" class="p-2">
                  Start Free Trial
                </CButton>
              </Link>
            </div>
          </div>

          <div class="hero-image">
            <div class="floating-cards">
              <div class="card tablet-card">
                <div class="card-glow"></div>
                <img :src="tabletImg" alt="Tablet Dashboard" />
              </div>
            </div>
          </div>
        </div>
      </CContainer>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-5">
      <CContainer class=" p-4">
        <h2 class="text-center mb-5 fw-bold">Everything You Need</h2>

        <CRow class="">
          <CCol
            v-for="(feature, index) in features"
            :key="index"
            xs="12"
            sm="6"
            lg="3"
            class="mb-4 mx-4"
          >
            <CCard class="h-100 border-0 shadow-lg feature-card-coreui p-5">
              <CCardBody class="text-center">
                <div class="fs-2 mb-3">
                  {{ feature.icon }}
                </div>

                <h5 class="fw-semibold">
                  {{ feature.title }}
                </h5>

                <p class="text-medium-emphasis mb-0">
                  {{ feature.desc }}
                </p>
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </CContainer>
    </section>

    <!-- FOOTER -->
    <footer class="footer-premium">
      <CContainer>
        <div
          class="footer-content d-flex justify-content-between align-items-center flex-wrap"
        >
          <div class="footer-brand d-flex align-items-center gap-2">
            RestoSaaS
          </div>

          <div class="footer-version">Developed by Saugat Gurung</div>

          <div class="ms-auto">
            Powered by
            <a href="https://cloudtechservice.com/" target="_blank">
              CloudTech Service
            </a>
          </div>
        </div>
      </CContainer>
    </footer>
  </div>
</template>

<style scoped>
/* =========================
   RESET
   ========================= */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  font-family: "Inter", sans-serif;
  line-height: 1.6;
  color: #333;
}

/* =========================
   CONTAINER
   ========================= */
.container {
  width: 100%;
  margin-left: auto;
  margin-right: auto;
  padding: 0 1rem;
}

/* =========================
   HEADER
   ========================= */
.header-premium {
  padding: 1rem 0;
  background-color: var(--cui-body-bg);
  border-bottom: 1px solid var(--cui-border-color);
}

.header-premium .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.header-premium .logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.header-premium .logo-text {
  font-weight: 700;
  font-size: 1.5rem;
  color: #ff6b35;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.header-actions a.btn {
  padding: 0.5rem 1rem;
  border-radius: 6px;
  text-decoration: none;
}

/* =========================
   HERO SECTION
   ========================= */
.hero-section {
  padding: 6rem 0 4rem;
  background: var(--cui-body-bg);
}
.hero-section .hero-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 2rem;
}

.hero-text {
  flex: 1 1 500px;
  max-width: 600px;
}

.hero-title {
  font-size: 2.5rem;
  line-height: 1.2;
  font-weight: 700;
  margin-bottom: 1rem;
}

.gradient-text {
  background: linear-gradient(
    90deg,
    var(--cui-primary),
    var(--cui-primary-hover)
  );
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  margin-bottom: 2rem;
  font-size: 1.125rem;
  color: var(--cui-body-color);
  opacity: 0.8;
}

.hero-buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.hero-buttons a.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 600;
}

/* Hero stats */
.hero-stats {
  display: flex;
  gap: 2rem;
  margin-top: 2rem;
  flex-wrap: wrap;
}

.hero-stats .stat {
  text-align: center;
}

.hero-stats .stat-number {
  font-size: 1.5rem;
  font-weight: 700;
  color: #ff6b35;
}

/* Hero image */
.hero-image {
  flex: 1 1 400px;
  display: flex;
  justify-content: center;
  position: relative;
}

.floating-cards .card {
  position: relative;
  margin: 1rem;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.floating-cards .card img {
  display: block;
  width: 100%;
  height: auto;
}

.floating-cards .card-glow {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 16px;
  background: rgba(255, 107, 53, 0.2);
  filter: blur(40px);
  z-index: -1;
}

/* =========================
   FEATURES
   ========================= */
.features-section {
  padding: 4rem 0;
}

.features-section .section-title {
  text-align: center;
  margin-bottom: 3rem;
  font-size: 2rem;
  font-weight: 700;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 2rem;
}

.feature-card {
  border-radius: 16px;
  padding: 2rem;
  text-align: center;
  background: var(--cui-card-bg);
  color: var(--cui-body-color);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s;
}

.feature-card:hover {
  transform: translateY(-5px);
}

.feature-icon {
  font-size: 2rem;
  margin-bottom: 1rem;
}

/* =========================
   FOOTER
   ========================= */
.footer-premium {
  background: var(--cui-card-bg);
  padding: 3rem 0;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.footer-version {
  color: #888;
}

/* =========================
   RESPONSIVE
   ========================= */
@media (max-width: 992px) {
  .hero-section .hero-content {
    flex-direction: column-reverse;
    text-align: center;
  }
  .hero-text,
  .hero-image {
    max-width: 100%;
  }
  .hero-buttons {
    justify-content: center;
  }
}

@media (max-width: 576px) {
  .header-premium .container {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }
  .header-actions {
    justify-content: center;
    gap: 0.5rem;
  }
  .features-grid {
    grid-template-columns: 1fr;
  }
}
</style>