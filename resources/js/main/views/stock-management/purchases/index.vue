<template>
    <AdminPageHeader>
        <template #header>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                    <h3 class="gstinvoice"> GST Invoice List</h3>
                </a-col>
                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                    <svg height="20" viewBox="0 0 576 512">
                        <path class=""
                            d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"
                            fill="#ff0000"></path>
                    </svg>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                </a-col>
                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <a class="btn addEffset" href="/dashboard" title="Home">
                        <img class="" src="../../../../../../images/homeIcon.png">
                        <span class="effset"></span>
                    </a>
                    <a-button type="primary" class="bulkaction">
                        {{ $t("stock_adjustment.bulk_action") }}
                    </a-button>

                    <template v-if="
                        permsArray.includes(`${orderPageObject.permission}_create`) ||
                        permsArray.includes('admin')
                    ">

                        <router-link :to="{
                            name: `admin.stock.${orderPageObject.type}.create`,
                        }">
                            <a-button type="primary" class="creating">
                                <PlusOutlined />
                                Create F2
                            </a-button>
                        </router-link>
                    </template>
                </a-col>
            </a-row>

        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="16">

            <a-col :xs="24" :sm="24" :md="12" :lg="24" :xl="24">
                <a-row :gutter="[16, 16]">
                    <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                        <a-input-search ref="searchInput" style="width: 100%" v-model:value="filters.searchString" show-search
                            placeholder="Search here" />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="3" :xl="3">
                        <div class="dropdown">
                            <button type="primary" id="dropdowning" class="dropdown-toggle px-4"
                                @click="toggleDropdown">
                                {{ buttonLabel }}
                            </button>
                            <ul v-if="isDropdownOpen" class="dropdown-menu">
                                <div class="dropdown-menu cust-date-filter shadow p-4 show" :style="dropdownStyle">
                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <a class="dropdown-item border mb-2" href="javascript:;"
                                                @click="selectDateRange('Today')">
                                                Today <span class="float-right"
                                                    v-if="selectedRange === 'Today'">✔</span>
                                            </a>
                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <a class="dropdown-item border mb-2" href="javascript:;"
                                                @click="selectDateRange('Yesterday')">
                                                Yesterday <span class="float-right"
                                                    v-if="selectedRange === 'Yesterday'">✔</span>
                                            </a>
                                        </a-col>
                                    </a-row>

                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <a class="dropdown-item border mb-2" href="javascript:;"
                                                @click="selectDateRange('This Week')">
                                                This Week <span class="float-right"
                                                    v-if="selectedRange === 'This Week'">✔</span>
                                            </a>
                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <a class="dropdown-item border mb-2" href="javascript:;"
                                                @click="selectDateRange('Last 7 days')">
                                                Last 7 days <span class="float-right"
                                                    v-if="selectedRange === 'Last 7 days'">✔</span>
                                            </a>
                                        </a-col>
                                    </a-row>

                                    <a-row :gutter="16">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <a class="dropdown-item border mb-2" href="javascript:;"
                                                @click="selectDateRange('This Month')">
                                                This Month <span class="float-right"
                                                    v-if="selectedRange === 'This Month'">✔</span>
                                            </a>
                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <a class="dropdown-item border mb-2" href="javascript:;"
                                                @click="selectDateRange('Previous Month')">
                                                Previous Month <span class="float-right"
                                                    v-if="selectedRange === 'Previous Month'">✔</span>
                                            </a>
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
                                            <DateTimePicker :dateTime="formData.anniversary_date" @dateTimeChanged="(changedDateTime) =>
                                                (formData.freeze_upto = changedDateTime)
                                                " />
                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                            <DateTimePicker :dateTime="formData.anniversary_date" @dateTimeChanged="(changedDateTime) =>
                                                (formData.freeze_upto = changedDateTime)
                                                " />
                                        </a-col>
                                    </a-row>
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24" id="customdate">
                                    <a-button type="primary" class="creating">
                                        Apply
                                    </a-button>
                                </a-col>

                                </div>
                            </ul>
                        </div>

                    </a-col>

                    <a-col :xs="24" :sm="24" :md="8" :lg="8" :xl="6">
                        <div>
                            <a-col :xs="24" :sm="24" :md="8" :lg="8" :xl="6">
                                <a-space>
                                    <template v-if="
                                        permsArray.includes('stock_adjustments_create') ||
                                        permsArray.includes('admin')
                                    ">
                                        <a-button type="primary" @click="showModal">
                                            <PlusOutlined />
                                            {{ $t("stock_adjustment.filter") }}
                                        </a-button>
                                    </template>
                                </a-space>
                            </a-col>
                            <ExpenseCategoryModal v-if="isModalVisible" :visible="isModalVisible" :formData="formData"
                                :url="url" :addEditType="addEditType" :pageTitle="pageTitle"
                                :successMessage="successMessage" @addEditSuccess="handleSuccess"
                                @closed="handleClose" />
                        </div>
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <OrderTable ref="orderTableRef" :orderType="orderType" :filters="filters" tableSize="middle" :bordered="true"
            :selectable="true" @onRowSelection="(selectedIds) => (selectedRowIds = selectedIds)" />
    </admin-page-table-content>
</template>
<script>
import { onMounted, watch, ref } from "vue";
import { PlusOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";
import OrderTable from "../../../components/order/OrderTable.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import ExpenseCategoryModal from './ExpenseCategoryModal.vue';
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue"
export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        ExpenseCategoryModal,
        OrderTable,
        DateRangePicker,
        AdminPageHeader,
        DateTimePicker,
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
            isDropdownOpen: false,
            dropdownStyle: {},
            dropdownStyle: {
                position: 'absolute',
                transform: 'translate3d(17px, -2px, 0px)',
                top: '0px',
                left: '0px'
            },
        };
    },

    
    // mounted() {
    //     // document.addEventListener('keydown', this.handleKeyDown);
    //     document.addEventListener('keyup', this.handleKeyDown);
    //     
    // },
    // beforeDestroy() {
    //     document.removeEventListener('keydown', this.handleKeyDown);
    // },

    mounted() {
    // Add keydown event listener for F2 key
    document.addEventListener('keydown', this.handleKeydown);
    this.autoFocusInput();
  },
  beforeDestroy() {
    // Remove keydown event listener
    document.removeEventListener('keydown', this.handleKeydown);
  },
    methods: {
        selectDateRange(range) {
            this.selectedRange = range.trim();  // Update selected range
            this.buttonLabel = range.trim();  // Update button label to show the selected range
            this.isDropdownOpen = false;  // Close dropdown after selection
        },

        handleKeydown(event) {
      if (event.key === 'F2') {
        // Perform route navigation
        this.$router.push({
          name: `admin.stock.${this.orderPageObject.type}.create`,
        });
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

        const fetchUsers = () => {
            const usersPromise = axiosAdmin.get(
                `${orderPageObject.value.userType}?limit=10000`
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

            selectedRowIds,
            orderTableRef,
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



.dropdown-toggle {
    position: relative;
}

.dropdown-menu {
    display: block;
    position: absolute;
    padding: 10px;
    list-style: none;
    border-radius: 4px;
    width: 300px;
}

.dropdown-menu li {
    padding: 5px 0;
}

.dropdown-menu li a {
    text-decoration: none;
    color: black;
}

.dropdown-menu li a:hover {
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
    width: 110px;
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
</style>
