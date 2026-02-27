export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/discount",
                component: () => import(""),
                name: "admin.discount.index",
                meta: {
                    requireAuth: true,
                    menuParent: "discount",
                    menuKey: (route) => "discount",
                },
            },
        ],
    },
];
