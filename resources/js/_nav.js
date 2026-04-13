export default function getNav(page) {
    return [
        {
            name: "Dashboard",
            to: "/dashboard",
            icon: "cil-home",
            roles: ["super-admin", "owner", "staff"],
        },

        {
            component: "CNavTitle",
            name: "Users",
            roles: ["super-admin"],
        },

        {
            name: "Owners",
            to: "/owners",
            icon: "cil-people",
            roles: ["super-admin"],
        },

        {
            component: "CNavTitle",
            name: "Feature",
            roles: ["owner"],
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
                    roles: ["owner"],
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
                },

                {
                    name: "Search Order",
                    to: "/orders/search",
                    roles: ["owner", "staff"],
                    icon: "cil-magnifying-glass"
                }
            ],
        },


        {
            name: "Tables",
            icon: "cil-view-module",
            to: "/tables",
            roles: ["owner", "staff"],
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
                    roles: ["owner", "super-admin"],
                    icon: "cil-smile",
                },
            ],
        },
    ];
}
