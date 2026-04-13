<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { useStore } from "vuex";
import { AppSidebarNav } from "@/Components/AppSidebarNav.js";
import { computed } from "vue";

// Access the store
const store = useStore();

// Access the page shared props:
const page = usePage();

//  Logo of the Copmany:
const logo = page.props.restaurant 
  ? `/storage/${page.props.restaurant.logo}`
  : '/storage/restaurant/logos/default.avif';

// Sidebar reactive state from Vuex
const sidebarVisible = computed(() => store.getters["sidebar/visible"]);
const sidebarUnfoldable = computed(() => store.getters["sidebar/unfoldable"]);

// Methods to dispatch actions
const toggleSidebar = (value) => store.dispatch("sidebar/toggleSidebar", value);
const toggleUnfoldable = () => store.dispatch("sidebar/toggleUnfoldable");
</script>

<template>
  <CSidebar
    class="border-end c-sidebar- c-sidebar-dark c-sidebar-fixed"
    colorScheme="dark"
    position="sticky"
    :unfoldable="sidebarUnfoldable"
    :visible="sidebarVisible"
    @visible-change="toggleSidebar"
  >
    <CSidebarHeader class="border-bottom ">
      <div class="bg-red w-full h-full flex items-center justify-center">
        <Link href="/" class="text-decoration-none text-white">
          <CImage :src="logo" class="w-full" v-if="logo" />
        </Link>
      </div>
      <CCloseButton
        class="d-lg-none"
        dark
        @click="toggleSidebar(!sidebarVisible)"
      />
    </CSidebarHeader>

    <AppSidebarNav />

    <CSidebarFooter class="border-top d-none d-lg-flex">
      <CSidebarToggler @click="toggleSidebar(!sidebarVisible)" />
    </CSidebarFooter>
  </CSidebar>
</template>

<style scoped>
/* 🔥 PERFECT SIDEBAR NAV */
:deep(.sidebar-nav-perfect .sidebar-nav-link) {
  color: rgba(255, 255, 255, 0.85) !important;
  text-decoration: none !important;
  border-radius: 0.5rem !important;
  font-size: 0.95rem !important;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
  border: 1px solid transparent !important;
}

:deep(.sidebar-nav-perfect .sidebar-nav-link:hover) {
  color: #fff !important;
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(255, 255, 255, 0.15) !important;
  transform: translateX(2px) !important;
}

:deep(.sidebar-nav-perfect .sidebar-nav-link.active) {
  color: #4dabf7 !important;
  background: rgba(77, 171, 247, 0.2) !important;
  border-color: rgba(77, 171, 247, 0.3) !important;
  font-weight: 600 !important;
}

/* ICON: EXACTLY 20x20 */
:deep(.nav-icon) {
  width: 20px !important;
  height: 20px !important;
  min-width: 20px !important;
  max-width: 20px !important;
  flex-shrink: 0 !important;
}

/* TEXT */
:deep(.nav-text) {
  flex: 1 !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}

.c-sidebar-orange {
  background: linear-gradient(180deg, #111827 0%, #1f2937 100%) !important;
}

/* UNFOLDABLE */
@media (min-width: 992px) {
  :deep(.c-sidebar.c-sidebar-unfoldable .nav-icon) {
    width: 22px !important;
    height: 22px !important;
  }
}
</style>