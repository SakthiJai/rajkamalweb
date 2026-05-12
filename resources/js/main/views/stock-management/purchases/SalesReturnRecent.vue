<template>
  <div id="recentInvoiceDiv">
    <a-modal
      :open="visible"
      :width="drawerWidth"
      :closable="false"
      :centered="true"
      @ok="onSubmit"
      @keydown.esc="hideModal"
      class="size"
    >
      <template v-slot:title>
        <div style="display: flex; justify-content: space-between; align-items: center">
          <span>{{ modalHeading }}: </span>
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

      <a-form layout="vertical">
        <a-row :gutter="16">
          <a-col :xs="24" :sm="24" :md="12" :lg="12">
            <a-input-group compact>
              <a-input-search
                @keydown="test"
                ref="searchInput"
                id="invoiceSearch"
                style="width: 75%"
                placeholder="search here.."
                v-model:value="table.searchString"
                show-search
                @change="onTableSearch"
                :loading="table.filterLoading"
                @focus="checkSelectedCustomer"
                @keydown.space.prevent
                class="inputssearch"
                autocomplete="off"
              />
            </a-input-group>
          </a-col>
        </a-row>
      </a-form>

      <admin-page-table-content>
        <a-row>
          <a-col :span="24">
            <div class="table-responsive">
              <a-table
                :id="'returns_table'"
                :columns="columns"
                :row-key="(record) => record.id"
                :data-source="billNumber.recentBill"
                :pagination="pagination"
                :loading="loading"
                @change="handleTableChange"
                :rowSelection="{
                  selectedRowKeys: selectedRowKeysValue,
                  onChange: onSelectChange,
                  hideDefaultSelections: true,
                  selections: true,
                  // type: 'radio',
                }"
                bordered
                size="middle"
              >
                <template v-if="column.dataIndex === 'tax_amount'">
                  - {{ record.tax_amount.toFixed(2) }}
                </template>
              </a-table>
            </div>
          </a-col>
        </a-row>
      </admin-page-table-content>

      <!--- end-->

      <template #footer>
        <div>
          <a-button
            type="button"
            id="btn-Ledger-recent"
            title="Ledger"
            class="btn default-btn ng-star-inserted gst"
            @click="selectInvoiceData()"
          >
            <span class="box">
              <span class="ng-star-inserted">Ok</span>
            </span>
            <span class="effect"></span>
          </a-button>
        </div>
      </template>
    </a-modal>
  </div>
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
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import fields from "./Payments/fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import { message, notification } from "ant-design-vue";
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
    "returnType",
  ],
  created() {
    console.log("created:", this.billNumber);

    const rec = this;
    setTimeout(function () {
      document.getElementById("invoiceSearch").focus();
    }, 1000);

    //this.reFetchDatatable(this.billNumber);
  },
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    StaffMemberAddButton,
    DeleteOutlined,
    EditOutlined,
  },

  setup(props, { emit }) {
    const {
      url,
      addEditUrl,
      hashableColumns,
      initData,
      columns,
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
    const valueChanged = (value, option) => {
      console.log("valueChanged", value);
      //  emit("valueSuccess");
    };

    const reFetchDatatable = async () => {
      crudVariables.tableUrl.value = {
        url,
        filterableColumns,
      };
      crudVariables.table.sorter = { field: "id", order: "asc" };
      var response = await crudVariables.fetch({
        page: 1,
      });
    };

    const onClose = () => {
      emit("closed");
      // Focus on invoiceSearch only if it exists
      const invoiceSearchElem = document.getElementById("invoiceSearch");
      if (invoiceSearchElem) {
        invoiceSearchElem.focus();
      }
      reFetchDatatable();
    };
    const onSelectChange = (changableRowKeys) => {
      console.log("selectedRowKeys changed: ", changableRowKeys);
      //emit("child-method", this.returnInvoice);
      selectedRowKeysValue = [changableRowKeys];
    };

    // const onClose = () => {
    //     emit("closed");
    // };

    watch(selectedWarehouse, (newVal, oldVal) => {
      reFetchDatatable();
    });

    return {
      columns,
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
      focus: null,
      selectedPaymentId: { id: 0, name: "" },
      selectedIndex: 0,
      isModalPopup: false,
      isModalVisible: false,
      isEnterModal: false,
      isPopupVisible: false,
      isLoading: false,
      dialog: false,
      formData: {
        sales_names: "1",
      },
      url: "your-url-here",
      addEditType: "add",
      pageTitle: "Select Party",
      successMessage: "Operation successful!",
      returnInvoice: { id: null, invoice: null },
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
      returnList: [],
    };
  },
  mounted() {
    this.reFetchDatatable();
    document
      .getElementById("recentInvoiceDiv")
      .addEventListener("keydown", this.handleKeyDownpay);
  },
  beforeDestroy() {
    //document.removeEventListener('keyup', this.handleKeydown);
  },
  methods: {
    handleKeyDownpay(event) {
      const activeElement = document.activeElement;
      //const index = parseInt(activeElement.id.split("_").pop()); // Get the current input index
      if (event.key === "Tab") {
        document.getElementById("btn-Ledger-recent").focus();
        event.preventDefault();
      }
    },
    selectInvoiceData() {
      if (selectedRowKeysValue.length > 0) {
        this.returnInvoice = selectedRowKeysValue.toString();
        this.$emit("product-method", this.returnInvoice);
      } else {
        this.$refs.searchInput.focus();
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
      const currentRadioInput = document.getElementsByClassName("ant-checkbox-input")[
        this.focus
      ];
      currentRadioInput.checked = true;

      const currentRow = currentRadioInput.closest("tr");

      currentRow.classList.add("ant-table-row-selected");
      const selectedRowKey = Number(currentRow.getAttribute("data-row-key"));
      console.log(this.focus, selectedRowKey);
      if (selectedRowKey > 0) {
        this.returnInvoice.invoice = currentRow
          .getElementsByTagName("td")[1]
          .innerHTML.replace(/<[^>]*>?/gm, "");
        this.billNumber.recentBill.forEach((data) => {
          if (data.invoice_number == this.returnInvoice.invoice) {
            this.returnInvoice.id = data.id;
          }
        });
      }
      // this.$emit('child-method', this.selectedPartyId);
      //console.log(this.table.data);
    },
    test(event) {
      console.log("====>", event.keyCode);
      switch (event.keyCode) {
        case 13: // Arrow up
          if (this.table.data.length > 0) {
            console.log("1====>", selectedRowKeysValue.toString());
            if (this.returnInvoice != null) {
              if (selectedRowKeysValue.length > 0) {
                this.returnInvoice = selectedRowKeysValue.toString();
              }
              console.log("2====>", this.returnInvoice);
              this.$emit("product-method", this.returnInvoice);
              // Close modal by clicking the modal close button
              const modalCloseButton = document.documentElement.querySelector(
                ".ant-modal-close-x"
              );
              if (modalCloseButton && selectedRowKeysValue) {
                modalCloseButton.click();
                //this.$emit("close-method");
                event.preventDefault();
                return false;
              }
            }
          }
          break;
        case 38: // Arrow up
          if (this.billNumber.recentBill.length > 0) {
            if (this.focus === null) {
              this.focus = 0;
            } else if (this.focus > 0) {
              this.focus--;
            }
            this.updateSelection();
          }
          break;
        case 40: // Arrow down
          if (this.billNumber.recentBill.length > 0) {
            if (this.focus === null) {
              this.focus = 0;
            } else if (this.focus <= this.billNumber.recentBill.length - 1) {
              this.focus++;
            }
            this.updateSelection();
          }
          break;
        case 46:
          this.autoFocusInput();
          this.showPopupModal();
          break;
        case 27:
          this.$emit("closed");
          break;
        case 9:
          document.getElementById("btn-Ledger-recent").focus();
          event.preventDefault();
          break;
        case 32:
          console.log("Spacebar pressed");
          const currentRadioInput = document.getElementsByClassName("ant-checkbox-input")[
            this.focus
          ];

          if (this.focus > 0) {
            currentRadioInput.click();
          }
          break;
        default:
          console.log("TEETET");
          var that = this;
          if (this.returnList.length == 0) {
            this.returnList = this.billNumber.recentBill;
          }
          setTimeout(function () {
            const value = document.getElementById("invoiceSearch").value;
            const filterTable = that.returnList.filter((o) =>
              Object.keys(o).some((k) =>
                String(o[k]).toLowerCase().includes(value.toLowerCase())
              )
            );
            that.billNumber.recentBill = [];
            that.billNumber.recentBill = filterTable;
            console.log(that.returnList);
          }, 1000);
          break;
      }
    },
    customRow(record) {
      return {
        onClick: (event) => {
          this.rowSelection = event;
          console.log("record", record, "event", record.party_name);
        },
      };
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
    getProducts() {
      console.log("Show prodecuts", document.getElementById("mode").value);
      this.$emit("child-method", document.getElementById("mode").value);
    },

    focusNext(e) {
      const formElements = Array.from(document.querySelectorAll("input, select"));
      const currentIndex = formElements.indexOf(e.target);
      if (currentIndex !== -1 && currentIndex < formElements.length - 1) {
        formElements[currentIndex + 1].focus();
      }
    },
  },
  computed: {
    modalHeading() {
      return this.returnType === "purchase" ? "Purchase Return" : "Sales Return";
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

.size {
  width: 48% !important;
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
table td {
  padding: 2px 5px !important;
}
</style>
