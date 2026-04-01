import {
    defineComponent,
    h,
    ref,
    onMounted,
    computed,
    resolveComponent,
} from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { useStore } from "vuex";
import getNav from "@/_nav.js";
import simplebar from "simplebar-vue";
import "simplebar-vue/dist/simplebar.min.css";
import {
    CSidebarNav,
    CNavGroup,
    CNavTitle,
    CBadge,
    useColorModes,
} from "@coreui/vue";

const normalizePath = (path) =>
    decodeURI(path)
        .replace(/#.*$/, "")
        .replace(/(index)?\.(html)$/, "");

const isActiveLink = (route, link) => {
    if (!link) return false;
    const currentPath = normalizePath(route);
    const targetPath = normalizePath(link);
    return currentPath === targetPath;
};

const AppSidebarNav = defineComponent({
    name: "AppSidebarNav",
    setup() {
        const store = useStore();
        const page = usePage();

        const userRole = page.props.auth.user.role;

        const sidebarVisible = computed(() => store.getters["sidebar/visible"]);
        const sidebarUnfoldable = computed(
            () => store.getters["sidebar/unfoldable"],
        );
        const toggleSidebar = (value) =>
            store.dispatch("sidebar/toggleSidebar", value);

        const navItems = computed(() => getNav(page));

        const filteredNav = computed(() =>
            navItems.value.filter(
                (item) => !item.roles || item.roles.includes(userRole),
            ),
        );

        const firstRender = ref(true);
        onMounted(() => {
            firstRender.value = false;
        });

        const renderItem = (item, level = 0) => {
            if (!item) return null; // Defensive guard

            if (item.items && item.items.length) {
                const filteredChildren = item.items.filter(
                    (child) => !child.roles || child.roles.includes(userRole),
                );

                if (!filteredChildren.length) return null;

                return h(
                    CNavGroup,
                    {
                        as: "div",
                        compact: true,
                        ...(firstRender.value && {
                            visible: filteredChildren.some((child) =>
                                isActiveLink(page.url, child.to || child.href),
                            ),
                        }),
                    },
                    {
                        togglerContent: () => [
                            item.icon
                                ? h(resolveComponent("CIcon"), {
                                      icon: item.icon,
                                      class: "nav-icon",
                                  })
                                : null,
                            item.name,
                        ],
                        default: () =>
                            filteredChildren.map(child => renderItem(child, level + 1)),
                    },
                );
            }

            return h(
                Link,
                {
                    href: item.to,
                    class: [
                        "sidebar-nav-link d-flex align-items-center gap-2 py-2 px-3 rounded mb-1 text-decoration-none text-white",
                        level > 0 ? "ml-12" : 'ps-3',
                        isActiveLink(page.url, item.to)
                            ? "active bg-primary bg-opacity-10"
                            : "",
                    ],
                },
                {
                    default: () => [
                        item.icon
                            ? h(resolveComponent("CIcon"), {
                                  icon: item.icon,
                                  class: "nav-icon flex-shrink-0",
                              })
                            : null,
                        h("span", { class: "nav-text fw-medium" }, item.name),
                        item.badge
                            ? h(
                                  CBadge,
                                  {
                                      class: "ms-auto",
                                      color: item.badge.color,
                                      shape: "rounded-pill",
                                      size: "sm",
                                  },
                                  item.badge.text,
                              )
                            : null,
                    ],
                },
            );
        };

        // 🔥 PERFECT FIX: Just change this return statement
        return () =>
            h(
                CSidebarNav,
                {
                    as: simplebar,
                    class: "sidebar-nav-perfect",
                },
                { 
                    default: () => filteredNav.value.map((item) => renderItem(item, 0))
                },
            );
    },
});

export { AppSidebarNav };