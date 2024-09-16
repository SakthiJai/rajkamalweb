import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "country";
	const { t } = useI18n();

	const initData = {
		name: "",
	};

	const columns = [
		{
			title: t("country.country_list_title"),
			dataIndex: "name",
			sorter:true
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("common.name")
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