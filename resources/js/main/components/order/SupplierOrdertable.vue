<template>
  <a-row>
    <a-col :span="24">
      <div class="table-responsive">
        <a-table
          :columns="PartyList"
          :row-key="(record) => record.id"
          :data-source="table.data"
          :pagination="table.pagination"
          :loading="table.loading"
          :sticky="{ offsetHeader: 60 }"
          :scroll="{ y: 400 }"
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
            <template v-if="column.dataIndex === 'party_name'">
              <a-typography-link @click="viewItem(record)">
                {{ record.party_name }}
              </a-typography-link>
            </template>

            <template v-if="column.dataIndex === 'contactinfo'">
              {{ getcontactinfo(record.contactinfo) }}
            </template>

            <template v-if="column.dataIndex === 'party_type'">
              {{ getPartyType(record.party_type) }}
            </template>
            <template v-if="column.dataIndex === 'stock_city'">
              {{ record.stock_city }}
            </template>
            <template v-if="column.dataIndex === 'whatsapp_number'">
              {{ record.whatsapp_number }}
            </template>
            <template v-if="column.dataIndex === 'opening_balance'">
              <a-typography-link>
                {{ record.opening_balance ? record.opening_balance.toFixed(2) : "0.00" }}
              </a-typography-link>
            </template>
            <template v-if="column.dataIndex === 'customer_status'">
              <a-typography-link @click="viewItem(record)">
                {{ record.customer_status }}
              </a-typography-link>
            </template>

            <template v-if="column.dataIndex === 'action'">
              <a-button @click="editReturn(record.id)" style="margin-left: 4px">
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
            </template>
          </template>
        </a-table>
      </div>
    </a-col>
  </a-row>

  <!-- update selection -->
  <a-row :gutter="16" style="margin-top: 10px">
    <a-col :xs="24" :sm="24" :md="6" :lg="7">
      <fieldset class="partyedits">
        <legend class="basicdetalis">Address</legend>
        <a-col :xs="12" :sm="12" :md="24" :lg="24">
          <span class="contactinfo">{{ formData.Address }}</span>
        </a-col>
        <br />
        <a-row :gutter="16">
          <a-col :xs="12" :sm="12" :md="10" :lg="10">
            <span class="addressinfo"> {{ $t("stock.contact_detail") }}</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="3" :lg="3">
            <span>:</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="10" :lg="10">
            <span class="contactinfo">{{ formData.mobile_number }}</span>
          </a-col>
        </a-row>
        <a-row :gutter="16">
          <a-col :xs="12" :sm="12" :md="10" :lg="10">
            <span class="addressinfo"> {{ $t("stock.ac_group") }}</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="3" :lg="3">
            <span>:</span>
          </a-col>

          <a-col :xs="12" :sm="12" :md="10" :lg="10">
            <span class="contactinfo"> {{ formData.account_group }}</span>
          </a-col>
        </a-row>
      </fieldset>
    </a-col>
    <a-col :xs="24" :sm="24" :md="7" :lg="7">
      <fieldset>
        <legend class="basicdetalis">Licence Info</legend>
        <a-row :gutter="16">
          <a-col :xs="12" :sm="12" :md="9" :lg="9">
            <span class="addressinfo"> {{ $t("stock.gst_number") }}</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="2" :lg="2">
            <span>:</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="12" :lg="12">
            <span class="contactinfo">{{ formData.gst_number }}</span>
          </a-col>
        </a-row>
      </fieldset>
      <fieldset>
        <legend class="basicdetalis">OtherInfo</legend>
        <a-row :gutter="16">
          <a-col :xs="12" :sm="12" :md="10" :lg="10">
            <span class="addressinfo"> {{ $t("stock.states") }}</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="3" :lg="3">
            <span>:</span>
          </a-col>
          <a-col :xs="12" :sm="12" :md="10" :lg="10">
            <span class="contactinfo">{{ formData.state_name }}</span>
          </a-col>
        </a-row>
      </fieldset>
    </a-col>
    <a-col :xs="24" :sm="24" :md="5" :lg="5">
      <fieldset class="partyedit">
        <legend class="basicdetalis">Current Status</legend>
        <div v-if="selectedIndex !== null" class="details">
          <a-row :gutter="16">
            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="addressinfo"> {{ $t("stock.opening") }}</span>
              <span>&nbsp;:&nbsp;</span>
            </a-col>

            <a-col
              :xs="12"
              :sm="12"
              :md="11"
              :lg="11"
              style="padding-left: 2px !important"
            >
              <span class="contactinfo">₹ {{ formData.opening_balance }}</span>
              <!-- <span>{{ formatCurrency(formData.tax_amount) }}</span> -->
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="addressinfo"> {{ $t("stock.debit") }}</span
              ><span>&nbsp;:&nbsp;</span>
            </a-col>

            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="contactinfo">₹ {{ formData.debit }}</span>
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="addressinfo"> {{ $t("stock.credit") }}</span
              ><span>&nbsp;:&nbsp;</span>
            </a-col>

            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="contactinfo">₹ {{ formData.credit }}</span>
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="addressinfo"> {{ $t("stock.balance") }}</span
              ><span>&nbsp;:&nbsp;</span>
            </a-col>

            <a-col :xs="12" :sm="12" :md="11" :lg="11">
              <span class="contactinfo"
                ><span>₹ {{ formData.opening_balance }}</span></span
              >
            </a-col>
          </a-row>
        </div>
      </fieldset>
    </a-col>
  </a-row>

  <div class="floats mt-4" style="margin-top: 1%">
    <a-button
      type="button"
      id="btn-Ledger"
      title="Ledger"
      class="btn default-btn ng-star-inserted"
      @click="EnterprisesModel"
      ><span class="box"
        ><span class="shortcut ng-star-inserted"><code>F9</code></span
        ><span class="ng-star-inserted">Ledger</span></span
      ><span class="effect"></span
    ></a-button>
    <a-button
      type="button"
      id="btn-Ledger"
      title="Ledger"
      class="btn default-btn ng-star-inserted"
      ><span class="box"
        ><span class="shortcut ng-star-inserted"><code>F8</code></span
        ><span class="ng-star-inserted">Outstanding</span></span
      ><span class="effect"></span
    ></a-button>
    <a-button
      type="button"
      id="btn-Ledger"
      title="Ledger"
      class="btn default-btn ng-star-inserted"
      ><span class="box">
        <span class="shortcut ng-star-inserted"><code>F7</code></span
        ><span class="ng-star-inserted">All</span></span
      ><span class="effect"></span
    ></a-button>
  </div>
  <EnterprisesModel
    v-if="isEnterModal"
    :visible="isEnterModal"
    :party-id="selectedInvoice"
    :url="url"
    :addEditType="addEditType"
    :pageTitle="pageTitle"
    :successMessage="successMessage"
    @addEditSuccess="handleSuccess"
    @closed="handleenterprise"
  />

  <!-- end update selection-->

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
import EnterprisesModel from "../../views/stock-management/purchases/EnterprisesModel.vue";
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
    currentPath: {
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
    EnterprisesModel,
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
      PartyList,
      hashableColumns,
      //setupTableColumns,
      filterableColumns,
      pageObject,
      orderType,
      currentPath,
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
        (permsArray.value.includes(`${pageObject.value.permission}_delete`) ||
          permsArray.value.includes("admin")) &&
        record.payment_status == "unpaid"
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
        datatableVariables.table.pagination.pageSize = props.perPageItems;
      }
      datatableVariables.table.pagination.current = 1;
      datatableVariables.table.pagination.currentPage = 1;
      datatableVariables.hashable.value = hashableColumns;

      //setupTableColumns();
      setUrlData();
    };

    const setUrlData = (searchBy) => {
      console.log(props.currentPath);
      let type =
        props.currentPath == "/admin/stock/party"
          ? "customer"
          : props.currentPath == "/admin/stock/customer/list"
          ? "walkin"
          : "supplier";
      if (searchBy == undefined) {
        searchBy = "Today";
      }
      const tableFilter = props.filters;
      console.log(tableFilter);
      const filterString = calculateOrderFilterString(tableFilter);
      console.log(filterString);
      var extraFilterObject = {};
      if (tableFilter.dates) {
        extraFilterObject.dates = tableFilter.dates;
      }
      if (tableFilter.transfer_type) {
        extraFilterObject.transfer_type = tableFilter.transfer_type;
      }

      datatableVariables.tableUrl.value = {
        url: `store-ledger?fields=id,xid,party_name,gst_number,debit,credit,station_name,station,name,state_name,Address,stock_country,stock_state,stock_city,parent_ledger,account_group,mail_to,stock_pincode,balancing_method,opening_balance,credit_days,phone_number,mobile_number,whatsapp_number,ledger_type,pan_number,customer_title,customer_first_name,customer_last_name,gender,designation,website,customer_email,ship_city,ship_address,ship_pincode,ship_contactno,bank_name,branch,ifsc_code,account_number,account_type,account_holder_name,party_full_name,party_type,contactinfo{contact_tittle,contact_number,contact_whatsapp_number,contact_email_id,contact_name,contact_last_name,contact_designation}&type=${type}&searchBy=${filterString}`,
        filters: {
          user_id: tableFilter.user_id ? tableFilter.user_id : undefined,
          warehouse_id: tableFilter.warehouse_id ? tableFilter.warehouse_id : undefined,
        },
        extraFilters: extraFilterObject,
      };
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
        content: t(`Are you sure you want to delete this Party?`),
        autoFocusButton: "ok",
        centered: true,
        okText: t("common.yes"),
        okType: "danger",
        cancelText: t("common.no"),
        onOk() {
          axiosAdmin.delete(`store-ledger/${id}`).then(() => {
            // Update Visible Subscription Modules
            updateSubscriptionModules();
            setUrlData();

            notification.success({
              message: t("Success"),
              description: t(`Party Delted Succesfully`),
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
            allDeletePromise.push(axiosAdmin.delete(`store-ledger/${selectedRow}`));
          });
          Promise.all(allDeletePromise).then((successResponse) => {
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
        totalAmount += tableRowData.total;
      });
      return {
        totalAmount,
      };
    });

    return {
      columns,
      PartyList,
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
      isEnterModal: false,
      selectedPartyId: { id: null },
      partyList: [],
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
          console.log("record", record, "event", record.id);
        },
      };
    },

    getPartyType(party_type) {
      console.log("party_type:", party_type);
      if (Number(party_type) === 1) return "Customer";
      if (Number(party_type) === 2) return "Supplier";
      return "Walk-in Customer";
    },

    getcontactinfo(contactinfo) {
      console.log("contactinfo:", contactinfo);
      if (contactinfo.length > 0) {
        return contactinfo[0].contact_name;
      } else {
        return " ";
      }
    },

    handleKeyDown(event) {
      var that = this;
      if (event.key === "F9") {
        console.log("F9", this.selectedInvoice);
        this.isLoading = true;

        setTimeout(function () {
          that.isEnterModal = true;
        }, 1000);

        event.preventDefault();
      } else if (event.key === "Escape" || event.keyCode === 27) {
        this.isModalVisible = false;
      } else if (event.key === "") {
        event.preventDefault();
      }
    },

    handleClose(input) {
      console.log("input", input);
      if (input && input.id && input.party_name) {
        this.selectedPartyId.id = input.id;
        this.selectedPartyId.name = input.party_name;
      } else {
        console.error("Invalid input:", input);
      }
      this.reFetchDatatable();
      this.isLegerModalVisible = false;
      this.isEnterModal = false;

      this.$emit("child-method", this.selectedPartyId);
    },

    handleSuccess(xid) {
      this.isLegerModalVisible = false;

      console.log("Success:", xid);
    },

    hideModal() {
      this.isLegerModalVisible = false;
    },

    handleenterprise() {
      //this.autoFocusInput();
      this.isEnterModal = false;
    },

    EnterprisesModel() {
      this.isEnterModal = true;

      console.log("Enterprise", this.isEnterModal, this.selectedInvoice);
      //this.isLoading = true;
    },

    calculateTotal(record) {
      // Assuming 'total' is the sum of all line item amounts in the record
      let subtotal = this.calculateSubtotal(record); // Use subtotal as part of total
      let otherCharges = record.shipping + record.handling;
      return subtotal + otherCharges;
    },

    updateSelection(event) {
      console.log(this.focus);
      const currentRadioInput = document.getElementsByClassName("ant-radio-input")[
        this.focus
      ];
      currentRadioInput.click();
      const currentRow = currentRadioInput.closest("tr");
      const selectedRowKey = currentRow.getAttribute("data-row-key");
      console.log("Selected Row Key:", selectedRowKey, this.selectedPartyId);
      selectedRowKeysValue = [selectedRowKey];
      this.selectedInvoice = selectedRowKey;
      this.selectedPartyId.id = selectedRowKey;
      currentRow.scrollIntoView({
        behavior: "auto",
        block: "nearest",
        inline: "nearest",
      });
      if (Array.isArray(this.table.data) && this.table.data.length > 0) {
        this.table.data.forEach((row) => {
          if (row.id === selectedRowKey || row.id == selectedRowKey) {
            this.formData.party_name = row.party_name;
            this.formData.account_group = row.account_group;
            this.formData.Address = row.Address;
            this.formData.state_name = row.state_name;
            this.formData.mobile_number = row.mobile_number;
            this.formData.opening_balance = row.opening_balance;
            this.formData.gst_number = row.gst_number;
            this.formData.debit = row.debit;
            //this.formData.credit = row.credit;
          }
        });
      } else {
        console.error("Table data is not an array or is empty.");
      }
      console.log(this.table.data);
    },
    // editReturn(dr_number)
    // {
    //     this.$emit('child-select',dr_number);
    // },

    test(event) {
      console.log(
        "2333=>",
        event.target.value,
        document.getElementById("searchInput").value
      );
      var that = this;
      switch (event.keyCode) {
        case 38: // Arrow up
          event.preventDefault();
          event.stopPropagation();
          if (this.table.data.length > 0) {
            if (this.focus === null) {
              this.focus = 0;
            } else if (this.focus > 0) {
              this.focus--;
            }
            this.updateSelection();
            console.log("that.selectedInvoice", this.selectedInvoice);
            setTimeout(function () {
              that.$emit("child-select-arrow", that.selectedInvoice);
            }, 1000);
          }
          break;
        case 40: // Arrow down
          event.preventDefault();
          event.stopPropagation();
          if (this.table.data.length > 0) {
            if (this.focus === null) {
              this.focus = 0;
            } else if (this.focus < this.table.data.length - 1) {
              this.focus++;
            }
            this.updateSelection();
            console.log("that.selectedInvoice", this.selectedInvoice);
            setTimeout(function () {
              that.$emit("child-select-arrow", that.selectedInvoice);
            }, 1000);
          }
          break;
        case 46:
          this.autoFocusInput();
          this.showPopupModal();
          break;
        case 13:
          if (this.table.data.length > 0) {
            console.log("<>", this.selectedInvoice);
            if (selectedRowKeysValue != undefined && selectedRowKeysValue.length > 0) {
              this.$emit("child-select", this.selectedInvoice);
            } else {
              this.focus = 0;
              this.updateSelection();
              var that = this;
              setTimeout(function () {
                that.$emit("child-select", this.selectedInvoice);
              }, 1500);
              console.log(this.selectedInvoice);
            }
          }
          break;
        default:
          var that = this;
          if (this.partyList.length == 0) {
            this.partyList = this.table.data;
          }
          setTimeout(function () {
            const value = document.getElementById("searchInput").value;
            const filterTable = that.partyList.filter((o) =>
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

    // edit option//
    async editReturn(id) {
      this.recordId = id;
      try {
        this.$router.push({
          name: "admin.stock.supplier.edit",
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

.popups {
  width: 80% !important;
}

.ant-modal-footer {
  background-color: #e4e4e4 important;
}

.ant-modal-body {
  background-color: #f6f6f6 !important;
}

.table-container {
  overflow-x: auto;
  margin: 0 auto;
}

.responsive-table {
  width: 100%;
}
.buttons {
  margin-left: 5px;
}

.basicdetalis {
  font-size: 13px !important;
}

.partyedits {
  height: 111px;
}

.partyedit {
  height: 111px;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-table-wrapper
  .ant-table-tbody
  > tr.ant-table-row-selected
  > td {
  background-color: #ffd451 !important;
}

body.is-loading {
  filter: blur(5px);
}

.loader-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.7);
  /* Optional: semi-transparent background */
  z-index: 9999;
}

.loader {
  text-align: center;
}
.ant-input-search .ant-input-search-button {
  height: 26px;
}
.partytable .ant-table-cell {
  padding: 2px !important;
}

.ant-select-selection-search-input:focus {
  background-color: #ffd451 !important;
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
.addressinfo {
  font-size: 13px;
  font-weight: bold;
}
.contactinfo {
  font-size: 13px;
}
</style>
