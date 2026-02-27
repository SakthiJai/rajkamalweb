import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "country";
	const { t } = useI18n();

	const initData = {
		name: "",
		product_type:"",
		packing:"",
		unit_1st:"",
		unit_in_decimal:"",
		hsn_sac:"",
		tax_category:"",
		company:"",
		god_owns:"",
		opening_stock:"",
		
	};

	const columns = [
		{
		    title: t("Description"),
			dataIndex: "name",
			sorter:true
		},
		{
		    title: t("Packing"),
			dataIndex: "packing",
			sorter:true
		},
		{
		    title: t("Stock"),
			dataIndex: "stock",
			sorter:true
		},
		{
		    title: t("Unit"),
			dataIndex: "unit_name",
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