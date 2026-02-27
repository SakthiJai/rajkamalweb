import { useI18n } from "vue-i18n";

const fields = () => {
    //const url ="taxes?fields=id,name,rate,tax_type,parent_id&filters=parent_id eq null";
    const url =
        "taxes?fields=id,xid,sales_type,lgst,cgst,sgst,cess";
    const addEditUrl = "taxes";
    const { t } = useI18n();
    const hashableColumns = ["id"];

    const initData = {
        sales_type: "",
        lgst:"",
        cgst:"",
        sgst:"",
    };

    const columns = [
        {
            title: t("tax.name"),
            dataIndex: "sales_type",
            sorter: true,
        },
        {
            title: t("IGST"),
            dataIndex: "lgst",
            sorter: true,
        },
        {
            title: t("CGST"),
            dataIndex: "cgst",
            sorter: true,
        },
        {
            title: t("SGST"),
            dataIndex: "sgst",
            sorter: true,
        },

        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "name",
            value: t("tax.name"),
        },


    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
    };
};

export default fields;
