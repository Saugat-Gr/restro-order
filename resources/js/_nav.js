export default function getNav(page) {
    return [
        {
            name: "Dashboard",
            to: "/dashboard",
            icon: "cil-home",
            roles: ["super-admin", "owner", "staff"],
        },

        {
            name: "Owners",
            to: "/owners",
            icon: "cil-people",
            roles: ["super-admin"],
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
                    icon: "cil-circle",
                },

                {
                     name: "Items",
                     to: "/menu/menu-items",
                     roles: ["owner"],
                     icon: "cil-circle"
                }
            ],
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

        {
            name: "Hi",
            to: "/",
            icon: "cil-speedometer",
            roles: ["staff"],
        },
    ];
}
