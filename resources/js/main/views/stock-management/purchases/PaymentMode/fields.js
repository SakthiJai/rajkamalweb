import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "sales-number";
	const url = "sales-number?fields=id,xid,ledger_id,name,mobile_number,gender,age,billing_discount,customer_type,customer_status";
	const hashableColumns = ['id'];
	const { t } = useI18n();
 
	const initData = {
		// xid: undefined,
		// id:undefined,
		// name:undefined,
		// ledger_id:undefined,
		// mobile_number: undefined,
		// gender: undefined,
		// age:undefined,
		// billing_discount:undefined,
		// customer_type: undefined,
		// customer_status: undefined,
	
	};
	const columns = [
		{
			title: t("Settlement Mode"),
			dataIndex: "",
			sorter:true
		},
		// {
		// 	title: t("stock.sales_id"),
		// 	dataIndex: "id",
		// 	sorter:true
		// },
		{
			title: t("₹ Amount"),
			dataIndex: "",
			sorter:true
		},
		{
			title: t("Remarks"),
			dataIndex: "",
			sorter:true
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

	const filterableColumns = [
		{
			key: "name",
			value: t("common.name") 
		},
	];
	

	const editItem = (product) => {
        console.log(product);
    };

	return {
		url,
		addEditUrl,
		hashableColumns,
		initData,
		columns,
		adjustmentTypes,
		filterableColumns,
		editItem,
	}
}

export default fields;