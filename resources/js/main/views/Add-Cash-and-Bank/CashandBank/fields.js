import { useI18n } from "vue-i18n";

const fields = () => {
    const url =
        "receiptbank?fields=id,xid,bank_name,accountant_name,ifsc_code,account_number";
    const addEditUrl = "receiptbank";
    const { t } = useI18n();

    const initData = {
        bank_name: "",
        accountant_name: "",
        ifsc_code: "",
        account_number: "",
    };

    const columns = [
        {
            title: t("Account Number"),
            dataIndex: "account_number",
        },

        {
            title: t("Accountant Name"),
            dataIndex: "accountant_name",
        },
        {
            title: t("IFSC Code"),
            dataIndex: "ifsc_code",
            sorter: true,
        },

        {
            title: t("Bank Name"),
            dataIndex: "bank_name",
            sorter: true,
        },

        {
            title: t("Action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "Accountant Name",
            value: t("accountant_name"),
        },
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
    };
};

export default fields;
