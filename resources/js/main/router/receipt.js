export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/receipt",
                component: () => import("../views/accounting-transfer/receipt/index.vue"),
                name: "admin.receipt.index",
                meta: {
                    requireAuth: true,
                    menuParent: "receipt",
                    menuKey: (route) => "receipt",
                },
            },

            {
                path: "receipt/create",
                component: () =>
                    import("../views/accounting-transfer/receipt/Create.vue"),
                name: "admin.receipt.receipt.create",
                meta: {
                    requireAuth: true,
                    menuParent: "receipt",
                    menuKey: (route) => "receipt",
                    permission: (route) => "receipt_create",
                    orderType: "receipt",
                },
            },
        ],
    },





];
