import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "categorys";
    const { t } = useI18n();

    const initData = {
        category_name: "",
    };

    const columns = [
        {
            title: t("category.category_list"),
            dataIndex: "category_name",
            sorter: true,
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "category_name",
            value: t("common.category_name"),
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
