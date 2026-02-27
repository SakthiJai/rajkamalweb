export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/bankdetails",
                component: () =>
                    import("../views/Add-Cash-and-Bank/CashandBank/index.vue"),
                name: "admin.account.index",
                meta: {
                    requireAuth: true,
                    menuParent: "cashbank",
                    menuKey: (route) => "cashbank",
                },
            },
        ],
    },
];
