export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/payment",
                component: () => import("../views/accounting-transfer/payment/index.vue"),
                name: "admin.payment.index",
                meta: {
                    requireAuth: true,
                    menuParent: "payment",
                    menuKey: (route) => "payment",
                },
            },
        ],
    },
];
