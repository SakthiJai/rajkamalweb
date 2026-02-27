export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/productcreate",
                component: () => import("../views/master/product/index.vue"),
                name: "admin.product.index",
                meta: {
                    requireAuth: true,
                    menuParent: "product",
                    menuKey: (route) => "product",
                },
            },
        ],
    },
];
