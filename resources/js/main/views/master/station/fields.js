import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "station";
	const { t } = useI18n();

	const initData = {
		station_name: "",
		description: "",
	};

	const columns = [
		{
			title: t("station.station_list_title"),
			dataIndex: "station_name",
			sorter:true
		},
		// {
		// 	title: t("expense_category.description"),
		// 	dataIndex: "description",
		// },
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "station_name",
			value: t("common.station_name")
		},
	];

	return {
		addEditUrl,
		initData,
		columns,
		filterableColumns
	}
}

export default fields;