export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/receipt",
                component: () => import("../views/accounting-transaction/receipt/index.vue"),
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
                    import("../views/accounting-transaction/receipt/Create.vue"),
                name: "admin.stock.receipt.create",
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
