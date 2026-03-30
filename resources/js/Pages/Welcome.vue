<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import tabletImg from "@/assets/app-images/tablet.webp";
defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});

const page = usePage();
const user = page.props.auth.user;

</script>

<template>
  <Head title="Restaurant SaaS - Order Management System" />

  <div class="landing-hero">
    <!-- HEADER -->
    <header class="header-premium">
      <div class="container">
        <div class="header-content flex justify-between w-full">
          <div class="logo">
            <svg class="logo-icon" viewBox="0 0 64 64">
              <circle cx="32" cy="32" r="28" fill="#FF6B35" />
              <path
                d="M32 12C20 12 10 22 10 34s10 22 22 22 22-10 22-22-10-22-22-22zM32 40c-5 0-9-4-9-9s4-9 9-9 9 4 9 9-4 9-9 9z"
                fill="#FFF"
              />
              <circle cx="32" cy="31" r="7" fill="#FF6B35" />
            </svg>
            <span class="logo-text">RestoSaaS</span>
          </div>

          <div class="header-actions">
            <Link
              v-if="user"
              :href="route('dashboard')"
              class="btn btn-outline-premium"
            >
              Dashboard
            </Link>
            <template v-else>
              <Link :href="route('login')" class="btn btn-primary-premium">
                Sign In
              </Link>
            </template>
          </div>
        </div>
      </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section">
      <div class="container">
        <div class="hero-content">
          <div class="hero-text">
            <h1 class="hero-title">
              Premium Restaurant<br />
              <span class="gradient-text">Order Management</span>
            </h1>
            <p class="hero-subtitle">
              Streamline your restaurant operations with our SaaS platform.
              Manage orders, tables, menus & payments in one elegant dashboard.
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
              <Link
                :href="route('register')"
                class="bg-orange-400 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded flex items-center text-decoration-none"
              >
                Start Free Trial
              </Link>
            </div>
          </div>
          <div class="hero-image">
            <div class="floating-cards">
              <div class="card tablet-card">
                <div class="card-glow"></div>
                <img :src="tabletImg" alt="Tablet Dashboard" />
              </div>
              <!-- <div class="card phone-card">
                <div class="card-glow"></div>
                <img src="/images/phone-mockup.png" alt="Mobile Orders" />
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="features-section">
      <div class="container">
        <h2 class="section-title">Everything You Need</h2>
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">🍽️</div>
            <h3>Table Management</h3>
            <p>Real-time table status, reservations & seating arrangements.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>POS Integration</h3>
            <p>Seamless POS sync with inventory & payment processing.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Analytics</h3>
            <p>Revenue insights, peak hours & customer analytics.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">👥</div>
            <h3>Staff Management</h3>
            <p>Shift scheduling, performance tracking & permissions.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-premium">
      <div class="container">
        <div class="footer-content">
          <div class="footer-brand">
            <div class="logo">
              <svg class="logo-icon-small" viewBox="0 0 32 32">
                <circle cx="16" cy="16" r="14" fill="#FF6B35" />
                <circle cx="16" cy="15.5" r="3.5" fill="#FFF" />
              </svg>
              RestoSaaS
            </div>
          </div>
          <div class="footer-version">Developed by Saugat Gurung</div>

          <div class="ms-auto">
            <span class="me-1" target="_blank">Powered by</span>
            <a href="https://cloudtechservice.com/" target="_blank"
              >CloudTech Service</a
            >
          </div>
        </div>
      </div>
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
  max-width: 1200px;
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