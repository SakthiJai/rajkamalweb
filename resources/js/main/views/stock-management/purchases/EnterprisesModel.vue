<template>
  <div id="ledgerenterprise">
    <a-modal
      :open="visible"
      :width="drawerWidth"
      :closable="false"
      :centered="true"
      @ok="onSubmit"
      @keydown.esc="onClose"
      @close="handleClose"
      class="popups"
    >
      <template v-slot:title>
        <div style="display: flex; justify-content: space-between; align-items: center">
          <span>Ledger : {{ party_full_name }}</span>
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
          <a-col :xs="24" :sm="24" :md="24" :lg="24">
            <a-row :gutter="16">
              <a-col :xs="12" :sm="12" :md="3" :lg="3">
                <span class="addressdetalis">{{ $t("Address") }}</span>
              </a-col>

              <a-col :xs="12" :sm="12" :md="21" :lg="21">
                <span> : {{ party_address }} </span>
              </a-col>
            </a-row>
          </a-col>
        </a-row>
        <a-row :gutter="16">
          <a-col :xs="24" :sm="24" :md="24" :lg="24">
            <a-row :gutter="16" style="margin-top: 12px">
              <a-col :xs="24" :sm="24" :md="1" :lg="1"> </a-col>
              <a-col :xs="24" :sm="24" :md="4" :lg="4"> </a-col>

              <a-col :xs="24" :sm="24" :md="2" :lg="2"> </a-col>
              <a-col :xs="24" :sm="24" :md="1" :lg="1">
                <span class="mr-3"
                  ><svg
                    class="mat-datepicker-toggle-default-icon"
                    fill="currentColor"
                    focusable="false"
                    height="18px"
                    viewBox="0 0 24 24"
                    width="18px"
                  >
                    <path
                      d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"
                    ></path></svg
                ></span>
              </a-col>
              <a-col :xs="24" :sm="24" :md="5" :lg="5">
                <span class="date-value rb"
                  ><span>01-04-2024</span><span class="px-3">-To- </span
                  ><span>31-03-2025</span></span
                >
              </a-col>
            </a-row>
          </a-col>
        </a-row>

        <admin-page-table-content>
          <a-row>
            <a-col :span="24">
              <div class="partytable table-responsive">
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
                >
                  <template #bodyCell="{ column, record, rowIndex }" class="highlight">
                    <template v-if="column.dataIndex.indexOf('due_amount') != '-1'">
                      {{ record.due_amount.toFixed(2) }}</template
                    ><template v-if="column.dataIndex.indexOf('paid_amount') != '-1'">
                      {{ record.paid_amount.toFixed(2) }}</template
                    >

                    <template v-if="column.dataIndex.indexOf('order_date') != '-1'">
                      {{ formatDate(record.order_date) }}</template
                    >
                  </template>
                </a-table>
              </div>
            </a-col>
          </a-row>
        </admin-page-table-content>
      </a-form>

      <template #footer class="floats">
        <div class="footer-container">
          <button
            ref="ledgerButton"
            type="button"
            id="btn-Ledger"
            title="Ledger"
         @click="onClose()"
            class="btn default-btn ng-star-inserted"
          >
            <span class="box">
              <span class="shortcut ng-star-inserted">
                <code>Esc</code>
              </span>
              <span class="ng-star-inserted">Close</span>
            </span>
            <span class="effect"></span>
          </button>
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
  ConsoleSqlOutlined,
  ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";

import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import LedgerModel from "./LedgerModel.vue";
import fields from "../../../../common/composable/fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import LedgerAddEdit from "./Ledger/LedgerAddEdit.vue";
import PopupModal from "./PopupModal.vue";

import { Modal } from "ant-design-vue";
import { createVNode } from "vue";

export default defineComponent({
  props: ["partyId", "visible", "url", "addEditType", "pageTitle", "successMessage"],
  created() {
    this.spinning = true;
    axiosAdmin.get("sales/partyDetails/" + this.partyId).then((response) => {
      //console.log(response)
      this.updateLabel(response);
      console.log("created:", this.successMessage);
      // Toastr Notificaiton
    });
    this.reFetchDatatable(this.partyId);
    console.log("created:", this.partyId);

    //this.reFetchDatatable(this.partyId);
  },
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    StaffMemberAddButton,
    DeleteOutlined,
    EditOutlined,
    LedgerModel,

    LedgerAddEdit,
    PopupModal,
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
    const { permsArray, selectedWarehouse, formatDate } = common();
    const selectedIndex = ref(-1);
    const onClose = () => {
      // rules.value = {};
      emit("closed");
      document.getElementById("searchInput").focus();
      
    };
    let selectedKey = ref(0);
    let party_address = ref("");

    onMounted(() => {
      crudVariables.table.filterableColumns = filterableColumns;
      crudVariables.crudUrl.value = addEditUrl;
      crudVariables.langKey.value = "expense_category";
      crudVariables.initData.value = { ...initData };
      crudVariables.formData.value = { ...initData };
      crudVariables.hashableColumns.value = [...hashableColumns];

      //reFetchDatatable();
    });
    const onSelectChange = (changableRowKeys) => {
      console.log("selectedRowKeys changed: ", changableRowKeys);

      selectedRowKeysValue = [changableRowKeys];
      setTimeout(function () {}, 1000);

      /* this.selectedPartyId.id = selectedRowKey;
              this.selectedPartyId.name = currentRow.getElementsByTagName('td')[1].innerHTML.replace(/<[^>]*>?/gm, '');*/
    };
    let selectedRowKeysValue = [];

    const reFetchDatatable = async (formParty) => {
      console.log("fetc", formParty);
      if (formParty !== undefined) {
        crudVariables.tableUrl.value = {
          url:
            "sales?fields=id,total_items,total_quantity,xid,unique_id,warehouse_id,x_warehouse_id,warehouse{id,xid,name},from_warehouse_id,x_from_warehouse_id,fromWarehouse{id,xid,name},invoice_number,order_type,order_date,tax_amount,discount,shipping,subtotal,paid_amount,due_amount,order_status,payment_status,total,tax_rate,staff_user_id,x_staff_user_id,staffMember{id,xid,name,profile_image,profile_image_url,shipping_address,tax_number,email,user_type},user_id,x_user_id,user{id,xid,user_type,name,email,address,tax_number,profile_image,profile_image_url,phone},user:details{opening_balance,opening_balance_type,credit_period,credit_limit,due_amount,warehouse_id,x_warehouse_id},orderPayments{id,xid,amount,payment_id,x_payment_id},orderPayments:payment{id,xid,payment_number,amount,payment_mode_id,x_payment_mode_id,date,notes},orderPayments:payment:paymentMode{id,xid,name},items{id,xid,product_id,x_product_id,unit_id,x_unit_id,single_unit_price,unit_price,quantity,tax_rate,total_tax,tax_type,total_discount,subtotal,mrp},items:unit{id,xid,name,short_name},items:product{id,xid,name,image,image_url},items:product:unit{id,xid,name,short_name},items:product:details{id,xid,warehouse_id,x_warehouse_id,product_id,x_product_id,current_stock},items:orderItemTaxes{id,xid,order_item_id,order_item_id,tax_name,tax_amount},cancelled,terms_condition,customer{id,cus_name,mobile_number},bill_number,payment_status,invoice_path,party{id,party_name,party_type,party_full_name,phone_number,Address}&party_id=" +
            formParty,
          filterableColumns,
        };
        crudVariables.table.sorter = { field: "id", order: "asc" };
        var response = await crudVariables.fetch({
          page: 1,
        });
      }
    };

    watch(selectedWarehouse, (newVal, oldVal) => {
      //reFetchDatatable();
    });

    return {
      columns,
      permsArray,
      ...crudVariables,
      onClose,
      filterableColumns,
      reFetchDatatable,
      onSelectChange,
      formatDate,
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
      selectedPartyId: { id: 0, name: "" },
      selectedIndex: 0,
      isModalPopup: false,
      isLegerModalVisible: false,
      isEnterModal: false,
      isPopupVisible: false,
      isLoading: false,
      dialog: false,
      party_full_name: "",
      party_address: "",
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

  watch: {
    selectedRowKeysValue(newVal, oldVal) {
      console.log("updated");
    },
  },

  mounted() {
    //this.reFetchDatatable();
    document
      .getElementById("ledgerenterprise")
      .addEventListener("keyup", this.handleKeyDownCustomer);
      //document.addEventListener("keyup", this.handleKeyDownCustomer);
    this.autoFocusInput();
    setTimeout(function () {
      this.selectedRowKeysValue = [];
    }, 2000);
    document.addEventListener("keyup", (event) => {
      if (event.key === "Escape" || event.keyCode === 27) {
        setTimeout(() => {
          this.$emit("closed", 27);  // Emit a "closed" event
          document.getElementById("searchInput").focus();  // Focus the search input
        }, 400);
      }
    });
    this.$nextTick(() => {
      if (this.$refs.ledgerButton) {
        this.$refs.ledgerButton.focus();
      }
    });
  },
  beforeDestroy() {
    document
      .getElementById("ledgerenterprise")
      .removeEventListener("keyup", this.handleKeyDownCustomer);
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

    onClose() {
      console.log("vickey");
  
      this.$emit("closed");
      document.getElementById("searchInput").focus();
    },

    test(event) {
      switch (event.keyCode) {
        case 13: // Arrow up
          if (this.table.data.length > 0) {
            console.log("====>", this.selectedPartyId);
            this.$emit("child-method", this.selectedPartyId);
            // Close modal by clicking the modal close button
            const modalCloseButton = document.documentElement.querySelector(
              ".ant-modal-close-x"
            );
            if (modalCloseButton) {
              modalCloseButton.click();
              this.$emit("close-method");
              event.preventDefault();
              return false;
            }
          }
          break;
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
        case 27:
          this.$emit("closed");
          break;
      }
    },
    //         removeClass() {
    //   const rows = Array.from(document.querySelectorAll('tr.ant-table-row-selected'));
    //   rows.forEach(row => {
    //     row.classList.remove('ant-table-row-selected');
    //   });
    // },

    removeClass() {
      const rows = Array.from(document.querySelectorAll("tr.ant-table-row-selected"));
      rows.forEach((row) => {
        row.classList.remove("ant-table-row-selected");
      });
    },

    updateSelection() {
      this.removeClass();
      const currentRadioInput = document.getElementsByClassName("ant-radio-input")[
        this.focus
      ];
      currentRadioInput.checked = true;
      const currentRow = currentRadioInput.closest("tr");
      currentRow.classList.add("ant-table-row-selected");
      const selectedRowKey = currentRow.getAttribute("data-row-key");
      console.log("Selected Row Key:", selectedRowKey, this.focus);

      this.selectedKey = selectedRowKey;
      this.selectedPartyId.id = selectedRowKey;
      this.selectedPartyId.name = currentRow
        .getElementsByTagName("td")[1]
        .innerHTML.replace(/<[^>]*>?/gm, "");
      if (Array.isArray(this.table.data) && this.table.data.length > 0) {
        this.table.data.forEach((row) => {
          console.log("Row ID:", row.id);
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
            console.log("Matched Row:", row);
            console.log("accountgroup:", row.state_name);
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
        if (this.$refs.searchInput) this.$refs.searchInput.focus(); // Automatically focus the input
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
      // this.reFetchDatatable();
      this.isLegerModalVisible = false;
      //this.isEnterModal = false;
      // Reset button color when closing
      this.autoFocusInput();
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

    handleenterprise() {
      this.isEnterModal = false;
    },

    handleKeyDownCustomer(event) {
      console.log("testtttttttt")
      const activeElement = document.activeElement;
      const index = parseInt(activeElement.id.split("_").pop()); // Get the current input index

      if (event.key === "F2") {
        this.isLoading = true;
        setTimeout(() => {
          this.isLoading = false;
          this.isLegerModalVisible = true;
        }, 500);
      } else if (event.key === "F6") {
        event.preventDefault();
        this.isLoading = true;
        setTimeout(() => {
          this.isLoading = false;
          this.isEnterModal = true;
        }, 500);
      } else if (event.key === "Escape" || event.keyCode === 27) {
        setTimeout(() => {
          that.$emit("closed", 27);
          document.getElementById("searchInput").focus();
        }, 400);
      } else if (event.key === "") {
        event.preventDefault();
      }
    },

    closePay() {
      emit("closed");
      document.getElementById("searchInput").focus();
    },

    checkSelectedCustomer() {
      if (this.selectedRowKeysValue == undefined) {
        var that = this;

        setTimeout(function () {
          const currentRadioInput = document.getElementsByClassName("ant-radio-input")[0];
          //currentRadioInput.click();
          const currentRow = currentRadioInput.closest("tr");
          currentRow.classList.add("ant-table-row-selected");
          const selectedRowKey = currentRow.getAttribute("data-row-key");
          console.log("Selected Row Key1:", selectedRowKey);
          this.selectedRowKeysValue = [selectedRowKey];
          that.selectedPartyId.id = selectedRowKey;
          that.selectedPartyId.name = currentRow
            .getElementsByTagName("td")[1]
            .innerHTML.replace(/<[^>]*>?/gm, "");
          document.querySelectorAll(".ant-radio-input").forEach((elem) => {
            elem.addEventListener("change", function (event) {
              var item = event.target.value;
              console.log("<>", item);

              const currentRadioInput = document.getElementsByClassName(
                "ant-table-row-selected"
              );
              const myElem = document.querySelectorAll(".ant-table-row-selected");
              let name = "";
              myElem.forEach(function (elem, index) {
                // console.log(  elem.closest('tr'));
                name = elem
                  .closest("tr")
                  .getElementsByTagName("td")[1]
                  .innerHTML.replace(/<[^>]*>?/gm, "");
                //console.log(name);
              });

              const modalCloseButton = document.documentElement.querySelector(
                ".ant-modal-close-x"
              );
              if (modalCloseButton) {
                //
                //console.log({id:selectedRowKey,name:name})
                that.$emit("child-method", {
                  id: selectedRowKey,
                  name: name,
                });
                //modalCloseButton.click();

                return false;
              }
            });
          });
        }, 1000);
      }
    },
    updateLabel(response) {
      this.party_full_name = response.data.party_name;
      this.party_address = response.data.Address;
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

.addressdetalis {
  font-weight: bold;
  font-size: 16px;
}

.dropdown-icon {
  margin-left: 8px;
  cursor: pointer;
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
