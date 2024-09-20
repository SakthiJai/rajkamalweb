import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "stock-adjustments";
	const url = "products?fields=xid,id,name,packing,stock,unit,sales_rate,mrp,purchase_rate,cost,margin,deal_free,w_o_free,hsn_sac,igst,company";
	const hashableColumns = ['product_id'];
	const { t } = useI18n();

	const initData = {
		product_id: undefined,
		quantity: 1,
		notes: "",
		adjustment_type: "add",
	};

	const columns = [
		{
			title: "Description",
			dataIndex: "name",
		},
		{
			title: "Packing",
			dataIndex: "packing",
		},
		{
			title: "Stock",
			dataIndex: "stock",
		},
		{
			title: "Unit",
			dataIndex: "unit_first",
		},
		{
			title: "₹ Sale Rate",
			dataIndex: "sales_rate",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const adjustmentTypes = [
		{
			key: "add",
			value: t("stock_adjustment.adjustment_add"),
		},
		{
			key: "subtract",
			value: t("stock_adjustment.adjustment_subtract"),
		},
	];

	const filters = reactive({
		product_id: undefined,
	});

	return {
		url,
		addEditUrl,
		hashableColumns,
		initData,
		columns,
		adjustmentTypes,
		filters,
	}
}

export default fields;