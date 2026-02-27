import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "product-category?fields=id,xid,product_category_name";

    const addEditUrl = "product-category";
    const { t } = useI18n();

    const initData = {
        product_category_name: "",
    };

    const columns = [
        {
            title: t("product category"),
            dataIndex: "product_category_name",
            sorter: true,
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "product_category_name",
            value: t("product_category_name"),
        },
    ];

    return {
        addEditUrl,
        initData,
        columns,
        filterableColumns,
    };
};

export default fields;
