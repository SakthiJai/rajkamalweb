<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`New Product List`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.stock_adjustment`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
             <a-button
          type="primary"
          class="creating"
          @click="addItem()"
          style="float: right; margin-top: -3%; background-color: #1f6d70;"
        >
          <PlusOutlined />
          Create F2
        </a-button>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="6">
                        <ProductSearchInput
                            ref="productSearchInputRef"
                            @valueChanged="
                                (productId) => {
                                    filters.product_id = productId;
                                    reFetchDatatable();
                                }
                            "
                        />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="5" :xl="5">
                        <div class="dropdown">
                            <button type="primary" id="dropdowning" class="ant-input dropdown-toggleing px-4"
                                @click="toggleDropdown" @keydown.enter="toggleDropdown" ref="dropdownButton">
                                {{ buttonLabel }}
                            </button>
                            <div v-if="isDropdownOpen" class="dropdown-menusing cust-date-filter shadow p-4">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('Today', event)" @keydown="nextFocus($event)" id="today" class="dropdown-item border mb-2 dropdown-item-list" value="Today">Today</button>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('Yesterday', event)" @keydown="nextFocus($event)" id="Yesterday" class="dropdown-item border mb-2 dropdown-item-list" value="Yesterday">Yesterday</button>
                                    </a-col>
                                </a-row>

                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('This Week', event)" @keydown="nextFocus($event)" id="this-week" class="dropdown-item border mb-2 dropdown-item-list" value="This Week">This Week</button>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('Last 7 days', event)" id="last_7_days" @keydown="nextFocus($event)" class="dropdown-item border mb-2 dropdown-item-list" value="Last 7 days">Last 7 days</button>
                                    </a-col>
                                </a-row>

                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('This Month', event)" id="this_month" @keydown="nextFocus($event)" class="dropdown-item border mb-2 dropdown-item-list" value="This Month">This Month</button>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('Previous Month', event)" id="previous_month" @keydown="nextFocus($event)" class="dropdown-item border mb-2 dropdown-item-list" value="Previous Month">Previous Month</button>
                                    </a-col>
                                </a-row>

                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <button type="button" @click="DeleteRow('Last 365 days', event)" id="365days" @keydown="nextFocus($event)" class="dropdown-item border mb-2 dropdown-item-list" value="Last 365 days">Last 365 days</button>
                                    </a-col>
                                </a-row>

                                <hr class="hrtages"></hr>
                                
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <a-form-item :label="$t('stock.customs')"></a-form-item>
                                    </a-col>
                                </a-row>

                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <DateTimePicker id="fromDate" @dateChanged="changedfromDate"/>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                        <DateTimePicker id="toDate" :dateTime="formData.toDate" @dateChanged="changedtoDate" />
                                    </a-col>
                                </a-row>

                                <a-col :xs="24" :sm="24" :md="24" :lg="24" id="customdate">
                                    <a-button type="primary" id="creating" class="creating" @click="enterClicked()" @keyup.enter="enterClicked()">
                                        Apply
                                    </a-button>
                                </a-col>
                            </div>
                        </div>
                    </a-col>
        
                </a-row>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space justify="end">
                    <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('stock_adjustments_delete') ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        justify="end"
                        @click="showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button>
                </a-space>
            </a-col>
        </a-row>
        
        <!-- Date Range Filter -->
      
    </admin-page-filters>

    <admin-page-table-content>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
        />

        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled: false,
                                name: record.xid,
                            }),
                        }"
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'product_id'">
                                <a-badge>
                                    <a-avatar
                                        shape="square"
                                        :src="record.product.image_url"
                                    />
                                    {{ record.product.name }}
                                </a-badge>
                            </template>
                            <template v-if="column.dataIndex === 'quantity'">
                                <a-typography-text
                                    v-if="record.adjustment_type == 'add'"
                                    type="success"
                                    strong
                                >
                                    +{{ record.quantity }}
                                </a-typography-text>
                                <a-typography-text v-else type="danger" strong>
                                    -{{ record.quantity }}
                                </a-typography-text>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('stock_adjustments_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        permsArray.includes('stock_adjustments_delete') ||
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
    </admin-page-table-content>
</template>
<script>
import { onMounted, watch, ref } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import ProductSearchInput from "../../../../common/components/product/ProductSearchInput.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        ProductSearchInput,
        DateTimePicker,
        AdminPageHeader,
    },
    data() {
        return {
            buttonLabel: 'Today',
            selectedRange: 'Today',
            isDropdownOpen: false,
            formData: {},
            dropdownStyle: {
                position: 'absolute',
                transform: 'translate3d(17px, -2px, 0px)',
                top: '0px',
                left: '0px'
            },
        };
    },
    mounted() {
        document.addEventListener('keydown', this.handleKeydown);
        // Auto-focus ProductSearchInput when page loads
        this.$nextTick(() => {
            if (this.$refs.productSearchInputRef?.$el?.querySelector?.('input')) {
                this.$refs.productSearchInputRef.$el.querySelector('input').focus();
            }
        });
    },
    beforeDestroy() {
        document.removeEventListener('keydown', this.handleKeydown);
    },
    methods: {
        selectDateRange(range) {
            this.selectedRange = range.trim();
            this.buttonLabel = this.selectedRange.trim();
            this.isDropdownOpen = false;
            this.reFetchDatatable();
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
            if (this.isDropdownOpen) {
                this.$nextTick(() => {
                    document.getElementById('today').focus({ focusVisible: true });
                });
            }
        },
        handleKeydown(event) {
            if (event.keyCode === 27) {
                // ESC key - close dropdown
                this.isDropdownOpen = false;
            } else if (event.keyCode === 113) {
                // F2 key - Open add/create dialog
                event.preventDefault();
                this.addItem();
            } else if (event.keyCode === 40) {
                // Arrow Down
                this.handleArrowDown();
            } else if (event.keyCode === 38) {
                // Arrow Up
                this.handleArrowUp();
            } else if (event.keyCode === 13) {
                // Enter
                if (this.isDropdownOpen && event.target.id) {
                    this.selectDateRange(event.target.textContent);
                }
            }
        },
        handleArrowDown() {
            const ranges = ['Today', 'This Week', 'This Month', 'Yesterday', 'Last 7 days', 'Previous Month', 'Last 365 days'];
            const idMap = {
                'Today': 'today',
                'Yesterday': 'Yesterday',
                'This Week': 'this-week',
                'Last 7 days': 'last_7_days',
                'This Month': 'this_month',
                'Previous Month': 'previous_month',
                'Last 365 days': '365days'
            };
            const currentIndex = ranges.indexOf(this.selectedRange);
            if (currentIndex < ranges.length - 1) {
                this.selectedRange = ranges[currentIndex + 1];
               
                setTimeout(() => {
                    const element = document.getElementById(idMap[this.selectedRange]);
                    if (element) element.focus({ focusVisible: true });
                }, 50);
            }
        },
        handleArrowUp() {
            const ranges = ['Today', 'This Week', 'This Month', 'Yesterday', 'Last 7 days', 'Previous Month', 'Last 365 days'];
            const idMap = {
                'Today': 'today',
                'Yesterday': 'Yesterday',
                'This Week': 'this-week',
                'Last 7 days': 'last_7_days',
                'This Month': 'this_month',
                'Previous Month': 'previous_month',
                'Last 365 days': '365days'
            };
            const currentIndex = ranges.indexOf(this.selectedRange);
            if (currentIndex > 0) {
                this.selectedRange = ranges[currentIndex - 1];
               
                setTimeout(() => {
                    const element = document.getElementById(idMap[this.selectedRange]);
                    if (element) element.focus({ focusVisible: true });
                }, 50);
            }
        },
        DeleteRow(range, event) {
            event.preventDefault();
            event.stopPropagation();
            this.selectDateRange(range);
        },
        nextFocus(event) {
            console.log("event=>", event.keyCode, "selectedRange=>", this.selectedRange);
            
            // Map of date ranges for navigation
            const dateRanges = ['Today', 'This Week', 'This Month', 'Yesterday', 'Last 7 days', 'Previous Month', 'Last 365 days'];
            const idMap = {
                'Today': 'today',
                'Yesterday': 'Yesterday',
                'This Week': 'this-week',
                'Last 7 days': 'last_7_days',
                'This Month': 'this_month',
                'Previous Month': 'previous_month',
                'Last 365 days': '365days'
            };

            if (event.keyCode == 40) {
                // Arrow Down
                const currentIndex = dateRanges.indexOf(this.selectedRange);
                if (currentIndex !== -1 && currentIndex < dateRanges.length - 1) {
                    const nextRange = dateRanges[currentIndex + 1];
                    this.selectedRange = nextRange;
                    const nextId = idMap[nextRange];
                    if (nextId) {
                        document.getElementById(nextId)?.focus({ focusVisible: true });
                    }
                }
            } else if (event.keyCode == 38) {
                // Arrow Up
                const currentIndex = dateRanges.indexOf(this.selectedRange);
                if (currentIndex > 0) {
                    const prevRange = dateRanges[currentIndex - 1];
                    this.selectedRange = prevRange;
                    const prevId = idMap[prevRange];
                    if (prevId) {
                        document.getElementById(prevId)?.focus({ focusVisible: true });
                    }
                }
            } else if (event.keyCode == 13) {
                // Enter - select this range
                this.selectDateRange(this.selectedRange);
            }
        },
        changedfromDate(value) {
            this.formData.fromDate = value;
            document.getElementById('toDate').focus();
            console.log(value, '<>', this.formData.fromDate);
        },
        changedtoDate(value) {
            this.formData.toDate = value;
            console.log(value, '<>', this.formData.toDate);
            document.getElementById('creating').focus();
        },
        enterClicked() {
            var formatter = new Intl.DateTimeFormat("en-CA");

            if (this.formData.fromDate == undefined) {
                document.getElementById('fromDate').focus();
            } else if (this.formData.toDate == undefined) {
                document.getElementById('toDate').focus();
            } else if (this.formData.fromDate != undefined && this.formData.toDate != undefined) {
                this.selectedRange = formatter.format(this.formData.fromDate) + " to " + formatter.format(this.formData.toDate);
                this.selectDateRange(this.selectedRange);
            }
        },
    },
    setup() {
        const { url, addEditUrl, hashableColumns, initData, columns, filters } = fields();
        const crudVariables = crud();
        const { permsArray, selectedWarehouse } = common();
        const dropdownButton = ref(null);

        onMounted(() => {
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "New Product List";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];

            reFetchDatatable();
        });

        const reFetchDatatable = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
            };

            crudVariables.fetch({
                page: 1,
            });
        };

        watch(selectedWarehouse, (newVal, oldVal) => {
            reFetchDatatable();
        });

        return {
            columns,
            permsArray,
            ...crudVariables,
            filters,
            reFetchDatatable,
            dropdownButton,
        };
    },
};
</script>

<style scoped>
.dropdown-toggleing {
    position: relative;
}

.dropdown-menusing {
    display: block !important;
    position: absolute !important;
    padding: 10px !important;
    list-style: none !important;
    border-radius: 4px !important;
    width: 300px !important;
    z-index: 999;
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
    cursor: pointer;
}

.dropdown-item:hover,
.dropdown-item:focus {
    background-color: #ffd451;
}

.dropdown-item-list {
    caret-color: yellow;
    cursor: pointer;
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

.hrtages {
    margin-top: 22px;
}

#customdate {
    float: right;
    margin-top: 12px;
}
</style>
