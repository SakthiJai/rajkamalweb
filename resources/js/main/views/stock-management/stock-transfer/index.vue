<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.stock_transfer`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.stock_transfer`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space>
                    <template
                        v-if="
                            (permsArray.includes('stock_transfers_create') ||
                                permsArray.includes('admin')) &&
                            filters.transfer_type == 'transfered'
                        "
                    >
                        <a-button type="primary" style="background-color: #1f6d70;" @click="createNewStockTransfer">
                            <PlusOutlined />
                            {{ $t("stock_transfer.add") }} / F2
                        </a-button>
                    </template>
                    <!-- <a-button
                        v-if="
                            selectedRowIds.length > 0 &&
                            (permsArray.includes('stock_transfers_delete') ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        @click="orderTableRef.showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button> -->
                </a-space>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
                        <a-input-search
                        ref="searchInputRef"
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
                    <a-col
                        v-if="filters.transfer_type == 'transfered'"
                        :xs="24"
                        :sm="24"
                        :md="8"
                        :lg="6"
                        :xl="6"
                    >
                        <a-select
                        ref="warehouseSelectRef"
                            v-model:value="filters.warehouse_id"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('warehouse.warehouse'),
                                ])
                            "
                            :allowClear="true"
                            style="width: 100%"
                            optionFilterProp="title"
                            show-search
                        >
                            <a-select-option
                                v-for="warehouse in warehouses"
                                :key="warehouse.xid"
                                :title="warehouse.name"
                                :value="warehouse.xid"
                            >
                                {{ warehouse.name }}
                            </a-select-option>
                        </a-select>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="6" :xl="6">
                        <DateRangePicker
                            ref="datePickerRef"
                            @dateTimeChanged="
                                (changedDateTime) => (filters.dates = changedDateTime)
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
                <a-tabs v-model:activeKey="filters.transfer_type">
                    <a-tab-pane key="transfered" :tab="$t('stock_transfer.transfered')" />
                    <a-tab-pane key="received" :tab="$t('stock_transfer.received')" />
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
             @child-select="handleEnterEdit" 
            @onRowSelection="(selectedIds) => (selectedRowIds = selectedIds)"
            @onEditRow="handleEditRow"
        />
    </admin-page-table-content>
</template>

<script>
import { onMounted, watch, ref, onBeforeUnmount } from "vue";
import { PlusOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import { useRouter } from "vue-router";
import common from "../../../../common/composable/common";
import OrderTable from "../../../components/order/OrderTable.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        OrderTable,
        DateRangePicker,
        AdminPageHeader,
    },
    setup() {
        const {
            formatAmountCurrency,
            orderType,
            orderPageObject,
            orderStatus,
            permsArray,
            selectedWarehouse,
        } = common();
        const router = useRouter();
        const selectedRowIndex = ref(-1);
        const searchInputRef = ref(null);
        const warehouseSelectRef = ref(null);
        const datePickerRef = ref(null);
        const filterFocusIndex = ref(0);

        const warehouses = ref([]);
        const serachDateRangePicker = ref(null);

        const selectedRowIds = ref([]);
        const orderTableRef = ref(null);
        // Create new stock transfer handler
        const createNewStockTransfer = () => {
            localStorage.setItem("selectedInvoice", null);
            router.push({
                name: "admin.stock.stock-transfers.create",
            });
        };
const handleEditRow = (row) => {
    console.log("Row to edit:", row);

    if (row && row.invoice_number) {
        localStorage.setItem("selectedInvoice", row.invoice_number);

        router.push({
            name: "admin.stock.stock-transfers.create",
            query: { invoice_number: row.invoice_number } // ✅ correct
        });
        console.log("Received invoice:", row.invoice_number);
    }
};
const handleEnterEdit = (invoiceNumber) => {
    console.log("ENTER received:", invoiceNumber);

    if (!invoiceNumber) return; // safety check

    localStorage.setItem("selectedInvoice", invoiceNumber);

    router.push({
        name: "admin.stock.stock-transfers.create",
        query: { invoice_number: invoiceNumber }
    });
};
        const handleKeyDown = (event) => {

            // LEFT / RIGHT for filter controls
            if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
                event.preventDefault();
                const controls = [
                    searchInputRef,
                    warehouseSelectRef,
                    datePickerRef,
                ].filter(Boolean);
                if (event.key === "ArrowRight") {
                    filterFocusIndex.value =
                        (filterFocusIndex.value + 1) % controls.length;
                } else {
                    filterFocusIndex.value =
                        (filterFocusIndex.value - 1 + controls.length) % controls.length;
                }
                const current = controls[filterFocusIndex.value];
                current.value?.focus?.();
                current.value?.$el?.querySelector("input")?.focus();
                return;
            }

            // Forward ArrowUp/ArrowDown to OrderTable's test method
            if (event.key === "ArrowUp" || event.key === "ArrowDown") {
                event.preventDefault();
                if (orderTableRef.value && typeof orderTableRef.value.test === 'function') {
                    orderTableRef.value.test(event);
                }
                return;
            }

            if (event.code === "F2") {
                event.preventDefault();
                if (
                    (permsArray.value.includes("stock_transfers_create") ||
                        permsArray.value.includes("admin")) &&
                    filters.value.transfer_type === "transfered"
                ) {
                    createNewStockTransfer();
                }
                return;
            }
            if (event.key === "Enter") {
    event.preventDefault();

    if (orderTableRef.value && typeof orderTableRef.value.test === "function") {
        orderTableRef.value.test(event);
    }
    return;
}
        };

        const filters = ref({
            payment_status: "all",
            warehouse_id: undefined,
            dates: [],
            // searchColumn: "invoice_number",
            transfer_type: "transfered",
            searchString: "",
        });

        onMounted(() => {
            const warehouseUrl = `warehouses?filters=id ne "${selectedWarehouse.value.xid}"&hashable=${selectedWarehouse.value.xid}&limit=10000`;
            const warehousesPromise = axiosAdmin.get(warehouseUrl);

            Promise.all([warehousesPromise]).then(([warehousesResponse]) => {
                warehouses.value = warehousesResponse.data;
            });

            window.addEventListener("keydown", handleKeyDown);

            setTimeout(() => {
                searchInputRef.value?.focus?.();
                searchInputRef.value?.$el?.querySelector("input")?.focus();
            }, 150);
        });

        onBeforeUnmount(() => {
            window.removeEventListener("keydown", handleKeyDown);
        });

        watch(selectedWarehouse, (newVal, oldVal) => {
            filters.value = {
                order_status: "all",
                user_id: undefined,
                dates: [],
                searchColumn: "invoice_number",
                transfer_type: "transfered",
                searchString: "",
            };

            serachDateRangePicker.value.resetPicker();
        });

        return {
            orderPageObject,
            permsArray,
            orderStatus,
            searchInputRef,
            warehouseSelectRef,
            datePickerRef,
            formatAmountCurrency,
            warehouses,
            filters,
            orderType,
            serachDateRangePicker,
            selectedRowIds,
            orderTableRef,
            createNewStockTransfer,
            handleEditRow,
            handleEnterEdit,
        };
    },
};
</script>
