<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" @keydown.esc="onClose" tabindex="0" :centered="true" @ok="onSubmit" class="popups">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>{{ pageTitle }}</span>
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
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                        <a-col :xs="12" :sm="12" :md="3" :lg="3">
                           <span class="addressdetalis">{{ $t("stock.address_detail") }}</span> 
                        </a-col>
                      
                        <a-col :xs="12" :sm="12" :md="21" :lg="21">
                            <span> :   A78,A BLOCK  ,  SWAMYVIVEKANANDA LAYOUT, 3RD MAIN ROAD PRAKRUTHI NILAYA,, SHIVAMOGGA, Shivamogga, Karnataka, India </span>
                        </a-col>
                    </a-row>
                    
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16" style="margin-top:12px;">
                        <a-col :xs="24" :sm="24" :md="1" :lg="1">
                           
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            
                        </a-col>
                      
                        <a-col :xs="24" :sm="24" :md="2" :lg="2">
                           
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="1" :lg="1">
                            <span class="mr-3"><svg class="mat-datepicker-toggle-default-icon" fill="currentColor" focusable="false" height="18px" viewBox="0 0 24 24" width="18px"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg></span>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <span class="date-value rb"><span>01-04-2024</span><span class="px-3">-To- </span><span>31-03-2025</span></span>
                        </a-col>
                  
                    </a-row>
                </a-col>
            </a-row>
        </a-form>

        <div id="app" class="table-container" style=" margin-top:13px;">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th v-for="(header, index) in headers" :key="index" class="tableheading">{{ header }}
                            <span @click="toggleDropdown(index)" class="dropdown-icon">
                                ⏷
                            </span>
                            <div v-if="dropdownIndex === index" class="dropdown-menu">
                                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                    <a-form-item name="party_name_search" ref="dummykeyboard"
                                        :help="rules.party_name ? rules.party_name.message : null"
                                        :validateStatus="rules.party_name ? 'error' : null" class="required">
                                        <a-input-search ref="searchInput" style="width: 100%" placeholder="Search here.." @focus="focusSearchInput" />
                                    </a-form-item>
                                </a-col>
                            
                                <a-row :gutter="16">
                                    <a-col :xs="12" :sm="12" :md="3" :lg="3">
                                        <input type="checkbox" id="select-all" v-model="selectAll" @change="toggleSelectAll" />
                                    </a-col>
                                    <a-col :xs="12" :sm="12" :md="20" :lg="20">
                                        <label for="select-all">Select All</label>
                                    </a-col>
                                </a-row>
                            
                                <!-- Individual Checkboxes -->
                                <a-row :gutter="16" v-for="(item, index) in items" :key="index" style="margin-left:-7px;">
                                    <a-col :xs="12" :sm="12" :md="3" :lg="3">
                                        <input type="checkbox" class="checkboxing" :id="item.id" :value="item.value" v-model="checkedNames" />
                                    </a-col>
                                    <a-col :xs="12" :sm="12" :md="20" :lg="20">
                                        <label :for="item.id">24/08/2024</label>
                                    </a-col>
                                </a-row>
                            
                                <!-- OK and Cancel Buttons -->
                                <a-row :gutter="16">
                                    <a-col :xs="12" :sm="12" :md="6" :lg="6">
                                        <a-button type="primary" @click="confirmSelection">OK</a-button>
                                    </a-col>
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                        <a-button @click="closeDropdown">Cancel</a-button>
                                    </a-col>
                                </a-row>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in items" :key="index" :class="{ highlight: index === selectedIndex }">
                        <td>{{ item.name }}</td>
                        <td>{{ item.location }}</td>
                        <td>{{ item.billno }}</td>
                        <td>{{ item.value }}</td>
                        <td>{{ item.debit }}</td>
                        <td>{{ item.credit }}</td>
                        <td>{{ item.balance }}</td>

                    </tr>
                </tbody>
            </table>

        </div>

        <a-row :gutter="16" class="row" style="margin-top:12px;">
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item :label="$t('stock.narration')" name="address"
                    :help="rules.address ? rules.address.message : null"
                    :validateStatus="rules.address ? 'error' : null" >
                    <a-textarea v-model:value="formData.address" disabled
                        
                        :rows="4" />
                </a-form-item>
            </a-col>
        </a-row>

        <template #footer class="floats">
            
                <a-button  key="back" @click="onClose"type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"><span
                    class="box">
                    <span class="shortcut ng-star-inserted"><code>Esc</code></span><span
                        class="ng-star-inserted">Close</span></span><span class="effect"></span></a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import LedgerModel from './LedgerModel.vue';

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
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
    data() {
        return {
            headers: ['Date', 'Particular', 'Vch/Bill No.', 'Voucher Type', '₹ Debit', '₹ Credit', '₹ Balance'],
            items: [
                { name: '15/08/2024', location: 'cash account', billno: '000012', value: 'Receipt', debit: '17000', credit: '5000', balance: '1000' },
                { name: '16/08/2024', location: 'sales', billno: '000016', value: 'Sale', debit: '17000', credit: '5000', balance: '1000' },

            ],
            dropdownIndex: null,
            selectedIndex: null,
            isModalVisible: false,
            formData: {
                sales_name: '1',
            },
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Ledger : ABC Enterprises',
            successMessage: 'Operation successful!',
            buttonStyle: {
                backgroundColor: '', // Initially no background color
                borderColor: '',
                color: '', // Initially no text color
            },
            buttonStyle: {},

            selectAll: false,  // Control the "Select All" checkbox
            checkedNames: [],  // List of selected checkboxes

        };
    },

    mounted() {
        document.addEventListener('keyup', this.handleEscKey);
    },
    beforeDestroy() {
        document.removeEventListener('keydown', this.handleKeyDown);
    },
    watch: {
        // Watch for changes in checkedNames to update the "Select All" checkbox state
        checkedNames(newVal) {
            this.selectAll = newVal.length === this.items.length;
        }
    },
    methods: {
        toggleSelectAll() {
            // If "Select All" is checked, add all items to checkedNames, else clear it
            if (this.selectAll) {
                this.checkedNames = this.items.map(item => item.value);
            } else {
                this.checkedNames = [];
            }
        },
        toggleDropdown(index) {
            this.dropdownIndex = this.dropdownIndex === index ? null : index;
        },
        sortBy(header) {

            console.log('Sorting by:', header);
        },
        filterBy(header) {

            console.log('Filtering by:', header);
        },
        showModal() {
            this.isModalVisible = true;
        },
        confirmSelection() {
            // Logic to handle selection confirmation
            this.closeDropdown();
        },
        closeDropdown() {
            this.dropdownIndex = null; // Close the dropdown
        },
        handleClose() {
            this.isModalVisible = false;
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
        handleEscKey(event) { console.log(event.key)
      if (event.key === 'Escape') {
        console.log('Esc key was pressed');
        // You can also call other methods here, e.g., this.onClose()
      }
    },

    
        handleKeyDown(event) {
            if (event.key === 'F2') {
                // Change button color
                this.buttonStyle = {
                    backgroundColor: 'green',
                    borderColor: 'green',
                    color: 'white',
                };
                setTimeout(() => {
                    this.isModalVisible = true;
                }, 500);
            } else if (event.key === 'ArrowUp') {
                if (this.selectedIndex === null) {
                    this.selectedIndex = this.items.length - 1;
                } else {
                    this.selectedIndex = (this.selectedIndex - 1 + this.items.length) % this.items.length;
                }
                this.handleClose(); // Call this if necessary
            } else if (event.key === 'ArrowDown') {
                if (this.selectedIndex === null) {
                    this.selectedIndex = 0;
                } else {
                    this.selectedIndex = (this.selectedIndex + 1) % this.items.length;
                }
            }
        },
        

    },
    mounted() {
    // Attach the keydown event listener to the document when the component is mounted
    document.addEventListener('keydown', this.handleEscKey);
  },
  beforeDestroy() {
    // Clean up the event listener when the component is destroyed to avoid memory leaks
    document.removeEventListener('keydown', this.handleEscKey);
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

.addressdetalis {
    font-weight: bold;
    font-size: 16px;
}

.dropdown-icon {
    margin-left: 8px;
    cursor: pointer;
}

.dropdown-menu {
    position: absolute;
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 10;
    margin-top: 5px;
    padding: 10px;
    min-width: 200px;
    max-height: 300px; /* Set the maximum height */
    overflow-y: auto; /* Enable vertical scrolling */
}

.dropdown-menu input {
    margin-bottom: 10px;
    width: 100%;
    padding: 5px;
}

.dropdown-menu select {
    margin-bottom: 10px;
    width: 100%;
    padding: 5px;
}

.dropdown-menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.dropdown-menu li {
    padding: 8px 16px;
    cursor: pointer;
}

.dropdown-menu li:hover {
    background-color: #f0f0f0;
}

.checkboxing {
    margin-right: 8px;
    cursor: pointer;
}
</style>