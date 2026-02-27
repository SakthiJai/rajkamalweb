import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	//const addEditUrl = "products";
	const url = "products?fields=xid,id,name,packing,margin,hsn,free_scheme_1,free_scheme_2,mfr_name,w_o_free,deal_free,stock,cgst,lgst,sgst,hsn,company_name,unit_name,sale_rate,unit_1st,unit,mrp,purchase_rate,cost,company_id";
	const hashableColumns = ['id'];
	const { t } = useI18n();

	const initData = {
		xid: undefined,
		packing:undefined,
		// product_id: undefined,
		// quantity: 1,
		// notes: "",
		// adjustment_type: "add",
	};

	const columns = [
		// {
		// 	title: "Description",
		// 	dataIndex: "name",
		// },
		// {
		// 	title: "Packing",
		// 	dataIndex: "packing",
		// },
		// {
		// 	title: "Stock",
		// 	dataIndex: "stock",
		// },
		// {
		// 	title: "Unit",
		// 	dataIndex: "unit_name",
		// },
		// {
		// 	title: "₹ Sale Rate",
		// 	dataIndex: "sale_rate",
		// },

        {
                title: t("Description"),
                dataIndex: ['name'],
                sorter: (a, b) => {
                    const aMobileNumber = a.party_name?.name || ''; 
                    const bMobileNumber = b.party_name?.name || '';
                    
                    return aMobileNumber.localeCompare(bMobileNumber); 
                },
                sortDirections: ['ascend', 'descend'],
            },


            {
                title: t("Packing"),
                dataIndex: ['packing'],
                sorter: (a, b) => {
                    const aMobileNumber = a.customer?.packing || ''; 
                    const bMobileNumber = b.customer?.packing || '';
                    
                    return aMobileNumber.localeCompare(bMobileNumber); 
                },
                sortDirections: ['ascend', 'descend'],
            },
            
            
			
        {
                title: t("Stock"),
                dataIndex: "stock",
                sorter: (a, b) => {
                    const aMobileNumber = a.customer?.Stock || ''; 
                    const bMobileNumber = b.customer?.Stock || '';
                    
                    return aMobileNumber.localeCompare(bMobileNumber); 
                },
                sortDirections: ['ascend', 'descend'],
		},
		
		{
                title: t("Unit"),
                dataIndex: "unit_name",
                sorter: (a, b) => {
                    const aMobileNumber = a.customer?.unit_name || ''; 
                    const bMobileNumber = b.customer?.unit_name || '';
                    
                    return aMobileNumber.localeCompare(bMobileNumber); 
                },
                sortDirections: ['ascend', 'descend'],
		},
		
		{
                title:"₹ "+ t("Sales Rate"),
                dataIndex: "sale_rate",align:"right",
                sorter: (a, b) => {
                    const aMobileNumber = a.customer?.sale_rate || ''; 
                    const bMobileNumber = b.customer?.sale_rate || '';
                    
                    return aMobileNumber.localeCompare(bMobileNumber); 
                },
                sortDirections: ['ascend', 'descend'],
            },

		// {
		// 	title: t("common.action"),
		// 	dataIndex: "action",
		// },
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
	const editItem = (product) => {
        console.log(product);
    };
	const filterableColumns = [
		{
			key: "name",
			value: t("common.name") 
		},
	];

	return {
		url,
		//addEditUrl,
		hashableColumns,
		initData,
		columns,
		adjustmentTypes,
		editItem,
		filterableColumns
	}
}

export default fields;