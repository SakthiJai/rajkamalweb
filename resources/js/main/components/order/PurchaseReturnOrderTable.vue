<template>
    <a-row>
        <a-col :span="24">
            <div class="table-responsiveof">

                <a-table :columns="purchaseReturnColumns" :row-key="(record) => record.id" :data-source="table.data"
                    :pagination="table.pagination" :loading="table.loading" :scroll="{ y: 500 }" @change="handleTableChange"
                    :rowSelection="{
                        selectedRowKeys: selectedRowKeysValue,
                        onChange: onSelectChange,
                        hideDefaultSelections: true,
                        selections: false,
                        type: 'radio'
                    }" bordered size="middle" id="payment-reports-table">


                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'cr_number'">
                            <a-typography-link @click="viewItem(record)">
                                {{ record.cr_number }}
                            </a-typography-link>
                        </template>
                        <template v-if="column.dataIndex === 'order_date'">
                            {{ formatDate(record.order_date) }}
                        </template>
                        <template v-if="column.dataIndex === 'party_name'">
                            <a-typography-link @click="viewItem(record)">
                                {{ record.party_name }}
                            </a-typography-link>
                        </template>

                        <template v-if="column.dataIndex === 'total'">
                            <a-typography-link @click="viewItem(record)"  style="color: black;">
                                {{ record.total_amount }}
                            </a-typography-link>
                        </template>

                        <template v-if="column.dataIndex === 'action'">
                            <a-button
                                @click="editReturn(record.dr_number)"
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
                                @click="showDeleteConfirm(record.xid)"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <DeleteOutlined />
                                </template>
                            </a-button>
                            <a-button
                                @click="printReturn(record.invoice_path)"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <span
                                        role="img"
                                        aria-label="printer"
                                        class="anticon anticon-printer"
                                        ><svg
                                            focusable="false"
                                            data-icon="printer"
                                            width="1em"
                                            height="1em"
                                            fill="currentColor"
                                            aria-hidden="true"
                                            viewBox="64 64 896 896"
                                        >
                                            <path
                                                d="M820 436h-40c-4.4 0-8 3.6-8 8v40c0 4.4 3.6 8 8 8h40c4.4 0 8-3.6 8-8v-40c0-4.4-3.6-8-8-8zm32-104H732V120c0-4.4-3.6-8-8-8H300c-4.4 0-8 3.6-8 8v212H172c-44.2 0-80 35.8-80 80v328c0 17.7 14.3 32 32 32h168v132c0 4.4 3.6 8 8 8h424c4.4 0 8-3.6 8-8V772h168c17.7 0 32-14.3 32-32V412c0-44.2-35.8-80-80-80zM360 180h304v152H360V180zm304 664H360V568h304v276zm200-140H732V500H292v204H160V412c0-6.6 5.4-12 12-12h680c6.6 0 12 5.4 12 12v292z"
                                            ></path></svg
                                        ><!----></span
                                    >
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
            </div>
        </a-col>
    </a-row>


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
        scrollY: {
            type: Number,
            default: 600,
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
    },
    setup(props, { emit }) {
        const store = useStore();
        const {
            columns,
            purchaseReturnColumns,
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
        let selectedInvoice=ref(0);
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

console.log('selectedRowKeys changed: ', changableRowKeys);

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

            setupTableColumns();
            setUrlData();
        };

        const setUrlData = (searchBy) => {
            if(searchBy==undefined){searchBy="Today";}
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
                    `${props.orderType}?fields=id,xid,return_by,partyName{id,party_name},customer{id,cus_name},dr_number,order_id,order_date,invoice_path,total_amount,&searchBy=` +
                    searchBy,
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
            setTimeout(function(){document.getElementById('searchInput').focus();},2000)
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
            columns,
            purchaseReturnColumns,
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
    data()
    {
        return {focus: null}
    },

    mounted() {

        document.addEventListener('keyup', this.handleKeyDown);
        setTimeout(function () { this.selectedRowKeysValue = [] }, 2000);
    },
    beforeDestroy() {
        document.removeEventListener('keyup', this.handleKeyDown);
    },

    methods: {

        customRow(record) {
            return {

                onClick: (event) => {
                    this.rowSelection = event
                    console.log('record', record, 'event', record.party_name);
                }
            }
        },

        calculateTotal(record) {
      // Assuming 'total' is the sum of all line item amounts in the record
      let subtotal = this.calculateSubtotal(record); // Use subtotal as part of total
      let otherCharges = record.shipping + record.handling;
      return subtotal + otherCharges;
   },

        test(event) { console.log(2333,event.keyCode)
            switch (event.keyCode) {
                case 38: // Arrow up
                    if(this.table.data.length>0){
                        if (this.focus === null) {
                            this.focus = 0;
                        } else if (this.focus > 0) {
                            this.focus--;
                        }
                        this.updateSelection();
                    }
                    break;
                case 40: // Arrow down
                    if(this.table.data.length>0){
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
                if(this.table.data.length>0){
                    console.log("<>",this.selectedInvoice);
                    if(selectedRowKeysValue!=undefined && selectedRowKeysValue.length>0)
                    {
                        this.$emit('child-select',this.selectedInvoice);
                    }
                    else
                    {

                        this.focus = 0;
                        this.updateSelection();
                        var that = this;
                        setTimeout(function(){that.$emit('child-select',that.selectedInvoice);},1500)
                    }
                }
                    break;
            }
        },

         updateSelection(event) {
            const currentRadioInput = document.getElementsByClassName('ant-radio-input')[this.focus];
            currentRadioInput.click();
            const currentRow = currentRadioInput.closest('tr');
            const selectedRowKey = currentRow.getAttribute('data-row-key');
            console.log('Selected Row Key:', selectedRowKey);
            selectedRowKeysValue=[selectedRowKey]
            this.selectedInvoice = currentRow.getElementsByTagName('td')[1].innerHTML.replace(/<[^>]*>?/gm, '');
            const tableBody = currentRow?.closest('.ant-table-container')?.querySelector('.ant-table-body');
            if (tableBody) {
                const rowTop = currentRow.offsetTop;
                const rowBottom = rowTop + currentRow.offsetHeight;
                const visibleTop = tableBody.scrollTop;
                const visibleBottom = visibleTop + tableBody.clientHeight;

                if (rowTop < visibleTop) {
                    tableBody.scrollTop = rowTop;
                } else if (rowBottom > visibleBottom) {
                    tableBody.scrollTop = rowBottom - tableBody.clientHeight;
                }
            }

            //this.$emit('child-select');*/
            //console.log(this.table.data);
        },
        editReturn(dr_number)
        {
            this.$emit('child-select',dr_number);
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
    },
};
</script>
<style>
    
.table-responsiveof .ant-table-body {
    max-height: 500px !important;
    overflow-y: auto !important;
}

 .ant-table-tbody>tr.ant-table-row-selected>td {
    background-color: #ffd451 !important;
}
a-typography-link
{
    color: black;
}
</style>
