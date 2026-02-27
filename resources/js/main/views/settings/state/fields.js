import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "states?fields=id,xid,state_name,state_code";
	const addEditUrl = "state";
	const { t } = useI18n();

	const initData = {
		name: "",
		state_name: "",
		state_code: "",
		parent_id: null,
		operator: "multiply",
		operator_value: 1,
	};

	const columns = [
		{
			title: t("state.state_name"),
			dataIndex: "state_name",
			sorter:true
		},
		{
           title: t("State Code"),
		   dataIndex: "state_code",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "state_name",
			value: t("common.name")
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