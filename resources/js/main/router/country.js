export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/country",
                component: () => import("../views/master/country/index.vue"),
                name: "admin.country.index",
                meta: {
                    requireAuth: true,
                    menuParent: "country",
                    menuKey: (route) => "country",
                },
            },
        ],
    },
];
