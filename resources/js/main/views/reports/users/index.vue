<template>
    <div id="userreportsindex">
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.users_reports`)" class="p-0">
                <template #extra>
                    <ExprotTable
                        exportType="user_reports_export_table"
                        tableName="users-reports-export-table"
                        :title="$t('menu.users_reports')"
                    />
                </template>
            </a-page-header>
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.reports`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.users_reports`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10"> </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="24" :lg="10" :xl="10">
                        <a-input-group compact>
                            <a-select
                                style="width: 35%"
                                v-model:value="table.searchColumn"
                                :placeholder="
                                    $t('common.select_default_text', [''])
                                "
                            >
                                <a-select-option
                                    v-for="filterableColumn in filterableColumns"
                                    :key="filterableColumn.key"
                                >
                                    {{ filterableColumn.value }}
                                </a-select-option>
                            </a-select>
                            <a-input-search
                                ref="userSearchInputRef"
                                style="width: 65%"
                                v-model:value="table.searchString"
                                show-search
                                @focus="isSearchFocused = true"
                                @blur="isSearchFocused = false"
                                @change="onTableSearch"
                                @search="onTableSearch"
                                :loading="table.filterLoading"
                            />
                        </a-input-group>
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <a-row>
            <a-col :span="24">
                <a-tabs v-model:activeKey="userType" @change="setUrlData">
                    <a-tab-pane
                        v-if="
                            permsArray.includes('customers_view') ||
                            permsArray.includes('admin')
                        "
                        key="customers"
                        :tab="$t('menu.customers')"
                    />
                    <a-tab-pane
                        v-if="
                            permsArray.includes('suppliers_view') ||
                            permsArray.includes('admin')
                        "
                        key="suppliers"
                        :tab="$t('menu.suppliers')"
                    />
                </a-tabs>
            </a-col>
        </a-row>

        <a-row :gutter="[16, 16]">
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :columns="columns"
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled: false,
                                name: record.xid,
                            }),
                            type: 'radio',
                        }"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        :scroll="{ y: 350 }"
                        @change="handleTableChange"
                        id="users-reports-table"
                        bordered
                        size="middle"
                    >
                        <template #bodyCell="{ column, text, record }">
                            <template v-if="column.dataIndex === 'name'">
                                <UserInfo :user="record" />
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.purchase_order_count'
                                "
                            >
                                <a
                                    v-if="
                                        permsArray.includes('purchases_view') ||
                                        permsArray.includes('admin')
                                    "
                                    @click="openUserReportDrawer('', record)"
                                >
                                    {{ record.details?.purchase_order_count ?? 0 }}
                                </a>
                                <span v-else>{{
                                    record.details?.purchase_order_count ?? 0
                                }}</span>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.purchase_return_count'
                                "
                            >
                                <a
                                    v-if="
                                        permsArray.includes(
                                            'purchase_returns_view'
                                        ) || permsArray.includes('admin')
                                    "
                                    @click="
                                        openUserReportDrawer('returns', record)
                                    "
                                >
                                    {{ record.details?.purchase_return_count ?? 0 }}
                                </a>
                                <span v-else>{{
                                    record.details?.purchase_return_count ?? 0
                                }}</span>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.sales_order_count'
                                "
                            >
                                <a
                                    v-if="
                                        permsArray.includes('sales_view') ||
                                        permsArray.includes('admin')
                                    "
                                    @click="openUserReportDrawer('', record)"
                                >
                                    {{ record.details?.sales_order_count ?? 0 }}
                                </a>
                                <span v-else>{{
                                    record.details?.sales_order_count ?? 0
                                }}</span>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.sales_return_count'
                                "
                            >
                                <a
                                    v-if="
                                        permsArray.includes(
                                            'sales_returns_view'
                                        ) || permsArray.includes('admin')
                                    "
                                    @click="
                                        openUserReportDrawer('returns', record)
                                    "
                                >
                                    {{ record.details?.sales_return_count ?? 0 }}
                                </a>
                                <span v-else>
                                    {{ record.details?.sales_return_count ?? 0 }}
                                </span>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.total_amount'
                                "
                            >
                                {{
                                    formatAmountCurrency(
                                        convertToPositive(
                                            record.details?.total_amount ?? 0
                                        )
                                    )
                                }}
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.paid_amount'
                                "
                            >
                                {{
                                    formatAmountCurrency(
                                        convertToPositive(
                                            record.details?.paid_amount ?? 0
                                        )
                                    )
                                }}
                            </template>
                            <template
                                v-if="
                                    column.dataIndex ===
                                    'user_details.due_amount'
                                "
                            >
                                <UserBalance
                                    :amount="record.details?.due_amount ?? 0"
                                />
                            </template>
                        </template>
                        <template #summary>
                            <a-table-summary fixed>
                                <a-table-summary-row>
                                    <a-table-summary-cell :col-span="5">
                                    </a-table-summary-cell>
                                    <a-table-summary-cell :col-span="1">
                                        <a-typography-text strong>
                                            {{ $t("common.total") }}
                                        </a-typography-text>
                                    </a-table-summary-cell>
                                    <a-table-summary-cell :col-span="1">
                                        <a-typography-text strong>
                                            {{
                                                formatAmountCurrency(
                                                    totals.totalAmount
                                                )
                                            }}
                                        </a-typography-text>
                                    </a-table-summary-cell>
                                    <a-table-summary-cell :col-span="1">
                                        <a-typography-text strong>
                                            {{
                                                formatAmountCurrency(
                                                    totals.paidAmount
                                                )
                                            }}
                                        </a-typography-text>
                                    </a-table-summary-cell>
                                    <a-table-summary-cell :col-span="1">
                                        <a-tooltip
                                            v-if="totals.dueAmount < 0"
                                            :title="$t('payments.you_will_pay')"
                                        >
                                            <ArrowUpOutlined
                                                :style="{ color: 'red' }"
                                            />
                                            {{
                                                formatAmountCurrency(
                                                    totals.dueAmount
                                                )
                                            }}
                                        </a-tooltip>
                                        <a-tooltip
                                            v-else
                                            :title="$t('payments.you_will_receive')"
                                        >
                                            <span v-if="totals.dueAmount > 0">
                                                <ArrowDownOutlined
                                                    :style="{ color: 'green' }"
                                                />
                                            </span>
                                            {{
                                                formatAmountCurrency(
                                                    totals.dueAmount
                                                )
                                            }}
                                        </a-tooltip>
                                    </a-table-summary-cell>
                                </a-table-summary-row>
                            </a-table-summary>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>

        <Details
            :visible="detailsVisible"
            @closed="detailsVisible = false"
            :data="selectedUser"
            :orderType="activeOrderType"
            :destroyOnClose="true"
        />

        <div class="export-only-table">
            <table id="users-reports-export-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Purchases</th>
                        <th>Purchase Return / Dr.Note</th>
                        <th>Sales</th>
                        <th>Sales Return / Cr.Note</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="record in table.data"
                        :key="`export-${record.xid}`"
                    >
                        <td>{{ record.name || "" }}</td>
                        <td>{{ record.details?.purchase_order_count ?? 0 }}</td>
                        <td>{{ record.details?.purchase_return_count ?? 0 }}</td>
                        <td>{{ record.details?.sales_order_count ?? 0 }}</td>
                        <td>{{ record.details?.sales_return_count ?? 0 }}</td>
                        <td>
                            {{
                                formatAmountCurrency(
                                    convertToPositive(
                                        record.details?.total_amount ?? 0
                                    )
                                )
                            }}
                        </td>
                        <td>
                            {{
                                formatAmountCurrency(
                                    convertToPositive(
                                        record.details?.paid_amount ?? 0
                                    )
                                )
                            }}
                        </td>
                        <td>
                            {{
                                formatAmountCurrency(
                                    convertToPositive(
                                        record.details?.due_amount ?? 0
                                    )
                                )
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </admin-page-table-content>
    </div>
</template>

<script>
import {
    onMounted,
    onBeforeMount,
    ref,
    watch,
    computed,
    nextTick,
    onBeforeUnmount,
} from "vue";
import { useRouter } from "vue-router";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import fields from "./fields";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import Details from "./Details.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import UserBalance from "../../users/UserBalance.vue";
import ExprotTable from "../../../components/report-exports/ExportTable.vue";
import { ArrowDownOutlined, ArrowUpOutlined } from "@ant-design/icons-vue";

export default {
    components: {
        UserInfo,
        Details,
        AdminPageHeader,
        UserBalance,
        ExprotTable,
        ArrowUpOutlined,
        ArrowDownOutlined
    },
    setup() {
        const { userType, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const {
            formatAmountCurrency,
            getOrderTypeFromstring,
            statusColors,
            permsArray,
            convertToPositive,
            selectedWarehouse,
            willSubscriptionModuleVisible,
        } = common();
        const detailsVisible = ref(false);
        const selectedUser = ref({});
        const activeOrderType = ref("sales");
        const router = useRouter();
        const userSearchInputRef = ref(null);
        const selectedRowIndex = ref(-1);
        const isSearchFocused = ref(false);

        const scrollSelectedRowIntoView = async () => {
            await nextTick();

            const tableBody = document.querySelector(
                "#userreportsindex .ant-table-body"
            );
            const selectedRow = document.querySelector(
                "#userreportsindex .ant-table-tbody > tr.ant-table-row-selected"
            );

            if (!tableBody || !selectedRow) {
                return;
            }

            const rowTop = selectedRow.offsetTop;
            const rowBottom = rowTop + selectedRow.offsetHeight;
            const visibleTop = tableBody.scrollTop;
            const visibleBottom = visibleTop + tableBody.clientHeight;

            if (rowTop < visibleTop) {
                tableBody.scrollTop = rowTop;
            } else if (rowBottom > visibleBottom) {
                tableBody.scrollTop = rowBottom - tableBody.clientHeight;
            }
        };

        const updateSelectedRow = async (row) => {
            if (!row) {
                return;
            }

            crudVariables.table.selectedRowKeys = [row.xid];
            await scrollSelectedRowIntoView();
        };

        const onRowSelectChange = (selectedKeys) => {
            crudVariables.table.selectedRowKeys = selectedKeys;

            if (selectedKeys.length > 0) {
                selectedRowIndex.value = crudVariables.table.data.findIndex(
                    (row) => row.xid === selectedKeys[0]
                );
                scrollSelectedRowIntoView();
            } else {
                selectedRowIndex.value = -1;
            }
        };

        const handleKeyDown = (event) => {
            if (
                isSearchFocused.value &&
                !["ArrowUp", "ArrowDown", "Enter"].includes(event.key)
            ) {
                return;
            }

            const data = crudVariables.table.data;

            if (event.key === "ArrowDown") {
                event.preventDefault();
                if (data.length === 0) return;

                if (selectedRowIndex.value < 0) {
                    selectedRowIndex.value = 0;
                } else if (selectedRowIndex.value < data.length - 1) {
                    selectedRowIndex.value++;
                }

                updateSelectedRow(data[selectedRowIndex.value]);
            }

            if (event.key === "ArrowUp") {
                event.preventDefault();
                if (data.length === 0) return;

                if (selectedRowIndex.value < 0) {
                    selectedRowIndex.value = 0;
                } else if (selectedRowIndex.value > 0) {
                    selectedRowIndex.value--;
                }

                updateSelectedRow(data[selectedRowIndex.value]);
            }
        };

        onBeforeMount(() => {
            if (
                !(
                    permsArray.value.includes("customers_view") ||
                    permsArray.value.includes("suppliers_view") ||
                    permsArray.value.includes("admin")
                ) ||
                !willSubscriptionModuleVisible("reports")
            ) {
                router.push("admin.dashboard.index");
            }

            if (
                permsArray.value.includes("customers_view") ||
                permsArray.value.includes("admin")
            ) {
                userType.value = "customers";
            } else if (
                permsArray.value.includes("suppliers_view") ||
                permsArray.value.includes("admin")
            ) {
                userType.value = "suppliers";
            }
        });

        onMounted(() => {
            setUrlData();
            window.addEventListener("keydown", handleKeyDown);

            // Auto-focus the user search input after mount
            setTimeout(() => {
                if (userSearchInputRef.value?.focus) {
                    userSearchInputRef.value.focus();
                } else {
                    userSearchInputRef.value?.$el?.querySelector('input')?.focus();
                }
            }, 0);
        });

        onBeforeUnmount(() => {
            window.removeEventListener("keydown", handleKeyDown);
        });

        const setUrlData = () => {
            crudVariables.table.pagination = {
                ...crudVariables.table.pagination,
                pageSize: 100,
                current: 1,
            };

            crudVariables.tableUrl.value = {
                url: `${userType.value}?fields=id,xid,user_type,name,email,profile_image,profile_image_url,phone,details{purchase_order_count,purchase_return_count,sales_order_count,sales_return_count,total_amount,paid_amount,due_amount}`,
                filters: "",
            };
            crudVariables.table.filterableColumns = filterableColumns;
            crudVariables.exportDetails.value = {
                allowExport: true,
                exportType: "user_reports",
            };

            crudVariables.fetch({
                page: 1,
            });
        };

        const openUserReportDrawer = (orderType, selectedUserData) => {
            selectedUser.value = selectedUserData;
            activeOrderType.value = orderType;
            detailsVisible.value = true;
        };

        watch(selectedWarehouse, (newVal, oldVal) => {
            setUrlData();
        });

        const totals = computed(() => {
            let totalAmount = 0;
            let dueAmount = 0;
            let paidAmount = 0;
            crudVariables.table.data.forEach((tableRowData) => {
                totalAmount += tableRowData.details?.total_amount ?? 0;
                dueAmount += tableRowData.details?.due_amount ?? 0;
                paidAmount += tableRowData.details?.paid_amount ?? 0;
            });
            return {
                totalAmount,
                dueAmount,
                paidAmount,
            };
        });

        return {
            columns,
            ...crudVariables,
            filterableColumns,
            statusColors,
            userType,
            formatAmountCurrency,
            convertToPositive,
            getOrderTypeFromstring,
            setUrlData,
            permsArray,

            detailsVisible,
            openUserReportDrawer,
            selectedUser,
            activeOrderType,
            totals,
            userSearchInputRef,
            isSearchFocused,
            onRowSelectChange,
        };
    },
};
</script>

<style scoped>
#userreportsindex :deep(.ant-table-thead > tr > th),
#userreportsindex :deep(.ant-table-tbody > tr > td) {
    padding: 5px !important;
}

.export-only-table {
    position: absolute;
    left: -99999px;
    top: 0;
    width: 1px;
    height: 1px;
    overflow: hidden;
}
</style>
