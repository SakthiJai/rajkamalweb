import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "state";
	const { t } = useI18n();

	const initData = {
		state_name: "",
	};

	const columns = [
		{
			title: t("state.state_name"),
			dataIndex: "state_name",
			sorter:true
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "state_name",
			value: t("common.state_name")
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