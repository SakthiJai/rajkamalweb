import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "payment-mode";
    const url =
        "payment-mode?fields=id,xid,bill_amount,balance_adjusted,amount,settlement_mode,settlement_id,remarks,cash_tender,cash_return,bill_number";
    const hashableColumns = ["id"];
    const { t } = useI18n();

    const initData = {
        
    };
    const paymentColumns = [{
			title: t("stock.settlement_mode"),
			dataIndex: "settlement_mode",
			sorter:true
		},
		
		{
			title: t("stock.payment_amount"),
			dataIndex: "cus_name",
			sorter:true
		},
		{
			title: t("stock.remarks"),
			dataIndex: "gender",
			sorter:true
		},];

    const columns = [
        // {
        //     title: "S.No",
        //     dataIndex: "",
        //     sorter: true,
        // },

        {
            title: "Invoice Number",
            dataIndex: "invoice_number",
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.invoice_number || "";
                const bMobileNumber = b.customer?.invoice_number || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },
        {
            title: "Invoice Date",
            dataIndex: "invoiceDate",
            
            sortDirections: ["ascend", "descend"],
        },

        {
            title: t("No. of products"),
            dataIndex: "totalProducts",
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.products || "";
                const bMobileNumber = b.customer?.products || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        {
            title: "₹ " + t("Amount"),
            dataIndex: "tax_amount",
            align: "right",
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.recent_bill_amount || "";
                const bMobileNumber = b.customer?.recent_bill_amount || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
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
            key: "bill_number",
            value: "bill_number",
        },
    ];

    const editItem = (product) => {
        console.log(product);
    };

    return {
        url,
        addEditUrl,
        hashableColumns,
        initData,
        columns,
        paymentColumns,
        adjustmentTypes,
        filterableColumns,
        editItem,
    };
};

export default fields;
