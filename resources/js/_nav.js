export default function getNav(page) {
    return [
        {
            name: "Dashboard",
            to: "/dashboard",
            icon: "cil-home",
            roles: ["owner"],
        },
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

        {
            name: "Restaurants",
            to: "/super-admin/restaurants",
            icon: "cil-building",
            roles: ["super-admin"]
        },

        {
             name: "Analytics",
             to: "/super-admin/analytics",
             icon: "cil-chart-line",
             roles:["super-admin"]
        },
        

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

        {
            name: "Tables",
            icon: "cil-view-module",
            to: "/tables",
            roles: ["owner", "staff"],
        },

        {
            name: "Transactions",
            icon: "cib-cashapp",
            to: "/transactions",
            roles: ["owner"],
        },

        {
             component: "CNavTitle",
             name: "Manage Staffs",
             roles: ["owner"],
        },

        {
              name: "Staffs",
              icon: "cil-people",
              to: "/staffs",
              roles: ["owner"]
        },

        {
              name: "Add Staff",
              icon: "cil-plus",
              to: "/staffs/create",
              roles: ["owner"]
        },

        {
            component: "CNavTitle",
            name: "App Configs",
            roles: ["owner", "super-admin"],
        },

        {
            component: "CNavGroup",
            name: "Settings",
            icon: "cil-settings",
            roles: ["owner", "super-admin"],
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
