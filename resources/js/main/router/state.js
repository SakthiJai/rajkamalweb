export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/state",
                component: () => import("../views/master/state/index.vue"),
                name: "admin.state.index",
                meta: {
                    requireAuth: true,
                    menuParent: "state",
                    menuKey: (route) => "state",
                },
            },
        ],
    },
];
