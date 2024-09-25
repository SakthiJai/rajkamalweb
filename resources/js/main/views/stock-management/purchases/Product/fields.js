import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    const addEditUrl = "stock-adjustments";
    const url =
        // "products?fields=xid,id,name,packing,stock,unit_1st,sales_rate,mrp,purchase_rate,cost,margin,deal_free,w_o_free,hsn_sac,igst,company";

        "products?fields: xid,id,name,packing,stock,unit_1st,sales_rate,mrp,purchase_rate,cost,margin,deal_free,w_o_free,hsn_sac,igst,company,company_id,warehouse_id,product_type,parent_id,parent_item_code,slug,barcode_symbology,item_code,image,category_id,brand_id,unit_id,description,user_id,created_at,updated_at,unit_in_decimal,tax_category,sale_rate,rate_b,free_scheme_1,free_scheme_2,scheme_type,status,color_type,rate_d,rate_f,discount,item_disc_1_percent,volume_disc_1,max_disc_percent,min_quantity,max_quantity,reorder_days,reorder_qty,min_margin_percent,prohibited,visibility,mfr_name,upload_image";

    const hashableColumns = ["product_id"];
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
            dataIndex: "unit_1st",
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
    };
};

export default fields;
