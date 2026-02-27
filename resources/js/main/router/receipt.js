export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/receipt",
                component: () =>
                    import("../views/accounting-transfer/receipt/index.vue"),
                name: "admin.receipt.index",
                meta: {
                    requireAuth: true,
                    menuParent: "accountingtrans",
                    menuKey: (route) => "receipt",
                    permission: (route) => "receipt",
                    orderType: "recepit-bill",
                },
            },
        ],
    },

    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/receiptcreate",
                component: () =>
                    import(
                        "../views/accounting-transfer/receipt/ReceiptCreate.vue"
                    ),
                name: "admin.receipt.ReceiptCreate",
                meta: {
                    requireAuth: true,
                    menuParent: "accountingtrans",
                    menuKey: (route) => "receipt",
                    permission: (route) => "receipt",
                    orderType: "recepit-bill",
                },
            },
        ],
    },


    /// 

    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/payments",
                component: () =>
                    import("../views/accounting-transfer/payment/index.vue"),
                name: "admin.payment.index",
                meta: {
                    requireAuth: true,
                    menuParent: "accountingtrans",
                    menuKey: (route) => "payment",
                    orderType: "receipt-payment",
                },
            },
        ],
    },
    //Payment model

    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/paymentcreate",
                component: () =>
                    import(
                        "../views/accounting-transfer/payment/PaymentCreate.vue"
                    ),
                name: "admin.payment.paymentCreate",
                meta: {
                    requireAuth: true,
                    menuParent: "accountingtrans",
                    menuKey: (route) => "payment",
                    orderType: "recepit-payment",
                },
            },
        ],
    },


    // payment edit //

    {
        path: "/payment/edit/:id",  // Added a leading slash
        component: () => import("../views/accounting-transfer/payment/PaymentEdit.vue"),
        name: "admin.stock.payment.edit",
        meta: {
            requireAuth: true,
            menuParent: "accountingtrans",
            menuKey: (route) => "payment",
            orderType: "recepit-payment",
        },
    },




    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/payment/edit/:id",
                component: () =>
                    import(
                        "../views/accounting-transfer/payment/PaymentEdit.vue"
                    ),
                name: "admin.stock.payment.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "accountingtrans",
                    menuKey: (route) => "payment",
                    orderType: "recepit-payment",
                },
            },
        ],
    },
    
    // end payment//



];
