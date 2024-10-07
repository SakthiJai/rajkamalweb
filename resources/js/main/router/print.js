export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/print",
                component: () => import("../views/accounting-transaction/print/print.vue"),
                name: "admin.print.index",
                meta: {
                    requireAuth: true,
                    menuParent: "print",
                    menuKey: (route) => "print",
                },
            },

          
        ],
    },





];
