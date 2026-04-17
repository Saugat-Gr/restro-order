<script setup>
import AppFooter from "@/Components/AppFooter.vue";
import AppHeader from "@/Components/AppHeader.vue";
import AppSidebar from "@/Components/AppSidebar.vue";
import { useStore } from "vuex";
import { computed, inject, watch } from "vue";
import { Head, usePage, Link } from "@inertiajs/vue3";

const store = useStore();
const sidebarVisible = computed(() => store.getters["sidebar/visible"]);
const sidebarUnfoldable = computed(() => store.getters["sidebar/unfoldable"]);

const page = usePage();
const appName = computed(() => page.props.app.title);

const toastr = inject("toastr");

watch(
  () => page.props.flash?.success,
  (success) => {
    if (success) {
      toastr.success(success);
    }
  },
  { immediate: true }
);

watch(
  () => page.props.flash?.error,
  (error) => {
    if (error) {
      toastr.error(error);
    }
  },
  { immediate: true }
);
</script>

<template>
  <Head>
    <title>{{ appName }}</title>
  </Head>

  <div class="layout-wrapper">
    <AppSidebar />

    <!-- MAIN CONTENT -->
    <div
      class="layout-wrapper-main"
      :class="{
        'sidebar-visible': sidebarVisible,
        'sidebar-folded': sidebarUnfoldable && sidebarVisible,
      }"
    >
      <AppHeader />

      <div class="content-scrollable">
        <CContainer fluid>
          <slot />
        </CContainer>
      </div>
      <AppFooter />

      <!-- FIXED FOOTER -->
    </div>
  </div>
</template>

<style scoped>
.layout-wrapper {
  height: 100vh; /* ✅ FULL viewport height */
  display: flex;
  overflow: hidden; /* Prevent body scroll */
}

.layout-wrapper-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh; /* Full height */
}

/* DESKTOP SIDEBAR PUSH */
@media (min-width: 992px) {
  .layout-wrapper-main.sidebar-folded {
    margin-left: 56px;
  }

  .layout-wrapper-main:not(.sidebar-visible) {
    margin-left: 0;
  }
}

/* HEADER ALIGNMENT */
:deep(.layout-wrapper-main.sidebar-visible .c-header .c-container) {
  padding-left: 0 !important;
  margin-left: 0 !important;
}

:deep(.layout-wrapper-main.sidebar-folded .c-header .c-container) {
  padding-left: 1rem !important;
}

@media (max-width: 991.98px) {
  .layout-wrapper-main {
    margin-left: 0 !important;
  }

  :deep(.c-header .c-container) {
    padding-left: 1rem !important;
  }
}

/* ✅ PERFECT SCROLLABLE CONTENT */
.content-scrollable {
  flex: 1; /* Takes remaining space */
  overflow-y: auto; /* Scroll only content */
  overflow-x: hidden;
  padding: 1.5rem 0; /* Your preferred padding */
}

/* Ensure footer stays at bottom */
:deep(.app-footer) {
  flex-shrink: 0;
}

/* Mobile content padding */
@media (max-width: 991.98px) {
  .content-scrollable {
    padding: 1rem;
  }
}

/* Applies to all vue-easymde instances */
.EasyMDEContainer {
  background-color: var(--cui-bg-dark); /* CoreUI dark background */
  color: var(--cui-text-light);
}

.EasyMDEContainer .CodeMirror {
  background-color: var(--cui-bg-dark);
  color: var(--cui-text-light);
}

.EasyMDEContainer .CodeMirror-cursor {
  border-left: 1px solid var(--cui-text-light);
}

.EasyMDEContainer .editor-toolbar {
  background-color: var(--cui-bg-dark);
  border: none;
}
</style>