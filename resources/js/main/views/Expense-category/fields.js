import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    const url =
        "expense-store?fields=id,company_id,warehouse_id,tax_type,entry_date,user_id,image,created_at,updated_at,party_type,expense_category,expense_date,original_bill_number,bill_amount,tax_amount,payment_mode,note";
    const hashableColumns = ["id"];
    const { t } = useI18n();

    const initData = {
        id: "",
        company_id: "",
        warehouse_id: "",
        tax_type: "With GST",
        entry_date: new Date().toISOString().split("T")[0],
        image: "",
        created_at: "",
        updated_at: "",
        party_type: "",
        expense_category: "",
        expense_date: "",
        original_bill_number: "",
        bill_amount: "0.00",
        tax_amount: "0.00",
        payment_mode: "",
        note: "",
    };

    const columns = [
        // {
        // 	title: t("stock.sales_ledger_name"),
        // 	dataIndex: "party_name",
        // 	sorter:true
        // },
        // {
        // 	title: t("stock.sales_station"),
        // 	dataIndex: "station_name",
        // 	sorter:true
        // },
        // {
        // 	title: "₹ " + t("stock.sales_balance"),
        // 	dataIndex: "opening_balance",
        // 	sorter:true
        // },

        {
            title: t("stock.sales_ledger_name"),
            dataIndex: ["party_name"],
            sorter: (a, b) => {
                const aMobileNumber = a.party_name?.party_name || "";
                const bMobileNumber = b.party_name?.party_name || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        {
            title: t("stock.sales_station"),
            dataIndex: ["station_name"],
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.station_name || "";
                const bMobileNumber = b.customer?.station_name || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },
        {
            title: t("stock.customer"),
            dataIndex: ["customer_first_name"],
        },
        {
            title: t("stock.sales_number"),
            dataIndex: ["mobile_number"],
        },
        {
            title: t("stock.whatsapp_number"),
            dataIndex: ["whatsapp_number"],
        },

        {
            title: "₹ " + t("stock.sales_balance"),
            align: "right",
            dataIndex: "opening_balance",
            headerStyle: " text-align: right",
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.opening_balance || "";
                const bMobileNumber = b.customer?.opening_balance || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        // {
        // 	title: t("common.action"),
        // 	dataIndex: "action",
        // },
    ];
    const invoiceDataColumns = [
        {
            title: t("Invoice"),
            dataIndex: "invoice_number",
            sorter: true,
        },
        ,
        {
            title: t("Item"),
            dataIndex: "product_name",
            sorter: true,
        },
        {
            title: t("Quantity"),
            dataIndex: "quantity",
            sorter: true,
        },

        {
            title: t("Rate"),
            dataIndex: "single_unit_price",
            align: "right",
            render: function (data, type, row) {
                return (100 * data).toFixed(2) + "%";
            },
        },
    ];

    const adjustmentTypes = [
        {
            key: "add",
            value: t("stock_adjustment.adjustment_add"),
        },
        {
            key: "subtract",
            value: t("stock_adjustment.adjustment_subtract"),
        },
    ];

    const filterableColumns = [
        {
            key: "party_name",
            value: t("common.party_name"),
        },
    ];

    const editItem = (product) => {
        console.log(product);
    };

    return {
        url,
        //addEditUrl,
        hashableColumns,
        initData,
        columns,
        //sortDirections,
        adjustmentTypes,
        filterableColumns,
        invoiceDataColumns,
        editItem,
    };
};

export default fields;
