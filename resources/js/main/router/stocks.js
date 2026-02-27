export default [
    {
        path: "/admin/stock/pos",
        component: () => import("../views/stock-management/pos/Pos.vue"),
        name: "admin.pos.index",
        meta: {
            requireAuth: true,
            menuParent: "pos",
            menuKey: "pos",
            permission: "pos_view",
        },
    },
    {
        path: "/admin/stock/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "adjustments",
                component: () =>
                    import("../views/stock-management/adjustment/index.vue"),
                name: "admin.stock_adjustments.index",
                meta: {
                    requireAuth: true,
                    menuParent: "stock_adjustment",
                    menuKey: "stock_adjustment",
                    permission: "stock_adjustments_view",
                },
            },

            {
                path: "rate-enquiry",
                component: () =>
                    import("../views/stock-management/rate_enquiry/index.vue"),
                name: "admin.stock.rate_enquiry.index",
                meta: {
                    requireAuth: true,
                    menuParent: "rate_enquiry",
                    menuKey: "rate_enquiry",
                    permission: "rate_enquiry_view",
                },
            },
            {
                path: "new-product-list",
                component: () =>
                    import("../views/stock-management/new-product-list/index.vue"),
                name: "admin.stock.new_product_list.index",
                meta: {
                    requireAuth: true,
                    menuParent: "new_product_list",
                    menuKey: "new_product_list",
                    permission: "new_product_list_view",
                },
            },

            //productes item

            {
                path: "productitem",
                component: () =>
                    import(
                        "../views/stock-management/purchases/ProductItem/ProductIndex.vue"
                    ),
                name: "admin.productitem.index",
                meta: {
                    requireAuth: true,
                    menuParent: "productlist",
                    menuKey: (route) => "productlist",
                    permission: (route) => "productlist_view",
                    orderType: "product-list",
                },
            },

            {
                path: "productitem/create",
                component: () =>
                    import(
                        "../views/stock-management/purchases/ProductItem/ProductItemModel.vue"
                    ),
                name: "admin.stock.productitem.create",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "product-item",
                    permission: (route) => "product-item_create",
                    orderType: "product-item",
                },
            },

            {
                path: "productitem/edit/:id",
                component: () =>
                    import(
                        "../views/stock-management/purchases/ProductItem/ProductItemModel.vue"
                    ),
                name: "admin.stock.productitem.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "product-item",
                    permission: (route) => "product-item_create",
                    orderType: "product-item",
                },
            },
            {
                path: "purchases/edit/:id",
                component: () =>
                    import("../views/stock-management/purchases/Edit.vue"),
                name: "admin.stock.purchases.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases",
                    permission: (route) => "purchases_edit",
                    orderType: "purchases",
                },
            },
            //discount
            // {
            //     path: "discount",
            //     component: () => import("../views/master/discount/index.vue"),
            //     name: "admin.discount.index",
            //     meta: {
            //         requireAuth: true,
            //         menuParent: "discount",
            //         menuKey: (route) => "discount",
            //         permission: (route) => "discount",
            //         orderType: "store-ledger",
            //     },
            // },
            // party edit
            {
                path: "party",
                component: () => import("../views/master/party/index.vue"),
                name: "admin.party.index",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "party",
                    permission: (route) => "party_view",
                    orderType: "store-ledger",
                },
            },

            //  customer edit//
            {
                path: "customer/list",
                component: () =>
                    import("../views/master/party/Customerindex.vue"),
                name: "admin.stock.customer.Customerindex",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "customer",
                    permission: (route) => "customer_view",
                    orderType: "store-ledger",
                    reload: true,
                },
            },

            // end of customer edit//

            //  Supplier edit//
            {
                path: "supplier/list",
                component: () =>
                    import("../views/master/party/Supplierindex.vue"),
                name: "admin.stock.supplier.Supplierindex",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "supplier",
                    permission: (route) => "supplier_view",
                    orderType: "store-ledger",
                    reload: true,
                },
            },
            // end of Supplier edit//

            {
                path: "party/create",
                component: () =>
                    import("../views/master/party/PartyCreate.vue"),
                name: "admin.stock.party.create",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "party",
                    permission: (route) => "party_view",
                    orderType: "store-ledger",
                },
            },

            {
                path: "party/customer",
                component: () =>
                    import("../views/master/party/CustomerCreate.vue"),
                name: "admin.stock.party.customer",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "customer",
                    permission: (route) => "party_creates",
                    orderType: "party-returns",
                },
            },

            {
                path: "party/supplier",
                component: () =>
                    import("../views/master/party/SupplierCreate.vue"),
                name: "admin.stock.party.supplier",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "supplier",
                    permission: (route) => "party_creates",
                    orderType: "party-returns",
                },
            },

            {
                path: "party/edit/:id",
                component: () => import("../views/master/party/PartyEdit.vue"),
                name: "admin.stock.party.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "party",
                    permission: (route) => "party_view",
                    orderType: "store-ledger",
                },
            },

            {
                path: "customer/edit/:id",
                component: () =>
                    import("../views/master/party/WalkCustomerEdit.vue"),
                name: "admin.stock.walkcustomer.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "customer",
                    permission: (route) => "party_view",
                    orderType: "store-ledger",
                },
            },

            {
                path: "supplier/edit/:id",
                component: () =>
                    import("../views/master/party/SupplierEdit.vue"),
                name: "admin.stock.supplier.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "party",
                    menuKey: (route) => "supplier",
                    permission: (route) => "party_view",
                    orderType: "store-ledger",
                },
            },

            //party edit//

            // product edit//

            {
                path: "productedit",
                component: () =>
                    import(
                        "../views/stock-management/purchases/Productedit/index.vue"
                    ),
                name: "admin.productedit.index",
                meta: {
                    requireAuth: true,
                    menuParent: "product_manager",
                    menuKey: (route) => "partyedit",
                    permission: (route) => "partyedit",
                    orderType: "products",
                },
            },

            {
                path: "product/productcreate",
                component: () =>
                    import(
                        "../views/stock-management/purchases/Productedit/ProductCreate.vue"
                    ),
                name: "admin.stock.productview.create",
                meta: {
                    requireAuth: true,
                    menuParent: "product_manager",
                    menuKey: (route) => "partyedit",
                    permission: (route) => "partyedit",
                    orderType: "products",
                },
            },

            {
                path: "product-category",
                component: () =>
                    import("../views/settings/product-category/index.vue"),
                name: "admin.settings.productcategory.index",
                meta: {
                    requireAuth: true,
                    menuParent: "product_manager",
                    menuKey: (route) => "categories",
                    permission: (route) => "partyedit",
                    orderType: "products",
                },
            },

            {
                path: "product/edit/:id",
                component: () =>
                    import(
                        "../views/stock-management/purchases/Productedit/ProductEdit.vue"
                    ),
                name: "admin.stock.products.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "product_manager",
                    menuKey: (route) => "partyedit",
                    permission: (route) => "partyedit",
                    orderType: "partyedit",
                },
            },

            // end product edit//

            //purchases Bill working
            {
                path: "purchases/bill",
                component: () =>
                    import("../views/stock-management/purchases/Billindex.vue"),
                name: "admin.stock.purchases.Billindex",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases_bill",
                    permission: (route) => "purchases_view",
                    orderType: "bill-returns",
                },
            },
            //purchases Bill working  End

            //purchases Return
            {
                path: "purchases/return",
                component: () =>
                    import(
                        "../views/stock-management/purchases/Returnindex.vue"
                    ),
                name: "admin.stock.purchases.Returnindex",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases_return",
                    permission: (route) => "return_view",
                    orderType: "purchases-return",
                },
            },

            //bill return//

            {
                path: "bill-return/create",
                component: () =>
                    import(
                        "../views/stock-management/purchases/BillReturn.vue"
                    ),
                name: "admin.stock.bill-return.create",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases_bill",
                    permission: (route) => "bill_returns_creates",
                    orderType: "bill-returns",
                },
            },

            //end of bill return//

            //customerasa create//

            // {
            //     path: "customer/create",
            //     component: () =>
            //         import(
            //             "../views/stock-management/purchases/PurchaseReturn.vue"
            //         ),
            //     name: "admin.stock.purchase-return.create",
            //     meta: {
            //         requireAuth: true,
            //         menuParent: "purchases",
            //         menuKey: (route) => "purchase_returns",
            //         permission: (route) => "purchase_returns_creates",
            //         orderType: "purchase-returns",
            //     },
            // },

            /// end customer create//

            // return create//

            {
                path: "customer/create/:id",
                component: () =>
                    import(
                        "../views/stock-management/purchases/customeredit/CustomerCreate.vue"
                    ),
                name: "admin.stock.customer.create",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "customer_create",
                    permission: (route) => "customer_create",
                    orderType: "customer_returns",
                },
            },

            {
                path: "purchase-return/create",
                component: () =>
                    import(
                        "../views/stock-management/purchases/PurchaseReturn.vue"
                    ),
                name: "admin.stock.purchase-return.create",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases_return",
                    permission: (route) => "purchase_returns_creates",
                    orderType: "purchase-returns",
                },
            },

            // end of return create//

            {
                path: "purchases/create",
                component: () =>
                    import("../views/stock-management/purchases/Create.vue"),
                name: "admin.stock.purchases.create",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases",
                    permission: (route) => "purchases_create",
                    orderType: "purchases",
                },
            },

            {
                path: "purchases",
                component: () =>
                    import("../views/stock-management/purchases/index.vue"),
                name: "admin.stock.purchases.index",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchases",
                    permission: (route) => "purchases_view",
                    orderType: "purchases",
                },
            },

            // Purchase Returns
            {
                path: "purchase-returns/edit/:id",
                component: () =>
                    import("../views/stock-management/purchases/Edit.vue"),
                name: "admin.stock.purchase-returns.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchase_returns",
                    permission: (route) => "purchase_returns_edit",
                    orderType: "purchase-returns",
                },
            },
            {
                path: "purchase-returns/create",
                component: () =>
                    import("../views/stock-management/purchases/Create.vue"),
                name: "admin.stock.purchase-returns.create",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchase_returns",
                    permission: (route) => "purchase_returns_create",
                    orderType: "purchase-returns",
                },
            },
            {
                path: "purchase-returns",
                component: () =>
                    import("../views/stock-management/purchases/index.vue"),
                name: "admin.stock.purchase-returns.index",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: (route) => "purchase_returns",
                    permission: (route) => "purchase_returns_view",
                    orderType: "purchase-returns",
                },
            },

            // Sales
            {
                path: "sales/edit/:id",
                component: () =>
                    import("../views/stock-management/purchases/Edit.vue"),
                name: "admin.stock.sales.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "sales",
                    permission: (route) => "sales_edit",
                    orderType: "sales",
                },
            },
            {
                path: "sales/create",
                component: () =>
                    import("../views/stock-management/purchases/Create.vue"),
                name: "admin.stock.sales.create",
                props: true,
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "sales",
                    permission: (route) => "sales_create",
                    orderType: "sales",
                },
            },
            {
                path: "sales",
                component: () =>
                    import("../views/stock-management/purchases/index.vue"),
                name: "admin.stock.sales.index",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "sales",
                    permission: (route) => "sales_view",
                    orderType: "sales",
                },
            },

            // Sales Returns
            {
                path: "sales-returns/edit/:id",
                component: () =>
                    import("../views/stock-management/purchases/Edit.vue"),
                name: "admin.stock.sales-returns.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "sales_returns",
                    permission: (route) => "sales_returns_edit",
                    orderType: "sales-returns",
                },
            },
            {
                path: "sales-returns/create",
                component: () =>
                    import(
                        "../views/stock-management/purchases/SalesReturn.vue"
                    ),
                name: "admin.stock.sales-returns.create",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "sales_returns",
                    permission: (route) => "sales_returns_create",
                    orderType: "sales-returns",
                },
            },
            {
                path: "sales-returns",
                component: () =>
                    import(
                        "../views/stock-management/purchases/salesEntryIndex.vue"
                    ),
                name: "admin.stock.sales-returns.index",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "sales_returns",
                    permission: (route) => "sales_returns_view",
                    orderType: "sales-returns",
                },
            },

            // Quotaiton/Estimate
            {
                path: "quotations/edit/:id",
                component: () =>
                    import("../views/stock-management/purchases/Edit.vue"),
                name: "admin.stock.quotations.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "quotations",
                    permission: (route) => "quotations_edit",
                    orderType: "quotations",
                },
            },
            {
                path: "quotations/create",
                component: () =>
                    import(
                        "../views/stock-management/purchases/CreateQuotation.vue"
                    ),
                name: "admin.stock.quotations.create",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "quotations",
                    permission: (route) => "quotations_create",
                    orderType: "quotations",
                },
            },

            {
                path: "quotations/edit",
                component: () =>
                    import(
                        "../views/stock-management/purchases/EditQuotation.vue"
                    ),
                name: "admin.stock.quotations.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "quotations",
                    permission: (route) => "quotations_edit",
                    orderType: "quotations",
                },
            },


            {
                path: "quotations",
                component: () =>
                    import(
                        "../views/stock-management/purchases/quotationindex.vue"
                    ),
                name: "admin.stock.quotations.index",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: (route) => "quotations",
                    permission: (route) => "quotations_view",
                    orderType: "quotations",
                },

            },



            /// expense route//


            {
                path: "expensemanger",
                component: () =>
                    import(
                        "../views/Expense/Expenseindex.vue"
                    ),
                name: "admin.stock.expense.index",
                meta: {
                    requireAuth: true,
                   // menuParent: "sales",
                    menuKey: (route) => "expense",
                    permission: (route) => "quotations_view",
                    orderType: "expense",
                },

            },

            // {
            //     path: "expensemanger/create",
            //     component: () =>
            //         import(
            //             "../../main/views/Expense-category/ExpenseCategoryCreate.vue"
            //         ),
            //     name: "admin.stock.expensecategory.create",
            //     meta: {
            //         requireAuth: true,
            //         menuKey: (route) => "expense",
            //         permission: (route) => "quotations_view",
            //         orderType: "expense",
            //     },
            // },




            //end expense route//




            // Online Orders
            {
                path: "online-orders",
                component: () =>
                    import("../views/stock-management/online-orders/index.vue"),
                name: "admin.online_orders.index",
                meta: {
                    requireAuth: true,
                    menuParent: "online_orders",
                    menuKey: "online_orders",
                    orderType: "online-orders",
                },
            },

            // Stock Transfer
            {
                path: "stock-transfers/edit/:id",
                component: () =>
                    import("../views/stock-management/purchases/Edit.vue"),
                name: "admin.stock.stock-transfers.edit",
                meta: {
                    requireAuth: true,
                    menuParent: "stock_transfer",
                    menuKey: (route) => "stock_transfer",
                    permission: (route) => "stock_transfers_edit",
                    orderType: "stock-transfers",
                },
            },
            {
                path: "stock-transfers/create",
                component: () =>
                    import("../views/stock-management/purchases/Create.vue"),
                name: "admin.stock.stock-transfers.create",
                meta: {
                    requireAuth: true,
                    menuParent: "stock_transfer",
                    menuKey: (route) => "stock_transfer",
                    permission: (route) => "stock_transfers_create",
                    orderType: "stock-transfers",
                },
            },
            {
                path: "stock-transfers",
                component: () =>
                    import(
                        "../views/stock-management/stock-transfer/index.vue"
                    ),
                name: "admin.stock.stock-transfers.index",
                meta: {
                    requireAuth: true,
                    menuParent: "stock_transfer",
                    menuKey: (route) => "stock_transfer",
                    permission: (route) => "stock_transfers_view",
                    orderType: "stock-transfers",
                },
            },
        ],
    },
    {
        path: "/admin/payment/",
        component: () => import("../../common/layouts/Admin.vue"),
        children: [
            {
                path: "in",
                component: () =>
                    import("../views/stock-management/payments/index.vue"),
                name: "admin.payments.in",
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: "payment_in",
                    permission: "payment_in_view",
                    paymentType: "in",
                },
            },
            {
                path: "out",
                component: () =>
                    import("../views/stock-management/payments/index.vue"),
                name: "admin.payments.out",
                meta: {
                    requireAuth: true,
                    menuParent: "purchases",
                    menuKey: "payment_out",
                    permission: "payment_out_view",
                    paymentType: "out",
                },
            },
        ],
    },
];
