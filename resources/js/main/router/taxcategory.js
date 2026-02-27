export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            // {
            //     path: "/admin/taxcategory",
            //     component: () =>
            //         import("../views/master/tax-category/index.vue"),
            //     name: "admin.taxcategory.index",
            //     meta: {
            //         requireAuth: true,
            //         menuParent: "taxcategory",
            //         menuKey: (route) => "taxcategory",
            //     },
            // },

            {
                path: "taxcategory",
                component: () =>
                    import("../views/master/taxcategory/index.vue"),
                name: "admin.taxcategory.index",
                meta: {
                    requireAuth: true,
                    menuParent: "taxcategory",
                    menuKey: (route) => "taxcategory",
                    permission: (route) => "taxcategory_view",
                    orderType: "store-ledger",
                },
            },

            {
                path: "taxcategory/create",
                component: () =>
                    import("../views/master/taxcategory/TaxCreate.vue"),
                name: "admin.stock.taxcategory.create",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "tax_returns",
                    permission: (route) => "tax_creates",
                    orderType: "tax-returns",
                },
            },
            {
                path: "taxcategory/edit/:id",
                component: () =>
                    import("../views/master/taxcategory/TaxCreate.vue"),
                name: "admin.stock.taxcategory.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "taxcategory_returns",
                    permission: (route) => "taxcategory_creates",
                    orderType: "taxcategory-returns",
                },
            },
        ],
    },
];
