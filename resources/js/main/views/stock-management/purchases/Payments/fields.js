import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "payment-mode";
	const url = "payment-mode?fields=id,xid,bill_amount,balance_adjusted,amount,settlement_mode,remarks,cash_tender,cash_return,bill_number";
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
			title: t("stock.settlement_mode"),
			dataIndex: "settlement_mode",
			sorter:true
		},
		
		{
			title: t("stock.payment_amount"),
			dataIndex: "cus_name",
			sorter:true
		},
		{
			title: t("stock.remarks"),
			dataIndex: "gender",
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
			key: "bill_number",
			value: 'bill_number'
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