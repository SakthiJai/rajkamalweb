import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";

const fields = () => {
    const { dayjs } = common();
    const store = useStore();
    const { t } = useI18n();
    const route = useRoute();
    const orderType = ref(route.meta.orderType);
    const columns = ref([]);
    const hashableColumns = ['user_id', 'warehouse_id','party_customer_id'];

    onMounted(() => {
        if (route.meta && route.meta.orderType) {
            orderType.value = route.meta.orderType;
        } else {
            orderType.value = "online-orders";
        }
    });

    const initData = {
        order_date: dayjs().utc().format("YYYY-MM-DDTHH:mm:ssZ"),
        user_id: undefined,
        notes: "",
        order_status: undefined,
        tax_id: undefined,
        warehouse_id: undefined,
        party_customer_id:undefined,
        
        discount: 0,
        shipping: 0,
        subtotal: 0,
    };

    const initPaymentData = {
        date: undefined,
        payment_mode_id: undefined,
        amount: "",
        notes: "",
    };

    const orderItemColumns = [
        {
            title: "#",
            dataIndex: "sn",
        },
        {
            title: t("product.name"),
            dataIndex: "name",
        },
        {
            title: t("product.quantity"),
            dataIndex: "unit_quantity",
        },
        {
            title: t("product.unit_price"),
            dataIndex: "single_unit_price",
        },
        {
            title: t("product.discount"),
            dataIndex: "total_discount",
        },
        {
            title: t("product.tax"),
            dataIndex: "total_tax",
        },
        {
            title: t("product.subtotal"),
            dataIndex: "subtotal",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];


    /* sales */

    const salesItemColumns = [
        {
            title: "#",
            dataIndex: "sn",
        },
      
        {
            title: t("product.additonal_details"),
            dataIndex: "unit_quantity",
        },
        {
            title: t("product.percentage "),
            dataIndex: "unit_quantity",
        },
        {
            title: t("product.amounts"),
            dataIndex: "unit_quantity1",
        },
       
       
    ];

     /*  end columnssales  */


    const orderItemDetailsColumns = [
        {
            title: t("product.product"),
            dataIndex: "product_id",
        },
        {
            title: t("product.quantity"),
            dataIndex: "quantity",
        },
        {
            title: t("product.unit_price"),
            dataIndex: "single_unit_price",
        },
        {
            title: t("product.discount"),
            dataIndex: "total_discount",
        },
        {
            title: t("product.tax"),
            dataIndex: "total_tax",
        },
        {
            title: t("product.subtotal"),
            dataIndex: "subtotal",
        },
    ];

    const filterableColumns = [
        {
            
            key: "invoice_number",
            value: t("stock.invoice_number")
        },
        {
            
            key: "party_name",
            value: t("stock.party_name")
        },
    ];

    const pageObject = computed(() => {
        var pageObjectDetails = {};

        if (orderType.value == "purchases") {
            pageObjectDetails = {
                type: "purchases",
                langKey: "purchase",
                menuKey: "purchases",
                userType: "suppliers",
                permission: "purchases",
            };
        } else if (orderType.value == "sales") {
            pageObjectDetails = {
                type: "sales",
                langKey: "sales",
                menuKey: "sales",
                userType: "customers",
                permission: "sales",
            };
        } else if (orderType.value == "purchase-returns") {
            pageObjectDetails = {
                type: "purchase-returns",
                langKey: "purchase_returns",
                menuKey: "purchase_returns",
                userType: "suppliers",
                permission: "purchase_returns",
            };
        } else if (orderType.value == "sales-returns") {
            pageObjectDetails = {
                type: "sales-returns",
                langKey: "sales_returns",
                menuKey: "sales_returns",
                userType: "customers",
                permission: "sales_returns",
            };
        } else if (orderType.value == "online-orders") {
            pageObjectDetails = {
                type: "online-orders",
                langKey: "online_orders",
                menuKey: "online_orders",
                userType: "customers",
                permission: "online_orders",
            };
        } else if (orderType.value == "quotations") {
            pageObjectDetails = {
                type: "quotations",
                langKey: "quotation",
                menuKey: "sales",
                userType: "customers",
                permission: "quotations",
            };
        } else if (orderType.value == "stock-transfers") {
            pageObjectDetails = {
                type: "stock-transfers",
                langKey: "stock_transfer",
                menuKey: "stock_transfer",
                userType: "customers",
                permission: "stock_transfers",
            };
        }

        return pageObjectDetails;
    });

    const setupTableColumns = () => {
        var allColumns = [
            {
                title: t(`stock.bill_no`),
                dataIndex: "invoice_number",
                sorter:true
            },
            // {
            //     title: t(`stock.bill_no`),
            //     dataIndex: "invoice_number",
            //     sorter:true
            // }
        ];

        if (pageObject.value.type == 'stock-transfers') {
            allColumns.push({
                title: t("stock_transfer.warehouse"),
                dataIndex: "warehouse",
                sorter:true,
                sorter_field:"orders.warehouse_id"
            });
        }
        if (pageObject.value.type == 'stock-transfers') {
            allColumns.push({
                title: t("stock_transfer.warehouse"),
                dataIndex: "stock",
                sorter:true,
                sorter_field:"orders.party_customer_id"
            });
        }

        allColumns.push({
            title: t(`${pageObject.value.langKey}.${pageObject.value.langKey}_date`),
            dataIndex: "order_date",
            sorter:true
        });

        /*if (pageObject.value.type != 'stock-transfers') {
            allColumns.push({
                title: t(`${pageObject.value.langKey}.user`),
                dataIndex: "user_id",
                sorter:true,
                sorter_field:"orders.user_id"
            });
        }*/

        columns.value = [
            ...allColumns,
            {
                title: t(`${pageObject.value.langKey}.user`),
                dataIndex: ['customer', 'cus_name'],
                sorter:true,
                //sorter_field:"orders.user_id"
            },
            
           
            {
                title: t("stock.party"),
                dataIndex: ['party_name', 'party_name'],
                sorter:true,
               
            },
            {
                title: t(`${pageObject.value.langKey}.${pageObject.value.langKey}_status`),
                dataIndex: "order_status",
                sorter:true,
            },
            {
                title: t("stock.sales_amount"),
                dataIndex: "payment_status",
                sorter:true,
            },
            {
                title: t("common.action"),
                dataIndex: "action",
            },
        ];
    };
<<<<<<< Updated upstream
=======
    salesReturnColumns.value = [
        {
            title: t(`CR.Number`),
            dataIndex: "cr_number",
            sorter: true,
        },
        {
            title: t("Date"),
            dataIndex: "order_date",
        },
        {
            title: t("stock.party"),
            dataIndex: ["party_name", "party_name"],
            sorter: true,
        },
        {
            title: t(`${pageObject.value.langKey}.user`),
            dataIndex: ["customer", "cus_name"],
            sorter: true,
            //sorter_field:"orders.user_id"
        },
        {
            title: t("stock.sales_amount"),
            dataIndex: "total",
            sorter: true,
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    //bill return//

    BillReturnColumns.value = [
        {
            title: t(`stock.invoices_number`),
            dataIndex: "invoice_number",
            sorter: true,
        },
        {
            title: t("stock.order_date"),
            dataIndex: "order_date",
        },

        {
            title: t("stock.party"),
            dataIndex: ["party_name", "party_full_name"],
            sorter: true,
        },

        {
            title: t("stock.sales_amount"),
            dataIndex: "total",
            sorter: true,
        },

        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

     // purchase return //
     purchaseReturnColumns.value = [
        {
            title: t(`stock.dr_numbers`),
            dataIndex: "dr_number",
            sorter: true,
        },
        {
            title: t("stock.order_date"),
            dataIndex: "order_date",
        },
        {
            title: t("stock.party"),
            dataIndex: ["party_name", "party_full_name"],
            sorter: true,
        },

        {
            title: t("stock.sales_amount"),
            dataIndex: "total",
            sorter: true,
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];
    //end purchase //


    ReceiptColumns.value = [
        {
            title: t(`Order Date`),
            dataIndex: "order_date",
            sorter: true,
        },
        {
            title: t("Vocher Number"),
            dataIndex: "voucher_number",
            //dataIndex: "party_full_name",
            sorter: true,
        },

        {
            title: t("Party Name"),
            dataIndex: ["party_name", "party_full_name"],
            sorter: true,
        },

        {
            title: t("Station"),
            dataIndex: "station",
        },

        {
            title: t("Ins.Type"),
            dataIndex: "inst_type",
        },
        {
            title: t("Ins.No"),
            dataIndex: "inst_no",
        },

        {
            title: t("Amount"),
            dataIndex: "amount",
        },

        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];
    /// end of bill retutn//

    ReceiptPaymentColumns.value = [
        {
            title: t(`Order Date`),
            dataIndex: "order_date",
            sorter: true,
        },
        {
            title: t("Voucher Number"),
            dataIndex: "payment_voucher",
             sorter: true,
        },

        {
            title: t("Party Name"),
            dataIndex: ["party_name", "party_full_name"],
            sorter: true,
        },

        {
            title: t("Station"),
            dataIndex: "station",
        },

        {
            title: t("Ins.Type"),
            dataIndex: "inst_type",
        },
        {
            title: t("Ins.No"),
            dataIndex: "inst_no",
        },

        {
            title: t("Amount"),
            dataIndex: "amount",
        },

        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    /// payment recepit//

    //end payment recepit//

    // Payment table data

    PaymentColumn.value = [
        {
            title: t("Date"),
            dataIndex: "order_date",
        },
        {
            title: t("Voucher No"),
            dataIndex: "invoice_number",
            // dataIndex: ["partyName", "party_name"],
        },
        {
            title: t("Party Name"),
            dataIndex: ["customer", "cus_name"],
        },
        {
            title: t("Station"),
            dataIndex: "total",
        },
        {
            title: t("Ins Type"),
            dataIndex: "order_date",
        },

        {
            title: t("Ins No"),
            dataIndex: "total",
        },
        {
            title: t("₹ Amount"),
            dataIndex: "total",
        },
        {
            title: t("Action"),
            dataIndex: "action",
        },
    ];
    //end payment table data

    /// expense column//
    ExpenseColumns.value = [
        {
            title: t(`stock.expense_number`),
            dataIndex: "expense_number",
            sorter: true,
        },

        {
            title: t("stock.order_date"),
            dataIndex: "order_date",
        },
        {
            title: t("stock.party"),
            dataIndex: ["party_name", "party_name"],
            sorter: true,
        },

        {
            title: t("stock.category"),
            dataIndex: "category_id",
            sorter: true,
        },

        {
            title: t("stock.sales_amount"),
            dataIndex: "total",
            sorter: true,
        },

        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];
    // end expense colunm//


>>>>>>> Stashed changes

    const orderPaymentsColumns = [
        {
            title: t("payments.transaction_number"),
            dataIndex: "payment_number",
        },
        {
            title: t("payments.date"),
            dataIndex: "date",
        },
        {
            title: t("payments.amount"),
            dataIndex: "amount",
        },
        {
            title: t("payments.payment_mode"),
            dataIndex: "payment_mode_id",
        },
      
    ];

    return {
        initData,
        initPaymentData,
        columns,
        hashableColumns,
        setupTableColumns,
        filterableColumns,
        pageObject,
        orderType,
        orderItemColumns,
        salesItemColumns,
        orderPaymentsColumns,
        orderItemDetailsColumns
    }
}

export default fields;
