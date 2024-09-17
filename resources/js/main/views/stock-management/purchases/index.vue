<template>
    <AdminPageHeader>

        <template #header>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                    <h3 class="gstinvoice">GST Invoice List</h3>
                </a-col>
                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                    <svg height="20" viewBox="0 0 576 512">
                        <path
                            class=""
                            d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"
                            fill="#ff0000"
                        ></path>
                    </svg>
                </a-col>
                <a-col :xs="24" :sm="24" :md="10" :lg="10"> </a-col>
                <a-col :xs="24" :sm="24" :md="9" :lg="9">
                    <a class="btn addEffset" href="/dashboard" title="Home">
                        <img
                            class=""
                            src="../../../../../../images/homeIcon.png"
                        />
                        <span class="effset"></span>
                    </a>
                    <a-button type="primary" class="bulkaction">
                        {{ $t("stock_adjustment.bulk_action") }}
                    </a-button>

                    <template
                        v-if="
                            permsArray.includes(
                                `${orderPageObject.permission}_create`
                            ) || permsArray.includes('admin')
                        "
                    >
                        <router-link
                            :to="{
                                name: `admin.stock.${orderPageObject.type}.create`,
                            }"
                        >
                            <a-button type="primary">
                                <PlusOutlined />
                                {{ $t(`${orderPageObject.langKey}.add`) }}
                            </a-button>
                        </router-link>
                    </template>
                </a-col>
            </a-row>
        </template>
    </AdminPageHeader>



    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space>
                    <template
                        v-if="
                            permsArray.includes(
                                `${orderPageObject.permission}_create`
                            ) || permsArray.includes('admin')
                        "
                    >
                        <router-link
                            :to="{
                                name: `admin.stock.${orderPageObject.type}.create`,
                            }"
                        >
                            <a-button type="primary">
                                <PlusOutlined />
                                {{ $t(`${orderPageObject.langKey}.add`) }}
                            </a-button>
                        </router-link>
                    </template>
                    <a-button
                        v-if="
                            selectedRowIds.length > 0 &&
                            (permsArray.includes(
                                `${orderPageObject.permission}_view`
                            ) ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        @click="orderTableRef.showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button>
                </a-space>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="6">
                        <a-input-search
                            style="width: 100%"
                            v-model:value="filters.searchString"
                            show-search
                            :placeholder="
                                $t('common.placeholder_search_text', [
                                    $t('stock.invoice_number'),
                                ])
                            "
                        />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8" :xl="6">
                        <a-select
                            v-model:value="filters.user_id"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t(`${orderPageObject.langKey}.user`),
                                ])
                            "
                            :allowClear="true"
                            style="width: 100%"
                            optionFilterProp="title"
                            show-search
                        >
                            <a-select-option
                                v-for="user in users"
                                :key="user.xid"
                                :title="user.name"
                                :value="user.xid"
                            >
                                {{ user.name }}
                            </a-select-option>
                        </a-select>
                    </a-col>

                        <col :xs="24" :sm="24" :md="5" :lg="5">
                        <div>
                            <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                <a-space>
                                    <template
                                        v-if="
                                            permsArray.includes(
                                                'stock_adjustments_create'
                                            ) || permsArray.includes('admin')
                                        "
                                    >
                                        <a-button
                                            type="primary"
                                            @click="showModal"
                                        >
                                            <PlusOutlined />
                                            {{ $t("stock_adjustment.filter") }}
                                        </a-button>
                                    </template>
                                </a-space>
                            </a-col>
                            <ExpenseCategoryModal
                                v-if="isModalVisible"
                                :visible="isModalVisible"
                                :formData="formData"
                                :url="url"
                                :addEditType="addEditType"

                                :successMessage="successMessage"
                                @addEditSuccess="handleSuccess"
                                @closed="handleClose"
                            />
                        </div>
                    </col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <a-row>
            <a-col :span="24">
                <a-tabs v-model:activeKey="filters.payment_status">
                    <a-tab-pane
                        key="all"
                        :tab="`${$t('common.all')} ${$t(
                            'menu.' + orderPageObject.menuKey
                        )}`"
                    />
                    <a-tab-pane
                        v-for="status in orderStatus"
                        :key="status.key"
                        :tab="`${status.value}`"
                    />
                </a-tabs>
            </a-col>
        </a-row>

        <OrderTable
            ref="orderTableRef"
            :orderType="orderType"
            :filters="filters"
            tableSize="middle"
            :bordered="true"
            :selectable="true"
            @onRowSelection="(selectedIds) => (selectedRowIds = selectedIds)"
        />
    </admin-page-table-content>
</template>
<script>
import { onMounted, watch, ref } from "vue";
import { PlusOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";
import OrderTable from "../../../components/order/OrderTable.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import ExpenseCategoryModal from "./ExpenseCategoryModal.vue";
export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        ExpenseCategoryModal,
        OrderTable,
        DateRangePicker,
        AdminPageHeader,
    },
    data() {
        return {
            isModalVisible: false,
            formData: {},
            url: "your-url-here",
            addEditType: "add",
            pageTitle: "GST Invoice Filters",
            successMessage: "Operation successful!",
        };
    },
    methods: {
        showModal() {
            this.isModalVisible = true;
        },
        handleClose() {
            this.isModalVisible = false;
        },
        handleSuccess(xid) {
            // Handle success logic
            this.isModalVisible = false;
            console.log("Success:", xid);
        },
    },
    setup() {
        const {
            formatAmountCurrency,
            orderType,
            orderPageObject,
            orderStatus,
            permsArray,
        } = common();
        const route = useRoute();

        const users = ref([]);
        const serachDateRangePicker = ref(null);

        const selectedRowIds = ref([]);
        const orderTableRef = ref(null);

        const filters = ref({
            payment_status: "all",
            user_id: undefined,
            dates: [],
            searchColumn: "invoice_number",
            searchString: "",
        });

        onMounted(() => {
            fetchUsers();
        });

        const fetchUsers = () => {
            const usersPromise = axiosAdmin.get(
                `${orderPageObject.value.userType}?limit=10000`
            );

            Promise.all([usersPromise]).then(([usersResponse]) => {
                users.value = usersResponse.data;
            });
        };

        watch(
            () => route.meta.orderType,
            (newVal, oldVal) => {
                if (
                    newVal == "purchases" ||
                    newVal == "purchase-returns" ||
                    newVal == "sales" ||
                    newVal == "sales-returns" ||
                    newVal == "quotations"
                ) {
                    orderType.value = route.meta.orderType;

                    filters.value = {
                        payment_status: "all",
                        user_id: undefined,
                        dates: [],
                        searchColumn: "invoice_number",
                        searchString: "",
                    };

                    fetchUsers();

                    serachDateRangePicker.value.resetPicker();
                }
            }
        );

        return {
            orderPageObject,

            permsArray,
            orderStatus,
            formatAmountCurrency,

            users,

            filters,
            orderType,
            serachDateRangePicker,
 selectedRowIds,
            orderTableRef,
        };
    },
};
</script>
<style>
.ant-row css-wosfq4 {
    height: 69px !important;
}
.gstinvoice {
    font-weight: bold;
    font-size: 16px;
}

.addEffset {
    padding-top: 4px;
    border-right: 1px solid #17d120;
    margin-right: 5px;
    padding-right: 8px;
}
.bulkaction {
    background-color: #f78d50 !important;
}
</style>
