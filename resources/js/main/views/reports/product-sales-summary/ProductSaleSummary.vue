<template>
    <a-row>
        <a-col :span="24">
            <div class="table-responsive">
                <a-table
                    :columns="columns"
                    :row-key="(record) => record.xid"
                    :data-source="table.data"
                    :pagination="table.pagination"
                    :loading="table.loading"
                    :scroll="{ y: 500 }"
                    @change="handleTableChange"
                    id="product-sales-summary-reports-table"
                    bordered
                    size="middle"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'name'">
                            <a-badge>
                            <a-avatar
                                shape="square"
                                :src="record.product?.image_url"
                            />
                            {{ record.product?.name || '-' }}
                            </a-badge>
                        </template>
                        <template v-if="column.dataIndex === 'unit_sold'">
                            {{ `${record.quantity} ${record.product?.unit?.short_name || ''}` }}
                        </template>
                        <template v-if="column.dataIndex === 'total_purchase_price'">
                            {{
                                formatAmountCurrency(
                                    record.quantity * (record.product?.details?.purchase_price || 0)
                                )
                            }}
                        </template>
                        <template v-if="column.dataIndex === 'total_sales_price'">
                            {{ formatAmountCurrency(record.subtotal) }}
                        </template>
                    </template>
                    <template #summary>
                        <a-table-summary-row>
                            <a-table-summary-cell :col-span="1"> </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{ $t("common.total") }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{ totals.unitSold }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{ formatAmountCurrency(totals.totalPurchasePrice) }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{ formatAmountCurrency(totals.totalSalesPrice) }}
                                </a-typography-text>
                            </a-table-summary-cell>
                        </a-table-summary-row>
                    </template>
                </a-table>
            </div>
        </a-col>
    </a-row>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import datatable from "../../../../common/composable/datatable";
import common from "../../../../common/composable/common";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import fields from "./fields";
import PaymentStatus from "../../../../common/components/order/PaymentStatus.vue";

export default defineComponent({
    props: {
        product_id: null,
        category_id: null,
        dates: {
            default: [],
            type: null,
        },
    },
    components: {
        UserInfo,
        PaymentStatus,
    },
    setup(props) {
        const { columns, hashableColumns, defaultSorter } = fields();
        const { formatDateTime, formatAmountCurrency, selectedWarehouse } = common();
        const datatableVariables = datatable();

        onMounted(() => {
            datatableVariables.table.default_sorter = defaultSorter;
            datatableVariables.table.pagination = {
                ...datatableVariables.table.pagination,
                pageSize: 100,
                current: 1,
                currentPage: 1,
                showSizeChanger: false,
            };

            const propsData = props;
            getData(propsData);
        });

        const getData = (propsData) => {
            datatableVariables.table.pagination = {
                ...datatableVariables.table.pagination,
                pageSize: 100,
                current: 1,
                currentPage: 1,
                showSizeChanger: false,
            };
            const filters = {};

            if (propsData.product_id && propsData.product_id != undefined) {
                filters.product_id = propsData.product_id;
            }

            datatableVariables.tableUrl.value = {
                url: "order-items?fields=id,xid,quantity,single_unit_price,unit_price,total_discount,discount_rate,total_tax,tax_rate,subtotal,order_id,x_order_id,order{id,xid,order_type,order_date},unit_id,x_unit_id,unit{id,xid,short_name},product_id,x_product_id,product{id,xid,unit_id,x_unit_id},product:unit{id,xid,short_name}",
                filters,
                extraFilters: {
                    dates: propsData.dates,
                    category_id: propsData.category_id,
                },
            };
            datatableVariables.hashable.value = [...hashableColumns];
            datatableVariables.table.sorter = { field: "product_id", order: "asc" };
            datatableVariables.exportDetails.value = {
                allowExport: true,
                exportType: "product_sales_summary_reports",
            };

            datatableVariables.fetch({
                page: 1,
            });
        };

        const totals = computed(() => {
            let totalPurchasePrice = 0;
            let totalSalesPrice = 0;
            let unitSold = 0;
            datatableVariables.table.data.forEach((tableRowData) => {
            totalPurchasePrice +=
                (tableRowData.product?.details?.purchase_price || 0) * tableRowData.quantity;

            totalSalesPrice += tableRowData.subtotal;
            unitSold += tableRowData.quantity;
            });
            return {
                totalPurchasePrice,
                totalSalesPrice,
                unitSold,
            };
        });

        watch(props, (newVal, oldVal) => {
            getData(newVal);
        });

        watch(selectedWarehouse, (newVal, oldVal) => {
            getData(props);
        });

        return {
            columns,
            ...datatableVariables,

            formatDateTime,
            formatAmountCurrency,
            totals,
        };
    },
});
</script>
