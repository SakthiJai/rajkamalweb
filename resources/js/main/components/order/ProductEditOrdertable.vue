<template>
    <a-row>
        <a-col :span="24">
            <div class="table-responsive">
                <a-table
                    :columns="ProducteditItem"
                    :row-key="(record) => record.id"
                    :data-source="table.data"
                    :pagination="table.pagination"
                    :loading="table.loading"
                    @change="handleTableChange"
                    :rowSelection="{
                        selectedRowKeys: selectedRowKeysValue,
                        onChange: onSelectChange,
                        hideDefaultSelections: true,
                        selections: false,
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
                            {{ record.current_stock }}
                        </template>
                        <template v-if="column.dataIndex === 'unit_name'">
                            {{ record.unit_name }}
                        </template>
                        <template v-if="column.dataIndex === 'sale_rate'">
                            {{ record.sale_rate.toFixed(2) }}
                        </template>
                        <template v-if="column.dataIndex === 'action'">
                            <a-button
                                @click="editReturn(record.id)"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <EditOutlined />
                                </template>
                            </a-button>

                            <!-- <a-button
              v-if="
                  permsArray.includes(
                      'expense_categories_delete'
                  ) ||
                  permsArray.includes('admin')
              "
              type="primary"
              @click="
                  showDeleteConfirm(record.id)
              "
              style="margin-left: 4px"
          >
              <template #icon
                  ><DeleteOutlined
              /></template>
          </a-button> -->

                            <a-button
                                v-if="
                                    permsArray.includes(
                                        'expense_categories_delete'
                                    ) || permsArray.includes('admin')
                                "
                                type="primary"
                                @click="showDeleteConfirm(record.xid)"
                                style="margin-left: 4px"
                            >
                                <template #icon><DeleteOutlined /></template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
            </div>
        </a-col>
    </a-row>

    <!--- product edit-->
    <a-row :gutter="16" style="margin-top: -13px">
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
            <fieldset style="height: 118px">
                <legend class="font-style-in-prouct-model">
                    Purchase Info
                </legend>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> M.R.P </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="mrp">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        Pur. Rate
                    </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="purchase_rate">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> Cost </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="cost">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        Cost+Tax
                    </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="cost_tax">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        Pur. Disc
                    </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="purchase_discount">0.00</span>
                    </a-col>
                </a-row>
            </fieldset>
        </a-col>
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
            <fieldset style="height: 118px">
                <legend class="font-style-in-prouct-model">Sale Info</legend>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> Rate </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="rate">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> Margin </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="margin">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        Deal Free
                    </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="deal_free">0 + 0</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        W/o Deal
                    </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="w_o_free"></span>
                    </a-col>
                </a-row>
            </fieldset>
        </a-col>
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
            <fieldset style="height: 118px">
                <legend class="font-style-in-prouct-model">Tax Info</legend>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> HSN/SAC </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>:</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="hsn">0</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> IGST % </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="igst">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> CGST % </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="cgst">0.00</span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="10" :sm="10" :md="10" :lg="10"> SGST % </a-col>
                    <a-col :xs="4" :sm="4" :md="4" :lg="4">
                        <span>: ₹</span>
                    </a-col>
                    <a-col :xs="10" :sm="10" :md="10" :lg="10">
                        <span id="sgst">0.00</span>
                    </a-col>
                </a-row>
            </fieldset>
        </a-col>
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
            <fieldset style="height: 118px">
                <legend class="font-style-in-prouct-model">Other Info</legend>
                <a-row :gutter="16">
                    <a-col :xs="8" :sm="8" :md="8" :lg="8"> Company </a-col>
                    <a-col :xs="1" :sm="1" :md="1" :lg="1">
                        <span>:</span>
                    </a-col>
                    <a-col :xs="14" :sm="14" :md="14" :lg="14">
                        <span id="company"></span>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="8" :sm="8" :md="8" :lg="8"> Mfr. </a-col>
                    <a-col :xs="1" :sm="1" :md="1" :lg="1">
                        <span>:</span>
                    </a-col>
                    <a-col :xs="14" :sm="14" :md="14" :lg="14">
                        <span id="mfr"></span>
                    </a-col>
                </a-row>
            </fieldset>
        </a-col>
    </a-row>

    <!-- end product edit-->

    <!--- ledger edit modal-->
    <LedgerModel
        v-if="isLegerModalVisible"
        :visible="isLegerModalVisible"
        :formData="formData"
        :url="url"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handleClose"
    />
    <!--- end ledger modal-->
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
import fields from "../../views/stock-management/purchases/fields";
import common from "../../../common/composable/common";
import datatable from "../../../common/composable/datatable";
import PaymentStatus from "../../../common/components/order/PaymentStatus.vue";
import OrderStatus from "../../../common/components/order/OrderStatus.vue";
import Details from "../../views/stock-management/purchases/Details.vue";
import UserInfo from "../../../common/components/user/UserInfo.vue";
import OrderDetails from "./OrderDetails.vue";
import ConfirmOrder from "../../views/stock-management/online-orders/ConfirmOrder.vue";
import ViewOrder from "../../views/stock-management/online-orders/ViewOrder.vue";
import AddEdit from "../../views/stock-management/order-payments/AddEdit.vue";
import InvoiceVue from "../../views/stock-management/pos/Invoice.vue";
import Payments from "../../views/stock-management/purchases/payments.vue";
import View from "../../views/users/View.vue";
import LedgerModel from "../../../main/views/stock-management/purchases/LedgerModel.vue";

export default {
    props: {
        selectable: {
            default: false,
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
        OrderDetails,
        ConfirmOrder,
        ViewOrder,
        AddEdit,
        DollarCircleOutlined,
        InvoiceVue,
        Payments,
        View,
        LedgerModel,
    },
    setup(props, { emit }) {
        const store = useStore();
        const {
            columns,
            ProducteditItem,
            hashableColumns,
            //setupTableColumns,
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
            var isDeleteable = false;

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

            //setupTableColumns();
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
                    `${props.orderType}?fields=xid,id,name,packing,short_code,product_id,margin,hsn,free_scheme_1,free_scheme_2,mfr_name,w_o_free,deal_free,stock,current_stock,cgst,lgst,sgst,hsn_sac,company_name,unit_name,sale_rate,unit_1st,unit,mrp,purchase_rate,cost,gst,company_id&searchBy=` +
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
                title: t("Delete") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`Are you sure you want to delete this Product?`),
                autoFocusButton: "ok",
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
                            message: t("Success"),
                            description: t(` Product Deleted Succesfully`),
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
                autoFocusButton: "ok",
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
                autoFocusButton: "ok",
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
                autoFocusButton: "ok",
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
                autoFocusButton: "ok",
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
            columns,
            ProducteditItem,
            ...datatableVariables,
            filterableColumns,
            pageObject,
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
        };
    },
    data() {
        return {
            isLegerModalVisible: false,
            focus: null,
        };
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
            this.selectedInvoice = selectedRowKey;

            // this.selectedInvoice = currentRow
            //   .getElementsByTagName("td")[1]
            //   .innerHTML.replace(/<[^>]*>?/gm, "");
            if (Array.isArray(this.table.data) && this.table.data.length > 0) {
                this.table.data.forEach((row) => {
                    if (row.cgst == undefined) {
                        row.cgst = 0;
                    }
                    if (row.sgst == undefined) {
                        row.sgst = 0;
                    }
                    if (row.id === selectedRowKey || row.id == selectedRowKey) {
                        if (!this.selectedProductId) {
                            this.selectedProductId = {};
                        }
                        this.selectedProductId.cgst = Number(
                            row.cgst >= 0 ? row.cgst : 0
                        );
                        this.selectedProductId.sgst = Number(
                            row.sgst >= 0 ? row.sgst : 0
                        );
                        this.selectedProductId.lgst = Number(
                            row.lgst >= 0 ? row.lgst : 0
                        );

                        let cgstAmount = 0,
                            sgstAmount = 0;

                        if (row.cgst && row.sale_rate) {
                            let cgstPercentage = Number(row.cgst) / 100;
                            cgstAmount =
                                Number(cgstPercentage) * Number(row.sale_rate);
                        }

                        if (row.sgst && row.sale_rate) {
                            let sgstPercentage = Number(row.sgst) / 100;
                            sgstAmount =
                                Number(sgstPercentage) * Number(row.sale_rate);
                        }

                        let totalTax =
                            Number(sgstAmount) +
                            Number(cgstAmount) +
                            Number(row.sale_rate);

                        document.getElementById("mrp").innerHTML =
                            this.formatOfAmount(row.mrp);
                        document.getElementById("purchase_rate").innerHTML =
                            this.formatOfAmount(row.purchase_rate);
                        document.getElementById("cost").innerHTML =
                            this.formatOfAmount(row.cost);
                        document.getElementById("cost_tax").innerHTML =
                            this.formatOfAmount(totalTax);
                        document.getElementById("purchase_discount").innerHTML =
                            "0.00";
                        document.getElementById("rate").innerHTML =
                            this.formatOfAmount(row.sale_rate);
                        document.getElementById("margin").innerHTML =
                            this.formatOfAmount(row.margin);
                        document.getElementById("deal_free").innerHTML =
                            (row.free_scheme_1 != null
                                ? row.free_scheme_1
                                : 0) +
                            " + " +
                            (row.free_scheme_2 != null ? row.free_scheme_2 : 0);

                        document.getElementById("w_o_free").innerHTML =
                            row.w_o_free;
                        document.getElementById("hsn").innerHTML = row.hsn;
                        document.getElementById("igst").innerHTML =
                            this.formatOfAmount(row.lgst);
                        document.getElementById("cgst").innerHTML =
                            this.formatOfAmount(row.cgst >= 0 ? row.cgst : 0);
                        document.getElementById("sgst").innerHTML =
                            this.formatOfAmount(row.sgst >= 0 ? row.sgst : 0);
                        document.getElementById("company").innerHTML =
                            row.company_name;
                        document.getElementById("mfr").innerHTML = row.mfr_name;
                    }
                });
            } else {
                console.error("Table data is not an array or is empty.");
            }

            console.log(this.table.data);
        },
        formatCurrency(value) {
            return value.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },
        formatOfAmount(value) {
            var res = new Intl.NumberFormat("en-IN", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value >= 0 ? value : 0);
            return res;
        },
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
        printReturn(pdfUrl) {
            if (pdfUrl == null) {
                notification.warning({
                    placement: "bottomRight",
                    message: "No invoice available !",
                });
            } else {
                let w = window.open(pdfUrl, "_blank");
                w.print();
            }
        },

        // edit option//
        async editReturn(id) {
            this.recordId = id;
            try {
                this.$router.push({
                    name: "admin.stock.products.edit",
                    params: { id: id },
                    // Pass modal visibility as query
                });
            } catch (error) {
                console.error("Error fetching sales number:", error);
            }
        },

        // end edit option//
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
:where(.css-dev-only-do-not-override-wosfq4).ant-table-wrapper
    .ant-table.ant-table-bordered
    > .ant-table-container
    > .ant-table-content
    > table
    > tbody
    > tr
    > td {
    padding: 0px 8px !important;
}

.font-style-in-prouct-model {
    font-size: 15px !important;
    font-weight: bolder;
}

.fieldheight {
    height: 142px;
}

.fieldheights {
    height: 143px;
}

button.btn .box {
    display: flex;
    align-items: center;
}

button.btn span.shortcut {
    display: inline-block;
    border-right: 1px solid #cacaca;
    padding-right: 4px;
    position: relative;
}

button.btn span.shortcut:after {
    position: absolute;
    right: -2px;
    top: 0;
    width: 1px;
    height: 100%;
    background: #fff;
    content: "";
}

button.btn .box span {
    padding: 0.01rem 0.5rem;
    display: flex;
    align-items: center;
}

button.btn {
    padding: 0.375rem 0.3rem;
    border: none;
    border: solid rgba(0, 0, 0, 0.2);
    border-width: 1px 1px 2px;
    text-transform: capitalize;
    font-size: 1rem;
    background: #fff;
    margin: 0 2px;
    font-size: 14px;
    height: 32px;
    padding: 4px 15px;
    border-radius: 4px;
}

.floats {
    text-align: start !important;
}

.createf2 {
    background-color: #1f6d70;
    color: white;
}

.closing {
    margin-right: 44px;
}

#closeicon {
    top: 14px !important;
}

.highlight {
    background-color: #ffd451;
}

fieldset {
    display: block !important;
    margin-inline-start: 2px !important;
    margin-inline-end: 2px !important;
    padding-block-start: 0.35em !important;
    padding-inline-start: 0.75em !important;
    padding-inline-end: 0.75em !important;
    padding-block-end: 0.625em !important;
    min-inline-size: min-content !important;
    border-width: 2px !important;
    border-style: groove !important;
    border-image: initial !important;
}
fieldset {
    min-width: 0;
    margin: 0;
    padding: 0;
    border: 0;
}
legend {
    width: auto !important;
}
</style>
