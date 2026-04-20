export default function getNav(page) {
    return [
        //  Owner
        {
            name: "Dashboard",
            to: "/dashboard",
            icon: "cil-home",
            roles: ["owner"],
        },

        //  Super-admin
        {
            name: "Dashboard",
            to: "/super-admin/dashboard",
            icon: "cil-home",
            roles: ["super-admin"],
        },

        {
            component: "CNavTitle",
            name: "Application",
            roles: ["super-admin"],
        },

        {
            component: "CNavGroup",
            name: "Owners",
            icon: "cil-industry",
            roles: ["super-admin"],

            items: [
                {
                    name: "Owners",
                    to: "/super-admin/owners",
                    icon: "cil-people",
                    roles: ["super-admin"],
                },
                {
                    name: "Create Owner",
                    to: "/super-admin/owners/create",
                    icon: "cil-plus",
                    roles: ["super-admin"],
                },
            ],
        },

        //   Restaurants:

        {
            name: "Restaurants",
            to: "/super-admin/restaurants",
            icon: "cil-building",
            roles: ["super-admin"],
        },

        //  Analytics: Super-admin

        {
            name: "Logs",
            to: "/super-admin/activity-logs",
            icon: "cil-clipboard",
            roles: ["super-admin"],
        },

        {
            name: "Analytics",
            to: "/super-admin/analytics",
            icon: "cil-chart-line",
            roles: ["super-admin"],
        },

        //    Owner

        {
            component: "CNavTitle",
            name: "Feature",
            roles: ["owner", "staff"],
        },

        {
            component: "CNavGroup",
            name: "Menu",
            icon: "cil-file",
            roles: ["owner", "staff"],

            items: [
                {
                    name: "Categories",
                    to: "/menu/category",
                    roles: ["owner", "staff"],
                    icon: "cil-minus",
                },

                {
                    name: "Items",
                    to: "/menu/menu-items",
                    roles: ["owner", "staff"],
                    icon: "cil-minus",
                },
            ],
        },

        {
            component: "CNavGroup",
            name: "Orders",
            icon: "cil-cart",
            roles: ["owner", "staff"],

            items: [
                {
                    name: "Order History",
                    to: "/orders",
                    roles: ["owner", "staff"],
                    icon: "cil-history",
                },

                {
                    name: "Create Order",
                    to: "/orders/create",
                    roles: ["owner", "staff"],
                    icon: "cil-plus",
                    permission: ["create-order"],
                },

                {
                    name: "Search Order",
                    to: "/orders/search",
                    roles: ["owner", "staff"],
                    icon: "cil-magnifying-glass",
                    permission: ["search-order"],
                },
            ],
        },

        //  Tables:
        {
            name: "Tables",
            icon: "cil-view-module",
            to: "/tables",
            roles: ["owner", "staff"],
        },

        //  Transactions:
        {
            name: "Transactions",
            icon: "cib-cashapp",
            to: "/transactions",
            roles: ["owner"],
        },

        //  Staffs:
        {
            component: "CNavTitle",
            name: "Manage Staffs",
            roles: ["owner"],
        },

        {
            name: "Staffs",
            icon: "cil-people",
            to: "/staffs",
            roles: ["owner"],
        },

        {
            name: "Add Staff",
            icon: "cil-plus",
            to: "/staffs/create",
            roles: ["owner"],
        },

        //  Activity Logs:
        {
            component: "CNavTitle",
            name: "Activity Logs",
            roles: ["owner"],
        },

        {
            name: "Logs",
            icon: "cil-clipboard",
            to: "/activity-logs",
            roles: ["owner"],
        },

        {
            name: "Analytics",
            to: "/analytics",
            icon: "cil-chart-line",
            roles: ["owner"],
        },

        //  App Configurations:
        {
            component: "CNavTitle",
            name: "App Configs",
            roles: ["owner"],
        },

        {
            component: "CNavGroup",
            name: "Settings",
            icon: "cil-settings",
            roles: ["owner"],
            items: [
                {
                    name: "General",
                    to: `/restaurant/${page.props.auth.user.restaurant_id}/edit`,
                    roles: ["owner"],
                    icon: "cil-smile",
                },
            ],
        },
    ];
}
