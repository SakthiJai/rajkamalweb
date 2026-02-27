import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "stock-adjustments";
	const url = "stock-adjustments?fields=xid,product_id,x_product_id,product{id,xid,name,image,image_url},quantity,adjustment_type,reason,value,packaging,updated_at";
	const hashableColumns = ['product_id'];
	const { t } = useI18n();

	const initData = {
	product_id: "",
	quantity: null,
	reason: "",
	packaging: "",
	value: null,
	adjustment_type: "add",
};


	const columns = [
		{
			title: t("Date"),
			dataIndex: "date",
			sorter: true
		},
		{
			title: t("Time"),
			dataIndex: "time",
			sorter: true
		},

		{
			title: t("product.product"),
			dataIndex: "product_id",
			sorter:true
		},
		{
			title: t("packaging"),
			dataIndex: "packaging",
			sorter:true
		},
		{
			title: t("Reason"),
			dataIndex: "reason",
			sorter:true
		},
		{
			title: t("stock_adjustment.quantity"),
			dataIndex: "quantity",
			sorter:true
		},
		{
			title: t("Value"),
			dataIndex: "value",
			sorter:true
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