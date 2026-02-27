export default [
    {
        path: "/admin/expense-category",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            //working now
            {
                path: "/admin/expense-categories",
                component: () =>
                    import(
                        "../views/expense-manager/expense-categories/index.vue"
                    ),
                name: "admin.expensecategory.index",
                meta: {
                    requireAuth: true,
                    menuParent: "expense_manager",
                    menuKey: (route) => "expense_categories",
                    permission: (route) => "expense_categories_view",
                    orderType: "expenses-list",
                },
            },

            // {
            //     path: "expense-categories/create",
            //     component: () =>
            //         import(
            //             "../views/expense-manager/expense-categories/ExpenseCreate.vue"
            //         ),
            //     name: "admin.expense_categories.create",
            //     meta: {
            //         requireAuth: true,
            //         menuParent: "expense_manager",
            //         menuKey: (route) => "expense_categories",
            //         permission: (route) => "expense_categories_create_view",
            //         orderType: "expenses-list",
            //     },
            // },

            {
                path: "/admin/expenses/test",
                component: () =>
                    import("../views/expense-manager/expenses/index.vue"),
                name: "admin.expenses.index",
                meta: {
                    requireAuth: true,
                    menuParent: "expense_manager",
                    menuKey: (route) => "expenses",
                    permission: "expenses-list",
                },
            },
            {
                path: "/admin/expenses",
                component: () =>
                    import(
                        "../views/expense-manager/expenses/expenses_new/index.vue"
                    ),
                name: "admin.expenses1.index",
                meta: {
                    requireAuth: true,
                    menuParent: "expense_manager",
                    menuKey: (route) => "expenses1",
                    permission: "expenses-list",
                },
            },
            {
                path: "/admin/expenses/create",
                component: () =>
                    import(
                        "../views/expense-manager/expenses/expenses_new/create.vue"
                    ),
                name: "admin.expenses1.create",
                meta: {
                    requireAuth: true,
                    menuParent: "expense_manager",
                    menuKey: (route) => "expenses1",
                    permission: "expenses-list",
                },
            },
            {
                path: "/admin/expenses/edit",
                component: () =>
                    import(
                        "../views/expense-manager/expenses/expenses_new/create.vue"
                    ),
                name: "admin.expenses1.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "expense_manager",
                    menuKey: (route) => "expenses1",
                    permission: "expenses-list",
                },
            },
        ],
    },
];
