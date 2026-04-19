<script setup>
import { onMounted, ref, computed } from "vue";
import { useStore } from "vuex";
import { useColorModes } from "@coreui/vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import AppBreadcrumb from "@/Components/AppBreadcrumb.vue";
import AppHeaderDropdownAccnt from "@/Components/AppHeaderDropdownAccnt.vue";

const headerClassNames = ref("mb-4 p-0");

const { colorMode, setColorMode } = useColorModes(
  "coreui-free-vue-admin-template-theme"
);

const store = useStore();

const sidebarVisible = computed(() => store.getters["sidebar/visible"]);

const page = usePage();

/* ✅ FIXED: reactive Inertia props */
const notifications = computed(() => page.props.notifications || []);
const unreadCount = computed(() => page.props.unread_count || 0);

function markAsRead(id) {
  router.post(
    `/notifications/${id}/read`,
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ["notifications", "unread_count"] });
      },
    }
  );
}

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

      <!-- 🔔 NOTIFICATIONS -->
      <CHeaderNav class="ms-auto">
        <CDropdown variant="nav-item" placement="bottom-end">
          <CDropdownToggle
            :caret="false"
            class="position-relative d-inline-flex align-items-center"
          >
            <CIcon icon="cil-bell" size="lg" />

            <CBadge
              v-if="unreadCount > 0"
              color="danger"
              shape="rounded-pill"
              class="position-absolute top-2 start-100 translate-middle border border-light"
            >
              {{ unreadCount }}
            </CBadge>
          </CDropdownToggle>

          <CDropdownMenu class="pt-0 overflow-hidden" style="width: 320px">
            <!-- Header -->
            <CDropdownHeader
              class="bg-light fw-semibold text-medium-emphasis border-bottom"
            >
              Notifications
            </CDropdownHeader>

            <!-- Items -->
            <CDropdownItem
              v-for="n in notifications"
              :key="n.id"
              @click="markAsRead(n.id)"
              class="d-flex flex-column py-2 px-3 border-bottom"
              :class="[
                'dropdown-item',
                !n.read_at ? 'bg-light bg-opacity-25 fw-semibold' : '',
              ]"
            >
              <div
                class="d-flex justify-content-between align-items-start w-100"
              >
                <div class="text-body small">
                  {{ n.message }}
                </div>

                <CBadge
                  v-if="!n.read_at"
                  color="info"
                  shape="rounded-pill"
                  class="ms-2"
                >
                  new
                </CBadge>
              </div>

              <div class="text-medium-emphasis small mt-1" v-if="!n.read_at">
                Tap to mark as read
              </div>
            </CDropdownItem>

            <!-- Empty state -->
            <CDropdownItem
              v-if="notifications.length === 0"
              disabled
              class="text-center text-medium-emphasis py-3"
            >
              No notifications
            </CDropdownItem>
          </CDropdownMenu>
        </CDropdown>
      </CHeaderNav>

      <!-- THEME -->
      <CHeaderNav>
        <li class="nav-item py-1">
          <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
        </li>

        <CDropdown variant="nav-item" placement="bottom-end" class="z-10">
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