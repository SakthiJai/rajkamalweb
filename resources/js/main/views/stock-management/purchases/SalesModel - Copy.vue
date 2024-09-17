<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @ok="onSubmit"
        @keydown.esc="hideModal" class="popups">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Select Party</span>
                <div @keydown="onKeydown">
                    <a-button :style="buttonStyle" @click="showModal" class="closing">
                        <PlusOutlined />
                        Create/F2
                    </a-button>
                    <div v-if="isLoading" class="loader-container">
                        <div class="loader">
                            <svg focusable="false" class="anticon-spin" data-icon="sync" width="30px" height="30px"
                                fill="currentColor" aria-hidden="true" viewBox="64 64 896 896">
                                <path
                                    d="M168 504.2c1-43.7 10-86.1 26.9-126 17.3-41 42.1-77.7 73.7-109.4S337 212.3 378 195c42.4-17.9 87.4-27 133.9-27s91.5 9.1 133.8 27A341.5 341.5 0 01755 268.8c9.9 9.9 19.2 20.4 27.8 31.4l-60.2 47a8 8 0 003 14.1l175.7 43c5 1.2 9.9-2.6 9.9-7.7l.8-180.9c0-6.7-7.7-10.5-12.9-6.3l-56.4 44.1C765.8 155.1 646.2 92 511.8 92 282.7 92 96.3 275.6 92 503.8a8 8 0 008 8.2h60c4.4 0 7.9-3.5 8-7.8zm756 7.8h-60c-4.4 0-7.9 3.5-8 7.8-1 43.7-10 86.1-26.9 126-17.3 41-42.1 77.8-73.7 109.4A342.45 342.45 0 01512.1 856a342.24 342.24 0 01-243.2-100.8c-9.9-9.9-19.2-20.4-27.8-31.4l60.2-47a8 8 0 00-3-14.1l-175.7-43c-5-1.2-9.9 2.6-9.9 7.7l-.7 181c0 6.7 7.7 10.5 12.9 6.3l56.4-44.1C258.2 868.9 377.8 932 512.2 932c229.2 0 415.5-183.7 419.8-411.8a8 8 0 00-8-8.2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="onClose" type="button" aria-label="Close" class="ant-modal-close" id="closeicon">
                <span class="ant-modal-close-x"><span role="img" aria-label="close"
                        class="anticon anticon-close ant-modal-close-icon">
                        <svg focusable="false" data-icon="close" width="1em" height="1em" fill="currentColor"
                            aria-hidden="true" fill-rule="evenodd" viewBox="64 64 896 896">
                            <path
                                d="M799.86 166.31c.02 0 .04.02.08.06l57.69 57.7c.04.03.05.05.06.08a.12.12 0 010 .06c0 .03-.02.05-.06.09L569.93 512l287.7 287.7c.04.04.05.06.06.09a.12.12 0 010 .07c0 .02-.02.04-.06.08l-57.7 57.69c-.03.04-.05.05-.07.06a.12.12 0 01-.07 0c-.03 0-.05-.02-.09-.06L512 569.93l-287.7 287.7c-.04.04-.06.05-.09.06a.12.12 0 01-.07 0c-.02 0-.04-.02-.08-.06l-57.69-57.7c-.04-.03-.05-.05-.06-.07a.12.12 0 010-.07c0-.03.02-.05.06-.09L454.07 512l-287.7-287.7c-.04-.04-.05-.06-.06-.09a.12.12 0 010-.07c0-.02.02-.04.06-.08l57.7-57.69c.03-.04.05-.05.07-.06a.12.12 0 01.07 0c.03 0 .05.02.09.06L512 454.07l287.7-287.7c.04-.04.06-.05.09-.06a.12.12 0 01.07 0z">
                            </path>
                        </svg>
                    </span>
                </span>
            </button>
        </template>
        <LedgerModel v-if="isModalVisible" :visible="isModalVisible" :formData="formData" :url="url"
            :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
            @addEditSuccess="handleSuccess" @closed="handleClose" />
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-input-group compact>

                        <a-input-search ref="searchInput" style="width: 75%" placeholder="search here.."
                            v-model:value="table.searchString" show-search @change="onTableSearch"
                            @search="onTableSearch" :loading="table.filterLoading" />
                    </a-input-group>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item name="name">
                        <a-select v-model:value="formData.sales_names">
                            <a-select-option key="1" value="1">
                                Name
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Mobile
                            </a-select-option>
                            <a-select-option key="3" value="3">
                                Address
                            </a-select-option>
                            <a-select-option key="4" value="4">
                                GSTIN
                            </a-select-option>
                            <a-select-option key="4" value="4">
                                Search in All
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>

        <!-- enterprises model-->

        <EnterprisesModel v-if="isEnterModal" :visible="isEnterModal" :formData="formData" :url="url"
            :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
            @addEditSuccess="handleSuccess" @closed="handleClose" />
        <!--- end enterprises model-->



        <!-- data table-->
        <LedgerAddEdit :addEditType="addEditType" :visible="addEditVisible" :url="addEditUrl"
            @addEditSuccess="addEditSuccess" @closed="onCloseAddEdit" :formData="formData" :data="viewData"
            :pageTitle="pageTitle" :successMessage="successMessage" />
        <admin-page-table-content>
            <a-row>
                <a-col :span="24">
                    <div class="table-responsive">
                        <a-table :columns="columns" :row-key="(record) => record.xid" :data-source="table.data"
                            :pagination="table.pagination" :loading="table.loading" @change="handleTableChange"
                            :customRow="customRow" :row-selection="rowSelection" bordered size="middle">
                            <template #bodyCell="{ column, record, rowIndex }">
                                <template v-if="column.dataIndex === 'id'">
                                    <a-badge :class="{ 'row-highlight': rowIndex === selectedIndex }">
                                        {{ record.product.name }}-{{ rowSelection }}
                                    </a-badge>
                                </template>
                                <a-typography-text 
                                v-if="record.adjustment_type === 'add'" 
                                type="success" 
                              
                            >
                                +{{ record.party_name }}
                            </a-typography-text>
                                <template v-if="column.dataIndex === 'station'">
                                    <a-typography-text v-if="record.adjustment_type === 'add'" type="success" strong>
                                        +{{ record.station }}
                                    </a-typography-text>
                                </template>
                                <template v-if="column.dataIndex === 'opening_balance'">
                                    <a-typography-text v-if="record.adjustment_type === 'add'" type="success" strong>
                                        +{{ record.opening_balance }}
                                    </a-typography-text>
                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button
                                        v-if="permsArray.includes('stock_adjustments_edit') || permsArray.includes('admin')"
                                        type="primary" @click="editItem(record)" style="margin-left: 4px">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                    <a-button
                                        v-if="permsArray.includes('stock_adjustments_delete') || permsArray.includes('admin')"
                                        type="primary" @click="showDeleteConfirm(record.xid)" style="margin-left: 4px">
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                    </a-button>
                                </template>
                            </template>
                        </a-table>

                    </div>
                </a-col>
            </a-row>
        </admin-page-table-content>
        <!--- end-->
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
                <fieldset class="fieldheight">
                    <legend class="basicdetalis">
                        Address
                    </legend>

                    <br>
                    <br>

                    <a-row :gutter="16">
                        <a-col :xs="12" :sm="12" :md="12" :lg="12">
                            {{ $t("stock.contact_detail") }}

                        </a-col>
                        <a-col :xs="12" :sm="12" :md="3" :lg="3">

                            <span>:</span>
                        </a-col>
                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                            <span>{{ formDataLedger.party_name }}</span>
                            
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="12" :sm="12" :md="12" :lg="12">
                            {{ $t("stock.ac_group") }}

                        </a-col>
                        <a-col :xs="12" :sm="12" :md="3" :lg="3">

                            <span>:</span>
                        </a-col>
                        <a-col :xs="12" :sm="12" :md="4" :lg="4">

 
                   <span>{{ formData.party_name }}</span>
  
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
                <fieldset>
                    <legend class="basicdetalis">
                        Licence Info
                    </legend>
                    <a-row :gutter="16">

                        <a-col :xs="12" :sm="12" :md="12" :lg="12">
                            {{ $t("stock.gst_number") }}

                        </a-col>
                        <a-col :xs="12" :sm="12" :md="3" :lg="3">

                            <span>:</span>
                        </a-col>
                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                            <span>{{ (formData.tax_amount) }}</span>
                        </a-col>
                    </a-row>
                </fieldset>
                <fieldset>
                    <legend class="basicdetalis">
                        OtherInfo
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="12" :sm="12" :md="12" :lg="12">
                            {{ $t("stock.states") }}

                        </a-col>
                        <a-col :xs="12" :sm="12" :md="3" :lg="3">

                            <span>:</span>
                        </a-col>
                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                            <span>{{ (formData.tax_amount) }}</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
                <fieldset class="fieldheights">
                    <legend class="basicdetalis">
                        Current Status
                    </legend>
                    <div v-if="selectedIndex !== null" class="details">


                        <a-row :gutter="16">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.opening") }}

                            </a-col>
                            <a-col :xs="12" :sm="12" :md="3" :lg="3">

                                <span>:</span>
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                <span>{{ (formData.tax_amount) }}</span>
                                <!-- <span>{{ formatCurrency(formData.tax_amount) }}</span> -->
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.debit") }}

                            </a-col>
                            <a-col :xs="12" :sm="12" :md="3" :lg="3">

                                <span>:</span>
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                <span>{{ (formData.tax_amount) }}</span>
                                <!-- <span>{{ formatCurrency(formData.tax_amount) }}</span> -->
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.credit") }}

                            </a-col>
                            <a-col :xs="12" :sm="12" :md="3" :lg="3">
                                <span>:</span>
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="4" :lg="4">

                                <span>{{ (formData.tax_amount) }}</span>
                            </a-col>

                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.balance") }}

                            </a-col>
                            <a-col :xs="12" :sm="12" :md="3" :lg="3">
                                <span>:</span>
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="8" :lg="8">

                                <span>&#8377; {{ items[selectedIndex].value }}</span>
                            </a-col>

                        </a-row>
                    </div>
                </fieldset>
            </a-col>
        </a-row>
        <template #footer class="floats">
            <div class="floats">
                <a-button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"
                    @click="EnterprisesModel"><span class="box"><span
                            class="shortcut ng-star-inserted"><code>F4</code></span><span
                            class="ng-star-inserted">Ledger</span></span><span class="effect"></span></a-button>
                <a-button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"><span
                        class="box"><span class="shortcut ng-star-inserted"><code>F8</code></span><span
                            class="ng-star-inserted">Outstanding</span></span><span class="effect"></span></a-button>
                <a-button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"><span
                        class="box">
                        <span class="shortcut ng-star-inserted"><code>F7</code></span><span
                            class="ng-star-inserted">All</span></span><span class="effect"></span></a-button>
            </div>
        </template>
    </a-modal>
</template>

<script>
import { onMounted, watch, ref, computed } from "vue";
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import LedgerModel from './LedgerModel.vue';
import EnterprisesModel from './EnterprisesModel.vue';
import fields from "./Ledger/fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import LedgerAddEdit from "./Ledger/LedgerAddEdit.vue";
export default defineComponent({
    props: [
        "formData",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        StaffMemberAddButton,
        DeleteOutlined,
        EditOutlined,
        LedgerModel,
        EnterprisesModel,
        LedgerAddEdit,
    },


    setup(props, { emit }) {
        const { url, addEditUrl, hashableColumns, initData, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const { permsArray, selectedWarehouse } = common();
        const selectedIndex = ref(-1);

        onMounted(() => {
            crudVariables.table.filterableColumns = filterableColumns;
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "expense_category";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];

            reFetchDatatable();
        });
        const onSelectChange = (changableRowKeys) => {
            console.log('selectedRowKeys changed: ', changableRowKeys);
            selectedRowKeys.value = changableRowKeys;
        };
        const selectedRowKeys = []; // Check here to configure the default column
        const rowSelection = computed(() => {
            return {
                selectedRowKeys: unref(0),
                onChange: onSelectChange,
                hideDefaultSelections: true,
                selections: [
                    Table.SELECTION_ALL,
                    Table.SELECTION_INVERT,
                    Table.SELECTION_NONE,
                    {
                        key: 'odd',
                        text: 'Select Odd Row',
                        onSelect: changableRowKeys => {
                            let newSelectedRowKeys = [];
                            newSelectedRowKeys = changableRowKeys.filter((_key, index) => {
                                if (index % 2 !== 0) {
                                    return false;
                                }
                                return true;
                            });
                            selectedRowKeys.value = newSelectedRowKeys;
                        },
                    },
                    {
                        key: 'even',
                        text: 'Select Even Row',
                        onSelect: changableRowKeys => {
                            let newSelectedRowKeys = [];
                            newSelectedRowKeys = changableRowKeys.filter((_key, index) => {
                                if (index % 2 !== 0) {
                                    return true;
                                }
                                return false;
                            });
                            selectedRowKeys.value = newSelectedRowKeys;
                        },
                    },
                ],
            };
        });
        const reFetchDatatable = () => {
            crudVariables.tableUrl.value = {
                url,
                filterableColumns,
            };

            crudVariables.fetch({
                page: 1,
            });
        };

        const onClose = () => {
            emit("closed");
        };

        const handleKeyDown = (event) => {
            if (event.key === 'ArrowDown') {
                selectedIndex.value = (selectedIndex.value + 1) % (crudVariables.table?.data?.length || 0);
            } else if (event.key === 'ArrowUp') {
                selectedIndex.value = (selectedIndex.value - 1 + (crudVariables.table?.data?.length || 0)) % (crudVariables.table?.data?.length || 0);
            }
        };
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
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },


    data() {
        return {
            headers: ['Ledger Name', 'Station', '₹ Balance', 'Actions'],
            items: [
                { name: 'Waste Furniture', location: 'Bangalore', value: '2,000.00 Cr' },
                { name: 'Recycled Paper', location: 'Delhi', value: '1,500.00 Cr' },
                { name: 'Scrap Metal', location: 'Mumbai', value: '3,000.00 Cr' }
            ],
            selectedIndex: 0,
            isModalVisible: false,
           
            isEnterModal: false,
            isPopupVisible: false,
            isLoading: false,
            dialog: false,
            formData: {
                sales_names: '1',
            },
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Select Party',
            successMessage: 'Operation successful!',
            buttonStyle: {
                backgroundColor: '', // Initially no background color
                borderColor: '',
                color: '', // Initially no text color
            },
            buttonStyle: {},
            formDataLedger: {
                party_name: "",
                account_group: "",
                station: "",
                mail_to: "",
                address: "",
                stock_country: "",
                stock_state: "",
                stock_city: "",
                stock_pincode: "",
                parent_ledger: "",
                balancing_method: "1",
                opening_balance: "",
                credit_days: "",
                phone_number: "",
                mobile_number: "",
                whatsapp_number: "",
                ledger_type: "1",
                gender: "Male",
                account_type: "Saving Account",
                customer_title: "Mr."
            },
        };
        
    },

    mounted() {
        // document.addEventListener('keydown', this.handleKeyDown);
        document.addEventListener('keyup', this.handleKeyDown);
        this.autoFocusInput();
    },
    beforeDestroy() {
        document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
    //     onCloseing(record) {
    //   console.log(record); 
    //   this.visible = false; // Hide the modal
    // },
    // showModal() {
    //   this.visible = true; // Show modal method
    // },
        onCloseing(record){
            this.visible = false;
        },
        customRow(record) {
            return {
                onClick: (event) => {console.log('record', record, 'event', event); this.onCloseing()}
            }
        },
        showModal() {
            this.isModalVisible = true;
        },  

        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.searchInput.focus();  // Automatically focus the input
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
                backgroundColor: '',
                borderColor: '',
                color: '',
            };
        },
        handleSuccess(xid) {
            // Handle success logic
            this.isModalVisible = false;
            console.log('Success:', xid);
        },
        handleKeyDown(event) {
            console.log(event.key)
            if (event.key === 'F2') {
                // Show loader
                this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                    this.buttonStyle = {
                        backgroundColor: 'green',
                        borderColor: 'green',
                        color: 'white',
                    };
                    this.isModalVisible = true;
                }, 500);
            } else if (event.key === 'F4') {
                this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                    this.isEnterModal = true;
                }, 500);
            } else if (event.key === 'ArrowDown') {
                if (this.selectedIndex === null) {
                    this.selectedIndex = 0;
                } else {
                    this.selectedIndex = (this.selectedIndex + 1) % this.items.length;
                }
                console.log(this.selectedIndex)
            } else if (event.key === 'Escape' || event.key === 'Esc') {
                // Hide the popup
                this.isModalVisible = false;
                this.isEnterModal = false;
                this.isLoading = false;
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
    padding: .01rem .5rem;
    display: flex;
    align-items: center;
}

button.btn {
    padding: .375rem .3rem;
    border: none;
    border: solid rgba(0, 0, 0, .2);
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

.highlight {
    background-color: #ffd451;
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
</style>