<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @close="handleClose"
        class="popups">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Create Customer</span>
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
            </div>
        </template>
        
        <form ref="myForm1" layout="vertical" @submit.prevent="">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="13" :lg="13" class="info">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">
                            Customer Details
                        </legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                <a-form-item :label="$t('customers.mobile_number')" name="mobile_number"
                                    :help="rules.mobile_number ? rules.mobile_number.message : null"
                                    :validateStatus="rules.mobile_number ? 'error' : null">
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                <a-input-group compact>
                                    
                                    <a-input v-on:keyup.enter="moveToNextField($event.target)" v-model:value="formDataLedger.mobile_number" id="phonenumber"
                                         @keypress="onlyForNumber"
                                        />
                                </a-input-group>
                            </a-col>
                        </a-row>
                       
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('common.name')" name="name"   :help="rules.name ? rules.name.message : null"
                                        :validateStatus="rules.name ? 'error' : null">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <a-input v-model:value="formDataLedger.name" v-on:keyup.enter="moveToNextField($event.target)" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!-- countrys-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.gender')" name="custome_gender"
                                            :help="rules.custome_gender ? rules.custome_gender.message : null"
                                            :validateStatus="rules.custome_gender ? 'error' : null" >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-select v-on:keyup.enter="moveToNextField($event.target)" v-model:value="formDataLedger.gender" >
                                            <a-select-option key="Male" value="Male" aria-selected="true">
                                               Male
                                            </a-select-option>
                                            <a-select-option key="Female" value="Female" aria-selected="true">
                                                Female
                                            </a-select-option>
                                            <a-select-option key="Female" value="Female" aria-selected="true">
                                                Transgender 
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                  
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('common.age')" name="customer_age"
                                            :help="rules.customer_age ? rules.customer_age.message : null"
                                            :validateStatus="rules.customer_age ? 'error' : null">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input v-model:value="formDataLedger.age" v-on:keyup.enter="moveToNextField($event.target)" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.parent_ledger')" name="parent_ledger">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <ParentSearchInput @valueChanged="(productId) => (formDataLedger.parent_ledger = productId)
                                            " @valueSuccess="getStockValue" :productData="data" v-on:keyup.enter="moveToNextField($event.target)" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                </a-col>
                <a-col :xs="24" :sm="24" :md="11" :lg="11" class="info">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">
                            Contact Details
                        </legend>
                       
                        <!-- accountsgroup-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('common.email')" name="mail_to">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <a-input v-model:value="formDataLedger.email" type="email" v-on:keyup.enter="moveToNextField($event.target)" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!--- accounts group-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.address')" name="address" >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <a-input v-model:value="formDataLedger.address" v-on:keyup.enter="moveToNextField($event.target)" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!-- countrys-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.whatsapp_number')" name="stock_country"
                                            :help="rules.stock_country ? rules.stock_country.message : null"
                                            :validateStatus="rules.stock_country ? 'error' : null" >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                       
                                        <a-input v-model:value="formDataLedger.whatsapp_number"   @keypress="onlyForNumber"/>
                                    
                                    </a-col>
                                    
                                </a-row>
                            </a-col>
                        </a-row>
                       
                        <!-- end countrys-->
                       
                    </fieldset>
                </a-col>
                

            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="24" class="">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">
                            Other Details
                        </legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                <a-form-item :label="$t('stock.dob')" name="custome_mobile"
                                    :help="rules.custome_mobile ? rules.custome_mobile.message : null"
                                    :validateStatus="rules.custome_mobile ? 'error' : null">
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                <a-input-group compact>
                                    <a-button type="primary" class="indiannumber">+91</a-button>
                                    <a-input v-on:keyup.enter="moveToNextField($event.target)" v-model:value="formDataLedger.dob" id="dob"
                                        style="width:74%"  @keypress=onlyForNumber 
                                        />
                                </a-input-group>
                            </a-col>
                        </a-row>
                       
                       
                        <!-- countrys-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="12" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.customer_type')" name="customer_type"
                                            :help="rules.custome_gender ? rules.custome_gender.message : null"
                                            :validateStatus="rules.custome_gender ? 'error' : null" >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="12" :sm="24" :md="12" :lg="7">
                                        <a-select  v-on:keyup.enter="moveToNextField($event.target)" v-model:value="formDataLedger.gender" >
                                            <a-select-option key="Male" value="Male" aria-selected="true">
                                               Male
                                            </a-select-option>
                                            <a-select-option key="Female" value="Female" aria-selected="true">
                                                Female
                                            </a-select-option>
                                            <a-select-option key="Female" value="Female" aria-selected="true">
                                                Transgender 
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                  
                                    <a-col :xs="24" :sm="24" :md="5" :lg="3">
                                <a-form-item :label="$t('stock.billing_discount')" name="billing_discount"
                                    :help="rules.custome_mobile ? rules.custome_mobile.message : null"
                                    :validateStatus="rules.custome_mobile ? 'error' : null">
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="13" :lg="6">
                                <a-input-group compact>
                                    <a-button type="primary" class="">&#8377;   </a-button>
                                    <a-input v-on:keyup.enter="moveToNextField($event.target)" v-model:value="formDataLedger.billing_discount" id="dob"
                                        style="width:74%"  @keypress=onlyForNumber 
                                        />
                                </a-input-group>
                            </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.Government_Id')" name="Government_Id">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <a-input v-on:keyup.enter="moveToNextField($event.target)" v-model:value="formDataLedger.government_id" id="dob"
                                        style="width:74%"  
                                        />
                                        </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                </a-col>
            </a-row>
            <!--- end tabs--->
           

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <button type="button" id="btn-Ledger" @click="switchTab" title="Ledger" class="btn default-btn">
                        <span class="box">
                            <span class="shortcut"><code>F6</code></span>
                            <span>Switch Tab</span>
                        </span>
                    </button>
                    <button @click="showGSTModal" id="btn-Ledger" title="Ledger"
                        class="btn default-btn ng-star-inserted gst"><span class="box">
                            <span class="ng-star-inserted">GST Verification</span></span></button>
                </a-col>
                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                </a-col>
                <a-col :xs="24" :sm="24" :md="9" :lg="9">
                    <button @click="onSubmitLedger" type="submit" id="btn-Ledger" title="Ledger"
                        class="btn default-btn ng-star-inserted gst">
                        <span class="box">
                            <span class="shortcut ng-star-inserted"><code>F10</code></span>
                            <span class="ng-star-inserted">Save</span>
                        </span>
                        <span class="effect"></span>
                    </button>
                    <button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"
                        @click="resetForm">
                        <span class="box">
                            <span class="shortcut ng-star-inserted">
                                <code>F9</code>
                            </span>
                            <span class="ng-star-inserted">Clear</span>
                        </span>
                        <span class="effect"></span>
                    </button>
                    <button type="button" id="btn-Ledger" title="Ledger" @click="onClose" class="btn default-btn ng-star-inserted"><span
                            class="box"><span class="shortcut ng-star-inserted"><code>Esc</code></span><span
                                class="ng-star-inserted">Close</span></span><span class="effect"></span></button>
                </a-col>
            </a-row>
            <!-- </template> -->
        </form>
        <template #footer class="floats" style="display:none !important;">
            <div class="floats" style="display:none;">
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
import { defineComponent, reactive, onMounted, watch, ref } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import common from "../../../../common/composable/common";
import { debounce } from "lodash-es";
import ExpenseCategoryModal from './ExpenseCategoryModal.vue';
import { SearchOutlined } from "@ant-design/icons-vue";
import DiscountModel from './DiscountModel.vue';
import LimitModel from './LimitModel.vue';
import OtherModel from './OtherModel.vue';
import BillingModel from './BillingModel.vue';
import CopyModel from './CopyModel.vue';
import LocationModel from './LocationModel.vue';
import BirthdayModel from './BirthdayModel.vue';
import GSTModel from './GSTModel.vue';
import CountySearchInput from "../../.../../../../common/components/ledger/CountySearchInput.vue";
import StateSearchInput from "../../.../../../../common/components/ledger/StateSearchInput.vue";
import ParentSearchInput from "../.../../../../../common/components/ledger/ParentSearchInput.vue";
import StationSearchInput from "../.../../../../../common/components/ledger/StationSearchInput.vue";
export default defineComponent({
    props: ["productData"],
    emits: ["valueSuccess", "valueChanged"],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        StaffMemberAddButton,
        DeleteOutlined,
        EditOutlined,
        ExpenseCategoryModal,
        DiscountModel,
        LimitModel,
        OtherModel,
        BillingModel,
        CopyModel,
        LocationModel,
        BirthdayModel,
        GSTModel,
        SearchOutlined,
        CountySearchInput,
        StateSearchInput,
        ParentSearchInput,
        StationSearchInput,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const onClose = () => {
            rules.value = {};
            const modalCloseButton = document.documentElement.querySelector(".ant-modal-close-x");
                if (modalCloseButton) {
                    modalCloseButton.click();
                }
            emit("closed");

        };

        const ledgerSubmit = (formDataLedger) => {
            addEditRequestAdmin({
                url: `store-ledger/customer`,
                data: formDataLedger,
                success: (res) => {
                    emit("closed");

                    //emit("addEditSuccess", res.xid);
                },
            });
        };

        const valueChanged = (value, option) => {
            emit("valueChanged", value);
            emit("valueSuccess");
        };

        return {
            loading,
            rules,
            onClose,
            // formDataLedger,
            ledgerSubmit,

            valueChanged,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },

    data() {
        return {
            formDataLedger: {
                name: "",                
                email: "",
                address: "",
               
                mobile_number: "",
                whatsapp_number: "",
                ledger_id: "1",
                gender: "1",
                age:0,
                billing_discount: "",
                government_id: "",
                customer_type:1,
                address:"",
                dob:'',

            },
            inputColor: "",
            partyNameColor: '',
            accountGroupColor: '',
            stationsColor: '',
            tabPressCount: 0,
            showInput: true,
            isModalVisible: false,
            isModalVisibles: false,
            isModalVisibleing: false,
            isModalBillingVisible: false,
            isModalCopy: false,
            isLocationModal: false,
            isModalBirthday: false,
            isModalGST: false,
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Create Ledger',
            successMessage: 'Operation successful!',
            activeKey: 'taxdetails',


            resetForm() {

                this.formDataLedger = {
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
                    balancing_method: "",
                    opening_balance: "",
                    credit_days: "",
                    phone_number: "",
                    mobile_number: "",
                    whatsapp_number: ""
                };
            },


        };


    },

    methods: {
        onlyForNumber($event) {
            // console.log($event.keyCode); //keyCodes value
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

            // only allow number and one dot
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.price.indexOf('.') != -1)) { // 46 is dot
                $event.preventDefault();
            }

            // restrict to 2 decimal places
            if (this.price != null && this.price.indexOf(".") > -1 && (this.price.split('.')[1].length > 1)) {
                $event.preventDefault();
            }
        },
        switchTab() { console.log(this.activeKey);
            const tabs = ['taxdetails','contactinfo','bankdetails', 'visibility',  ];
            const currentIndex = tabs.indexOf(this.activeKey);
            this.activeKey = tabs[(currentIndex + 1) % tabs.length];
        },
        resetForm() {
            if (this.$refs.myForm1) {
                this.$refs.myForm1.resetFields();
            }
        },
        async onSubmitLedger() {
            try {
                this.ledgerSubmit(this.formDataLedger);
            } catch (error) {
                this.$message.error(this.$t("company.updateFailed"));
                console.error(error);
            }
            
        },
        autoFocusInput() {
            /*this.$nextTick(() => {
                this.$refs.partyInput.focus();  // Automatically focus the input
            });*/
        },
        showGSTModal() {
            this.isModalGST = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showBirthdayModal() {
            this.isModalBirthday = true;

            document.addEventListener('keydown', this.handleKeydown);
        },
        showLocationModal() {
            this.isLocationModal = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showCopyModal() {
            this.isModalCopy = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showBillingModal() {
            this.isModalBillingVisible = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showModal() {
            this.isModalVisible = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showModales() {
            this.isModalVisibles = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showOtherModal() {
            this.isModalVisibleing = true; // Open the modal
            document.addEventListener('keydown', this.handleKeydown);
        },
        handleLocationClose() {
            this.isLocationModal = false;
        },
        handleCopy() {
            this.isModalCopy = false;
        },
        handleClose() {
            this.isModalVisible = false;
            document.removeEventListener('keydown', this.handleKeydown);
        },
        handleCloses() {
            this.isModalVisibles = false;
            document.removeEventListener('keydown', this.handleKeydown);
        },
        handleClosing() {
            this.isModalVisibleing = false;
        },
        handlebillingClose() {
            this.isModalBillingVisible = false;
        },
        handleBirthday() {
            this.isModalBirthday = false;
        },
        handleGST() {
            this.isModalGST = false;
        },
        handleSuccess(xid) {
            this.isModalVisible = false;
            document.removeEventListener('keydown', this.handleKeydown);
            console.log('Success:', xid);
        },
        handleKeydown(event) {
            if (event.key === 'Escape') {
                this.handleClose();
            } else if (event.key === 'F9') {
                event.preventDefault();
                this.resetForm();
            } else if (event.key === 'F6') {
                event.preventDefault();
                this.switchTab();
            } else if (event.key === 'F10') {
                event.preventDefault();
               
            }
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
        onInputPhoneNumber(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.phone_number = value;
        },
        onInputMobileNumber(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.mobile_number = value;
        },
        onInputWattsappNumber(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.whatsapp_number = value;
        },
        onInputPanNumber(event) {
            let value = event.target.value.toUpperCase();
            value = value.replace(/[^A-Z0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.pan_number = value;
        },
        onInputIFSCCODE(event) {
            let value = event.target.value.toUpperCase();
            value = value.replace(/[^A-Z0-9]/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            this.formDataLedger.ifsc_code = value;
        },
        onInputACCOUNTNUMBER(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 15) {
                value = value.slice(0, 15);
            }
            this.formDataLedger.account_number = value;
        },

        onInputOpeningBalance(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.opening_balance = value;
        },


        onInputCreditDays(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.credit_days = value;
        },

        onInputPincode(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 6) {
                value = value.slice(0, 6);
            }
            this.formDataLedger.stock_pincode = value;
        },
        moveToNextField(elem) { console.log(elem.value)
            const currentIndex = Array.from(elem.form.elements).indexOf(elem);
            elem.form.elements.item(
                currentIndex < elem.form.elements.length - 1 ?
                currentIndex + 1 :
                0
            ).focus();
        }


    },


    mounted() {
        // Add keydown event listener to capture F9 key
        window.addEventListener('keydown', this.handleKeydown);
    },
    beforeDestroy() {
        // Remove event listener when component is destroyed
        window.removeEventListener('keydown', this.handleKeydown);
    },
});
</script>

<style>
:where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
    margin-bottom: 6px !important;
}

.popups {
    width: 80% !important;
    /*vertical-align: top !important;
    top:15px !important;*/
}

.ant-modal-footer {
    background-color: #e4e4e4 !important;
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

#ledger {
    display: flex !important;
    flex-flow: revert !important;
    min-width: 0 !important;
}

/*:where(.css-dev-only-do-not-override-wosfq4).ant-form-item .ant-form-item-label >label {
    width: 400px !important;
}*/
.ant-modal-header {
    padding: 7px 10px !important;
}

.balance {
    width: 100% !important;
}

.opening {
    display: flex;
}

.selecting {
    height: 37px !important;
}

.buttonstype {
    background-color: #ffffff;
    border-color: #d9d9d9;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.02);
    margin: 1px;
    color: black;
    padding: 3px 7px !important;

}

.info {
    margin-top: -24px;
}

#closeicon {
    top: 14px !important;
}

.indiannumber {
    padding: 4px 7px !important;
    background: #f6f6f6;
    border-color: #c2c2c2;
    color: black;
}

.gst {
    background-color: #1f6d70 !important;
    color: white !important;
}

button.btn {
    padding: 4px 7px !important;
}

/*.borderright {
    border-right: 1px solid #c2c2c2;
}*/
.ant-tabs-tab {
    position: relative !important;
    background: #fff !important;
    border-radius: 0 !important;
    border-bottom: 0 !important;
    border-top: 0 !important;
    display: block !important;
    padding: 10px 12px !important;
    line-height: 1 !important;

}

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs .ant-tabs-tab+.ant-tabs-tab {
    margin: 0 0 0 0px !important;
    border-left: 1px solid #e4e4e4 !important;
}

.ant-tabs-nav-wrap {
    background-color: #e4e4e4 !important;
    margin-top: 12px !important;
}

.ant-tabs-content-holder {
    border: 1px solid #c2c2c2 !important;
    background: #f6f6f6 !important;
    border-radius: 0 0 4px 4px !important;
    padding: 15px 12px 5px !important;
    position: relative !important;
    top: -16px !important;
    z-index: 0 !important;
    box-shadow: inset 0 0 0 1px #fff !important;
}

button.btn {
    cursor: pointer;
}

.ant-form-item-explain-error {
    font-size: 13px;
}
</style>