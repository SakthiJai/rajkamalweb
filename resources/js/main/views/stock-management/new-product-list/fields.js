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
        title: "Code",
        dataIndex: "xid",
        key: "xid",
    },   
    {
        title: "Product Name",
        dataIndex: "product_name",
        key: "product_name",
    },
    {
        title: "Package",
        dataIndex: "package",
        key: "package",
    },
    {
        title: "Manufacturer",
        dataIndex: "manufacturer",
        key: "manufacturer",
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
