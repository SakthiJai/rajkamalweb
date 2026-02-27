<template>
    <a-row>
        <a-col :span="24">
            <div class="table-responsive">
                <a-table
                    :columns="columns"
                    :row-key="(record) => record.id"
                    :data-source="table.data"
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
                        <template v-if="column.dataIndex === 'expense_date'">
                            {{ formatDate(record.expense_date) }}
                        </template>
                        <template v-if="column.dataIndex === 'order_date'">
                            {{ formatDate(record.order_date) }}
                        </template>
                        <template v-if="column.dataIndex === 'bill_amount'">
                            {{ formatAmountCurrency(record.bill_amount) }}
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
                            <a-button
                                @click="printReturn(record.image_url)"
                                type="primary"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <PrinterOutlined />
                                </template>
                            </a-button>
                            <a-button
                                @click="showDeleteConfirm(record.id)"
                                type="default"
                                danger
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <DeleteOutlined />
                                </template>
                            </a-button>
                            <!-- <a-button
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
                            </a-button> -->
                        </template>
                    </template>
                    <!--  <template #expandedRowRender="orderItemData">
            <a-table
              v-if="orderItemData && orderItemData.record && orderItemData.record.items"
              :row-key="(record) => record.xid"
              :columns="orderItemDetailsColumns"
              :data-source="orderItemData.record.items"
              :pagination="true"
            >
              <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex === 'product_id'">
                  <a-badge>
                    <a-avatar shape="square" :src="record.product.image_url" />
                    {{ record.product.name }}
                  </a-badge>
                </template>
              </template>
            </a-table>
          </template>-->
                    <template #summary>
                        <a-table-summary-row>
                            <a-table-summary-cell :col-span="4">
                            </a-table-summary-cell>

                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong style="float: right">
                                    {{ $t("common.total") }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    <a-typography-text strong>
                                        <a-tooltip>
                                            <a-typography-text strong>
                                                <a-tooltip>
                                                    <span style="float: right"
                                                        >₹
                                                        {{
                                                            totals.totalBillAmount.toFixed(
                                                                2
                                                            )
                                                        }}
                                                    </span></a-tooltip
                                                >
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

    <!--<OrderDetails
    :visible="detailsDrawerVisible"
    :order="selectedItem"
    @close="onDetailDrawerClose"
    @goBack="restSelectedItem"
    @reloadOrder="paymentSuccess"
  />-->

    <ExpenseTable
        :visible="paymentModalVisible"
        :order="selectedItem"
        @close="closePaymentDetails"
    />

    <!--<ConfirmOrder
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
  />-->
    <!--<InvoiceVue
    :visible="printInvoiceModalVisible"
    :order="printInvoiceOrder"
    @closed="printInvoiceModalVisible = false"
  />-->
    <!-- <View :visible="userVisible" :user="modalData" @closed="userVisible = false" />-->
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
//import fields from "../../../views/stock-management/purchases/fields";
import fields from "./fields";
import common from "../../../../../common/composable/common";
import datatable from "../../../../../common/composable/datatable";

import crud from "../../../../../common/composable/crud";

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
        /* orderType: {
      default: "",
    },*/
        salesType: {
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
        //Details,
        // UserInfo,
        // Details,
        // PaymentStatus,
        // OrderStatus,
        //OrderDetails,
        // ConfirmOrder,
        // ViewOrder,
        //AddEdit,
        DollarCircleOutlined,
        //InvoiceVue,
        //Payments,
        // View,
    },
    setup(props, { emit }) {
        const store = useStore();
        const {
            columns,
            hashableColumns,
            setupTableColumns,
            filterableColumns,
            pageObject,
            //  orderType,
            salesType,
            orderStatus,
            orderItemDetailsColumns,
            initPaymentData,
            orderPageObject,
        } = fields();
        const datatableVariables = crud();
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
        onMounted(() => {
            initialSetup();
        });

        const onSelectChange = (changableRowKeys, $event) => {
            console.log("selectedRowKeys changed:  ", changableRowKeys[0]);
            /* datatableVariables.table.data.forEach((data) => {
          if (data.id == changableRowKeys[0]) {
            emit("mouse-select", data.invoice_number);
          }
        });*/
            emit("mouse-select", changableRowKeys[0]);
            // this.$emit("row-select", this.selectedInvoice);
            selectedRowKeysValue = [changableRowKeys];
        };

        let selectedRowKeysValue = [];

        const initialSetup = () => {
            setupTableColumns();
            setUrlData();
        };

        const setUrlData = (searchBy) => {
            console.log(searchBy);
            console.log("after delete");
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

            // datatableVariables.tableUrl.value = {
            //     url:
            //         `expenses1?fields=id,xid,tax_type,expense_category_id,orginal_bill_number,bill_amount,tax_amount,order_date,expense_date,image,image_url,notes,expense_category_id,expenseCategory{id,xid,name},party_id,party{id,party_name,party_type,party_full_name,phone_number},payment_mode_id,paymentModes{id,xid,name},user_id,user{name,user_type},modified_user_id,modified_user{name,user_type}&searchByDate=` +
            //         searchBy +
            //         `&searchByDateColoumn=order_date&is_delete=N`,
            //     filterString,
            //     filters: {
            //         user_id: tableFilter.user_id
            //             ? tableFilter.user_id
            //             : undefined,
            //         warehouse_id: tableFilter.warehouse_id
            //             ? tableFilter.warehouse_id
            //             : undefined,
            //     },
            //     // extraFilters: extraFilterObject,
            // };


            datatableVariables.tableUrl.value = {
                url: `expenses1?fields=id,xid,tax_type,expense_category_id,orginal_bill_number,bill_amount,tax_amount,order_date,expense_date,image,image_url,notes,expense_category_id,expenseCategory{id,xid,name},party_id,party{id,party_name,party_type,party_full_name,phone_number},payment_mode_id,paymentModes{id,xid,name},user_id,user{name,user_type},modified_user_id,modified_user{name,user_type}&searchBy=`+searchBy,
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
            // crudVariables.table.sorter = { field: "id", order: "desc" };
            var response = datatableVariables.fetch({
                page: 1,
            });
            //datatableVariables.fetch();
            setTimeout(function () {
                document.getElementById("searchInput").focus();
            }, 1000);
        };

        const showDeleteConfirm = (id) => {
            let removeKeyListener;

            const modal = Modal.confirm({
                title: t("common.delete") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`Are you sure you want to Delete this Expesnse?`),
                autoFocusButton: "ok",
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    axiosAdmin
                        .post(`expenses1/deleteExpenses/${id}`)
                        .then(() => {
                            setUrlData();
                            notification.success({
                                message: t("common.success"),
                                description: t(`Expense Deleted Successfully.`),
                            });
                        });
                    removeKeyListener();
                },
                onCancel() {
                    removeKeyListener();
                },
            });

            setTimeout(() => {
                const handleKeydown = (e) => {
                    if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
                        e.preventDefault();

                        const buttons = document.querySelectorAll(
                            ".ant-modal-confirm .ant-btn"
                        );
                        const cancelBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-default")
                        );
                        const okBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-primary")
                        );

                        const focusedElement = document.activeElement;

                        if (e.key === "ArrowLeft" && focusedElement === okBtn) {
                            cancelBtn?.focus();
                        } else if (
                            e.key === "ArrowRight" &&
                            focusedElement === cancelBtn
                        ) {
                            okBtn?.focus();
                        }
                    }
                };

                document.addEventListener("keydown", handleKeydown);

                // Store the function to remove event listener later
                removeKeyListener = () => {
                    document.removeEventListener("keydown", handleKeydown);
                };
            }, 0);
        };

        // Update Visible Subscription Modules
        /*  const updateSubscriptionModules = () => {
      store.dispatch("auth/updateVisibleSubscriptionModules");
    };
*/
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
            let totalBillAmount = 0,
                totalTaxAmount = 0;
            datatableVariables.table.data.forEach((tableRowData) => {
                totalBillAmount += tableRowData.bill_amount;
                // totalTaxAmount += tableRowData.tax_amount;
            });
            return {
                totalBillAmount,
                // totalTaxAmount,
            };
        });

        return {
            columns,
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

            detailsDrawerVisible,
            onDetailDrawerClose,
            orderItemDetailsColumns,
            selectedLang,
            initialSetup,

            //   viewOrder,

            confirmModalVisible,
            viewModalVisible,
            modalData,

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

            printInvoiceOrder,
            paymentModalVisible,
            viewPaymentDetails,
            closePaymentDetails,
            userVisible,
            userView,
            salesType,
            printInvoicePDF,
            onSelectChange,
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

        /// sorter//

        handleTableChanges(pagination, filters, sorter) {
            // Handle sorting
            if (sorter.field && sorter.order) {
                const sortedData = [...this.table.data];
                sortedData.sort((a, b) => {
                    const field = sorter.field;
                    const order = sorter.order === "ascend" ? 1 : -1;

                    const valueA = a[field] !== undefined ? a[field] : "";
                    const valueB = b[field] !== undefined ? b[field] : "";

                    if (typeof valueA === "string") {
                        return order * valueA.localeCompare(valueB);
                    } else {
                        return order * (valueA - valueB);
                    }
                });
                this.table.data = sortedData;
            }
            // Handle pagination if needed
            this.table.pagination = pagination;
        },

        // end of sorter//

        calculateTotal(record) {
            // Assuming 'total' is the sum of all line item amounts in the record
            let subtotal = this.calculateSubtotal(record); // Use subtotal as part of total
            let otherCharges = record.shipping + record.handling;
            return subtotal + otherCharges;
        },
        editReturn(invoice_number) {
            //console.log('enter editReturn');
            console.log("enter editReturn");
            console.log(invoice_number);
            this.$emit("child-select", invoice_number);
            /* localStorage.setItem("selectedInvoice", invoice_number);
            this.$router.push({
            name: `admin.expenses1.edit`
      });*/
        },
        test(event) {
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
                    console.log(
                        "event from order list",
                        event.keyCode,
                        event.searchString,
                        this.table.data.length
                    );

                    if (
                        event.searchString != null &&
                        event.searchString !== ""
                    ) {
                        console.log("<dd>", this.selectedInvoice);
                        console.log("testing");
                        this.searchBy = event.selectedRange;
                        this.setUrlData();
                    } else if (this.table.data.length > 0) {
                        console.log("<>", this.selectedInvoice);

                        if (
                            selectedRowKeysValue != undefined &&
                            selectedRowKeysValue.length > 0
                        ) {
                            this.$emit("child-select", this.selectedInvoice);
                        } else {
                            this.focus = 0;
                            this.updateSelection();

                            // Use an arrow function to preserve the 'this' context
                            setTimeout(() => {
                                this.$emit(
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
            console.log(pdfUrl);
            if (pdfUrl == null) {
                notification.warning({
                    placement: "bottomRight",
                    message: "No documents available !",
                });
            } else {
                let w = window.open(pdfUrl, "_blank");
                w.print();
            }
        },
        removeClass() {
            const rows = Array.from(
                document.querySelectorAll("tr.ant-table-row-selected")
            );
            rows.forEach((row) => {
                row.classList.remove("ant-table-row-selected");
            });
        },
        updateSelection(event) {
            this.removeClass();
            const currentRadioInput =
                document.getElementsByClassName("ant-radio-input")[this.focus];
            //currentRadioInput.click();
            currentRadioInput.checked = true;
            //console.log('currentRadioInput'+currentRadioInput.value);
            const currentRow = currentRadioInput.closest("tr");
            const selectedRowKey = currentRow.getAttribute("data-row-key");
            currentRow.classList.add("ant-table-row-selected");
            //console.log("Selected Row Key:", selectedRowKey);
            selectedRowKeysValue = [selectedRowKey];
            //this.selectedInvoice = currentRow
            //.getElementsByTagName("td")[1]
            //.innerHTML.replace(/<[^>]*>?/gm, "");
            this.selectedInvoice = selectedRowKey;
            this.$emit("row-select", this.selectedInvoice);
            //console.log(this.table.data);
        },
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
