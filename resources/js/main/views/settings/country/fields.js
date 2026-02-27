import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "country?fields=id,xid,country_name";
	const addEditUrl = "country";
	const { t } = useI18n();

	const initData = {
		country_name: "",
		short_name: "",
		parent_id: null,
		operator: "multiply",
		operator_value: 1,
	};

	const columns = [
		{
			title: t("country.country_list_title"),
			dataIndex: "country_name",
			sorter:true
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "country_name",
			value: t("common.country_name")
		},
	];

	return {
		url,
		addEditUrl,
		initData,
		columns,
		filterableColumns
	}
}

export default fields;