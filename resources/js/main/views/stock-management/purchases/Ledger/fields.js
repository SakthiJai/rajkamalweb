import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	//const addEditUrl = "store-ledger";
	const url = "store-ledger?fields=id,xid,party_name,gst_number,debit,credit,station_name,station,name,state_name,Address,stock_country,stock_state,stock_city,parent_ledger,account_group,mail_to,stock_pincode,balancing_method,opening_balance,credit_days,phone_number,mobile_number,whatsapp_number,ledger_type,pan_number,customer_title,customer_first_name,customer_last_name,gender,designation,website,customer_email,bank_name,branch,ifsc_code,account_number,account_type,account_holder_name";
	const hashableColumns = ['id'];
	const { t } = useI18n();

	
	const initData = {
		xid: undefined,
		name:undefined,
		state_name:undefined,
		party_name: undefined,
		station: undefined,
		station_name:undefined,
		Address:undefined,
		mail_to: undefined,
		stock_country: undefined,
		stock_state: undefined,
		stock_city: undefined,
		parent_ledger: undefined,
		account_group: undefined,
		stock_pincode: undefined,
		balancing_method: undefined,
		opening_balance: undefined,
		credit_days: undefined,
		phone_number: undefined,
		mobile_number: undefined,
		whatsapp_number: undefined,
		ledger_type: undefined,
		pan_number: undefined,
		customer_title: undefined,
		customer_first_name: undefined,
		customer_last_name: undefined,
		gender: undefined,
		designation: undefined,
		website: undefined,
		customer_email: undefined,
		bank_name: undefined,
		branch: undefined,
		ifsc_code: undefined,
		account_number: undefined,
		account_type: undefined,
		account_holder_name: undefined,
		gst_number:undefined,
		debit:undefined,
		credit:undefined,
		
	};
	

	const columns = [
	
		{
			title: t("stock.sales_ledger_name"),
			dataIndex: "party_name",
			sorter:true
		},
		{
			title: t("stock.sales_station"),
			dataIndex: "station_name",
			sorter:true
		},
		{
			title: "₹ " + t("stock.sales_balance"),
			dataIndex: "opening_balance",
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
			key: "party_name",
			value: t("common.party_name") 
		},
	];
	

	const editItem = (product) => {
        console.log(product);
    };

	return {
		url,
		//addEditUrl,
		hashableColumns,
		initData,
		columns,
		adjustmentTypes,
		filterableColumns,
		editItem,
	}
}

export default fields;