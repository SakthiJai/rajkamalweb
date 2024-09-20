import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "sales-number";
	const url = "sales-number?fields=id,xid,ledger_id,cus_name,mobile_number,gender,age,billing_discount,customer_type,customer_status";
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
			title: t("stock.sales_number"),
			dataIndex: "mobile_number",
			sorter:true
		},
		// {
		// 	title: t("stock.sales_id"),
		// 	dataIndex: "id",
		// 	sorter:true
		// },
		{
			title: t("stock.sales_name"),
			dataIndex: "cus_name",
			sorter:true
		},
		{
			title: t("stock.sales_gender"),
			dataIndex: "gender",
			sorter:true
		},
		{
			title: t("stock.sales_age"),
			dataIndex: "age",
			sorter:true
		},
		{
			title: t("stock.sales_status"),
			dataIndex: "customer_status",
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