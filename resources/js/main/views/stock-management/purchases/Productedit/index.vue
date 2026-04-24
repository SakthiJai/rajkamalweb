<template>
  <div id="productcreate">
    <AdminPageHeader class="sticky-header">
    <template #breadcrumb>
      <a-breadcrumb separator="-" style="font-size: 12px">
        <a-breadcrumb-item>
          <router-link :to="{ name: 'admin.dashboard.index' }">
            {{ $t(`menu.dashboard`) }}
          </router-link>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          {{ $t(`Product`) }}
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <span style="font-weight: bold"> {{ $t(`Product List`) }}</span>
        </a-breadcrumb-item>
      </a-breadcrumb>
      <a-button
        type="primary"
        class="creating"
        @click="createNewpartyeditEntry()"
        style="float: right;"
      >
        <PlusOutlined />
        Create F2
      </a-button>
    </template>
    <template> </template>
  
    </AdminPageHeader>
  
    <admin-page-filters class="sticky-filters">
      <a-row :gutter="16">
        <loading
          v-model:active="isLoading"
          :can-cancel="true"
          :on-cancel="onCancel"
          :is-full-page="fullPage"
        />
        <a-col :xs="24" :sm="24" :md="12" :lg="24" :xl="24">
          <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="24" :lg="5" :xl="5">
              <a-input
                ref="searchInput"
                id="searchInput"
                style="width: 100%"
                v-model:value="filters.searchString"
                placeholder="Search here"
                @keydown="test"
                @keydown.enter="handleEnterKey"
              />
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="9" :xl="9"> </a-col>
  
            <a-col :xs="24" :sm="24" :md="8" :lg="10" :xl="10">
              <ExprotTable
                exportType="payment_reports"
                tableName="payment-reports-table"
                :title="`${$t('menu.payments')} ${$t('menu.reports')}`"
              />
            </a-col>
          </a-row>
        </a-col>
      </a-row>
    </admin-page-filters>
  
    <!--  ledger modal-->
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
  
    <EnterprisesModel
      v-if="isEnterModal"
      :visible="isEnterModal"
      :formData="formData"
      :url="url"
      :addEditType="addEditType"
      :pageTitle="pageTitle"
      :successMessage="successMessage"
      @addEditSuccess="handleSuccess"
      @closed="handleClose"
    />
    <!--- end ledger modal-->
  
    <admin-page-table-content>
      <ProductEditOrdertable
        ref="PartyTable"
        :orderType="orderType"
        :filters="filters"
        tableSize="middle"
        :bordered="true"
        :selectable="true"
        :perPageItems="100"
        @onRowSelection="(selectedIds) => (selectedRowIds = selectedIds)"
        v-on:child-select="updateselect"
      />
    </admin-page-table-content>
  </div>
  </template>
  <script>
  import { onMounted, watch, ref } from "vue";
  import { PlusOutlined, DeleteOutlined } from "@ant-design/icons-vue";
  import { useRoute } from "vue-router";
  import common from "../../../../../common/composable/common";
  import ProductEditOrdertable from "../../../../components/order/ProductEditOrdertable.vue";
  import DateRangePicker from "../../../../../common/components/common/calendar/DateRangePicker.vue";
  import AdminPageHeader from "../../../../../common/layouts/AdminPageHeader.vue";
  import DateTimePicker from "../../../../../common/components/common/calendar/DatePicker.vue";
  import ExprotTable from "../../../../../main/components/report-exports/ExportTable.vue";
  import table from "../../../../../common/composable/datatable";
  
  export default {
    components: {
      PlusOutlined,
      DeleteOutlined,
      // ExpenseCategoryModal,
     
      DateRangePicker,
  
  
      AdminPageHeader,
      DateTimePicker,
      ExprotTable,
  
      ProductEditOrdertable,
    },
    data() {
      return {
        isModalVisible: false,
        formData: {},
        url: "your-url-here",
        addEditType: "add",
        pageTitle: "GST Invoice Filters",
        successMessage: "Operation successful!",
        isDropdownOpen: false,
        accountGroupColor: "",
  
        buttonLabel: "Today",
        selectedRange: "Today",
        isDropdownOpen: false,
        dropdownStyle: {},
        dropdownStyle: {
          position: "absolute",
          transform: "translate3d(17px, -2px, 0px)",
          top: "0px",
          left: "0px",
        },
        isLoading: false,
        fullPage: true,
        isLegerModalVisible: false,
      };
    },
  
    mounted() {
      document
      .getElementById("productcreate")
      .addEventListener("keydown", this.handleKeydown);
      this.autoFocusInput();
    },
  
    beforeDestroy() {
      document.removeEventListener("keydown", this.handleKeydown);
    },
    methods: {
      selectDateRange(range) {
        // this.selectedRange = range.trim();
        this.buttonLabel = this.selectedRange.trim();
        this.isDropdownOpen = false;
        this.PartyTable.setUrlData(this.selectedRange);
        setTimeout(function () {
          document.getElementById("searchInput").focus();
        }, 600);
        // this.fetchUsers(this.selectedRange);
      },
      createNewpartyeditEntry() {
        this.$router.push({
          name: `admin.stock.productview.create`,
        });
      },
  
      handleKeydown(event) {
        if (event.key === "F2") {
          this.isLoading = true;
          setTimeout(() => {
            this.isLoading = false;
            this.createNewpartyeditEntry();
          }, 500);
        }
      },
      handleEnterKey() {
        this.isDropdownOpen = false;
    
      },
      handleEnterKeyDrop() {
        var that = this;
        setTimeout(() => {
          document.getElementById("today").focus({ focusVisible: true });
        }, 500);
      },
      DeleteRow(that, event) {
        console.log("<>", that);
        this.selectedRange = that;
        this.buttonLabel = this.selectedRange.trim();
        this.isDropdownOpen = false;
        // this.fetchUsers(this.selectedRange);
        this.PartyTable.setUrlData(this.selectedRange);
      },
  
      nextFocus(event) {
        console.log("event=>", event.keyCode);
        if (event.keyCode == 40) {
          if (this.selectedRange == "Today") {
            this.selectedRange = "This Week";
            document.getElementById("this-week").focus({ focusVisible: true });
          } else if (this.selectedRange == "This Week") {
            this.selectedRange = "This Month";
            document.getElementById("this_month").focus({ focusVisible: true });
          } else if (this.selectedRange == "This Month") {
            this.selectedRange = "Yesterday";
            document.getElementById("Yesterday").focus({ focusVisible: true });
          } else if (this.selectedRange == "Yesterday") {
            this.selectedRange = "Last 7 days";
            document.getElementById("last_7_days").focus({ focusVisible: true });
          } else if (this.selectedRange == "Last 7 days") {
            this.selectedRange = "Previous month";
            document.getElementById("previous_month").focus({ focusVisible: true });
          } else if (this.selectedRange == "Previous month") {
            this.selectedRange = "fromDate";
            document.getElementById("fromDate").focus({ focusVisible: true });
          } else if (this.selectedRange == "fromDate") {
            this.selectedRange = "toDate";
            document.getElementById("toDate").focus({ focusVisible: true });
          }
        } else if (event.keyCode == 38) {
          if (this.selectedRange == "This Week") {
            this.selectedRange = "Today";
            document.getElementById("today").focus({ focusVisible: true });
          } else if (this.selectedRange == "This Month") {
            this.selectedRange = "This Week";
            document.getElementById("this-week").focus({ focusVisible: true });
          } else if (this.selectedRange == "Yesterday") {
            this.selectedRange = "This Month";
            document.getElementById("this_month").focus({ focusVisible: true });
          } else if (this.selectedRange == "Last 7 days") {
            this.selectedRange = "Yesterday";
            document.getElementById("Yesterday").focus({ focusVisible: true });
          } else if (this.selectedRange == "Previous month") {
            this.selectedRange = "Last 7 days";
            document.getElementById("last_7_days").focus({ focusVisible: true });
          }
        } else if (event.keyCode == 13) {
          this.selectDateRange();
        }
      },
      autoFocusInput() {
        this.$nextTick(() => {
          this.$refs.searchInput.focus(); // Automatically focus the input
        });
      },
      showModal() {
        this.isLegerModalVisible = true;
      },
      toggleDropdown() {
        this.isDropdownOpen = !this.isDropdownOpen;
        if (!this.isDropdownOpen) {
          this.$refs.searchInput.focus();
        }
        if (this.selectedRange == "Today") {
          this.selectedRange = "This Week";
          setTimeout(function () {
            document.getElementById("this-week").focus({ focusVisible: true });
          }, 500);
        } else if (this.selectedRange == "This Week") {
          this.selectedRange = "This Month";
          setTimeout(function () {
            document.getElementById("this_month").focus({ focusVisible: true });
          }, 500);
        } else if (this.selectedRange == "This Month") {
          this.selectedRange = "Yesterday";
          setTimeout(function () {
            document.getElementById("Yesterday").focus({ focusVisible: true });
          }, 500);
        } else if (this.selectedRange == "Yesterday") {
          this.selectedRange = "Last 7 days";
  
          setTimeout(function () {
            document.getElementById("last_7_days").focus({ focusVisible: true });
          }, 500);
        } else if (this.selectedRange == "Last 7 days") {
          this.selectedRange = "Previous month";
  
          setTimeout(function () {
            document.getElementById("previous_month").focus({ focusVisible: true });
          }, 500);
        } else {
          setTimeout(function () {
            document.getElementById("today").focus({ focusVisible: true });
          }, 500);
        }
      },
    
  
      handleClose() {
        this.isLegerModalVisible = false;
        this.isEnterModal = false;
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
        this.isModalVisible = false;
        console.log("Success:", xid);
      },
      changeColorOnFocus(inputField) {
        if (inputField === "partyName") {
          this.partyNameColor = "#ffd451";
        } else if (inputField === "accountGroup") {
          this.accountGroupColor = "#ffd451";
        } else if (inputField === "stations") {
          this.stationsColor = "#ffd451";
        }
      },
      resetColorOnBlur(inputField) {
        if (inputField === "partyName") {
          this.partyNameColor = "";
        } else if (inputField === "accountGroup") {
          this.accountGroupColor = "";
        } else if (inputField === "stations") {
          this.stationsColor = "";
        }
      },
  
  
      updateselect(id) {
        this.recordId = id;
        console.log("id",);
        console.log("value", id);
        localStorage.setItem("selectedInvoice", id);
        this.$router.push({
          name: `admin.stock.products.edit`,
          params: { id: id },
        });
      },
      test(event) {
        console.log("key code", event.keyCode, this.PartyTable);
        this.PartyTable.test(event);
      },
      changedfromDate(value) {
        this.formData.fromDate = value;
        document.getElementById("toDate").focus();
        console.log(value, "<>", this.formData.fromDate);
      },
      changedtoDate(value) {
        this.formData.toDate = value;
        console.log(value, "<>", this.formData.toDate);
        document.getElementById("creating").focus();
      },
      enterClicked() {
        var formatter = new Intl.DateTimeFormat("en-CA");
  
        if (this.formData.fromDate == undefined) {
          document.getElementById("toDate").focus();
        } else if (this.formData.toDate == undefined) {
          document.getElementById("toDate").focus();
        } else if (
          this.formData.fromDate != undefined &&
          this.formData.toDate != undefined
        ) {
          this.selectedRange =
            formatter.format(this.formData.fromDate) +
            " to " +
            formatter.format(this.formData.toDate);
          this.selectDateRange("custom");
        }
      },
    },
    setup() {
      const {
        formatAmountCurrency,
        orderType,
        orderPageObject,
        orderStatus,
        permsArray,
      } = common();
      const route = useRoute();
      const datatable = table();
      const users = ref([]);
      const serachDateRangePicker = ref(null);
  
      const selectedRowIds = ref([]);
      const PartyTable = ref(null);
  
      const filters = ref({
        payment_status: "all",
        user_id: undefined,
        dates: [],
        searchColumn: "name",
        searchString: "",
      });
  
      onMounted(() => {
      });
  
      const fetchUsers = (data) => {
        const usersPromise = axiosAdmin.get(
          `${orderPageObject.value.userType}?limit=10000&range=` + data
        );
  
        Promise.all([usersPromise]).then(([usersResponse]) => {
          users.value = usersResponse.data;
        });
      };
  
      watch(
        () => route.meta.orderType,
        (newVal, oldVal) => {
          if (
            newVal == "purchases" ||
            newVal == "purchase-returns" ||
            newVal == "sales" ||
            newVal == "sales-returns" ||
            newVal == "quotations"
          ) {
            orderType.value = route.meta.orderType;
  
            filters.value = {
              payment_status: "all",
              user_id: undefined,
              dates: [],
              searchColumn: "name",
              searchString: "",
            };
  
            fetchUsers();
  
            serachDateRangePicker.value.resetPicker();
          }
        }
      );
  
      return {
        orderPageObject,
  
        permsArray,
        orderStatus,
        formatAmountCurrency,
  
        users,
  
        filters,
        orderType,
        serachDateRangePicker,
        fetchUsers,
        selectedRowIds,
        PartyTable,
        ...datatable,
      };
    },
  };
  </script>
  <style>
  .ant-row css-wosfq4 {
    height: 69px !important;
  }
  
  .gstinvoice {
    font-weight: bold;
    font-size: 16px;
  }
  
  .addEffset {
    padding-top: 4px;
    border-right: 1px solid #e4e4e4;
    margin-right: 5px;
    padding-right: 8px;
  }
  
  .bulkaction {
    background-color: #f78d50 !important;
  }
  
  .creating {
    background-color: #1f6d70;
    margin-left: 12px;
  }
  
  .dropdown-toggleing {
    position: relative;
  }
  
  .dropdown-menusing {
    display: block !important;
    position: absolute !important;
    padding: 10px !important;
    list-style: none !important ;
    border-radius: 4px !important;
    width: 300px !important;
  }
  
  .dropdown-menusing li {
    padding: 5px 0 !important;
  }
  
  .dropdown-menusing li a {
    text-decoration: none;
    color: black;
  }
  
  .dropdown-menusing li a:hover {
    color: #007bff;
  }
  
  #dropdowning {
    padding: 0.375rem 0.3rem;
    border: none;
    border: solid rgba(0, 0, 0, 0.2);
    border-width: 1px 1px 2px;
    text-transform: capitalize;
    font-size: 14px;
    background: #fff;
    margin: 0 2px;
  }
  
  .cust-date-filter {
    background-color: white;
    border-radius: 4px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 999;
  }
  
  .dropdown-item {
    display: block;
    width: 100%;
    padding: 0.25rem 0.5rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 1px solid #dee2e6 !important;
    margin-top: 12px;
  }
  
  .blue-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 4px;
  }
  
  .blue-btn:hover {
    background-color: #0056b3;
  }
  
  .form-control {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .matdatepanel {
    margin-top: 20px;
  }
  #customdate {
    float: right;
    margin-top: 12px;
  }
  :where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
    margin-bottom: 5px !important;
  }
  .hrtages {
    margin-top: 22px;
  }
  .dropdown-item:focus {
    background-color: yellow;
  }
  input[readonly] {
    pointer-events: none;
  }
  .cust-date-filter {
    transform: translate3d(0, 0px, 0px) !important;
  }
  .dropdown-item-list {
    caret-color: yellow;
    cursor: pointer;
  }
  .ant-picker-focused:focus-within {
    background-color: yellow !important;
  }
  .sticky-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: white;
  }
  .sticky-filters {
    position: sticky;
    top: 60px;
    z-index: 999;
    background-color: white;
  }
  #productcreate .ant-table-thead > tr > th,
  #productcreate .ant-table-tbody > tr > td {
    padding: 2px !important;
  }
  </style>
  
