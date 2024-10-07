<template>
    <AddProduct v-if="isAddProductModalVisible" :visible="isAddProductModalVisible" :formData="formData" :url="url"
        :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
        @addEditSuccess="handleSuccess" @closed="handleClose" />

    <!-- popup modal-->
    <PopupModal v-if="isModalPopup" :visible="isModalPopup" :formDataLedger="formDataLedger" :url="url"
        :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
        @addEditSuccess="handleSuccess" @closed="handlePopup" />
    <!-- end popup modal-->

    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" class="popups">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Select Item</span>
                <div @keydown="onKeydown">
                    <a-button :style="buttonStyle" @click="showAddProductModal" class="closing">
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
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item name="party_name_search" @keydown="test" ref="dummykeyboard">
                        <a-input-search ref="searchInput" autocomplete="off" style="width: 100%"
                            placeholder="search here.." v-model:value="table.searchString" show-search
                            @change="onTableSearch" @search="onTableSearch" :loading="table.filterLoading" @focus="checkSelectedproduct"  />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item name="sales_names">
                        <a-select v-model:value="formData.sales_names">
                            <a-select-option key="1" value="1">
                                Description
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Any Where
                            </a-select-option>
                            <a-select-option key="3" value="3">
                                Top Sales
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <admin-page-table-content>
            <a-row>
                <a-col :span="24">
                    <div class="table-responsive">
                        <a-table :columns="columns" :row-key="(record) => record.id" :data-source="table.data"
                            :pagination="table.pagination" :loading="table.loading" 
                            :rowSelection="{
                                selectedRowKeys: selectedRowKeysValue,
                                onChange: onSelectChange,
                                hideDefaultSelections: true,
                                selections: true,
                                type: 'radio'
                            }" bordered size="middle">
                            <template #bodyCell="{ column, record }" v-for="(item, index) in items" :key="index"
                                :class="{ highlight: index === selectedIndex }" @click="selectProduct(record)">
                                <template v-if="column.dataIndex === 'id'">
                                    <a-badge>
                                        {{ record.product.name }}
                                    </a-badge>
                                </template>
                                <template v-if="column.dataIndex === 'name'">
                                    <a-typography-text type="success" strong @click.stop="selectProduct(record)">
                                        {{ record.name }}
                                    </a-typography-text>
                                </template>

                                <template v-if="column.dataIndex === 'station'">
                                    <a-typography-text v-if="record.adjustment_type == 'add'" type="success" strong>
                                        +{{ record.station }}
                                    </a-typography-text>

                                </template>
                                <template v-if="column.dataIndex === 'opening_balance'">
                                    <a-typography-text v-if="record.adjustment_type == 'add'" type="success" strong>
                                        +{{ record.opening_balance }}
                                    </a-typography-text>

                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button v-if="
                                        permsArray.includes('stock_adjustments_edit') ||
                                        permsArray.includes('admin')
                                    " type="primary" @click="editItem(record)" style="margin-left: 4px">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                    <a-button v-if="
                                        permsArray.includes('stock_adjustments_delete') ||
                                        permsArray.includes('admin')
                                    " type="primary" @click="showDeleteConfirm(record.xid)" style="margin-left: 4px">
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
            <a-col :xs="24" :sm="24" :md="6" :lg="6">
                <fieldset style="height: 167px;">
                    <legend class="font-style-in-prouct-model">
                        Purchase Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            M.R.P
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="mrp">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Pur. Rate
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="purchase_rate">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Cost
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="cost">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Cost+Tax
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="cost_tax">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Pur. Disc
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="purchase_discount">0.00</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="6" :lg="6">
                <fieldset style="height: 167px;">
                    <legend class="font-style-in-prouct-model">
                        Sale Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Rate
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="rate">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Margin
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="margin">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Deal Free
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="deal_free">0 + 0</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            W/o Deal
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="w_o_free"></span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="6" :lg="6">
                <fieldset style="height: 167px;">
                    <legend class="font-style-in-prouct-model">
                        Tax Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            HSN/SAC
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="hsn">0</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            IGST %
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="igst">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            CGST %
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="cgst">0.00</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            SGST %
                        </a-col>
                        <a-col :xs="4" :sm="4" :md="4" :lg="4">
                            <span>: ₹</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span id="sgst">0.00</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="6" :lg="6">
                <fieldset style="height: 167px;">
                    <legend class="font-style-in-prouct-model">
                        Other Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="8" :sm="8" :md="8" :lg="8">
                            Company
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="14" :sm="14" :md="14" :lg="14">
                            <span id="company"></span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="8" :sm="8" :md="8" :lg="8">
                            Mfr.
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="14" :sm="14" :md="14" :lg="14">
                            <span id="mfr"></span>
                        </a-col>
                    </a-row>
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
import { onMounted, watch } from "vue";
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../../views/users/StaffAddButton.vue";
import LedgerModel from './../LedgerModel.vue';
import EnterprisesModel from './../EnterprisesModel.vue';
import fields from "./../Product/fields";
import AddProduct from "./../Product/AddProduct.vue";
import crud from "../../../../../common/composable/crud";
import common from "../../../../../common/composable/common";
import PopupModal from "../PopupModal.vue";
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
        AddProduct,
        PopupModal
    },


    setup(props, { emit }) {
        const { url, addEditUrl, hashableColumns, initData, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const { permsArray, selectedWarehouse } = common();

        onMounted(() => {
            crudVariables.table.filterableColumns = filterableColumns;
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "expense_category";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];

            reFetchDatatable();
        });

        const reFetchDatatable = () => {
            document.getElementById('form_item_party_name_search').focus() 
            focus=null;
            crudVariables.tableUrl.value = {
                url,
                filterableColumns,
            };

            crudVariables.fetch({
                page: 1,
            });
           
        };

        const onClose = () => { console.log("Product Closed call");
            emit("productclose-method");
            //reFetchDatatable();
        };
        const onSelectChange = (changableRowKeys) => {
            // selectedRowKeysValue=[];
            //consolelog('selectedRowKeys changed: ', changableRowKeys);
            //selectedRowKeys.value = changableRowKeys;
            selectedRowKeysValue = changableRowKeys;
            setTimeout(function(){
                const currentRadioInput = document.getElementsByClassName('ant-table-row-selected');
                const myElem = document.querySelectorAll(".ant-table-row-selected");
                let name ='',packing='',quantity='',price='';

                myElem.forEach(function (elem, index) {
                    name = elem.closest('tr').getElementsByTagName('td')[1].innerHTML.replace(/<[^>]*>?/gm, '');
                    packing = elem.closest('tr').getElementsByTagName("td")[2].innerHTML.replace(/<[^>]*>?/gm, '')
                    quantity= elem.closest('tr').getElementsByTagName("td")[3].innerHTML.replace(/<[^>]*>?/gm, '')
                    price= elem.closest('tr').getElementsByTagName("td")[5].innerHTML.replace(/<[^>]*>?/gm, '')
                    //consolelog(name);
                    });
                    //consolelog('<>',this.selectedProductId.cgst)
                    emit('child-method', {id:changableRowKeys.toString(),
                                                        name:name,
                                                        packing:packing,
                                                        quantity:quantity,
                                                        single_unit_price:price,
                                                        cgst: this.selectedProductId.cgst || 0,
                                                        sgst: this.selectedProductId.sgst || 0
                                                    });
                    document.getElementById('form_item_party_name_search').focus()                                   

            },400)

           
        };
        let selectedRowKeysValue = []; // Check here to configure the default column
        //selectedRowKeysValue[0]=0;

        watch(selectedWarehouse, (newVal, oldVal) => {
            if(this.$emit == true){
                reFetchDatatable();
                
            }
        });

        return {
            columns,
            permsArray,
            ...crudVariables,
            onClose,
            filterableColumns,
            reFetchDatatable,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
            onSelectChange,
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
            selectedProductId: { id: 0, name: "" },
            selectedIndex: 0,
            focus: null,
            isAddProductModalVisible: false,
            isModalPopup: false,
            isEnterModal: false,
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
        };
    },

    mounted() {
        this.isLoading = false;
        // document.addEventListener('keydown', this.handleKeyDown);
        document.addEventListener('keyup', this.handleKeyDown);
        
        this.autoFocusInput();
    },
    beforeDestroy() {
        document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
        customRow(record) {
            return {
                onClick: (event) => {
                    this.rowSelection = event
                    ////consolelog('record', record, 'event', record.party_name);
                }
            }
        },
        test: function (event) {
            //consolelog("focus",event.keyCode);
            switch (event.keyCode) {
                case 38:
                     if (this.focus === null) {
                        this.focus = 0;
                    } else if (this.focus > 0) {
                        this.focus--;
                    }
                    this.updateSelection()
                    break;
                case 40:

                    if (this.focus === null) {
                        this.focus = 0;
                    } else if (this.focus < this.table.data.length - 1) {
                        this.focus++;
                    }
                    this.updateSelection()
                    break;
                    case 46:
                    this.autoFocusInput();
                    this.showPopupModal();
                    break;
                    case 13:
                        if(this.table.data.length>0)
                         {
                            //consolelog("Enter Key")
                            this.$emit('productclose-method');
                            //consolelog(this.table.data);
                        }
                    break;
                    case 27:
                        if(this.table.data.length>0)
                         {
                            //consolelog("Esc Key")
                            this.$emit('productclose-method');
                            //consolelog(this.table.data);
                        }
                    break;
                    case 113:
                       this.showAddProductModal();
                    break;



            }
        },
        updateSelection() 
        {
            this.removeClass();
            const currentRadioInput = document.getElementsByClassName('ant-radio-input')[this.focus];
            currentRadioInput.checked=true;
            const temp = currentRadioInput.closest('tr');
            temp.classList.add("ant-table-row-selected");
            const selectedRowKey = temp.getAttribute('data-row-key');
            console.log('Selected Row Key:', selectedRowKey); 
            this.selectedProductId.id = temp.getAttribute('data-row-key');
            this.selectedProductId.name = temp.getElementsByTagName("td")[1].innerHTML.replace(/<[^>]*>?/gm, '')
            this.selectedProductId.packing = temp.getElementsByTagName("td")[2].innerHTML.replace(/<[^>]*>?/gm, '')
            this.selectedProductId.quantity = temp.getElementsByTagName("td")[3].innerHTML.replace(/<[^>]*>?/gm, '')
            this.selectedProductId.single_unit_price = temp.getElementsByTagName("td")[5].innerHTML.replace(/<[^>]*>?/gm, '')
            if (Array.isArray(this.table.data) && this.table.data.length > 0)
            {
                        this.table.data.forEach(row =>
                          {
                            if(row.cgst==undefined){row.cgst=0;}
                            if(row.sgst==undefined){row.sgst=0;}
                            if (row.id === selectedRowKey || row.id == selectedRowKey) {
                                this.selectedProductId.cgst = Number((row.cgst>=0?row.cgst:0));
                                this.selectedProductId.sgst = Number((row.sgst>=0?row.sgst:0));
                                let cgstAmount = 0, sgstAmount = 0;

                                if (row.cgst && row.sale_rate) {
                                    let cgstPercentage = Number(row.cgst) / 100;
                                    cgstAmount = Number(cgstPercentage) * Number(row.sale_rate);
                                }

                                if (row.sgst && row.sale_rate) {
                                    let sgstPercentage = Number(row.sgst) / 100;
                                    sgstAmount = Number(sgstPercentage) * Number(row.sale_rate);
                                }

                                let totalTax = Number(sgstAmount) + Number(cgstAmount) + Number(row.sale_rate);

                                document.getElementById("mrp").innerHTML = this.formatOfAmount(row.mrp);
                                document.getElementById("purchase_rate").innerHTML = this.formatOfAmount(row.purchase_rate);
                                document.getElementById("cost").innerHTML = this.formatOfAmount(row.cost);
                                document.getElementById("cost_tax").innerHTML = this.formatOfAmount(totalTax);
                                document.getElementById("purchase_discount").innerHTML = '0.00';
                                document.getElementById("rate").innerHTML = this.formatOfAmount(row.sale_rate);
                                document.getElementById("margin").innerHTML = this.formatOfAmount(row.margin);
                                document.getElementById("deal_free").innerHTML = 
                                (row.free_scheme_1 != null ? row.free_scheme_1 : 0) + 
                                ' + ' + 
                                (row.free_scheme_2 != null ? row.free_scheme_2 : 0);

                                document.getElementById("w_o_free").innerHTML = row.w_o_free;
                                document.getElementById("hsn").innerHTML = row.hsn;
                                document.getElementById("igst").innerHTML = this.formatOfAmount(row.lgst);
                                document.getElementById("cgst").innerHTML = this.formatOfAmount((row.cgst>=0?row.cgst:0));
                                document.getElementById("sgst").innerHTML = this.formatOfAmount((row.sgst>=0?row.sgst:0));
                                document.getElementById("company").innerHTML = row.company_name;
                                document.getElementById("mfr").innerHTML = row.mfr_name;
                            }
                        });
            } 
                    else {
                console.error('Table data is not an array or is empty.');
            }
            this.$emit('child-method', this.selectedProductId);
           // this.$emit('child-method', this.selectedPartyId);
            //console.log(this.table.data);
        },
        formatCurrency(value) {
            return value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        formatOfAmount(value) {
            var res = new Intl.NumberFormat('en-IN', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format((value>=0)?value:0);
            return res;
        },
        showAddProductModal() {
            this.isAddProductModalVisible = true;
        },
        showPopupModal() {
            this.isModalPopup = true;
        },
        handlePopup() {
            this.isModalPopup = false;
        },
        autoFocusInput() {
            this.$nextTick(() => { //consolelog("Search");
                this.$refs.searchInput.focus();  // Automatically focus the input
            });
        },
        hideModal() {
            document.documentElement.querySelector(".ant-modal-close-x").click()
            //this.isAddProductModalVisible = false;
        },

        EnterprisesModel() {
            this.isEnterModal = true;
            //this.isLoading = true;
        },
        handleClose() {
            this.isAddProductModalVisible = false;
            this.reFetchDatatable();
            //consolelog("test1223");


            this.buttonStyle = {
                backgroundColor: '',
                borderColor: '',
                color: '',
            };
        },
        handleSuccess(xid) {

            this.isAddProductModalVisible = false;
            //consolelog('Success:', xid);
        },
        handleProductModal() {
            this.reFetchDatatable();
            this.isProuctsModalVisible = false;
        },

        handleKeyDown(event) {
            const activeElement = document.activeElement;
            const index = parseInt(activeElement.id.split('_').pop()); // Get the current input index

            if (event.key === 'F2') {
                this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                    this.isAddProductModalVisible = true;
                }, 500);
            } else if (event.key === 'F4') {
                this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                    this.isEnterModal = true;
                }, 500);
            } else if (event.key === 'Escape' || event.key === 'Esc') {
                this.isAddProductModalVisible = false;
                this.isEnterModal = false;
                this.isLoading = false;
            } else if (event.key === 'Enter') {

                /*this.$emit('productclose-method');
                //consolelog("pppjjj");
                // Close modal by clicking the modal close button
                const modalCloseButton = document.documentElement.querySelector(".ant-modal-close-x");
                 
                if (modalCloseButton) {
                    modalCloseButton.click();
                   
                }*/



            }
        },
        removeClass() {
            const rows = Array.from(document.querySelectorAll('tr.ant-table-row-selected'));
            rows.forEach(row => {
                row.classList.remove('ant-table-row-selected');
            });
            },
            checkSelectedproduct()
             {
            if(this.selectedRowKeysValue==undefined )
                {
                    this.focus=0;
                    var that = this;                
                    setTimeout(function(){
                        const currentRadioInput = document.getElementsByClassName('ant-radio-input')[0];
                   // currentRadioInput.click();
                    const currentRow = currentRadioInput.closest('tr');
                    currentRow.classList.add("ant-table-row-selected");
                    const selectedRowKey = currentRow.getAttribute('data-row-key');
                    that.selectedProductId.id = currentRow.getAttribute('data-row-key');
                    that.selectedProductId.name = currentRow.getElementsByTagName("td")[1].innerHTML.replace(/<[^>]*>?/gm, '')
                    that.selectedProductId.packing = currentRow.getElementsByTagName("td")[2].innerHTML.replace(/<[^>]*>?/gm, '')
                    that.selectedProductId.quantity = currentRow.getElementsByTagName("td")[3].innerHTML.replace(/<[^>]*>?/gm, '')
                    that.selectedProductId.single_unit_price = currentRow.getElementsByTagName("td")[5].innerHTML.replace(/<[^>]*>?/gm, '')
                    console.log('Selected Row Key1:', selectedRowKey); 
                    if (Array.isArray(that.table.data) && that.table.data.length > 0)
                        {
                            that.table.data.forEach(row =>
                                    { 
                                        if(row.cgst==undefined){row.cgst=0;}
                                        if(row.sgst==undefined){row.sgst=0;}
                                        if (row.id === selectedRowKey || row.id == selectedRowKey) {
                                            that.selectedProductId.cgst = Number((row.cgst>=0?row.cgst:0));
                                            that.selectedProductId.sgst = Number((row.sgst>=0?row.sgst:0));
                                            let cgstAmount = 0, sgstAmount = 0;

                                            if (row.cgst && row.sale_rate) {
                                                let cgstPercentage = Number(row.cgst) / 100;
                                                cgstAmount = Number(cgstPercentage) * Number(row.sale_rate);
                                            }

                                            if (row.sgst && row.sale_rate) {
                                                let sgstPercentage = Number(row.sgst) / 100;
                                                sgstAmount = Number(sgstPercentage) * Number(row.sale_rate);
                                            }

                                            let totalTax = Number(sgstAmount) + Number(cgstAmount) + Number(row.sale_rate);

                                            document.getElementById("mrp").innerHTML = that.formatOfAmount(row.mrp);
                                            document.getElementById("purchase_rate").innerHTML = that.formatOfAmount(row.purchase_rate);
                                            document.getElementById("cost").innerHTML = that.formatOfAmount(row.cost);
                                            document.getElementById("cost_tax").innerHTML = that.formatOfAmount(totalTax);
                                            document.getElementById("purchase_discount").innerHTML = '0.00';
                                            document.getElementById("rate").innerHTML = that.formatOfAmount(row.sale_rate);
                                            document.getElementById("margin").innerHTML = that.formatOfAmount(row.margin);
                                            document.getElementById("deal_free").innerHTML = 
                                            (row.free_scheme_1 != null ? row.free_scheme_1 : 0) + 
                                            ' + ' + 
                                            (row.free_scheme_2 != null ? row.free_scheme_2 : 0);

                                            document.getElementById("w_o_free").innerHTML = row.w_o_free;
                                            document.getElementById("hsn").innerHTML = row.hsn;
                                            document.getElementById("igst").innerHTML = that.formatOfAmount(row.lgst);
                                            document.getElementById("cgst").innerHTML = that.formatOfAmount((row.cgst>=0?row.cgst:0));
                                            document.getElementById("sgst").innerHTML = that.formatOfAmount((row.sgst>=0?row.sgst:0));
                                            document.getElementById("company").innerHTML = row.company_name;
                                            document.getElementById("mfr").innerHTML = row.mfr_name;
                                        }
                                    });
                        } 
                                else {
                            console.error('Table data is not an array or is empty.');
                        }
                    
                        document.querySelectorAll('.ant-radio-input').forEach((elem) =>
                        { 
                            elem.addEventListener("change", function(event) { 
                            var item = event.target.value;
                            console.log('<>',item);

                            const currentRadioInput = document.getElementsByClassName('ant-table-row-selected');
                            const myElem = document.querySelectorAll(".ant-table-row-selected");
                            let name ='';
                            myElem.forEach(function (elem, index) {
                                    name = elem.closest('tr').getElementsByTagName('td')[1].innerHTML.replace(/<[^>]*>?/gm, '');
                            });
                                
                            const modalCloseButton = document.documentElement.querySelector(".ant-modal-close-x");
                                        if (modalCloseButton) {
                                        
                                        that.$emit('child-method', that.selectedProductId);
                                        modalCloseButton.click();
                                        
                                           

                                        }

                            });
                        });
                    },2000);
                    
                }
            
            }    

    },
    mounted() {
        this.reFetchDatatable(); // Fetch data on component mount
    }

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

.font-style-in-prouct-model {
    font-size: 15px !important;
    font-weight: bolder;
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