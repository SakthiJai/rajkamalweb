<template>
  <div id="invoiceitemselect">
    <a-modal
      :open="visible"
      :width="drawerWidth"
      :closable="false"
      :centered="true"
      @ok="onSubmit"
      @keydown.esc="hideModal"
      class="popups"
    >
      <template v-slot:title>
        <div style="display: flex; justify-content: space-between; align-items: center">
          <span
            >Products :
            <span
              id="recent_bill"
              style="color: black; font-weight: bolder; text-align-last: right"
            ></span>
          </span>
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
                id="invoiceProdSearch"
                ref="searchInput"
                style="width: 75%"
                placeholder="search here.."
                v-model:value="table.searchString"
                show-search
                :loading="table.filterLoading"
                @focus="checkSelectedCustomer"
                class="inputssearch"
              />
            </a-input-group>
          </a-col>
        </a-row>
      </a-form>

      <admin-page-table-content>
        <a-row>
          <a-col :span="24">
            <div class="partytable table-responsive">
              <a-table
                :columns="invoiceDataColumns"
                :row-key="(record) => record.unique_id || record.id || `${record.product_id}-${record.invoice_number}`"
                :data-source="invoiceList"
                :pagination="table.pagination"
                :loading="table.loading"
                @change="handleTableChange"
                :rowSelection="{
                  // row keys are managed internally; we only care about full rows
                  onChange: onSelectChange,
                  hideDefaultSelections: true,
                  selections: true,
                  type: 'checkbox',
                }"
                bordered
                size="middle"
                >{{}}
                <template #bodyCell="{ column, record, rowIndex }" class="highlight">
                  <template v-if="column.dataIndex === 'product_name'">
                    <a-badge
                      :class="{
                        'row-highlight': rowIndex === selectedIndex,
                      }"
                    >
                      {{ record.product_name || record.product?.name || "Unknown" }}
                    </a-badge>
                  </template>
                  <template v-if="column.dataIndex === 'single_unit_price'">
                    {{ record.single_unit_price.toFixed(2) }}
                  </template>
                  <template v-if="column.dataIndex === 'station'">
                    <a-typography-text
                      v-if="record.adjustment_type === 'add'"
                      type="success"
                      strong
                    >
                      +{{ record.station }}
                    </a-typography-text>
                  </template>
                  <template v-if="column.dataIndex === 'opening_balance'">
                    <a-typography-text
                      v-if="record.adjustment_type === 'add'"
                      type="success"
                      strong
                    >
                      +{{ record.opening_balance }}
                    </a-typography-text>
                  </template>
                </template>
              </a-table>
            </div>
          </a-col>
        </a-row>
      </admin-page-table-content>
      <template #footer>
        <div>
          <a-button
            type="button"
            id="btn-Ledger-invoice"
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
      <!--- end-->
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
  ConsoleSqlOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import LedgerModel from "./LedgerModel.vue";
import EnterprisesModel from "./EnterprisesModel.vue";
import fields from "./Ledger/fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";

import PopupModal from "./PopupModal.vue";
export default defineComponent({
  props: [
    "formData",
    "visible",
    "url",
    "addEditType",
    "pageTitle",
    "successMessage",
    "invoiceList",
  ],
  created() {
    console.log("created:", this.invoiceList);
    const rec = this;
  },
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    StaffMemberAddButton,
    DeleteOutlined,
    EditOutlined,
    LedgerModel,
    EnterprisesModel,

    PopupModal,
  },

  setup(props, { emit }) {
    const { addEditUrl, hashableColumns, initData, invoiceDataColumns } = fields();
    const crudVariables = crud();
    const { permsArray, selectedWarehouse } = common();
    const selectedIndex = ref(-1);
    let selectedKey = ref(0);

    onMounted(() => {
      ///crudVariables.table.filterableColumns = filterableColumns;
      crudVariables.crudUrl.value = addEditUrl;
      crudVariables.langKey.value = "expense_category";
      crudVariables.initData.value = { ...initData };
      crudVariables.formData.value = { ...initData };
      crudVariables.hashableColumns.value = [...hashableColumns];
      ///reFetchDatatable();
    });

    // onSelectChange handled in methods to capture full selected rows

    const reFetchDatatable = () => {
      crudVariables.tableUrl.value = {
        url,
        //filterableColumns,
      };
      crudVariables.table.sorter = { field: "id", order: "asc" };
      crudVariables.fetch({
        page: 1,
      });
    };

    const onClose = () => {
      emit("closed");
      // reFetchDatatable();
    };

    watch(selectedWarehouse, (newVal, oldVal) => {
      //reFetchDatatable();
    });

    return {
      invoiceDataColumns,
      permsArray,
      ...crudVariables,
      onClose,
      //filterableColumns,
      reFetchDatatable,
      drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
    };
  },
  data() {
    return {
      headers: ["Item Name", "Qty", "Return Qty", "Reason", "Return Mode", "₹ Rate"],
      focus: null,
      selectedItems: [],
      selectedPartyId: { id: 0, name: "" },
      selectedIndex: 0,
      isModalPopup: false,
      isLegerModalVisible: false,
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
      .getElementById("invoiceitemselect")
      .addEventListener("keydown", this.handleKeyDownpay);
    this.autoFocusInput();
    // no-op
  },
  beforeDestroy() {
    //document.removeEventListener("keyup", this.handleKeyDownpay);
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
selectInvoiceData() {
  if (this.selectedItems.length > 0) {
    this.$emit("select-method", this.selectedItems);
  } else {
    this.$refs.searchInput.focus();
  }
},

    handleKeyDownpay(event) {
      const activeElement = document.activeElement;
      //const index = parseInt(activeElement.id.split("_").pop()); // Get the current input index
      if (event.key === "Tab") {
        document.getElementById("btn-Ledger-invoice").focus();
        event.preventDefault();
      }
    },

    onClose() {
      this.autoFocusInput();
    },

    onSelectChange(selectedRowKeys, selectedRows) {
      // store full selected rows so parent gets complete objects
      console.log('onSelectChange rows:', selectedRowKeys, selectedRows);
      this.selectedItems = Array.isArray(selectedRows) ? selectedRows.slice() : [];
    },

    test(event) {
      console.log(event.keyCode);
      switch (event.keyCode) {
        case 13: // Arrow up//
          if (this.invoiceList.length > 0 && this.selectedItems) {
            let that = this;
            this.$emit("select-method", this.selectedItems);
          }
          break;
        case 38: // Arrow up
          if (this.invoiceList.length > 0) {
            if (this.focus === null) {
              this.focus = 1;
            } else if (this.focus > 0) {
              this.focus--;
              if (this.focus == 0) {
                this.focus = 1;
              }
            }

            this.updateSelection();
          }
          break;
        case 40: // Arrow down
          if (this.invoiceList.length > 0) {
            if (this.focus === null) {
              this.focus = 1;
            } else if (this.focus <= this.invoiceList.length - 1) {
              this.focus++;
              console.log("this.focus", this.focus);
            }

            this.updateSelection();
          }
          break;
        case 46:
          this.autoFocusInput();
          this.showPopupModal();
          break;

        case 46:
          this.autoFocusInput();
          this.showPopupModal();
          break;
        case 27:
          this.$emit("closed");
          break;
        case 9:
          document.getElementById("btn-Ledger-invoice").focus();
          event.preventDefault();
          break;

        case 32:
          console.log(this.focus);
          this.focus = this.focus ? this.focus : 1;
          let currentRadioInput = document.getElementsByClassName("ant-checkbox-input")[
            this.focus
          ];
 
          if (!currentRadioInput) {
            console.warn('No checkbox input found for focus', this.focus);
            break;
          }
 
          if (!currentRadioInput.checked) {
            console.log(currentRadioInput);
            currentRadioInput.click();
            currentRadioInput.checked = "checked";
            const currentRow = currentRadioInput.closest && currentRadioInput.closest("tr");
            if (!currentRow) {
              console.warn('No row found for current checkbox');
              break;
            }
            const selectedRowKey = currentRow.getAttribute("data-row-key");
            const selectedRow = Array.isArray(this.invoiceList)
              ? this.invoiceList.find((row) =>
                  String(row.unique_id || row.id || `${row.product_id}-${row.invoice_number}`) === String(selectedRowKey)
                )
              : null;

            if (selectedRow && !this.selectedItems.some((row) =>
              String(row.unique_id || row.id || `${row.product_id}-${row.invoice_number}`) === String(selectedRowKey)
            )) {
              this.selectedItems.push(selectedRow);
            }
            console.log("<>", this.selectedItems);
          } else {
            console.log(currentRadioInput);
            currentRadioInput.click();
            currentRadioInput.removeAttribute("checked");
            const currentRow = currentRadioInput.closest && currentRadioInput.closest("tr");
            if (!currentRow) {
              console.warn('No row found for current checkbox');
              break;
            }
            const selectedRowKey = currentRow.getAttribute("data-row-key");

            const existingIndex = this.selectedItems.findIndex((row) =>
              String(row.unique_id || row.id || `${row.product_id}-${row.invoice_number}`) === String(selectedRowKey)
            );

            if (existingIndex !== -1) {
              this.selectedItems.splice(existingIndex, 1);
            }
            console.log("<>", this.selectedItems);
          }
          //currentRadioInput.checked=true;

          break;
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
      if (!currentRadioInput) {
        console.warn('updateSelection: no checkbox input for focus', this.focus);
        return;
      }
      // currentRadioInput.checked=true;
      const currentRow = currentRadioInput.closest && currentRadioInput.closest("tr");
      if (!currentRow) {
        console.warn('updateSelection: no row found for current checkbox');
        return;
      }
      currentRow.classList.add("ant-table-row-selected");
      const selectedRowKey = currentRow.getAttribute("data-row-key");
      console.log("Selected Row Key:", selectedRowKey, this.focus);
      this.selectedKey = selectedRowKey;
      this.selectedPartyId.id = selectedRowKey;
      this.selectedPartyId.name = currentRow
        .getElementsByTagName("td")[1]
        .innerHTML.replace(/<[^>]*>?/gm, "");
      if (Array.isArray(this.invoiceList) && this.invoiceList.length > 0) {
        this.invoiceList.forEach((row) => {
          // console.log('Row ID:', row.id);
          if (row.id === selectedRowKey || row.id == selectedRowKey) {
            this.formData.party_name = row.party_name;
            this.formData.account_group = row.account_group;
            this.formData.Address = row.Address;
            this.formData.state_name = row.state_name;
            this.formData.mobile_number = row.mobile_number;
            this.formData.opening_balance = row.opening_balance;
            this.formData.gst_number = row.gst_number;
            this.formData.debit = row.debit;
            this.formData.credit = row.credit;
            //  console.log('Matched Row:', row);
            // console.log('accountgroup:', row.state_name);
          }
        });
      } else {
        console.error("Table data is not an array or is empty.");
      }
      // this.$emit('child-method', this.selectedPartyId);
      //console.log(this.table.data);
    },

    showPopupModal() {
      this.isModalPopup = true;
    },
    handlePopup() {
      this.isModalPopup = false;
    },
    showModal() {
      this.isLegerModalVisible = true;
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
      this.isLegerModalVisible = false;
    },
    EnterprisesModel() {
      this.isEnterModal = true;
      //this.isLoading = true;
    },
    handleClose() {
      this.isLegerModalVisible = false;
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
      this.isLegerModalVisible = false;
      console.log("Success:", xid);
    },

    // handleKeyDownCustomer(event) {
    //     console.log(event.key);
    //     const activeElement = document.activeElement;
    //     const index = parseInt(activeElement.id.split("_").pop()); // Get the current input index
    //     if (event.key === "Tab") {
    //         document.getElementById("btn-Ledger-invoice").focus();
    //         event.preventDefault();
    //     } else if (event.key === "F2") {
    //         this.isLoading = true;
    //         setTimeout(() => {
    //             this.isLoading = false;
    //             this.isLegerModalVisible = true;
    //         }, 500);
    //     } else if (event.key === "F6") {
    //         this.isLoading = true;
    //         setTimeout(() => {
    //             this.isLoading = false;
    //             this.isEnterModal = true;
    //         }, 500);
    //     } else if (event.key === "Escape" || event.keyCode === 27) {
    //         this.isLegerModalVisible = false;
    //     }
    // },

    checkSelectedCustomer() {
      // optional highlight first selectable row when search input gains focus
      setTimeout(() => {
        const currentRadioInput = document.getElementsByClassName("ant-checkbox-input")[1];
        if (currentRadioInput) {
          const currentRow = currentRadioInput.closest && currentRadioInput.closest("tr");
          if (currentRow) {
            currentRow.classList.add("ant-table-row-selected");
          }
        }
      }, 1000);
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
.ant-input-search .ant-input-search-button {
  height: 26px;
}
.partytable .ant-table-cell {
  padding: 2px !important;
}
</style>
