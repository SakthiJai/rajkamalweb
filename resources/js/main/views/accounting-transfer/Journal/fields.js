import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    const { t } = useI18n();

    const orderType = ref("");
    const columns = ref([]);

    const hashableColumns = [
        "user_id",
        "warehouse_id",
        "party_customer_id",
        "transfer_type",
    ];

    const initPaymentData = {
        date: undefined,
        payment_mode_id: undefined,
        amount: "",
        notes: "",
    };

    const orderStatus = [
        {
            key: "pending",
            value: t("common.unpaid"),
        },
        {
            key: "paid",
            value: t("common.paid"),
        },
    ];

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
            value: t("stock.bill_no"),
        },
        {
            key: "bill_number",
            value: t("Voucher No"),
        },
    ];

    const pageObject = computed(() => {
        if (orderType.value === "stock-transfers") {
            return {
                type: "stock-transfers",
                langKey: "stock_transfer",
                menuKey: "stock_transfer",
                userType: "customers",
                permission: "stock_transfers",
            };
        }

        if (orderType.value === "journals" || orderType.value === "journal") {
            return {
                type: "journal",
                langKey: "journal",
                menuKey: "journal",
                userType: "customers",
                permission: "journal",
            };
        }

        return {
            type: orderType.value || "journal",
            langKey: "journal",
            menuKey: "journal",
            userType: "customers",
            permission: "journal",
        };
    });

    const setupTableColumns = () => {
        columns.value = [
            {
                title: t("Date"),
                dataIndex: "order_date",
                sorter: true,
            },
            {
                title: t("Voucher No"),
                dataIndex: "invoice_number",
                sorter: true,
            },
            {
                title: t("Party Name"),
                dataIndex: ["party", "party_name"],
                sorter: true,
            },
            {
                title: t("Station"),
                dataIndex: ["warehouse", "name"],
                sorter: true,
            },
            {
                title: t("Inst. Type."),
                dataIndex: "payment_status",
            },
            {
                title: t("Inst. No."),
                dataIndex: "bill_number",
            },
            {
                title: t("stock.sales_amount"),
                dataIndex: "total",
                sorter: true,
            },
            {
                title: t("payments.paid_amount"),
                dataIndex: "paid_amount",
                sorter: true,
            },
            {
                title: t("payments.due_amount"),
                dataIndex: "due_amount",
                sorter: true,
            },
            {
                title: t("common.action"),
                dataIndex: "action",
            },
        ];
    };

    return {
        columns,
        hashableColumns,
        setupTableColumns,
        filterableColumns,
        pageObject,
        orderType,
        orderStatus,
        orderItemDetailsColumns,
        initPaymentData,
    };
};

export default fields;
