<template>
    <a-row>
        <a-col :span="24">
            <div class="table-responsive">
                <a-table
                    :columns="purchaseReturnColumnss"
                    :row-key="(record) => record.id"
                    :data-source="table.data"
                    s
                    :pagination="table.pagination"
                    :loading="table.loading"
                    @change="handleTableChange"
                    :rowSelection="{
                        selectedRowKeys: selectedRowKeysValue,
                        onChange: onSelectChange,
                        hideDefaultSelections: true,
                        selections: true,
                        type: 'radio',
                    }"
                    bordered
                    size="middle"
                    id="payment-reports-table"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'name'">
                            <a-typography-link @click="viewItem(record)">
                                {{ record.name }}
                            </a-typography-link>
                        </template>
                        <template v-if="column.dataIndex === 'packing'">
                            {{ record.packing }}
                        </template>
                        <template v-if="column.dataIndex === 'stock'">
                            <a-typography-link @click="viewItem(record)">
                                {{ record.stock }}
                            </a-typography-link>
                        </template>

                        <template v-if="column.dataIndex === 'unit_name'">
                            <a-typography-link
                                @click="viewItem(record)"
                                style="color: black"
                            >
                                {{ record.unit_name }}
                            </a-typography-link>
                        </template>

                        <template v-if="column.dataIndex === 'sale_rate'">
                            <a-typography-link
                                @click="viewItem(record)"
                                style="color: black"
                            >
                                {{ record.sale_rate }}
                            </a-typography-link>
                        </template>

                        <template v-if="column.dataIndex === 'action'">
                            <a-button
                                 @click="editItem(record)"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <EditOutlined />
                                </template>
                            </a-button>
                            <a-button
                                v-if="
                                    permsArray.includes(
                                        'stock_adjustments_delete'
                                    ) || permsArray.includes('admin')
                                "
                                type="primary"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <PrinterOutlined />
                                </template>
                            </a-button>
                          
                        </template>
                    </template>
                    <template #expandedRowRender="orderItemData">
                        <a-table
                            v-if="
                                orderItemData &&
                                orderItemData.record &&
                                orderItemData.record.items
                            "
                            :row-key="(record) => record.xid"
                            :ProductItem="orderItemDetailsColumns"
                            :data-source="orderItemData.record.items"
                            :pagination="false"
                        >
                            <template #bodyCell="{ column, record }">
                                <template
                                    v-if="column.dataIndex === 'product_id'"
                                >
                                    <a-badge>
                                        <a-avatar
                                            shape="square"
                                            :src="record.product.image_url"
                                        />
                                        {{ record.product.name }}
                                    </a-badge>
                                </template>
                                <template
                                    v-if="column.dataIndex === 'quantity'"
                                >
                                    {{
                                        `${record.quantity} ${record.product.unit.short_name}`
                                    }}
                                </template>
                                <template
                                    v-if="
                                        column.dataIndex === 'single_unit_price'
                                    "
                                >
                                    {{
                                        formatAmountCurrency(
                                            record.single_unit_price
                                        )
                                    }}
                                </template>
                                <template
                                    v-if="column.dataIndex === 'total_discount'"
                                >
                                    {{
                                        formatAmountCurrency(
                                            record.total_discount
                                        )
                                    }}
                                </template>
                                <template
                                    v-if="column.dataIndex === 'total_tax'"
                                >
                                    <span
                                        v-if="
                                            record.order_item_taxes.length > 0
                                        "
                                    >
                                        <span
                                            v-for="order_item_tax in record.order_item_taxes"
                                            :key="order_item_tax.xid"
                                        >
                                            <span>
                                                {{ order_item_tax.tax_name }} :
                                                {{
                                                    formatAmountCurrency(
                                                        order_item_tax.tax_amount
                                                    )
                                                }}
                                            </span>
                                            <br />
                                        </span>
                                    </span>
                                    <span v-else>
                                        {{
                                            formatAmountCurrency(
                                                record.total_tax
                                            )
                                        }}
                                    </span>
                                </template>
                                <template
                                    v-if="column.dataIndex === 'subtotal'"
                                >
                                    {{ record.subtotal }}
                                </template>
                            </template>
                        </a-table>
                    </template>
                    <template #summary>
                        <a-table-summary-row>
                            <a-table-summary-cell
                                :col-span="
                                    selectable && orderType != 'online-orders'
                                        ? 5
                                        : 4
                                "
                            >
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong> </a-typography-text>
                            </a-table-summary-cell>

                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{ $t("common.total") }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    <a-typography-text strong>
                                        <a-tooltip>
                                            <a-typography-text strong>
                                                <a-tooltip>
                                                    ₹ {{ totals.totalAmount }}
                                                </a-tooltip>
                                            </a-typography-text>
                                        </a-tooltip>
                                    </a-typography-text>
                                </a-typography-text>
                            </a-table-summary-cell>
                        </a-table-summary-row>
                    </template>
                </a-table>
            </div>
        </a-col>
    </a-row>

    <!-- <OrderDetails
        :visible="detailsDrawerVisible"
        :order="selectedItem"
        @close="onDetailDrawerClose"
        @goBack="restSelectedItem"
        @reloadOrder="paymentSuccess"
    /> -->

    <Payments
        :visible="paymentModalVisible"
        :order="selectedItem"
        @close="closePaymentDetails"
    />

    <ConfirmOrder
        :visible="confirmModalVisible"
        :data="modalData"
        @closed="confirmModalVisible = false"
        @confirmSuccess="initialSetup"
    />

    <ViewOrder
        :visible="viewModalVisible"
        :order="modalData"
        @closed="viewModalVisible = false"
    />

    <AddEdit
        :addEditType="addEditType"
        :visible="addEditVisible"
        :url="addEditUrl"
        @addEditSuccess="addEditSuccess"
        @closed="onCloseAddEdit"
        :formData="formData"
        :data="selectedItem"
        :editItemAmount="editItemAmount"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
    />
    <InvoiceVue
        :visible="printInvoiceModalVisible"
        :order="printInvoiceOrder"
        @closed="printInvoiceModalVisible = false"
    />
    <View
        :visible="userVisible"
        :user="modalData"
        @closed="userVisible = false"
    />
</template>

<script>
import { onMounted, watch, ref, createVNode, computed } from "vue";
import {
    EyeOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    ExclamationCircleOutlined,
    MoreOutlined,
    DownloadOutlined,
    CheckOutlined,
    StopOutlined,
    SendOutlined,
    SisternodeOutlined,
    DollarCircleOutlined,
    WalletOutlined,
    ShoppingCartOutlined,
    PrinterOutlined,
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { find, forEach } from "lodash-es";
import { useI18n } from "vue-i18n";
import print from "print-js";

import fields from "../../purchases/ProductItem/fields";

import common from "../../../../../common/composable/common";
import datatable from "../../../../../common/composable/datatable";
import PaymentStatus from "../../../../../common/components/order/PaymentStatus.vue";
import OrderStatus from "../../../../../common/components/order/OrderStatus.vue";
import Details from "../../../../views/stock-management/purchases/Details.vue";
import UserInfo from "../../../../../common/components/user/UserInfo.vue";
import ConfirmOrder from "../../../../views/stock-management/online-orders/ConfirmOrder.vue";
import ViewOrder from "../../../../views/stock-management/online-orders/ViewOrder.vue";
import AddEdit from "../../../../views/stock-management/order-payments/AddEdit.vue";
import InvoiceVue from "../../../../views/stock-management/pos/Invoice.vue";
import Payments from "../../../../views/stock-management/purchases/payments.vue";
import View from "../../../../views/users/View.vue";

export default {
    props: {
        selectable: {
            default: true,
        },
        tableSize: {
            default: "large",
        },
        bordered: {
            default: false,
        },
        orderType: {
            default: "",
        },
        filters: {
            default: {},
        },
        perPageItems: Number,
    },
    emits: ["onRowSelection"],
    components: {
        EyeOutlined,
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        MoreOutlined,
        DownloadOutlined,
        ExclamationCircleOutlined,
        SisternodeOutlined,
        CheckOutlined,
        StopOutlined,
        SendOutlined,
        WalletOutlined,
        ShoppingCartOutlined,
        PrinterOutlined,
        Details,
        UserInfo,
        Details,
        PaymentStatus,
        OrderStatus,
        // OrderDetails,
        ConfirmOrder,
        ViewOrder,
        AddEdit,
        DollarCircleOutlined,
        InvoiceVue,
        Payments,
        View,
        fields,
    },
    setup(props, { emit }) {
        const store = useStore();
        const {
            purchaseReturnColumnss,
            ProductItem,
            hashableColumns,
            setupTableColumns,
            filterableColumns,
            pageObject,
            orderType,
            orderStatus,
            orderItemDetailsColumns,
            initPaymentData,
            orderPageObject,
        } = fields();
        const datatableVariables = datatable();
        const {
            formatAmountCurrency,
            invoiceBaseUrl,
            permsArray,
            calculateOrderFilterString,
            formatDate,
            selectedWarehouse,
            selectedLang,
            orderStatusColors,
        } = common();
        const route = useRoute();
        const { t } = useI18n();
        let selectedInvoice = ref(0);
        const detailsDrawerVisible = ref(false);

        const selectedItem = ref({});
        const printInvoiceModalVisible = ref(false);
        const payNowVisible = ref(false);
        const printInvoiceOrder = ref({});

        // For Online Orders
        const confirmModalVisible = ref(false);
        const viewModalVisible = ref(false);
        const paymentModalVisible = ref(false);
        const userVisible = ref(false);
        const modalData = ref({});
        // End For Online Orders

        const posView = (order) => {
            var totalMrp = 0;
            var totalTax = 0;
            forEach(order.items, (item) => {
                totalMrp += item.quantity * item.mrp;
                totalTax += item.total_tax;
            });

            let savingOnMrp = totalMrp - order.total;
            let savingPercentage =
                totalMrp > 0 ? ((savingOnMrp / totalMrp) * 100).toFixed(2) : 0;
            let totalTaxOnItems = totalTax + order.tax_amount;

            printInvoiceOrder.value = {
                ...order,
                saving_on_mrp: savingOnMrp,
                saving_percentage: savingPercentage,
                total_tax_on_items: totalTaxOnItems,
            };
            printInvoiceModalVisible.value = true;
        };

        onMounted(() => {
            initialSetup();
        });

        const onSelectChange = (changableRowKeys) => {
            console.log("selectedRowKeys changed: ", changableRowKeys);

            selectedRowKeysValue = [changableRowKeys];
        };
        let selectedRowKeysValue = [];

        const getCheckboxProps = (record) => {
            var isDeleteable = true;

            if (
                record.order_type == "stock-transfers" &&
                props.filters.transfer_type == "transfered" &&
                (permsArray.value.includes(
                    `${pageObject.value.permission}_delete`
                ) ||
                    permsArray.value.includes("admin")) &&
                record.payment_status == "unpaid"
            ) {
                isDeleteable = true;
            } else if (
                record.order_type != "online-orders" &&
                (permsArray.value.includes(
                    `${pageObject.value.permission}_delete`
                ) ||
                    permsArray.value.includes("admin")) &&
                record.payment_status == "unpaid"
            ) {
                isDeleteable = true;
            }

            return {
                disabled: !isDeleteable,
                name: record.xid,
            };
        };

        const initialSetup = () => {
            orderType.value = props.orderType;
            if (props.perPageItems) {
                datatableVariables.table.pagination.pageSize =
                    props.perPageItems;
            }
            datatableVariables.table.pagination.current = 1;
            datatableVariables.table.pagination.currentPage = 1;
            datatableVariables.hashable.value = hashableColumns;

            setupTableColumns();
            setUrlData();
        };

        const setUrlData = (searchBy) => {
            if (searchBy == undefined) {
                searchBy = "Today";
            }
            const tableFilter = props.filters;

            const filterString = calculateOrderFilterString(tableFilter);

            var extraFilterObject = {};
            if (tableFilter.dates) {
                extraFilterObject.dates = tableFilter.dates;
            }
            if (tableFilter.transfer_type) {
                extraFilterObject.transfer_type = tableFilter.transfer_type;
            }

            datatableVariables.tableUrl.value = {
                url:
                    `${props.orderType}?fields=xid,id,name,packing,margin,hsn,free_scheme_1,free_scheme_2,mfr_name,w_o_free,deal_free,stock,cgst,lgst,sgst,hsn_sac,company_name,unit_name,sale_rate,unit_1st,unit,mrp,purchase_rate,cost,tax_category,company_id"&searchBy=` +
                    filterString,
                filters: {
                    user_id: tableFilter.user_id
                        ? tableFilter.user_id
                        : undefined,
                    warehouse_id: tableFilter.warehouse_id
                        ? tableFilter.warehouse_id
                        : undefined,
                },
                extraFilters: extraFilterObject,
            };
            datatableVariables.table.filterableColumns = filterableColumns;

            if (
                tableFilter.searchColumn &&
                tableFilter.searchString &&
                tableFilter.searchString != ""
            ) {
                datatableVariables.table.searchColumn =
                    tableFilter.searchColumn;
                datatableVariables.table.searchString =
                    tableFilter.searchString;
            } else {
                datatableVariables.table.searchColumn = undefined;
                datatableVariables.table.searchString = "";
            }

            datatableVariables.fetch({
                page: datatableVariables.table.pagination.currentPage,
            });
            setTimeout(function () {
                document.getElementById("searchInput").focus();
            }, 2000);
        };

        const showDeleteConfirm = (id) => {
            Modal.confirm({
                title: t("common.delete") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`${pageObject.value.langKey}.delete_message`),
                autoFocusButton:'ok',
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    axiosAdmin.delete(`${props.orderType}/${id}`).then(() => {
                        // Update Visible Subscription Modules
                        updateSubscriptionModules();
                        setUrlData();

                        notification.success({
                            message: t("common.success"),
                            description: t(
                                `${pageObject.value.langKey}.deleted`
                            ),
                        });
                    });
                },
                onCancel() {},
            });
        };

        const showSelectedDeleteConfirm = () => {
            Modal.confirm({
                title: t("common.delete") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(
                    `${pageObject.value.langKey}.selected_delete_message`
                ),
                autoFocusButton:'ok',
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    const allDeletePromise = [];
                    forEach(
                        datatableVariables.table.selectedRowKeys,
                        (selectedRow) => {
                            allDeletePromise.push(
                                axiosAdmin.delete(
                                    `${props.orderType}/${selectedRow}`
                                )
                            );
                        }
                    );

                    Promise.all(allDeletePromise).then((successResponse) => {
                        // Update Visible Subscription Modules
                        updateSubscriptionModules();

                        resetSelectedRows();
                        setUrlData();

                        notification.success({
                            message: t("common.success"),
                            description: t(
                                `${pageObject.value.langKey}.deleted`
                            ),
                            placement: "bottomRight",
                        });
                    });
                },
                onCancel() {},
            });
        };

        // Update Visible Subscription Modules
        const updateSubscriptionModules = () => {
            store.dispatch("auth/updateVisibleSubscriptionModules");
        };

        const viewItem = (record) => {
            selectedItem.value = record;
            detailsDrawerVisible.value = true;
        };
        const userView = (record) => {
            let user = {};
            user = record.user;
            modalData.value = { ...record, ...user };
            userVisible.value = true;
        };

        const viewPaymentDetails = (record) => {
            selectedItem.value = record;
            paymentModalVisible.value = true;
        };
        const closePaymentDetails = () => {
            paymentModalVisible.value = false;
        };

        const restSelectedItem = () => {
            selectedItem.value = {};
        };

        const paymentSuccess = () => {
            datatableVariables.fetch({
                page: datatableVariables.currentPage.value,
                success: (results) => {
                    const searchResult = find(results, (result) => {
                        return result.xid == selectedItem.value.xid;
                    });

                    if (searchResult != undefined) {
                        selectedItem.value = searchResult;
                    }
                },
            });
        };

        const onDetailDrawerClose = () => {
            detailsDrawerVisible.value = false;
        };

        // For Online Orders
        const confirmOrder = (order) => {
            modalData.value = order;
            confirmModalVisible.value = true;
        };

        const viewOrder = (order) => {
            modalData.value = order;
            viewModalVisible.value = true;
        };

        const changeOrderStatus = (order) => {
            processRequest({
                url: `online-orders/change-status/${order.unique_id}`,
                data: { order_status: order.order_status },
                success: (res) => {
                    // Toastr Notificaiton
                    notification.success({
                        placement: "bottomRight",
                        message: t("common.success"),
                        description: t("online_orders.order_status_changed"),
                    });
                },
                error: (errorRules) => {},
            });
        };

        const convertToSale = (order) => {
            Modal.confirm({
                title: t("quotation.convert_to_sale") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`quotation.convert_message`),
                autoFocusButton:'ok',
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    axiosAdmin
                        .post(`quotations/convert-to-sale/${order.unique_id}`)
                        .then(() => {
                            datatableVariables.fetch();

                            // Toastr Notificaiton
                            notification.success({
                                placement: "bottomRight",
                                message: t("common.success"),
                                description: t(
                                    "quotation.quotation_converted_to_sales"
                                ),
                            });
                        });
                },
                onCancel() {},
            });
        };

        const cancelOrder = (order) => {
            Modal.confirm({
                title: t("online_orders.cancel_order") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`online_orders.cancel_message`),
                autoFocusButton:'ok',
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    axiosAdmin
                        .post(`online-orders/cancel/${order.unique_id}`)
                        .then(() => {
                            initialSetup();
                            notification.success({
                                message: t("common.success"),
                                description: t(`online_orders.order_cancelled`),
                                placement: "bottomRight",
                            });
                        });
                },
                onCancel() {},
            });
        };

        const confirmDelivery = (order) => {
            Modal.confirm({
                title: t("common.delivered") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`online_orders.deliver_message`),
                autoFocusButton:'ok',
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    axiosAdmin
                        .post(`online-orders/delivered/${order.unique_id}`)
                        .then(() => {
                            initialSetup();
                            notification.success({
                                message: t("common.success"),
                                description: t(`online_orders.order_delivered`),
                                placement: "bottomRight",
                            });
                        });
                },
                onCancel() {},
            });
        };
        // End For Online Orders

        const resetSelectedRows = () => {
            datatableVariables.table.selectedRowKeys = [];
            emit("onRowSelection", []);
        };

        const printInvoicePDF = async (pdfUrl) => {
            datatableVariables.table.loading = true;

            await axiosAdmin
                .get(pdfUrl, {
                    responseType: "blob",
                })
                .then((response) => {
                    const pdfBlob = response;

                    const url = URL.createObjectURL(pdfBlob);

                    print(url);

                    URL.revokeObjectURL(url);

                    datatableVariables.table.loading = false;
                })
                .catch((error) => {
                    datatableVariables.table.loading = false;
                });
        };

        watch(props, (newVal, oldVal) => {
            // Reset Selected Rows
            resetSelectedRows();

            initialSetup();
            restSelectedItem();
        });

        watch(selectedWarehouse, (newVal, oldVal) => {
            resetSelectedRows();

            datatableVariables.table.pagination.current = 1;
            setUrlData();
        });
        // for payments //
        const addEditVisible = ref(false);
        const addEditType = ref("add");
        const addEditUrl = ref("order-payments");
        const formData = ref({});
        const editItemAmount = ref(0);

        const pageTitle = computed(() => {
            return addEditType.value == "add"
                ? t(`payments.add`)
                : t(`payments.edit`);
        });

        const successMessage = computed(() => {
            return addEditType.value == "add"
                ? t(`payments.created`)
                : t(`payments.updated`);
        });

        const addItem = (record) => {
            selectedItem.value = record;
            addEditUrl.value = `order-payments`;
            addEditType.value = "add";
            formData.value = {
                ...initPaymentData,
                order_id: selectedItem.value.xid,
            };
            addEditVisible.value = true;
            editItemAmount.value = 0;
            setUrlData();
        };

        const addEditSuccess = (id) => {
            setUrlData();

            // If add action is performed then move page to first
            if (addEditType.value == "add") {
                formData.value = {
                    order_id: selectedItem.value.xid,
                    date: undefined,
                    payment_mode_id: undefined,
                    amount: "",
                    notes: "",
                };
            }
            addEditVisible.value = false;
        };

        const onCloseAddEdit = () => {
            formData.value = {
                ...initPaymentData,
                order_id: selectedItem.value.xid,
            };
            addEditVisible.value = false;
            editItemAmount.value = 0;
        };

        const totals = computed(() => {
            let totalAmount = 0;
            datatableVariables.table.data.forEach((tableRowData) => {
                totalAmount += tableRowData.total;
            });
            return {
                totalAmount,
            };
        });

        return {
            ...datatableVariables,
            filterableColumns,
            pageObject,
            purchaseReturnColumnss,
            totals,
            formatDate,
            orderStatus,
            orderStatusColors,
            orderPageObject,
            setUrlData,
            formatAmountCurrency,
            invoiceBaseUrl,
            permsArray,

            selectedItem,
            viewItem,
            restSelectedItem,
            paymentSuccess,

            showDeleteConfirm,
            showSelectedDeleteConfirm,

            detailsDrawerVisible,
            onDetailDrawerClose,
            orderItemDetailsColumns,
            selectedLang,
            initialSetup,

            convertToSale,

            // For Online Orders
            confirmOrder,
            cancelOrder,
            viewOrder,
            confirmDelivery,
            changeOrderStatus,
            confirmModalVisible,
            viewModalVisible,
            modalData,
            // End For Online Orders
            getCheckboxProps,

            //for payment//
            initPaymentData,
            addEditSuccess,
            addEditType,
            addEditUrl,
            addEditVisible,
            formData,
            onCloseAddEdit,
            successMessage,
            editItemAmount,
            pageTitle,
            addItem,
            printInvoiceModalVisible,
            payNowVisible,
            posView,
            printInvoiceOrder,
            paymentModalVisible,
            viewPaymentDetails,
            closePaymentDetails,
            userVisible,
            userView,

            printInvoicePDF,
            onSelectChange,
            ProductItem,
        };
    },
    data() {
        return { focus: null };
    },

    mounted() {
        document.addEventListener("keyup", this.handleKeyDown);
        setTimeout(function () {
            this.selectedRowKeysValue = [];
        }, 2000);
    },
    beforeDestroy() {
        document.removeEventListener("keyup", this.handleKeyDown);
    },

    methods: {
        customRow(record) {
            return {
                onClick: (event) => {
                    this.rowSelection = event;
                    console.log("record", record, "event", record.party_name);
                },
            };
        },

        calculateTotal(record) {
            // Assuming 'total' is the sum of all line item amounts in the record
            let subtotal = this.calculateSubtotal(record); // Use subtotal as part of total
            let otherCharges = record.shipping + record.handling;
            return subtotal + otherCharges;
        },
        // editReturn(invoice_number) {
        //     this.$emit("child-select", invoice_number);
        // },
        test(event) {
            console.log(2333, event.keyCode);
            switch (event.keyCode) {
                case 38: // Arrow up
                    if (this.table.data.length > 0) {
                        if (this.focus === null) {
                            this.focus = 0;
                        } else if (this.focus > 0) {
                            this.focus--;
                        }
                        this.updateSelection();
                    }
                    break;
                case 40: // Arrow down
                    if (this.table.data.length > 0) {
                        if (this.focus === null) {
                            this.focus = 0;
                        } else if (this.focus < this.table.data.length - 1) {
                            this.focus++;
                        }
                        this.updateSelection();
                    }
                    break;
                case 46:
                    this.autoFocusInput();
                    this.showPopupModal();
                    break;
                case 13:
                    if (this.table.data.length > 0) {
                        console.log("<>", this.selectedInvoice);
                        if (
                            selectedRowKeysValue != undefined &&
                            selectedRowKeysValue.length > 0
                        ) {
                            this.$emit("child-select", this.selectedInvoice);
                        } else {
                            this.focus = 0;
                            this.updateSelection();
                            var that = this;
                            setTimeout(function () {
                                that.$emit(
                                    "child-select",
                                    this.selectedInvoice
                                );
                            }, 1500);
                        }
                    }
                    break;
            }
        },

        updateSelection(event) {
            const currentRadioInput =
                document.getElementsByClassName("ant-radio-input")[this.focus];
            currentRadioInput.click();
            const currentRow = currentRadioInput.closest("tr");
            const selectedRowKey = currentRow.getAttribute("data-row-key");
            console.log("Selected Row Key:", selectedRowKey);
            selectedRowKeysValue = [selectedRowKey];
            this.selectedInvoice = currentRow
                .getElementsByTagName("td")[2]
                .innerHTML.replace(/<[^>]*>?/gm, "");

            //this.$emit('child-select');*/
            //console.log(this.table.data);
        },
    },

       // edit option//
       async editReturn(id) {
  this.recordId = id;
  try {
    this.$router.push({
      name: "admin.stock.productitem.edit",
      params: { id: id }// Pass form data as a query string
    });
  } catch (error) {
    console.error("Error fetching sales number:", error);
  }
},
};
</script>
<style>
.ant-table-tbody > tr.ant-table-row-selected > td {
    background-color: #ffd451 !important;
}
a-typography-link {
    color: black;
}
</style>
