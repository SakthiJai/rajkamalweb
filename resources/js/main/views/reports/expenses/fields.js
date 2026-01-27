import { useI18n } from "vue-i18n";
import common from "../../../../common/composable/common";

const fields = () => {
    const { formatAmountCurrency, formatDate } = common();
    const url =
          `expenses1?fields=id,xid,tax_type,expense_category_id,orginal_bill_number,bill_amount,tax_amount,order_date,expense_date,image,image_url,notes,expense_category_id,expenseCategory{id,xid,name},party_id,party{id,party_name,party_type,party_full_name,phone_number},payment_mode_id,paymentModes{id,xid,name},user_id,user{name,user_type},modified_user_id,modified_user{name,user_type}&searchBy`;
    const { t } = useI18n();
    const expenseHashableColumns = ["user_id", "expense_category_id"];

    const expenseColumns = [
        {
            title: t("Expense Date"),
            dataIndex: "date",
            dbKey: "date",
            dataFormat: (row) => {
                return formatDate(row.date);
            },
            sorter: true,
        },
        {
            title: t("Expense category"),
            dataIndex: "expense_category_id",
            dbKey: "expense_category.name",
            dataFormat: (row) => {
                return row.expense_category && row.expense_category.name
                    ? row.expense_category.name
                    : "";
            },
            sorter: true,
        },
        {
            title: t(""),
            dataIndex: "",
            dbKey: "user.name",
            dataFormat: (row) => {
                return row.user && row.user.name ? row.user.name : "";
            },
        },
        {
            title: t("Bill Amount"),
            dataIndex: "amount",
            dbKey: "amount",
            dataFormat: (row) => {
                return formatAmountCurrency(row.amount);
            },
            sorter: true,
        },
    ];

    return {
        url,
        expenseColumns,
        expenseHashableColumns,
    };
};

export default fields;
