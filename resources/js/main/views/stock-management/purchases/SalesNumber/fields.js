import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "sales-number";
    //const url = `sales-number?fields=id,xid,ledger_id,cus_name,address,customer_city,mobile_number,gender,age,billing_discount,customer_type,customer_status&party`;
    const hashableColumns = ["id"];
    const { t } = useI18n();

    const initData = {
        xid: undefined,
        id:undefined,
        name:undefined,
        ledger_id:undefined,
        mobile_number: undefined,
        gender: undefined,
        age:undefined,
        billing_discount:undefined,
        customer_type: undefined,
        customer_status: undefined,
    };
    const columns = [
        {
            title: t("stock.sales_number"),
            dataIndex: "mobile_number",
            sorter: true,
        },
        {
            title: t("stock.sales_name"),
            dataIndex: "cus_name",
            sorter: true,
        },

        
        {
            title: t("Whatsapp Number"),
            dataIndex: "whatsapp_numbner",
            sorter: true,
        },
        {
            title: t("stock.address"),
            dataIndex: "address",
            sorter: true,
        },
        {
            title: t("City"),
            dataIndex: "customer_city",
            sorter: true,
        },
        {
            title: t("State"),
            dataIndex: "customer_state",
            sorter: true,
        },
        

        {
            title: t("common.action"),
            dataIndex: "action",
            key: "action",
            scopedSlots: { customRender: "action" },
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
            key: "cus_name",
            value: t("common.cus_name"),
        },
        {
            key: "mobile_number",
            value: t("common.mobile_number"),
        },
    ];

    const editItem = (product) => {
        console.log(product);
    };

    return {
        //url,
        addEditUrl,
        hashableColumns,
        initData,
        columns,
        adjustmentTypes,
        filterableColumns,
        editItem,
    };
};

export default fields;
