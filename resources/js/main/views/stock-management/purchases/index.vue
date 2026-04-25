<template>
    <div id="listindex">
    <AdminPageHeader>
        <template #header>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                    <h3 class="gstinvoice"> Sales Entry List</h3>
                </a-col>
                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                </a-col>
                <a-col :xs="24" :sm="24" :md="7" :lg="7" style="text-align:right;">
                    <a class="btn addEffset" href="/dashboard" title="Home">
                        <img class="" src="../../../../../../images/homeIcon.png">
                        <span class="effset"></span>
                    </a>
                   

                    <template v-if="
                        permsArray.includes(`${orderPageObject.permission}_create`) ||
                        permsArray.includes('admin')
                    ">
                            <a-button type="primary" class="creating" @click="createNewSalesEntry()">
                                <PlusOutlined />
                                Create F2
                            </a-button>
                    </template>
                </a-col>
            </a-row>

        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="16">
            <loading v-model:active="isLoading"
                 :can-cancel="true"
                 :on-cancel="onCancel"
                 :is-full-page="fullPage"/>
            <a-col :xs="24" :sm="24" :md="12" :lg="24" :xl="24">
                <a-row :gutter="[16, 16]">
                    <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <a-input ref="searchInput" id="searchInput" autocomplete="off" style="width: 100%" v-model:value="filters.searchString"
                            placeholder="Search here" @keydown="test" @keydown.enter="handleEnterKey" />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="5" :xl="5">
                        <div class="dropdown">
                            <button type="primary" id="dropdowning" class="ant-input dropdown-toggleing px-4"
                                @click="toggleDropdown" @keydown.enter="handleEnterKeyDrop"  ref="dropdownButton"  >
                                {{ buttonLabel }}
                            </button>
                            <ul v-if="isDropdownOpen" class="dropdown-menusing">
                                <div class="dropdown-menusing cust-date-filter shadow p-4 show" :style="dropdownStyle">
                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <button type="button" @click="DeleteRow('Today', event)"  @keydown="nextFocus($event)" id="today"  class="dropdown-item border mb-2 dropdown-item-list" value="Today">Today </button>

                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <button type="button" @click="DeleteRow('Yesterday', event)"  @keydown="nextFocus($event)" id="Yesterday"  class="dropdown-item border mb-2 dropdown-item-list" value="Yesterday"> Yesterday</button>

                                        </a-col>
                                    </a-row>

                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                             <button type="button"@click="DeleteRow('This Week', event)" @keydown="nextFocus($event)" id="this-week"  class="dropdown-item border mb-2 dropdown-item-list" value="This Week">This Week </button>

                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                             <button type="button" @click="DeleteRow('Last 7 days', event)" id="last_7_days"  @keydown="nextFocus($event)"  class="dropdown-item border mb-2 dropdown-item-list" value="Last 7 days"> Last 7 days</button>

                                        </a-col>
                                    </a-row>

                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <button type="button" @click="DeleteRow('This Month', event)" id="this_month"   @keydown="nextFocus($event)" class="dropdown-item border mb-2 dropdown-item-list" value="This Month"> This Month</button>

                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <button type="button" @click="DeleteRow('Previous Month', event)" id="previous_month"  @keydown="nextFocus($event)"  class="dropdown-item border mb-2 dropdown-item-list" value="Previous Month"> Previous Month</button>

                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <button type="button" @click="DeleteRow('Last 365 days', event)" id="365days"  @keydown="nextFocus($event)"  class="dropdown-item border mb-2 dropdown-item-list" value="Last 365 days"> Last 365 days</button>

                                        </a-col>
                                    </a-row>

                                    <hr class="hrtages">
                                    </hr>
                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12" >
                                            <a-form-item :label="$t('stock.customs')">
                                        </a-form-item>
                                        </a-col>

                                    </a-row>
                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12" >
                                            <DateTimePicker id="fromDate"
                                            @dateChanged="changedfromDate"/>
                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <DateTimePicker id="toDate" :dateTime="formData.toDate"
                                            @dateChanged="changedtoDate" />
                                        </a-col>
                                    </a-row>
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24" id="customdate">
                                    <a-button type="primary" id="creating" class="creating" @click="enterClicked()" @keyup.enter="enterClicked()">
                                        Apply
                                    </a-button>
                                </a-col>

                                </div>
                            </ul>
                        </div>

                    </a-col>

                    <a-col :xs="24" :sm="24" :md="8" :lg="11" :xl="11" style="text-align:right">

                        <ExprotTable
                        exportType="payment_reports"
                        tableName="payment-reports-table"
                        :title="`${$t('Sales Entry List')}`"
                    />

                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
            <OrderTable 
            ref="orderTableRef"  
            salesType="salestype=sales" 
            :orderType="orderType" 
            :filters="filters" 
            tableSize="middle" 
            :bordered="true"
            :perPageItems="100"
            :scrollY="500"
                :selectable="true"
                 @onRowSelection="(selectedIds) => (selectedRowIds = selectedIds)" 
                 v-on:child-select="updateselect" 
                 v-on:row-select="rowselect" 
                 v-on:mouse-select="mouseselect" />
        </admin-page-table-content>
        </div>
</template>
<script>
import { onMounted, watch, ref } from "vue";
import { PlusOutlined, DeleteOutlined,ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";
import OrderTable from "../../../components/order/OrderTable.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import ExpenseCategoryModal from './ExpenseCategoryModal.vue';
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue";
import ExprotTable from "../../../../main/components/report-exports/ExportTable.vue";
import table from "../../../../common/composable/datatable";
import { Modal } from 'ant-design-vue';
import { createVNode } from 'vue';
export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        ExpenseCategoryModal,
        OrderTable,
        DateRangePicker,
        AdminPageHeader,
        DateTimePicker,
        ExprotTable,
        ExclamationCircleOutlined
    },
    data() {
        return {
            isModalVisible: false,
            formData: {},
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'GST Invoice Filters',
            successMessage: 'Operation successful!',
            isDropdownOpen: false,
             accountGroupColor: '',

            buttonLabel: 'Today',
            selectedRange: 'Today',
            selectedRowInvoice: null,
            isDropdownOpen: false,
            dropdownStyle: {},
            dropdownStyle: {
                position: 'absolute',
                transform: 'translate3d(17px, -2px, 0px)',
                top: '0px',
                left: '0px'
            },
            isLoading: false,
            fullPage: true
        };
    },

    mounted() {
     document
      .getElementById("listindex").addEventListener('keydown', this.handleKeydown);
    this.autoFocusInput();

  },
  beforeDestroy() {

    document.removeEventListener('keydown', this.handleKeydown);
  },
    methods:
    {
        showconfirm()
        {
            let that = this;
            Modal.confirm({
            title: 'Confirmation',
            icon: createVNode(ExclamationCircleOutlined),
            content: 'Transaction data will be loss, Are you sure you want to close?',
            //autoFocusButton:'cancel',
                onOk() {
                    that.isDropdownOpen = false;
                localStorage.setItem('selectedInvoice',null)
                            that.$router.push({
                    name: `admin.stock.sales.index`,

                    });
            },
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            onCancel() {
                document.getElementById(that.recentEventId.target.id).focus()
            },
        });
        },
        selectDateRange(range) {
           // this.selectedRange = range.trim();
            this.buttonLabel = this.selectedRange.trim();
            this.isDropdownOpen = false;
            this.orderTableRef.setUrlData(this.selectedRange);
            setTimeout(function(){document.getElementById('searchInput').focus();},600)
           // this.fetchUsers(this.selectedRange);
        },
                createNewSalesEntry()
                {
                     console.log(this.orderPageObject.type);
                        localStorage.setItem("selectedInvoice", null);
                        // Pass 'from' param as query for reliability
                        this.$router.push({
                            name: `admin.stock.${this.orderPageObject.type}.create`,
                            query: { from: 'purchases' }
                        });
                },


        handleKeydown(event) {
            console.log("index", event.keyCode,this.selectedRange)
        if(event.shiftKey && event.keyCode == 9)
            {
            console.log("event target=>", event.target.id,this.selectedRange);
                if(this.selectedRange=="This Week")
                {
                    this.selectedRange ="Yesterday";
                    document.getElementById("Yesterday").focus({focusVisible: true});
                    event.preventDefault();
                }
               else if(this.selectedRange=="This Month")
                {
                     this.selectedRange ="Last 7 days";
                    document.getElementById("last_7_days").focus({ focusVisible: true });
                    event.preventDefault();
                }

                else if(this.selectedRange=="Yesterday")
                {
                 this.selectedRange ="Today";
                    document.getElementById("today").focus({ focusVisible: true });
                    event.preventDefault();
                }
                else if(this.selectedRange=="Last 7 days")
                {

                    this.selectedRange ="This Week";
                    document.getElementById("this-week").focus({focusVisible: true});
                    event.preventDefault();
                }
                else if(event.target.id=="Previous month")
                {
                    this.selectedRange ="This Month";
                    document.getElementById("this_month").focus({focusVisible: true});
                    event.preventDefault();
                }
                else if(event.target.id=="365 Days")
                {
                    this.selectedRange = "Previous month";

                    document.getElementById("previous_month").focus({ focusVisible: true });
                    event.preventDefault();
                }
                else if(event.target.id=="fromDate")
                {
                    this.selectedRange ="365 Days";
                    document.getElementById("365days").focus({focusVisible: true});
                    event.preventDefault();


                }
                else if(event.target.id=="toDate")
                {
                    this.selectedRange ="fromDate";
                   document.getElementById("dropdowning").text="Custom "
                    document.getElementById("fromDate").focus({focusVisible: true});
                    event.preventDefault();
                }

            }
            else if(event.keyCode==40)
            {
                if(this.selectedRange=="Today")
                {
                    this.selectedRange ="Yesterday";
                    document.getElementById("Yesterday").focus({ focusVisible: true });
                    event.preventDefault();

                }
               else if(this.selectedRange=="This Week")
                {
                    this.selectedRange ="Last 7 days";
                    document.getElementById("last_7_days").focus({focusVisible: true});
                    event.preventDefault();
                }

                else if(this.selectedRange=="This Month")
                {
                    this.selectedRange = "Previous month";
                    document.getElementById("previous_month").focus({focusVisible: true});
                    event.preventDefault();
                }
                else if(this.selectedRange=="Yesterday")
                {
                    this.selectedRange ="This Week";
                    document.getElementById("this-week").focus({ focusVisible: true });
                    event.preventDefault();


                }
                else if(this.selectedRange=="Last 7 days")
                {
                    this.selectedRange ="This Month";
                    document.getElementById("this_month").focus({ focusVisible: true });
                    event.preventDefault();

                }
                else if(this.selectedRange=="Previous month")
                {
                    this.selectedRange ="365 Days";
                    document.getElementById("365days").focus({focusVisible: true});
                    event.preventDefault();
                }
                else if(this.selectedRange=="365 Days")
                {
                    this.selectedRange = "Today";
                    document.getElementById("dropdowning").text="Custom "
                    document.getElementById("fromDate").focus({focusVisible: true});
                }

                else if(this.selectedRange=="fromDate")
                {
                    this.selectedRange ="Today";
                    document.getElementById("toDate").focus({focusVisible: true});
                }
            }
            else if(event.keyCode==38)
            {
                if(this.selectedRange=="This Week")
                {
                    this.selectedRange ="Yesterday";
                    document.getElementById("Yesterday").focus({focusVisible: true});
                }
               else if(this.selectedRange=="This Month")
                {
                     this.selectedRange ="Last 7 days";
                    document.getElementById("last_7_days").focus({focusVisible: true});
                }

                else if(this.selectedRange=="Yesterday")
                {
                 this.selectedRange ="Today";
                    document.getElementById("today").focus({focusVisible: true});
                }
                else if(this.selectedRange=="Last 7 days")
                {

                    this.selectedRange ="This Week";
                    document.getElementById("this-week").focus({focusVisible: true});
                }
                else if(this.selectedRange=="Previous month")
                {
                    this.selectedRange ="This Month";
                    document.getElementById("this_month").focus({focusVisible: true});
                }
                else if(this.selectedRange=="365 Days")
                {
                    this.selectedRange = "Previous month";

                    document.getElementById("previous_month").focus({focusVisible: true});
                }
                else if(this.selectedRange=="fromDate")
                {
                    this.selectedRange ="365 Days";
                    document.getElementById("365days").focus({focusVisible: true});
                }
                else if(this.selectedRange=="toDate")
                {
                    this.selectedRange ="fromDate";
                   document.getElementById("dropdowning").text="Custom "
                    document.getElementById("fromDate").focus({focusVisible: true});
                }
            }
      if (event.key === 'F2') {
          // Perform route navigation
          document.removeEventListener('keydown', this.handleKeydown);
        localStorage.setItem("selectedInvoice", null);
        this.$router.push({
          name: `admin.stock.${this.orderPageObject.type}.create`,
          params: { username: 'eduardo' }
        });
      }
      else if(event.keyCode ==27)
      {
         this.isDropdownOpen = false;
          //this.showconfirm();
      }
        },

        handleEnterKey() {
            console.log("dropfownwww");
           this.isDropdownOpen= false;
           this.$nextTick(() => {
            this.$refs.dropdownButton.focus();
        });

        },
        handleEnterKeyDrop() {
            

        var that= this;
        setTimeout(() => {
            document.getElementById("today").focus({focusVisible: true});

        }, 500);

        },
        DeleteRow(that,event)
        {
            console.log('<>',that)
            this.selectedRange = that;
            this.buttonLabel = this.selectedRange.trim();
            this.isDropdownOpen = false;
           // this.fetchUsers(this.selectedRange);
            this.orderTableRef.setUrlData(this.selectedRange);
        },

        nextFocus(event)
        {
            console.log("event=>", event.keyCode, this.selectedRange);
            if(event.shiftKey && event.keyCode == 9)
            {
                 console.log("event target=>", event.target.value);
            }

            else if(event.keyCode==13)
            {
                this.selectDateRange();
            }

        },
        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.searchInput.focus();  // Automatically focus the input
            });
        },
        showModal() {
            this.isModalVisible = true;
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
            if(!this.isDropdownOpen ){ this.$refs.searchInput.focus(); }
            if(this.selectedRange=="Today")
                {
                    this.selectedRange ="This Week";
                    setTimeout(function(){
                        document.getElementById("this-week").focus({focusVisible: true});
                    },500)

                }
               else if(this.selectedRange=="This Week")
                {
                    this.selectedRange ="This Month";
                    setTimeout(function(){
                        document.getElementById("this_month").focus({focusVisible: true});
                    },500)

                }

                else if(this.selectedRange=="This Month")
                {
                    this.selectedRange ="Yesterday";
                    setTimeout(function(){
                        document.getElementById("Yesterday").focus({focusVisible: true});
                    },500)

                }
                else if(this.selectedRange=="Yesterday")
                {
                    this.selectedRange ="Last 7 days";

                    setTimeout(function(){
                        document.getElementById("last_7_days").focus({focusVisible: true});
                    },500)
                }
                else if(this.selectedRange=="Last 7 days")
                {
                    this.selectedRange ="Previous month";

                    setTimeout(function(){
                        document.getElementById("previous_month").focus({focusVisible: true});
                    },500)
                }
                else
                {
                    setTimeout(function(){
                        document.getElementById("today").focus({focusVisible: true});
                    },500)

                }
        },
        handleClose() {
            this.isModalVisible = false;
        },
        handleSuccess(xid) {
            // Handle success logic
            this.isModalVisible = false;
            console.log('Success:', xid);
        },
                changeColorOnFocus(inputField) {
            if (inputField === 'partyName') {
                this.partyNameColor = '#ffd451';
            } else if (inputField === 'accountGroup') {
                this.accountGroupColor = '#ffd451';
            } else if (inputField === 'stations') {
                this.stationsColor = '#ffd451';
            }
        },
        resetColorOnBlur(inputField) {
            if (inputField === 'partyName') {
                this.partyNameColor = '';
            } else if (inputField === 'accountGroup') {
                this.accountGroupColor = '';
            } else if (inputField === 'stations') {
                this.stationsColor = '';
            }
        },
        //     handleKeyPress(event) {
        //   this.$refs.orderTable.test(event);
        //   console.log(122);

        // },

        updateselect(value) {
            console.log("testingg", value)
            this.isDropdownOpen = false;
        document.removeEventListener('keydown', this.handleKeydown);
        localStorage.setItem("selectedInvoice", value);
        this.$router.push({
        name: `admin.stock.${this.orderPageObject.type}.create`
      });

        },
        rowselect(value){ console.log("rowtestingg",value)
            this.selectedRowInvoice = value;

        },
        mouseselect(value)
        {
             console.log("testingg", value)
            localStorage.setItem("selectedInvoice", value);
                    this.$router.push({
                    name: `admin.stock.${this.orderPageObject.type}.create`
                });
        },
        test(event)
        {
            console.log(99999999999, this.selectedRowInvoice ,event.keyCode);
            console.log(this.filters.searchString, 'key code', event.keyCode, this.orderTableRef);
            event.SearchString = document.getElementById('searchInput').value;
            event.selectedRange = this.selectedRange.trim()
            if (this.selectedRowInvoice != null || this.filters.searchString != "") {
                this.orderTableRef.test(event);
            }
            else if (event.keyCode == 40 || event.keyCode == 38) { this.orderTableRef.test(event); }

        },
        changedfromDate(value)
        {
          this.formData.fromDate=value;
          document.getElementById('toDate').focus();
            console.log(value,'<>',this.formData.fromDate);
        },
        changedtoDate(value)
        {
            this.formData.toDate =value;
            //this.formData.toDate=new Date(value);
            console.log(value,'<>',this.formData.toDate);
            document.getElementById('creating').focus();
        },
        enterClicked()
        {
            var formatter = new Intl.DateTimeFormat("en-CA");

            if(this.formData.fromDate==undefined){document.getElementById('toDate').focus();}
            else if(this.formData.toDate==undefined){document.getElementById('toDate').focus();}
            else if(this.formData.fromDate!=undefined && this.formData.toDate!=undefined){
                this.selectedRange = formatter.format(this.formData.fromDate)+" to "+formatter.format(this.formData.toDate);
                this.selectDateRange('custom');
            }
        }




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
        const orderTableRef = ref(null);

        const filters = ref({
            payment_status: "all",
            user_id: undefined,
            dates: [],
            searchColumn: "invoice_number",
            searchString: "",
        });

        onMounted(() => {
            fetchUsers();
        });

        const fetchUsers = (data) => {
            const usersPromise = axiosAdmin.get(
                `${orderPageObject.value.userType}?limit=10000&range=`+data
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
                        searchColumn: "invoice_number",
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
            orderTableRef,
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
    padding: .375rem .3rem;
    border: none;
    border: solid rgba(0, 0, 0, .2);
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
    padding: .25rem 0.5rem;
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
#customdate{
    float:right;
    margin-top:12px;
}
:where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
    margin-bottom:5px !important;
}
.hrtages{
    margin-top:22px;
}
.dropdown-item:focus {
  background-color: yellow;

}
input[readonly] {
  pointer-events: none;
}
.cust-date-filter{transform: translate3d(0, 0px, 0px) !important;}
.dropdown-item-list {
  caret-color: yellow;
  cursor: pointer;
}
.ant-picker-focused:focus-within{background-color: yellow !important;}
table td {
    padding: 2px 5px !important;
}
.ant-btn-icon-only {
    font-size: 12px  !important;
    height: 25px !important;
    padding: 4px 11px !important;
    border-radius: 4px;
}

</style>
