export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/journal",
                component: () =>
                    import("../views/accounting-transfer/Journal/index.vue"),
                name: "admin.journal.index",
                meta: {
                    requireAuth: true,
                    menuParent: "journal",
                    menuKey: (route) => "journal",
                },
            },
        ],
    },
];
