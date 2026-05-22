<template>
  <a-row>
    <a-col :span="24">
      <div class="table-responsive">
        <a-table
          :columns="newcolumns"
          :row-key="(record) => record.id"
          :data-source="table.data"
          :pagination="table.pagination"
          :loading="table.loading"
          :scroll="tableScroll"
          :sticky="{ offsetHeader: 60 }"
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
            <template v-if="column.dataIndex === 'invoice_number'">
              <a-typography-link>
                {{ record.invoice_number }}
              </a-typography-link>
            </template>
            <template v-if="column.dataIndex === 'order_date'">
              {{ formatDate(record.order_date) }}
            </template>
            <template v-if="column.dataIndex === 'party'">
              {{ record.party?.party_name || record.party_name || "" }}
            </template>
            <template v-if="column.dataIndex.includes('party_type')">
              {{ record.party?.party_type == 1 ? "Customer" : "Supplier" }}
            </template>
            <template v-if="column.dataIndex.includes('phone_number')">
              {{ record.party?.phone_number || "" }}
            </template>

            <template v-if="column.dataIndex === 'order_status'">
              <OrderStatus :data="record" />
            </template>

            <template
              v-if="column.dataIndex === 'total'"
              style="color: red; text-align: right !important"
            >
              <span style="float: right">
                {{ Number(record.total ?? record.total_amount ?? 0).toFixed(2) }}
              </span>
            </template>

            <template v-if="column.dataIndex === 'action'">
             <a-button @click="editRow(record)">
  <template #icon>
    <EditOutlined />
  </template>
</a-button>
              <a-button
                v-if="
                  permsArray.includes('expense_categories_delete') ||
                  permsArray.includes('admin')
                "
                type="primary"
                @click="showDeleteConfirm(record.xid)"
                style="margin-left: 4px"
              >
                <template #icon><DeleteOutlined /></template>
              </a-button>
              <!-- <a-button
                                @click="printReturn(record.invoice_path)"
                                type="primary"
                                style="margin-left: 4px"
                            >
                                <template #icon>
                                    <PrinterOutlined />
                                </template>
                            </a-button> -->
              <a-button
                v-if="column.dataIndex === 'action'"
                type="primary"
                style="margin-left: 4px"
              >
                <template #icon>
                  <SendOutlined />
                </template>
              </a-button>
            </template>
          </template>
          <template #expandedRowRender="orderItemData">
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
                <template v-if="column.dataIndex === 'quantity'">
                  {{ `${record.quantity} ${record.product.unit.short_name}` }}
                </template>
                <template v-if="column.dataIndex === 'single_unit_price'">
                  {{ formatAmountCurrency(record.single_unit_price) }}
                </template>
                <template v-if="column.dataIndex === 'total_discount'">
                  {{ formatAmountCurrency(record.total_discount) }}
                </template>
                <template v-if="column.dataIndex === 'total_tax'">
                  <span v-if="record.order_item_taxes.length > 0">
                    <span
                      v-for="order_item_tax in record.order_item_taxes"
                      :key="order_item_tax.xid"
                    >
                      <span>
                        {{ order_item_tax.tax_name }} :
                        {{ formatAmountCurrency(order_item_tax.tax_amount) }}
                      </span>
                      <br />
                    </span>
                  </span>
                  <span v-else>
                    {{ formatAmountCurrency(record.total_tax) }}
                  </span>
                </template>
                <template v-if="$route.name == 'admin.stock.quotations.index'">
                  {{ record.subtotal }}
                </template>
              </template>
            </a-table>
          </template>
          <template #summary>
            <a-table-summary-row >
              <a-table-summary-cell
                :col-span="selectable && orderType != 'online-orders' ? 6 : 7"
              >
              </a-table-summary-cell>
              <a-table-summary-cell :col-span="1">
                <a-typography-text strong> </a-typography-text>
              </a-table-summary-cell>
              <a-table-summary-cell  :col-span="2">
                <a-typography-text strong >
                  {{ $t("common.total") }}:
                  <span style="float: right"> ₹{{ totals.totalAmount.toFixed(2) }} </span>
                </a-typography-text>
              </a-table-summary-cell>

              
              <a-table-summary-cell :col-span="1">
                <a-typography-text strong>
                  <a-typography-text strong>
                    <a-tooltip>
                      <a-typography-text strong>
                        <a-tooltip> <span> </span></a-tooltip>
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

  <OrderDetails
    :visible="detailsDrawerVisible"
    :order="selectedItem"
    @close="onDetailDrawerClose"
    @goBack="restSelectedItem"
    @reloadOrder="paymentSuccess"
  />

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
  <View :visible="userVisible" :user="modalData" @closed="userVisible = false" />
</template>

<script>
import { onMounted, watch, ref, createVNode, computed,defineExpose } from "vue";
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
import crud from "../../../common/composable/crud";


export default {
  props: {
    scroll: {
      type: Object,
      default: null,
    },
    scrollY: {
      type: Number,
      default: 500
    },
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
    salesType: {
      default: "",
    },
    filters: {
      default: {},
    },
    perPageItems: Number,
  },
  emits: ["onRowSelection", "child-select", "row-select", "mouse-select"],
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
    const tableScroll = computed(() => {
      if (props.scroll) {
        return props.scroll;
      }

      return { y: props.scrollY };
    });
        // Edit row handler for action button
        const editRow = (record) => {
          emit("onEditRow", record);
        };
    const store = useStore();
    const {
      columns,
      hashableColumns,
      setupTableColumns,
      filterableColumns,
      pageObject,
      orderType,
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
    let newcolumns = [];
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
      console.log(route);
      initialSetup();
    });

    const onSelectChange = (changableRowKeys, $event) => {
      console.log("selectedRowKeys changed:  ", changableRowKeys, $event);
      datatableVariables.table.data.forEach((data) => {
        if (data.id == changableRowKeys[0]) {
          emit("mouse-select", data.invoice_number);
          // Store selected invoice in localStorage
          if (data.xid) {
            localStorage.setItem("selectedInvoice", data.invoice_number);
          } else if (data.id) {
            localStorage.setItem("selectedInvoice", data.invoice_number);
          }
        }
      });
      // this.$emit("row-select", this.selectedInvoice);
      selectedRowKeysValue.value = changableRowKeys;
    };

    const selectedRowKeysValue = ref([]);

    const getCheckboxProps = (record) => {
      var isDeleteable = true;

      if (
        record.order_type == "stock-transfers" &&
        props.filters.transfer_type == "transfered" &&
        (permsArray.value.includes(`${pageObject.value.permission}_delete`) ||
          permsArray.value.includes("admin")) &&
        record.payment_status == "unpaidss"
      ) {
        isDeleteable = true;
      } else if (
        record.order_type != "online-orders" &&
        (permsArray.value.includes(`${pageObject.value.permission}_delete`) ||
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
    datatableVariables.table.pagination.pageSize = props.perPageItems || 100 ;
  }

  setupTableColumns();
  setUrlData();
};

    const setUrlData = (searchBy) => {
      if (
        props.orderType !== "stock-transfers" &&
        searchBy == undefined &&
        (!props.filters?.dates || props.filters.dates.length === 0)
      ) {
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

      let endpoint = props.orderType;
      
      // Map purchases to bill-returns
      if (props.orderType === "purchases") {
        endpoint = "bill-returns";
      }

      const fields =
        props.orderType === "sales-returns"
          ? "id,xid,cr_number,order_id,party_id,party_customer_id,order_date,return_by,total_amount,tax_amount,total_discount,total_items,invoice_path,user{id,xid,user_type,name,email,address,tax_number,profile_image,profile_image_url,phone},orderPayments{id,xid,amount,payment_id,x_payment_id},orderPayments:payment{id,xid,payment_number,amount,payment_mode_id,x_payment_mode_id,date,notes},orderPayments:payment:paymentMode{id,xid,name},items{id,xid,product_id,x_product_id,unit_id,x_unit_id,single_unit_price,unit_price,quantity,return_qty,tax_rate,total_tax,tax_type,total_discount,subtotal,mrp},items:unit{id,xid,name,short_name},items:product{id,xid,name,image,image_url},items:product:unit{id,xid,name,short_name},items:orderItemTaxes{id,xid,order_item_id,order_item_id,tax_name,tax_amount},party{id,party_name,party_type,party_full_name,phone_number},customer{id,cus_name,mobile_number,phone_number}"
          : props.orderType === "purchases"
          ? "id,xid,partyName{id,party_name,party_full_name},customer{id,cus_name},order_id,order_date,total_amount,invoice_path,invoice_number"
          : "id,total_items,invoice_number,total_quantity,xid,warehouse_id,x_warehouse_id,warehouse{id,xid,name},from_warehouse_id,x_from_warehouse_id,fromWarehouse{id,xid,name},order_type,order_date,tax_amount,discount,shipping,subtotal,paid_amount,due_amount,order_status,payment_status,total,tax_rate,staff_user_id,x_staff_user_id,staffMember{id,xid,name,profile_image,profile_image_url,shipping_address,tax_number,email,user_type},user_id,x_user_id,user{id,xid,user_type,name,email,address,tax_number,profile_image,profile_image_url,phone},user:details{opening_balance,opening_balance_type,credit_period,credit_limit,due_amount,warehouse_id,x_warehouse_id},orderPayments{id,xid,amount,payment_id,x_payment_id},orderPayments:payment{id,xid,payment_number,amount,payment_mode_id,x_payment_mode_id,date,notes},orderPayments:payment:paymentMode{id,xid,name},items{id,xid,product_id,x_product_id,unit_id,x_unit_id,single_unit_price,unit_price,quantity,tax_rate,total_tax,tax_type,total_discount,subtotal,mrp},items:unit{id,xid,name,short_name},items:product{id,xid,name,image,image_url},items:product:unit{id,xid,name,short_name},items:product:details{id,xid,warehouse_id,x_warehouse_id,product_id,x_product_id,current_stock},items:orderItemTaxes{id,xid,order_item_id,order_item_id,tax_name,tax_amount},cancelled,terms_condition,shippingAddress{id,xid,order_id,name,email,phone,address,address,city,state,country,zipcode},party_name,party{id,party_name,party_type,party_full_name,phone_number},customer{id,cus_name,mobile_number,phone_number},bill_number,payment_status,invoice_path";

      datatableVariables.tableUrl.value = {
        url:
          `${endpoint}?fields=${fields}&${props.salesType}&searchBy=` +
          (searchBy ?? ""),
        filterString,
        filters: {
          user_id: tableFilter.user_id ? tableFilter.user_id : undefined,
          warehouse_id: tableFilter.warehouse_id ? tableFilter.warehouse_id : undefined,
        },
        extraFilters: extraFilterObject,
      };
      console.log("🔍 DEBUG OrderTable - URL Config:", {
        baseUrl: datatableVariables.tableUrl.value.url,
        filterString: filterableColumns,
        filters: datatableVariables.tableUrl.value.filters,
        extraFilters: datatableVariables.tableUrl.value.extraFilters,
        searchBy: searchBy,
        orderType: props.orderType,
        salesType: props.salesType
      });
      datatableVariables.table.filterableColumns = filterableColumns;

      if (
        tableFilter.searchColumn &&
        tableFilter.searchString &&
        tableFilter.searchString != ""
      ) {
        datatableVariables.table.searchColumn = tableFilter.searchColumn;
        datatableVariables.table.searchString = tableFilter.searchString;
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
        var searchInput = document.getElementById("searchInput");
        if (searchInput) {
          searchInput.focus();
        }
      }, 1000);
    };

    const showDeleteConfirm = (id) => {
            Modal.confirm({
                title: t("Delete") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`Are you sure you want to delete this Party?`),
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
                            description: t(`Resource deleted successfully`),
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
        content: t(`${pageObject.value.langKey}.selected_delete_message`),
        autoFocusButton: "ok",
        centered: true,
        okText: t("common.yes"),
        okType: "danger",
        cancelText: t("common.no"),
        onOk() {
          const allDeletePromise = [];
          forEach(datatableVariables.table.selectedRowKeys, (selectedRow) => {
            allDeletePromise.push(axiosAdmin.delete(`${props.orderType}/${selectedRow}`));
          });

          Promise.all(allDeletePromise).then((successResponse) => {
            // Update Visible Subscription Modules
            updateSubscriptionModules();

            resetSelectedRows();
            setUrlData();

            notification.success({
              message: t("common.success"),
              description: t(`${pageObject.value.langKey}.deleted`),
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
          axiosAdmin.post(`quotations/convert-to-sale/${order.unique_id}`).then(() => {
            datatableVariables.fetch();

            // Toastr Notificaiton
            notification.success({
              placement: "bottomRight",
              message: t("common.success"),
              description: t("quotation.quotation_converted_to_sales"),
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
          axiosAdmin.post(`online-orders/cancel/${order.unique_id}`).then(() => {
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
          axiosAdmin.post(`online-orders/delivered/${order.unique_id}`).then(() => {
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

    // for payments //
    const addEditVisible = ref(false);
    const addEditType = ref("add");
    const addEditUrl = ref("order-payments");
    const formData = ref({});
    const editItemAmount = ref(0);

    const pageTitle = computed(() => {
      return addEditType.value == "add" ? t(`payments.add`) : t(`payments.edit`);
    });

    const successMessage = computed(() => {
      return addEditType.value == "add" ? t(`payments.created`) : t(`payments.updated`);
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
        totalAmount += Number(tableRowData?.total ?? tableRowData?.total_amount ?? 0);
      });
      return {
        totalAmount,
      };
    });
defineExpose({
  selectedRowKeysValue,
  table: datatableVariables.table,
  editRow
});
    return {
      newcolumns,
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
      showSelectedDeleteConfirm,

      detailsDrawerVisible,
      onDetailDrawerClose,
      orderItemDetailsColumns,
      selectedLang,
      initialSetup,
      tableScroll,

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
      salesType,
      printInvoicePDF,
      onSelectChange,
      editRow,
    };
  },
  data() {
    return { focus: null, salesList: [] };
  },

  mounted() {
    console.log(this.$route.name, this.columns, this.newcolumns);
    document.addEventListener("keyup", this.handleKeyDown);
    if (this.$route.name == "admin.stock.quotations.index") {
      this.columns.forEach((data) => {
        if (data.dataIndex != "total" && data.dataIndex != "payment_status") {
          this.newcolumns.push(data);
        }
      });
    } else {
      this.newcolumns = this.columns;
      //this.columns = this.newcolumns;
    }
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
editRow(record) {
  this.$emit("onEditRow", record); // ✅ send full record to parent
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
      this.$emit("child-select", invoice_number);
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
      case 13: // Enter
    if (this.table.data.length > 0) {

        let selectedData = null;

        if (this.selectedRowKeysValue.length > 0) {
            const selectedKey = this.selectedRowKeysValue[0];

            selectedData = this.table.data.find(
                item => item.id == selectedKey
            );
        } else if (this.focus !== null) {
            selectedData = this.table.data[this.focus];
        }

        if (selectedData) {
            this.selectedInvoice = selectedData.invoice_number;

            // 🔥 THIS IS KEY
            this.$emit("child-select", selectedData.invoice_number);
        }
    }
    break;
        default:
          var that = this;
          if (this.salesList.length == 0) {
            this.salesList = this.table.data;
          }
          setTimeout(function () {
            const value = document.getElementById("searchInput").value;
            const filterTable = that.salesList.filter((o) =>
              Object.keys(o).some((k) =>
                String(o[k]).toLowerCase().includes(value.toLowerCase())
              )
            );
            that.table.data = [];
            that.table.data = filterTable;
          }, 1000);

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
    removeClass() {
      const rows = Array.from(document.querySelectorAll("tr.ant-table-row-selected"));
      rows.forEach((row) => {
        row.classList.remove("ant-table-row-selected");
      });
    },
   updateSelection() {
  this.removeClass();

  const currentRadioInput = document.getElementsByClassName("ant-radio-input")[this.focus];

  currentRadioInput.checked = true;

  const currentRow = currentRadioInput.closest("tr");
  const selectedRowKey = currentRow.getAttribute("data-row-key");

  const selectedData = this.table.data.find(item => item.id == selectedRowKey);

if (selectedData) {
    this.selectedInvoice = selectedData.invoice_number;

    this.selectedRowKeysValue = [selectedData.id];

    localStorage.setItem("selectedInvoice", selectedData.invoice_number);

    this.$emit("row-select", selectedData.invoice_number);
}

  currentRow.classList.add("ant-table-row-selected");
    const container = document.querySelector(".ant-table-body");
  if (container && currentRow) {
    const rowTop = currentRow.offsetTop;
    const rowBottom = rowTop + currentRow.offsetHeight;

    const containerScrollTop = container.scrollTop;
    const containerHeight = container.clientHeight;

    if (rowTop < containerScrollTop) {
      container.scrollTop = rowTop;
    } else if (rowBottom > containerScrollTop + containerHeight) {
      container.scrollTop = rowBottom - containerHeight;
    }
  }
},
  },
};
</script>
<style>

::v-deep(.ant-table-thead > tr > th),
::v-deep(.ant-table-tbody > tr > td) {
  padding: 3px !important;
}

.ant-table-tbody > tr.ant-table-row-selected > td {
  background-color: #ffd451 !important;
}
a-typography-link {
  color: black;
}
.table-footer {
    background-color: #ffffff;
  position: sticky;
  bottom: 0;
  left: 0;
  width: 100%;
}
</style>
