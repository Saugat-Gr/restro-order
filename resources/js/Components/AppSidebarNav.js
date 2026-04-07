import { defineComponent, h, ref, onMounted, resolveComponent, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useStore } from "vuex";
import getNav from "@/_nav.js";
import simplebar from "simplebar-vue";
import "simplebar-vue/dist/simplebar.min.css";
import { CSidebarNav, CBadge, CNavGroup, CNavTitle, CNavItem } from "@coreui/vue";

const normalizePath = (path) =>
  decodeURI(path).replace(/#.*$/, "").replace(/(index)?\.(html)$/, "");

const isActiveLink = (route, link) => {
  if (!link) return false;
  const currentPath = normalizePath(route);
  const targetPath = normalizePath(link);
  return currentPath === targetPath;
};

const isActiveItem = (route, item) => {
  if (isActiveLink(route, item.to)) return true;
  if (item.items) return item.items.some((child) => isActiveItem(route, child));
  return false;
};

const AppSidebarNav = defineComponent({
  name: "AppSidebarNav",
  setup() {
    const store = useStore();
    const page = usePage();
    const userRole = page.props.auth.user.role;

    const firstRender = ref(true);
    onMounted(() => (firstRender.value = false));

    const navItems = computed(() => getNav(page));
    const filteredNav = computed(() =>
      navItems.value.filter((item) => !item.roles || item.roles.includes(userRole))
    );

    const renderItem = (item) => {
      if (!item) return null;

      // Render CNavTitle
      if (item.component === "CNavTitle") {
        return h(CNavTitle, { key: item.name }, { default: () => item.name });
      }

      // Render CNavGroup
      if (item.component === "CNavGroup" || (item.items && item.items.length)) {
        const children = (item.items || []).filter(
          (child) => !child.roles || child.roles.includes(userRole)
        );
        if (!children.length) return null;

        return h(
          CNavGroup,
          {
            key: item.name,
            compact: true,
            ...(firstRender.value && { visible: children.some((c) => isActiveItem(page.url, c)) }),
          },
          {
            togglerContent: () => [
              item.icon
                ? h(resolveComponent("CIcon"), { icon: item.icon, class: "nav-icon", size: "sm" })
                : null,
              item.name,
            ],
            default: () => children.map((child) => renderItem(child)),
          }
        );
      }

      // Render CNavItem (leaf)
      return h(
        CNavItem,
        {
          key: item.name,
          href: item.to,
          active: isActiveLink(page.url, item.to),
        },
        {
          default: () => [
            item.icon
              ? h(resolveComponent("CIcon"), { icon: item.icon, class: "nav-icon flex-shrink-0", size: "sm" })
              : null,
            h("span", { class: "nav-text fw-medium" }, item.name),
            item.badge
              ? h(
                  CBadge,
                  { class: "ms-auto", color: item.badge.color, shape: "rounded-pill" },
                  item.badge.text
                )
              : null,
          ],
        }
      );
    };

    return () =>
      h(
        CSidebarNav,
        { as: simplebar },
        { default: () => filteredNav.value.map((item) => renderItem(item)) }
      );
  },
});

export { AppSidebarNav };
export default AppSidebarNav;