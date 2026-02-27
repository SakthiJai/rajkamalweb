import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "Discount";
	const { t } = useI18n();

	const initData = {
		discount_type: "",
		status:"",
	};

	const columns = [
		{
			title: t("Discount percentage"),
			dataIndex: "discount_type",
			sorter:true
		},
		{
			title: t("Status"),
			dataIndex: "status",
			sorter:true
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "discount_type",
			value: t("common.discount_type")
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