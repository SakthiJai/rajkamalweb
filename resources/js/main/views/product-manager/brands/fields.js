import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "brands";
    const { t } = useI18n();

    const initData = {
        brands_name: "",
        slug: "",
        image: undefined,
        image_url: undefined,
    };

    const columns = [
        {
            title: t("Brand Name"),
            dataIndex: "brands_name",
            sorter: true,
        },
        {
            title: t("brand.logo"),
            dataIndex: "image",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "brands_name",
            value: t("brand.brands_name"),
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
