import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "shipping-list";
    //const url = `sales-number?fields=id,xid,ledger_id,cus_name,address,mobile_number,gender,age,billing_discount,customer_type,customer_status&party`;
    const hashableColumns = ["id"];
    const { t } = useI18n();

    const initData = {
        xid: undefined,
        id: undefined,
        shipping_city: undefined,
        shipping_address: undefined,
        shipping_pincode: undefined,
        shipping_contact_number: undefined,
    };
    const columns = [
        {
            title: t("Shipping City"),
            dataIndex: "shipping_city",
            sorter: true,
        },

        {
            title: t("Shipping Pincode"),
            dataIndex: "shipping_pincode",
            sorter: true,
        },
        {
            title: t("Shipping Address"),
            dataIndex: "shipping_address",
            sorter: true,
        },
        {
            title: t("Shipping Contact No"),
            dataIndex: "shipping_contact_number",
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
            key: "shipping_address",
            value: t("common.shipping_address"),
        },
        {
            key: "shipping_contact_number",
            value: t("common.shipping_contact_number"),
        },
        {
            key: "shipping_pincode",
            value: t("common.shipping_pincode"),
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
