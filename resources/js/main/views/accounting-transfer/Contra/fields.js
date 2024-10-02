import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "expense-categories";
    const { t } = useI18n();

    const initData = {
        name: "",
        description: "",
    };

    const columns = [
        {
            title: t(" Date"),
            dataIndex: "Date",
        },

        {
            title: t("Voucher No"),
            dataIndex: "Voucher No",
        },
        {
            title: t("Party Name"),
            dataIndex: "Party Name",
        },
        {
            title: t("Station"),
            dataIndex: "station",
        },
        {
            title: t("Inst. Type."),
            dataIndex: "Inst. Type.",
        },
        {
            title: t("Inst. No."),
            dataIndex: "Inst. No.",
        },
        {
            title: t("₹ Amount"),
            dataIndex: "amount",
        },
    ];

    const filterableColumns = [
        {
            key: "name",
            value: t("common.name"),
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
