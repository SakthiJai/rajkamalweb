import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "station?fields=id,xid,station_name";
    const addEditUrl = "station";
    const { t } = useI18n();

    const initData = {
        station_name: "",
        parent_id: null,
        operator: "multiply",
        operator_value: 1,
    };

    const columns = [
        {
            title: t("station.station_list_title"),
            dataIndex: "station_name",
            sorter: true,
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "station_name",
            value: t("common.station_name"),
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
