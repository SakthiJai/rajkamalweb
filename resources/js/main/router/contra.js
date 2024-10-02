export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/contra",
                component: () =>
                    import("../views/accounting-transfer/Contra/index.vue"),
                name: "admin.contra.index",
                meta: {
                    requireAuth: true,
                    menuParent: "contra",
                    menuKey: (route) => "contra",
                },
            },
        ],
    },
];
