import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "god-owns?fields=id,xid,god_owns_name";

    const addEditUrl = "god-owns";
    const { t } = useI18n();

    const initData = {
        god_owns_name: "",
    };

    const columns = [
        {
            title: t("Godown Name"),
            dataIndex: "god_owns_name",
            sorter: true,
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "god_owns_name",
            value: t("common.god_owns_name"),
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
