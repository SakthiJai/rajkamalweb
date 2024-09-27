<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @ok="onSubmit"
        @keydown.esc="hideModal" class="popups">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Customer List</span>
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
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-input-group compact>

                        <a-input-search ref="searchInput" @keydown="test"  style="width: 75%" placeholder="search here.."
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
        <admin-page-table-content>
            <a-row>
                <a-col :span="24">
                    <div class="table-responsive">
                        <a-table :columns="columns" :row-key="(record) => record.id" :data-source="table.data"
                            :pagination="table.pagination" :loading="table.loading" @change="handleTableChange"
                            :customRow="customRow" :rowSelection="{
        selectedRowKeys: selectedRowKeysValue,
        onChange: onSelectChange,
        hideDefaultSelections: true,
        selections: true,
        type: 'radio'
      }" bordered size="middle">
                            <template #bodyCell="{ column, record, rowIndex }">
                                <template v-if="column.dataIndex === 'id'">
                                    <a-badge :class="{ 'row-highlight': rowIndex === selectedIndex }">
                                        {{ record.product.name }}-{{ rowSelection }}
                                    </a-badge>
                                </template>
                                <a-typography-text v-if="record.adjustment_type === 'add'" type="success">
                                    +{{ record.mobile_number }}
                                </a-typography-text>
                                <template v-if="column.dataIndex === 'name'">
                                    <a-typography-text v-if="record.adjustment_type === 'add'" type="success" strong>
                                        +{{ record.cus_name }}
                                    </a-typography-text>
                                </template>
                                <template v-if="column.dataIndex === 'gender'">
                                    <a-typography-text v-if="record.adjustment_type === 'add'" type="success" strong>
                                        +{{ record.gender }}
                                    </a-typography-text>
                                </template>
                                <template v-if="column.dataIndex === 'age'">
                                    <a-typography-text v-if="record.adjustment_type === 'add'" type="success" strong>
                                        +{{ record.age }}
                                    </a-typography-text>
                                </template>
                                <template v-if="column.dataIndex === 'customer_status'">
                                    <a-typography-text v-if="record.adjustment_type === 'add'" type="success" strong>
                                        +{{ record.customer_status }}
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
        <template #footer class="floats">

        </template>
    </a-modal>

    <PopupModal v-if="isModalPopup" :visible="isModalPopup" :formDataLedger="formDataLedger" :url="url"
            :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
            @addEditSuccess="handleSuccess" @closed="handlePopup" />
</template>

<script>
import { onMounted, watch, ref, computed } from "vue";
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import fields from "./SalesNumber/fields";
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
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        StaffMemberAddButton,
        DeleteOutlined,
        EditOutlined,
        PopupModal,
    },


    setup(props, { emit }) {
        const { url, addEditUrl, hashableColumns, initData, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const { permsArray, selectedWarehouse } = common();
        const selectedIndex = ref(-1);
        let selectedRowKeysValue=[]; // Check here to configure the default column
        selectedRowKeysValue[0]=4;

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
         
          selectedRowKeysValue=changableRowKeys;
         
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
            focus: null,
            selectedPartyId:{id:null,name:"",mobile_number:"",},

            isEnterModal: false,
            isModalPopup: false,
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
        };

    },

    mounted() {
        document.addEventListener('keydown', this.handleKeyDown);
        //document.addEventListener('keyup', this.handleKeyDown);
        this.autoFocusInput();
    },
    beforeDestroy() {
        document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
        onClose() {
            this.visible = false;
        },


        showPopupModal() {
            this.isModalPopup = true;
        },

        handlePopup() {
            this.isModalPopup = false;
        },
        customRow(record) {
            return {
                onClick: (event) => { console.log('record', record, 'event', event); this.onCloseing() }
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
        onClose() {
            this.isNumberVisible = false;

        },

        handleClose() {
            this.isModalVisible = false;
            this.isEnterModal = false;
            this.isNumberVisible = false;
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
            else if (event.key === 'Enter') { 
               
               document.documentElement.querySelector(".ant-modal-close-x").click()

           }
        },
        test: function(event) { 
        switch (event.keyCode) {
        case 38:
            
            document.getElementsByClassName('ant-radio-input')[this.focus].click();
           //console.log('<>',document.getElementsByClassName('ant-radio-input')[this.focus].closest('tr').attr(''))
            const temp = document.getElementsByClassName('ant-radio-input')[this.focus].closest('tr');

            console.log('up=>',temp)
            
            console.log('up=>',temp.getElementsByTagName("td")[1].innerHTML.replace(/<[^>]*>?/gm, ''))
            this.selectedPartyId.id = temp.getAttribute('data-row-key');
            this.selectedPartyId.name= temp.getElementsByTagName("td")[2].innerHTML.replace(/<[^>]*>?/gm, '')
            this.selectedPartyId.mobile_number= temp.getElementsByTagName("td")[1].innerHTML.replace(/<[^>]*>?/gm, '')
            
            console.log('up=>',this.selectedPartyId)
            this.$emit('cutomer-method',this.selectedPartyId)
          if (this.focus === null) {
            this.focus = 0;
          } else if (this.focus > 0) {
            this.focus--;
          }
          break;
        case 40:
            
          if (this.focus === null) {
            this.focus = 0;
          } else if (this.focus < this.items.length - 1) {
            this.focus++;
          }
          document.getElementsByClassName('ant-radio-input')[this.focus].click();
          const temp1 = document.getElementsByClassName('ant-radio-input')[this.focus].closest('tr');
            console.log('down5555=>',temp1.getAttribute('data-row-key'))
            this.selectedPartyId.id = temp1.getAttribute('data-row-key');
            this.selectedPartyId.name= temp1.getElementsByTagName("td")[2].innerHTML.replace(/<[^>]*>?/gm, '')
            this.selectedPartyId.mobile_number= temp1.getElementsByTagName("td")[1].innerHTML.replace(/<[^>]*>?/gm, '')
            console.log('up=>',this.selectedPartyId)
            this.$emit('cutomer-method',this.selectedPartyId)
          
          break;

          case 46:
                    this.autoFocusInput();
                    this.showPopupModal();
                    break;
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