export default [
    {
        path: "/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "/admin/station",
                component: () => import("../views/master/station/index.vue"),
                name: "admin.station.index",
                meta: {
                    requireAuth: true,
                    menuParent: "country",
                    menuKey: (route) => "station",
                },
            },
        ],
    },
];
