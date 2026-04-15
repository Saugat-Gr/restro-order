<script setup>
import { onMounted, ref, computed } from "vue";
import { useStore } from "vuex";
import { useColorModes } from "@coreui/vue";
import { Link } from "@inertiajs/vue3";
import AppBreadcrumb from "@/Components/AppBreadcrumb.vue";
import AppHeaderDropdownAccnt from "@/Components/AppHeaderDropdownAccnt.vue";

const headerClassNames = ref("mb-4 p-0");

const { colorMode, setColorMode } = useColorModes(
  "coreui-free-vue-admin-template-theme"
);

const store = useStore();

const sidebarVisible = computed(() => store.getters["sidebar/visible"]);

const toggleSidebar = (value) => {
  store.dispatch("sidebar/toggleSidebar", value);
};

onMounted(() => {
  document.addEventListener("scroll", () => {
    headerClassNames.value =
      document.documentElement.scrollTop > 0
        ? "mb-4 p-0 shadow-sm"
        : "mb-4 p-0";
  });
});
</script>
<template>
  <CHeader position="sticky" :class="headerClassNames">
    <CContainer class="border-bottom px-4" fluid>
      <CHeaderToggler
        @click="toggleSidebar(!sidebarVisible)"
        style="margin-inline-start: -14px"
      >
        <CIcon icon="cil-menu" size="lg" />
      </CHeaderToggler>


      <CHeaderNav class="ms-auto">

        <CNavItem>
          <CNavLink href="#">
            <CIcon icon="cil-bell" size="lg" />
          </CNavLink>
        </CNavItem>
        
      </CHeaderNav>

      <CHeaderNav>
        <li class="nav-item py-1">
          <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
        </li>
        <CDropdown variant="nav-item" placement="bottom-end">
          <CDropdownToggle :caret="false">
            <CIcon v-if="colorMode === 'dark'" icon="cil-moon" size="lg" />
            <CIcon v-else-if="colorMode === 'light'" icon="cil-sun" size="lg" />
            <CIcon v-else icon="cil-contrast" size="lg" />
          </CDropdownToggle>
          <CDropdownMenu>
            <CDropdownItem
              :active="colorMode === 'light'"
              class="d-flex align-items-center"
              component="button"
              type="button"
              @click="setColorMode('light')"
            >
              <CIcon class="me-2" icon="cil-sun" size="lg" /> Light
            </CDropdownItem>
            <CDropdownItem
              :active="colorMode === 'dark'"
              class="d-flex align-items-center"
              component="button"
              type="button"
              @click="setColorMode('dark')"
            >
              <CIcon class="me-2" icon="cil-moon" size="lg" /> Dark
            </CDropdownItem>

          </CDropdownMenu>
        </CDropdown>
        <li class="nav-item py-1">
          <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
        </li>
        <AppHeaderDropdownAccnt />
      </CHeaderNav>
    </CContainer>
    <CContainer class="px-4" fluid>
      <AppBreadcrumb />
    </CContainer>
  </CHeader>
</template>
