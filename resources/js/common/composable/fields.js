import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
    //const addEditUrl = "store-ledger";
    const url =
        "sales?fields=id,total_items,total_quantity,xid,unique_id,warehouse_id,x_warehouse_id,warehouse{id,xid,name},from_warehouse_id,x_from_warehouse_id,fromWarehouse{id,xid,name},invoice_number,order_type,order_date,tax_amount,discount,shipping,subtotal,paid_amount,due_amount,order_status,payment_status,total,tax_rate,staff_user_id,x_staff_user_id,staffMember{id,xid,name,profile_image,profile_image_url,shipping_address,tax_number,email,user_type},user_id,x_user_id,user{id,xid,user_type,name,email,address,tax_number,profile_image,profile_image_url,phone},user:details{opening_balance,opening_balance_type,credit_period,credit_limit,due_amount,warehouse_id,x_warehouse_id},orderPayments{id,xid,amount,payment_id,x_payment_id},orderPayments:payment{id,xid,payment_number,amount,payment_mode_id,x_payment_mode_id,date,notes},orderPayments:payment:paymentMode{id,xid,name},items{id,xid,product_id,x_product_id,unit_id,x_unit_id,single_unit_price,unit_price,quantity,tax_rate,total_tax,tax_type,total_discount,subtotal,mrp},items:unit{id,xid,name,short_name},items:product{id,xid,name,image,image_url},items:product:unit{id,xid,name,short_name},items:product:details{id,xid,warehouse_id,x_warehouse_id,product_id,x_product_id,current_stock},items:orderItemTaxes{id,xid,order_item_id,order_item_id,tax_name,tax_amount},cancelled,terms_condition,shippingAddress{id,xid,order_id,name,email,phone,address,address,city,state,country,zipcode},party,customer{id,cus_name,mobile_number},bill_number,payment_status,invoice_path,";
    const hashableColumns = ["id"];
    const { t } = useI18n();

    const initData = {
        xid: undefined,
        name: undefined,
        state_name: undefined,
        party_name: undefined,
        party_type: undefined,
        station: undefined,
        station_name: undefined,
        Address: undefined,
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
        bill_number: undefined,
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
        ship_city: undefined,
        ship_address: undefined,
        ship_pincode: undefined,
        ship_contactno: undefined,
        gst_number: undefined,
        debit: undefined,
        credit: undefined,
        inputFields: undefined,
    };
    //const sortDirections= "ascend";

    const columns = [


        {
            title: t("Date"),
            dataIndex: ["order_date"],

            render: function (data, type, row, meta) {
                console.log("data",data);
                return data .split("T")[0];
            },
            /*render: (data) => {
                return data .split("T")[0]; // Show only the date
            }*/
            sortDirections: ["ascend", "descend"],
        },


        {
            title: t("Particular"),
            dataIndex: ["order_type"],
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.order_type || "";
                const bMobileNumber = b.customer?.order_type || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        {
            title: t("Vch/Bill No."),
            dataIndex: ["invoice_number"],
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.invoice_number || "";
                const bMobileNumber = b.customer?.invoice_number || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        {
            title: t("Voucher Type"),
            dataIndex: ["order_type"],
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.order_type || "";
                const bMobileNumber = b.customer?.order_type || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        {
            title: "₹ " + t("stock.debit"),
            dataIndex: ["due_amount"], align: 'right',
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.due_amount || "";
                const bMobileNumber = b.customer?.due_amount || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            className: 'column-money',
            render: (_, due_amount) => ({due_amount}
    ),
            sortDirections: ["ascend", "descend"],
        },

        {
            title: "₹ " + t("stock.credit"),
            dataIndex: ["paid_amount"], align: 'right',
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.paid_amount || "";
                const bMobileNumber = b.customer?.paid_amount || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        {
            title: "₹ " + t("stock.sales_balance"),
            dataIndex: ["opening_balance"], align: 'right',
            sorter: (a, b) => {
                const aMobileNumber = a.customer?.opening_balance || "";
                const bMobileNumber = b.customer?.opening_balance || "";

                return aMobileNumber.localeCompare(bMobileNumber);
            },
            sortDirections: ["ascend", "descend"],
        },

        // {
        // 	title: t("common.action"),
        // 	dataIndex: "action",
        // },
    ];
    const invoiceDataColumns = [
        {
            title: t("stock.item"),
            dataIndex: "product_name",
            sorter: true,
        },
        {
            title: t("stock.quantity"),
            dataIndex: "quantity",
            sorter: true,
        },

        {
            title: t("common.rate"),
            dataIndex: "single_unit_price",
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
            value: t("common.party_name"),
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
        //sortDirections,
        adjustmentTypes,
        filterableColumns,
        invoiceDataColumns,
        editItem,
    };
};

export default fields;
