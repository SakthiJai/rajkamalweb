<template>
    <div id="stockalertindex">
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.stock_alert`)" class="p-0">
                <template #extra>
                    <ExportTable
                        exportType="stock_alert_reports"
                        tableName="stock-alert-reports-table"
                        :title="`${$t('menu.stock_alert')} ${$t(
                            'menu.reports'
                        )}`"
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
                    {{ $t(`menu.stock_alert`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10"> </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="6">
                        <ProductSearchInput
                            ref="productSearchInputRef"
                            @valueChanged="
                                (productId) => {
                                    searchProductId = productId;
                                    getTableData();
                                }
                            "
                        />
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :columns="stockAlertColumns"
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
                        :scroll="{ y: 500 }"
                        @change="handleTableChange"
                        id="stock-alert-reports-table"
                        bordered
                        size="middle"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'name'">
                                <a-badge>
                                    <a-avatar
                                        shape="square"
                                        :src="record.image_url"
                                    />
                                    {{ record.name }}
                                </a-badge>
                            </template>
                            <template
                                v-if="column.dataIndex === 'current_stock'"
                            >
                                {{
                                    `${record.details?.current_stock ?? 0} ${record.unit?.short_name ?? ""}`
                                }}
                            </template>
                            <template
                                v-if="
                                    column.dataIndex === 'stock_quantitiy_alert'
                                "
                            >
                                {{
                                    `${record.details?.stock_quantitiy_alert ?? 0} ${record.unit?.short_name ?? ""}`
                                }}
                            </template>
                        </template>
                        <template #summary>
                            <a-table-summary-row>
                                 <a-table-summary-cell :col-span="1">
                                </a-table-summary-cell>
                                <a-table-summary-cell :col-span="1">
                                    <a-typography-text strong>
                                        {{ $t("common.total") }}
                                    </a-typography-text>
                                </a-table-summary-cell>
                                <a-table-summary-cell :col-span="1">
                                    <a-typography-text strong>
                                        <a-tooltip>
                                            {{
                                               (
                                                    totals.totalCurrentStock
                                                )
                                            }}
                                        </a-tooltip>
                                    </a-typography-text>
                                </a-table-summary-cell>
                            </a-table-summary-row>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
    </div>
</template>
<script>
import { onMounted, ref, onBeforeMount, watch, computed, nextTick, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";
import ProductSearchInput from "../../../../common/components/product/ProductSearchInput.vue";
import common from "../../../../common/composable/common";
import datatable from "../../../../common/composable/datatable";
import fields from "./fields";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import ExportTable from "../../../components/report-exports/ExportTable.vue";

export default {
    components: {
        ProductSearchInput,
        AdminPageHeader,
        ExportTable,
    },
    setup() {
        const {
            permsArray,
            selectedWarehouse,
            willSubscriptionModuleVisible,
            formatAmountCurrency,
        } = common();
        const { url, stockAlertColumns, stockAlertHashableColumns } = fields();
        const searchProductId = ref(undefined);
        const productSearchInputRef = ref(null);
        const selectedRowIndex = ref(-1);
        const router = useRouter();
        const datatableVariables = datatable();

        const scrollSelectedRowIntoView = async () => {
            await nextTick();

            const tableBody = document.querySelector("#stockalertindex .ant-table-body");
            const selectedRow = document.querySelector(
                "#stockalertindex .ant-table-tbody > tr.ant-table-row-selected"
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

            datatableVariables.table.selectedRowKeys = [row.xid];
            await scrollSelectedRowIntoView();
        };

        const onRowSelectChange = (selectedKeys) => {
            datatableVariables.table.selectedRowKeys = selectedKeys;

            if (selectedKeys.length > 0) {
                selectedRowIndex.value = datatableVariables.table.data.findIndex(
                    (row) => row.xid === selectedKeys[0]
                );
                scrollSelectedRowIntoView();
            } else {
                selectedRowIndex.value = -1;
            }
        };

        const handleKeyDown = (event) => {
            const activeElement = document.activeElement;
            const searchInput = productSearchInputRef.value?.$el?.querySelector("input");

            if (
                searchInput &&
                activeElement === searchInput &&
                !["ArrowUp", "ArrowDown", "Enter"].includes(event.key)
            ) {
                return;
            }

            const data = datatableVariables.table.data;

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
                    permsArray.value.includes("products_view") ||
                    permsArray.value.includes("admin")
                ) ||
                !willSubscriptionModuleVisible("reports")
            ) {
                router.push("admin.dashboard.index");
            }
        });

        onMounted(() => {
            getTableData();
            window.addEventListener("keydown", handleKeyDown);

            // Auto-focus the product search input after mount
            setTimeout(() => {
                if (productSearchInputRef.value?.focus) {
                    productSearchInputRef.value.focus();
                } else {
                    productSearchInputRef.value?.$el?.querySelector('input')?.focus();
                }
            }, 0);
        });

        onBeforeUnmount(() => {
            window.removeEventListener("keydown", handleKeyDown);
        });

        const getTableData = () => {
            datatableVariables.table.pagination = {
                ...datatableVariables.table.pagination,
                pageSize: 100,
                current: 1,
            };

            datatableVariables.tableUrl.value = {
                url,
                filters: {
                    "products.id": searchProductId.value,
                },
                extraFilters: {
                    fetch_stock_alert: true,
                },
            };
            datatableVariables.hashable.value = [...stockAlertHashableColumns];
            datatableVariables.exportDetails.value = {
                allowExport: true,
                exportType: "stock_alert_reports",
            };

            datatableVariables.fetch({
                page: 1,
            });
        };

        const totals = computed(() => {
            let totalCurrentStock = 0;
            datatableVariables.table.data.forEach((tableRowData) => {
                totalCurrentStock += tableRowData.details?.current_stock ?? 0;
            });
            return {
                totalCurrentStock,
            };
        });

        watch(selectedWarehouse, (newVal, oldVal) => {
            getTableData();
        });

        return {
            stockAlertColumns,
            ...datatableVariables,

            searchProductId,
            getTableData,
            permsArray,
            totals,
            formatAmountCurrency,
            productSearchInputRef,
            onRowSelectChange,
        };
    },
};
</script>
<style>
#stockalertindex .ant-table-thead > tr > th,
#stockalertindex .ant-table-tbody > tr > td {
    padding: 3px !important;
}
</style>