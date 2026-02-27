import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import common from "../../../../../common/composable/common";

const fields = () => {
    const { dayjs } = common();
    const store = useStore();
    const { t } = useI18n();
    const route = useRoute();

    const columns = ref([]);

    onMounted(() => {});

    const initData = {
        tax_type: "",
        notes: "",
        payment_mode: "",
        orginal_bill_number: "",
        image: "",
        bill_amount: 0,
        tax_amount: 0,
        order_date: dayjs().utc().format("YYYY-MM-DDTHH:mm:ssZ"),
        expense_date: dayjs().utc().format("YYYY-MM-DDTHH:mm:ssZ"),

        expense_category_id: undefined,
        party_id: undefined,
        image: undefined,

        discount: 0,
        shipping: 0,
        subtotal: 0,
    };

    const filterableColumns = [
        {
            key: "orginal_bill_number",
            value: t("orginal_bill_number"),
        },
        {
            key: "party_id",
            value: t("party_id"),
        },
        {
            key: "party_full_name",
            value: t("party_full_name"),
        },
        {
            key: "name",
            value: t("name"),
        },
        {
            key: "entry_date",
            value: t("order_date"),
        },
    ];

    const setupTableColumns = () => {
        columns.value = [
            {
                title: t(`expense1.party`),
                dataIndex: ["party", "party_full_name"],
                sorter: true,
                //sorter_field:"orders.user_id"
            },
            {
                title: t(`expense1.expense_category`),
                dataIndex: ["expense_category", "name"],
                sorter: true,
                //sorter_field:"orders.user_id"
            },
            {
                title: t(`expense1.entry_date`),
                dataIndex: "order_date",
                sorter: true,
                //sorter_field:"orders.user_id"
            },
            {
                title: t(`expense1.expense_date`),
                dataIndex: "expense_date",
                sorter: true,
                //sorter_field:"orders.user_id"
            },

            {
                title: t(`expense1.bill_amount`),
                dataIndex: "bill_amount",
                align: "right",
                sorter: true,
                //sorter_field:"orders.user_id"
            },

            {
                title: t("common.action"),
                dataIndex: "action",
            },
        ];
    };

    return {
        initData,

        columns,

        setupTableColumns,
        filterableColumns,
        //pageObject,
    };
};

export default fields;
