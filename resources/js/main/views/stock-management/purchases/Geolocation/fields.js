import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "geolocation";
	const { t } = useI18n();

	const initData = {
		
		latitude:"",  // Make sure this property exists
		longitude:"", // Make sure this property exists
	};

	const columns = [
		{
			title: t("geolocation.latitude"),
			dataIndex: "latitude",
			sorter:true
		},
		{
			title: t("geolocation.longitude"),
			dataIndex: "longitude",
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