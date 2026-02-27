<template>
  <a-modal
    :open="visible"
    :width="drawerWidth"
    :closable="false"
    :centered="true"
    @keydown="handleKeyDown"
    @ok="onSubmit"
    @keydown.esc="hideModal"
  >
    <template v-slot:title>
      <div style="display: flex; justify-content: space-between; align-items: center">
        <span>Mode Of Payment</span>
        <div @keydown="onKeydown">
          <div v-if="isLoading" class="loader-container">
            <div class="loader">
              <svg
                focusable="false"
                class="anticon-spin"
                data-icon="sync"
                width="30px"
                height="30px"
                fill="currentColor"
                aria-hidden="true"
                viewBox="64 64 896 896"
              >
                <path
                  d="M168 504.2c1-43.7 10-86.1 26.9-126 17.3-41 42.1-77.7 73.7-109.4S337 212.3 378 195c42.4-17.9 87.4-27 133.9-27s91.5 9.1 133.8 27A341.5 341.5 0 01755 268.8c9.9 9.9 19.2 20.4 27.8 31.4l-60.2 47a8 8 0 003 14.1l175.7 43c5 1.2 9.9-2.6 9.9-7.7l.8-180.9c0-6.7-7.7-10.5-12.9-6.3l-56.4 44.1C765.8 155.1 646.2 92 511.8 92 282.7 92 96.3 275.6 92 503.8a8 8 0 008 8.2h60c4.4 0 7.9-3.5 8-7.8zm756 7.8h-60c-4.4 0-7.9 3.5-8 7.8-1 43.7-10 86.1-26.9 126-17.3 41-42.1 77.8-73.7 109.4A342.45 342.45 0 01512.1 856a342.24 342.24 0 01-243.2-100.8c-9.9-9.9-19.2-20.4-27.8-31.4l60.2-47a8 8 0 00-3-14.1l-175.7-43c-5-1.2-9.9 2.6-9.9 7.7l-.7 181c0 6.7 7.7 10.5 12.9 6.3l56.4-44.1C258.2 868.9 377.8 932 512.2 932c229.2 0 415.5-183.7 419.8-411.8a8 8 0 00-8-8.2z"
                ></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
      <button
        @click="onClose"
        type="button"
        aria-label="Close"
        class="ant-modal-close"
        id="closeicon"
      >
        <span class="ant-modal-close-x"
          ><span
            role="img"
            aria-label="close"
            class="anticon anticon-close ant-modal-close-icon"
          >
            <svg
              focusable="false"
              data-icon="close"
              width="1em"
              height="1em"
              fill="currentColor"
              aria-hidden="true"
              fill-rule="evenodd"
              viewBox="64 64 896 896"
            >
              <path
                d="M799.86 166.31c.02 0 .04.02.08.06l57.69 57.7c.04.03.05.05.06.08a.12.12 0 010 .06c0 .03-.02.05-.06.09L569.93 512l287.7 287.7c.04.04.05.06.06.09a.12.12 0 010 .07c0 .02-.02.04-.06.08l-57.7 57.69c-.03.04-.05.05-.07.06a.12.12 0 01-.07 0c-.03 0-.05-.02-.09-.06L512 569.93l-287.7 287.7c-.04.04-.06.05-.09.06a.12.12 0 01-.07 0c-.02 0-.04-.02-.08-.06l-57.69-57.7c-.04-.03-.05-.05-.06-.07a.12.12 0 010-.07c0-.03.02-.05.06-.09L454.07 512l-287.7-287.7c-.04-.04-.05-.06-.06-.09a.12.12 0 010-.07c0-.02.02-.04.06-.08l57.7-57.69c.03-.04.05-.05.07-.06a.12.12 0 01.07 0c.03 0 .05.02.09.06L512 454.07l287.7-287.7c.04-.04.06-.05.09-.06a.12.12 0 01.07 0z"
              ></path>
            </svg>
          </span>
        </span>
      </button>
    </template>

    <a-form layout="vertical" id="paymentform">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item :label="$t('stock.bill_amount')" name="bill_amount">
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input-group compact>
                <a-span type="primary" class="indiannumbers"> ₹ </a-span>
                <a-input
                  readonly
                  @keydown="test"
                  ref="searchInput"
                  :value="formatCurrency(billValue)"
                  class="amount"
                  style="width: 79%"
                  autocomplete="off"
                />
              </a-input-group>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-form-item :label="$t('stock.balance_adjusted')" name="account_group">
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="6" :lg="6">
              <a-input
                readonly
                id="adjust_balance"
                :value="balance_adjusted"
                class="amount"
                style="width: 79%"
                autocomplete="off"
              />
            </a-col>
          </a-row>
        </a-col>
      </a-row>
    </a-form>
    <!-- data table-->

    <admin-page-table-content>
      <a-row>
        <a-col :span="24">
          <div class="table-responsive">
            <table class="responsive-table">
              <thead>
                <tr>
                  <th
                    v-for="(header, index) in paymentColumns"
                    :key="index"
                    class="tableheading"
                  >
                    &nbsp;&nbsp;{{ header.title }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, index) in table.data" :key="index">
                  <!-- <td style="width:25%">&nbsp;&nbsp;{{ data.settlement_mode }}</td> -->
                  <td style="width: 25%">&nbsp;&nbsp;{{ data.settlement_mode }}</td>

                  <td style="width: 15%">
                    <input
                      autocomplete="off"
                      @keypress="onlyForCurrency"
                      :value="data.amount"
                      :v-model="data.amount"
                      :id="`amount_${index}`"
                      @keydown.space.prevent="showCustomerModal"
                      class="ant-input css-dev-only-do-not-override-wosfq4 amount amount_0"
                      @keyup="updateBalance(index, $event)"
                      @keyup.enter="focusNext"
                    />
                  </td>
                  <td style="width: 15%">
                    <input
                      autocomplete="off"
                      :v-model="data.remarks"
                      :id="`remarks_${index}`"
                      class="ant-input css-dev-only-do-not-override-wosfq4 amount"
                      @keydown="updateBalanceRemarks(index, $event)"
                      :value="data.remarks"
                      @keyup.enter="focusNext"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </a-col>
      </a-row>
      <a-row :gutter="16" class="mt-10">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item :label="$t('stock.cash_tender')" name="cash_tender">
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input-group compact>
                <a-span type="primary" class="indiannumbers"> ₹ </a-span>
                <a-input
                  id="cash_tender"
                  @keypress="onlyForCurrency"
                  @keyup="updateCashReturn(index, $event)"
                  v-model="formDataLedger.cash_tender"
                  class="amount"
                  style="width: 79%"
                  autocomplete="off"
                  @keyup.enter="focusNext"
                />
              </a-input-group>
            </a-col>
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item :label="$t('stock.cash_return')" name="cash_return">
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input-group compact>
                <a-span type="primary" class="indiannumbers"> ₹ </a-span>
                <a-input
                  disabled
                  id="cash_return"
                  tabindex="-1"
                  v-model:value="formDataLedger.cash_return"
                  autocomplete="off"
                  class="amount"
                  style="width: 79%"
                  @keyup.enter="focusNext"
                />
              </a-input-group>
            </a-col>
          </a-row>
        </a-col>
      </a-row>
    </admin-page-table-content>
    <!--- en d-->

    <template #footer>
      <a-button
        type="button"
        id="btn-Ledger"
        style="margin-top: 2%"
        title="Ledger"
        class="btn default-btn"
        @click="closePay()"
      >
        <span class="shortcut">
          <code>Esc</code>
        </span>
        <span class="savebutton">
          <svg height="10px" width="10px" viewBox="0 0 611.96 611.96">
            <path
              d="M378.71,306,596.9,87.79a51.43,51.43,0,0,0-72.73-72.73L306,233.25,87.79,15.06A51.43,51.43,0,0,0,15.06,87.79L233.25,306,15.06,524.17a51.43,51.43,0,1,0,72.73,72.73L306,378.71,524.17,596.89a51.43,51.43,0,1,0,72.73-72.73Z"
            ></path>
          </svg>
        </span>
      </a-button>
      <a-button
        type="button"
        id="savebtn"
        key="back"
        class="btn default-btn ng-star-inserted gst"
        @click="saveBillAmtEntry"
      >
        <span class="box">
          <span class="shortcut ng-star-inserted"><code>F8</code></span>
          <span class="ng-star-inserted">Save</span>
        </span>
      </a-button>
    </template>

    <!--- eway bill-->
    <!-- <EwayBillModel
      v-if="isEwaybillModalVisible"
      :visible="isEwaybillModalVisible"
      :formData="formData"
      :url="url"
      :addEditType="addEditType"
      :pageTitle="pageTitle"
      :successMessage="successMessage"
      @addEditSuccess="handleSuccess"
      @closed="handleCloseEwaybill"
      :account-number="formData.account_number"
      :pay-items="billItems"
    /> -->

    <!-- end of eway  bill-->
  </a-modal>
</template>

<script>
import { onMounted, watch, ref, computed } from "vue";
import { defineComponent } from "vue";
import {
  PlusOutlined,
  LoadingOutlined,
  SaveOutlined,
  EditOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import fields from "./Payments/fields";
// import EwayBillModel from "../../../../main/views/stock-management/purchases/EwayBillModel.vue";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import { message, notification } from "ant-design-vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";
export default defineComponent({
  props: [
    "formData",
    "visible",
    "url",
    "addEditType",
    "pageTitle",
    "successMessage",
    "billValue",
    "billNumber",
    "billItems",
  ],
  created() {
    console.log("created:", this.billItems);
    this.reFetchDatatable(this.billNumber);
  },
  components: {
    ExclamationCircleOutlined,
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    StaffMemberAddButton,
    DeleteOutlined,
    EditOutlined,
    // EwayBillModel,
  },

  setup(props, { emit }) {
    const {
      url,
      addEditUrl,
      hashableColumns,
      initData,
      columns,
      paymentColumns,
      filterableColumns,
    } = fields();
    const crudVariables = crud();
    const { permsArray, selectedWarehouse } = common();
    const selectedIndex = ref(-1);

    onMounted(() => {
      let vm = this;
      console.log("Calling 123", this);
      crudVariables.table.filterableColumns = filterableColumns;
      crudVariables.crudUrl.value = addEditUrl;
      crudVariables.langKey.value = "expense_category";
      crudVariables.initData.value = { ...initData };
      crudVariables.formData.value = { ...initData };
      crudVariables.hashableColumns.value = [...hashableColumns];
    });
    const onSelectChange = (changableRowKeys) => {
      console.log("selectedRowKeys changed: ", changableRowKeys);

      selectedRowKeysValue = [changableRowKeys];
    };
    let selectedRowKeysValue = [15];

    const reFetchDatatable = (bill) => {
      console.log("bill", bill);
      const url =
        "payment-mode?fields=id,xid,bill_amount,balance_adjusted,amount,settlement_mode,settlement_id,remarks,cash_tender,cash_return,bill_number&filters=(bill_number%20lk%20%22%25" +
        bill +
        "%25%22%20)";
      crudVariables.tableUrl.value = {
        url,
        filterableColumns,
      };

      let response = crudVariables.fetch({
        page: 1,
        focus: "amount_0",
      });
      console.log(response);
      setTimeout(function () {
        //console.log("Payment focus3",params.focus);
        document.getElementById("amount_0").focus();
      }, 1000);
    };

    const onClose = () => {
      emit("closedbyclose");
    };

    const handleKeyDown1 = (event) => {
      if (event.key === "ArrowDown") {
        selectedIndex.value =
          (selectedIndex.value + 1) % (crudVariables.table?.data?.length || 0);
      } else if (event.key === "ArrowUp") {
        selectedIndex.value =
          (selectedIndex.value - 1 + (crudVariables.table?.data?.length || 0)) %
          (crudVariables.table?.data?.length || 0);
      }

      if (event.key === "Tab" && !event.shiftKey) {
        event.preventDefault();
        moveFocusForward(event.target);
      }
    };

    const moveFocusForward = (currentElement) => {
      const focusableElements = Array.from(
        document.querySelectorAll(
          "input, button, select, textarea, a[href], [tabindex]:not([tabindex='-1'])"
        )
      ).filter((el) => !el.disabled && el.offsetParent !== null);
      const currentIndex = focusableElements.indexOf(currentElement);
      const nextIndex = (currentIndex + 1) % focusableElements.length;
      focusableElements[nextIndex]?.focus();
    };

    watch(selectedWarehouse, (newVal, oldVal) => {
      reFetchDatatable();
    });

    return {
      columns,
      paymentColumns,
      permsArray,
      ...crudVariables,
      onClose,
      filterableColumns,
      reFetchDatatable,
      onSelectChange,
      drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
    };
  },

  data() {
    return {
      headers: ["Ledger Name", "Station", "₹ Balance", "Actions"],
      items: [
        {
          name: "Waste Furniture",
          location: "Bangalore",
          value: "2,000.00 Cr",
        },
        {
          name: "Recycled Paper",
          location: "Delhi",
          value: "1,500.00 Cr",
        },
        {
          name: "Scrap Metal",
          location: "Mumbai",
          value: "3,000.00 Cr",
        },
      ],
      focus: null,
      selectedPaymentId: { id: 0, name: "" },
      selectedIndex: 0,
      isModalPopup: false,
      isModalVisible: false,
      isEnterModal: false,
      isPopupVisible: false,
      isEwaybillModalVisible: false,
      isLoading: false,
      dialog: false,
      formData: {
        sales_names: "1",
      },
      url: "your-url-here",
      addEditType: "add",
      pageTitle: "Select Party",
      successMessage: "Operation successful!",
      buttonStyle: {
        backgroundColor: "",
        borderColor: "",
        color: "",
      },
      buttonStyle: {},
      formData: {
        account_number: "",
        Address: "",
        state_name: "",
        mobile_number: "",
        opening_balance: "",
        gst_number: "",
        debit: "",
        credit: "",
      },
    };
  },
  mounted() {
    document
      .getElementById("paymentform")
      .addEventListener("keydown", this.handleKeydown);
    // document.addEventListener('keyup', this.handleKeydown);
    this.autoFocusInput();
    setTimeout(function () {
      this.selectedRowKeysValue = [15];
    }, 2000);
  },
  beforeDestroy() {
    //document.removeEventListener('keyup', this.handleKeydown);
  },

  computed: {
    balance_adjusted() {
      const sum = this.getTotalEnteredAmt();
      return this.formatCurrency(Number(this.billValue) - sum);
    },
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
    test(event) {
      switch (event.keyCode) {
        case 38: // Arrow up
          if (this.focus === null) {
            this.focus = 0;
          } else if (this.focus > 0) {
            this.focus--;
          }
          this.updateSelection();
          break;
        case 40: // Arrow down
          if (this.focus === null) {
            this.focus = 0;
          } else if (this.focus < this.table.data.length - 1) {
            this.focus++;
          }
          this.updateSelection();
          break;
        case 46:
          this.autoFocusInput();
          this.showPopupModal();
          break;
      }
    },
    updateSelection() {
      const currentRadioInput = document.getElementsByClassName("ant-radio-input")[
        this.focus
      ];
      currentRadioInput.click();
      const currentRow = currentRadioInput.closest("tr");
      const selectedRowKey = currentRow.getAttribute("data-row-key");
      console.log("Selected Row Key:", selectedRowKey);
      this.selectedPaymentId.id = selectedRowKey;
      this.selectedPaymentId.settlement_mode = currentRow
        .getElementsByTagName("td")[1]
        .innerHTML.replace(/<[^>]*>?/gm, "");

      this.$emit("child-method", this.selectedPaymentId);
      console.log(this.table.data);
    },

    showPopupModal() {
      this.isModalPopup = true;
    },
    handlePopup() {
      this.isModalPopup = false;
    },
    showModal() {
      this.isModalVisible = true;
    },
    updatePartyName(newName) {
      this.formDataLedger.party_name = newName;
    },
    autoFocusInput() {
      this.$nextTick(() => {
        this.$refs.searchInput.focus(); // Automatically focus the input
      });
    },
    hideModal() {
      this.isModalVisible = false;
    },
    EnterprisesModel() {
      this.isEnterModal = true;
      //this.isLoading = true;
    },
    handleClose() {
      this.isModalVisible = false;
      this.isEnterModal = false;
      // Reset button color when closing
      this.buttonStyle = {
        backgroundColor: "",
        borderColor: "",
        color: "",
      };
    },
    handleSuccess(xid) {
      // Handle success logic
      this.isModalVisible = false;
      console.log("Success:", xid);
    },

    focusNext(e) {
      console.log(e.target.id, e.keyCode);

      const formElements = Array.from(document.querySelectorAll("input, select, button"));
      const currentIndex = formElements.indexOf(e.target);
      if (e.keyCode === 13) {
        if (
          e.target.id === "cash_tender" &&
          document.getElementById("cash_tender").value === ""
        ) {
          document.getElementById("savebtn").focus();
        } else if (
          e.target.id === "cash_tender" &&
          document.getElementById("cash_tender").value !== ""
        ) {
          document.getElementById("savebtn").focus();
        } else {
          if (currentIndex !== -1 && currentIndex < formElements.length - 1) {
            formElements[currentIndex + 1].focus();
          }
        }
        e.preventDefault();
      } else {
        if (currentIndex !== -1 && currentIndex < formElements.length - 1) {
          formElements[currentIndex + 1].focus();
        }
      }
    },

    handleKeyDown(event) {
      console.log("payment", event.key);
      const activeElement = document.activeElement;
      const index = parseInt(activeElement.id.split("_").pop());

      if (event.key === "F8") {
        event.preventDefault();
        this.saveBillAmtEntry();
      } else if (event.key === "F4") {
        this.isLoading = true;
        setTimeout(() => {
          this.isLoading = false;
          this.isEnterModal = true;
        }, 500);
      } else if (event.key === "Escape" || event.key === "Esc") {
        this.closePay();
      } else if (event.key === "Enter") {
        // Handle Enter key here if necessary
      } else if (event.shiftKey && event.key === "Tab") {
        console.log("Current index:", index);

        if (index > 0) {
          const previousInput = document.getElementById(`amount_${index - 1}`);
          if (previousInput) {
            previousInput.focus();
          }
        }
      }
    },

    formatCurrency(value) {
      return value.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },
    updateBalance(index, event) {
      console.log(
        index,
        "<>",
        document.getElementById("amount_" + index).value,
        event.keyCode
      );
      if (event.shiftKey && event.altKey) {
        document.getElementById("amount_" + index).focus();
        console.log("Tab or Shift + Alt on remarks");

        return false;
      } else if (event.keyCode === 119) {
        this.saveBillAmtEntry();
        return false;
      } else if (event.keyCode === 13) {
        document.getElementById("remarks_" + index).focus();
        return false;
      } else if (event.keyCode === 27) {
        this.closePay();
        return false;
      }
      this.table.data[index].amount = Number(
        document.getElementById("amount_" + index).value
      );

      var sum = this.getTotalEnteredAmt();
      console.log(Number(sum), "> ", Number(this.billValue.toFixed(2)));
      if (Number(sum) > Number(this.billValue.toFixed(2))) {
        notification.warning({
          placement: "bottomRight",
          message: "Please Enter the Amount Should be less then Bill amount !",
          //description: configObject.successMessage
        });
        this.table.data[index].amount = 0;
        document.getElementById("adjust_balance").value = 0;
        document.getElementById("amount_" + index).focus();
      } else {
        // let adjustBalance = Number(this.billValue) - Number(sum);
        // document.getElementById("adjust_balance").value = this.formatCurrency(
        //   adjustBalance
        // );
      }
      document.getElementById("adjust_balance").value = this.formatCurrency(
        Number(this.billValue) - Number(sum)
      );
      //Please Enter less then Bill amount
    },
    updateBalanceRemarks(index, event) {
      const amt = this.getTotalEnteredAmt();
      console.log(this.billValue);
      if (event.keyCode == 27) {
        this.closePay();
      } else if (event.keyCode === 119) {
        this.saveBillAmtEntry();
        return false;
      } else if (amt == undefined || amt == "") {
        this.table.data[index].amount = 0;
        //return false;
      } else if (amt < 0 && event.shiftKey && event.altKey) {
        console.log("Tab or Shift + Alt on remarks");
        document.getElementById("cash_tender").focus();
        event.preventDefault();
        return false;
      } else if (Number(this.billValue) == Number(amt) && event.keyCode === 13) {
        document.getElementById("cash_tender").focus();
        event.preventDefault();
        return false;
      }
      this.table.data[index].remarks = document.getElementById("remarks_" + index).value;
    },
    getTotalEnteredAmt() {
      let total = 0;

      this.table.data.forEach((element) => {
        if (element.amount != null && element.amount > 0) {
          total += Number(element.amount);
        }
      });
      console.log(total.toFixed(2));
      return total.toFixed(2);
    },

    onlyForCurrency($event) {
      let keyCode = $event.keyCode || $event.which;
      const inputValue = $event.target.value;

      if (
        (keyCode < 48 || keyCode > 57) &&
        (keyCode !== 46 || inputValue.includes("."))
      ) {
        $event.preventDefault();
        return;
      }

      if (inputValue === "" && (keyCode === 46 || keyCode < 48 || keyCode > 57)) {
        $event.preventDefault();
      }

      setTimeout(() => {
        const updatedValue = $event.target.value;

        if (!/^\d*\.?\d{0,2}$/.test(updatedValue)) {
          const sanitizedValue = updatedValue
            .replace(/[^0-9.]/g, "")
            .replace(/(\..*?)\./g, "$1")
            .replace(/(\.\d{2})\d+/, "$1");

          if (sanitizedValue !== updatedValue) {
            /*notification.warning({
              placement: "bottomRight",
              message: "Only 2 decimal places are allowed.",
            });*/
          }

          $event.target.value = sanitizedValue;
        }
      }, 0);
    },

    updateCashReturn(index, event) {
      if (event.keyCode == 27) {
        this.closePay();
        return false;
      } else if (event.keyCode === 119) {
        this.saveBillAmtEntry();
        return false;
      }
      var sum = this.getTotalEnteredAmt();
      var cashTender = document.getElementById("cash_tender").value;
      if (Number(sum) > 0 && Number(cashTender) > 0 && event.keyCode != 9) {
        const cashRetun = Number(cashTender) - Number(sum);
        document.getElementById("cash_return").value =
          cashRetun > 0 ? cashRetun.toFixed(2) : 0;
      } else if (event.keyCode == 9) {
        // document.getElementById('btn-Ledger').focus();
      }
    },
    success(resonse) {
      this.isLoading = false;
      notification.success({
        placement: "bottomRight",
        message: resonse.message,
        description: resonse.message,
      });
      this.$emit("closed");
    },

    closePay() {
      console.log("Esc called");
      let that = this;
      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Transaction data will be lost. Are you sure you want to close?",
        onOk() {
          that.$emit("closed", 27);
        },
        onCancel() {
          console.log("Modal close canceled");
        },
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
      });

      this.$nextTick(() => {
        const handleKeydown = (e) => {
          if (modal && (e.key === "ArrowLeft" || e.key === "ArrowRight")) {
            e.preventDefault();
            const buttons = document.querySelectorAll(".ant-modal-confirm .ant-btn");
            const cancelBtn = Array.from(buttons).find((btn) =>
              btn.classList.contains("ant-btn-default")
            );
            const okBtn = Array.from(buttons).find((btn) =>
              btn.classList.contains("ant-btn-primary")
            );

            const focusedElement = document.activeElement;

            if (e.key === "ArrowRight") {
              if (okBtn) {
                okBtn.focus();
              }
            } else if (e.key === "ArrowLeft") {
              if (cancelBtn) {
                cancelBtn.focus();
              }
            }
          }
        };

        document.addEventListener("keydown", handleKeydown);

        /*modal.afterClose(() => {
          document.removeEventListener("keydown", handleKeydown);
        });*/
      });
    },
    ewaybillPay() {
      console.log("Esc called");
      let that = this; // Store the correct `this` context

      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Bill amount is more than 50000. Want to generate E-way Bill?",
        onOk() {
          that.ewaybill(); // Call the ewaybill function using the stored context
        },
        onCancel() {
          console.log("Modal close canceled");
        },
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
      });

      this.$nextTick(() => {
        const handleKeydown = (e) => {
          if (modal && (e.key === "ArrowLeft" || e.key === "ArrowRight")) {
            e.preventDefault();
            const buttons = document.querySelectorAll(".ant-modal-confirm .ant-btn");
            const cancelBtn = Array.from(buttons).find((btn) =>
              btn.classList.contains("ant-btn-default")
            );
            const okBtn = Array.from(buttons).find((btn) =>
              btn.classList.contains("ant-btn-primary")
            );

            const focusedElement = document.activeElement;

            if (e.key === "ArrowRight") {
              if (okBtn) {
                okBtn.focus();
              }
            } else if (e.key === "ArrowLeft") {
              if (cancelBtn) {
                cancelBtn.focus();
              }
            }
          }
        };

        document.addEventListener("keydown", handleKeydown);

        /* modal.afterClose(() => {
      document.removeEventListener("keydown", handleKeydown);
    });*/
      });
    },

    // Define the ewaybill function correctly
    ewaybill() {
      this.isEwaybillModalVisible = true; // Set the modal visibility to true
      console.log("E-way Bill generation triggered");
    },
    handleCloseEwaybill() {
      this.isEwaybillModalVisible = false;
    },
    saveBillAmtEntry() {
      console.log(this.table.data);
      let ewaybillTriggered = false;

      this.table.data.forEach((element) => {
        if (
          element.amount != null &&
          element.amount != undefined &&
          Number(element.amount) > 0
        ) {
          element.bill_amount = this.billValue; // revert from git
          element.balance_adjusted = this.balance_adjusted;
          element.cash_tender = document.getElementById("cash_tender").value;
          element.cash_return = document.getElementById("cash_return").value;
          element.invoice_number = document.getElementById("form_item_bill_number").value;
        }
      });

      // If no ewaybill was triggered, proceed with saving the data
      if (!ewaybillTriggered) {
        this.isLoading = true;
        axiosAdmin
          .post("sales/savepayment", { data: this.table.data })
          .then((response) => {
            // Toastr Notification
            this.success(response);
          })
          .catch((errorResponse) => {
            var err = errorResponse.data;
            const errorCode = errorResponse.status;
            var errorRules = {};

            if (errorCode == 422) {
              if (err.error && typeof err.error.details != "undefined") {
                var keys = Object.keys(err.error.details);
                for (var i = 0; i < keys.length; i++) {
                  // Escape dot that comes with error in array fields
                  var key = keys[i].replace(".", "\\.");

                  errorRules[key] = {
                    required: true,
                    message: err.error.details[keys[i]][0],
                  };
                }
              }

              rules.value = errorRules;
              message.error(t("common.fix_errors"));
            }

            if (err && err.message) {
              message.error(err.message);
              err = {
                error: {
                  ...err,
                },
              };
            }
          });
      }
    },
  },
});
</script>

<style>
.popups {
  width: 80% !important;
  /*vertical-align: top !important;
    top:15px !important;*/
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

table,
th,
td {
  border-collapse: collapse !important;
  border: 1px solid #0000003d;
}

tr {
  background-color: white;
}

.tableheading {
  background-color: #7da5a7;
}

.responsive-table th,
.responsive-table td {
  padding: 4px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

@media (max-width: 600px) {
  .responsive-table thead {
    display: none;
  }

  .responsive-table,
  .responsive-table tbody,
  .responsive-table tr,
  .responsive-table td {
    display: block;
    width: 100%;
  }

  .responsive-table tr {
    margin-bottom: 15px;
  }

  .responsive-table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
  }

  .responsive-table td::before {
    content: attr(data-label);
    position: absolute;
    left: 0;
    width: 50%;
    padding-left: 15px;
    text-align: left;
    font-weight: bold;
  }
}

.buttons {
  margin-left: 5px;
}

.basicdetalis {
  font-size: 15px !important;
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
.indiannumbers {
  padding: 4px 7px !important;
  background: #eaeaea;
  border-color: #c2c2c2;
}
</style>
