import { reactive } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "rate-enquiry";
    const url = "rate-enquiry?fields=xid,product_id,rate,product{id,xid,name,image,image_url}";
    const hashableColumns = ['product_id'];
    const { t } = useI18n();

    const initData = {
        product_id: undefined,
        rate: 0,
        notes: "",
    };

   const columns = [
   
    {
        title: "Product Name",
        dataIndex: "product_name",
        key: "product_name",
    },
    {
        title: "Purchase Rate",
        dataIndex: "purchase_rate",
        key: "purchase_rate",
    },
    {
        title: "Selling Rate",
        dataIndex: "selling_rate",
        key: "selling_rate",
    },
    {
        title: "MRP",
        dataIndex: "mrp",
        key: "mrp",
    },
    {
        title: "GST Code",
        dataIndex: "gst_code",
        key: "gst_code",
    },
    {
        title: "Action",
        key: "action",
        slots: { customRender: "action" },
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
        filters,
    };
};

export default fields;
