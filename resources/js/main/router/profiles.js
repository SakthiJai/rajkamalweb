export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/profiles",
                component: () => import("../views/Profile.vue"),
                name: "admin.profiles.index",
                meta: {
                    requireAuth: true,
                    menuParent: "profiles",
                    menuKey: (route) => "profiles",
                },
            },
        ],
    },
];
