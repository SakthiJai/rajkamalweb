export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/godown",
                component: () => import("../views/master/godown/index.vue"),
                name: "admin.godown.index",
                meta: {
                    requireAuth: true,
                    menuParent: "god-owns",
                    menuKey: (route) => "god-owns",
                },
            },
        ],
    },
];
