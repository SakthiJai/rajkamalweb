export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/cashbank",
                component: () =>
                    import("../views/Add-Cash-and-Bank/CashandBank/index.vue"),
                name: "admin.cashbank.index",
                meta: {
                    requireAuth: true,
                    menuParent: "cashbank",
                    menuKey: (route) => "cashbank",
                },
            },
        ],
    },
];
