<template>
    <a-modal
        :open="visible"
        :width="drawerWidth"
        :closable="false"
        :centered="true"
        @close="handleClose"
        class="popups"
    >
        <template v-slot:title>
            <div
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                "
            >
                <span>Create Sale Master</span>
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
            </div>
        </template>
        <form id="addproductform" layout="vertical" @submit.prevent="false">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-form-item
                                        :label="$t('stock.sales_type')"
                                        name="sales_type"
                                        :help="
                                            rules.sales_type
                                                ? rules.sales_type.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.sales_type ? 'error' : null
                                        "
                                        class="required"
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="sales_type"
                                        ref="ProductInput"
                                        id="ProductInput"
                                        v-model:value="
                                            formDataLedger.sales_type
                                        "
                                        value=""
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </form>

        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8" class="required">
                <fieldset class="fieldheightsss">
                    <legend class="basicdetalis">Tax Type</legend>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('IGST %')"
                                        name="igst"
                                        :help="
                                            rules.igst
                                                ? rules.igst.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.igst ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="igst"
                                        v-model:value="formDataLedger.igst"
                                        placeholder="0.00"
                                        @input="onInputigst"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('CGST %')"
                                        name="cgst"
                                        :help="
                                            rules.cgst
                                                ? rules.cgst.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.cgst ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="cgst"
                                        v-model:value="formDataLedger.cgst"
                                        placeholder="0.00"
                                        @input="onInputcgst"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('SCST %')"
                                        name="sgst"
                                        :help="
                                            rules.sgst
                                                ? rules.sgst.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.sgst ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="sgst"
                                        v-model:value="formDataLedger.sgst"
                                        placeholder="0.00"
                                        @input="onInputsgst"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('Cess %')"
                                        name="cess"
                                        :help="
                                            rules.cess
                                                ? rules.cess.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.cess ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="cess"
                                        v-model:value="formDataLedger.cess"
                                        placeholder="0.00"
                                        @input="onInputcess"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>

                            <!-- <a-form-item>
                                    <span style="display: flex">
                                        <ProductSearchInput
                                            @valueChanged="
                                                (productId) =>
                                                    (formData.product_id =
                                                        productId)
                                            "
                                            @valueSuccess="getStockValue"
                                            :productData="data"
                                            @keyup.enter="focusNext"
                                        />
                                    </span>
                                </a-form-item> -->
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>

            <a-col :xs="24" :sm="24" :md="1" :lg="10" class="required">
                <fieldset class="fieldheightsss">
                    <legend class="basicdetalis">Tax Ledger</legend>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="20" :lg="20">
                            <a-row :gutter="16">
                                <a-col :xs="20" :sm="20" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('IGST Ledger')"
                                        name="igst_ledger"
                                        :help="
                                            rules.igst_ledger
                                                ? rules.igst_ledger.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.igst_ledger ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="2" :sm="2" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="igst_ledger"
                                        v-model:value="
                                            formDataLedger.igst_ledger
                                        "
                                        @input="onInputIGSTLedger"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('CGST Ledger')"
                                        name="cgst_ledger"
                                        :help="
                                            rules.cgst_ledger
                                                ? rules.cgst_ledger.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.cgst_ledger ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="cgst_ledger"
                                        v-model:value="
                                            formDataLedger.cgst_ledger
                                        "
                                        @input="onInputCGSTLedger"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('SGST Ledger')"
                                        name="sgst_ledger"
                                        :help="
                                            rules.sgst_ledger
                                                ? rules.sgst_ledger.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.sgst_ledger ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="sgst_ledger"
                                        v-model:value="
                                            formDataLedger.sgst_ledger
                                        "
                                        @input="onInputSGSTLedger"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="10" :lg="10">
                                    <a-form-item
                                        :label="$t('CESS Ledger')"
                                        name="cess_ledger"
                                        :help="
                                            rules.cess_ledger
                                                ? rules.cess_ledger.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.cess_ledger ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                    <a-input
                                        autocomplete="off"
                                        name="cess_ledger"
                                        v-model:value="
                                            formDataLedger.cess_ledger
                                        "
                                        @input="onInputCESSLedger"
                                        @keyup.enter="focusNext"
                                    >
                                    </a-input>
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
        </a-row>

        <template #footer class="floats" style="display: none !important">
            <div class="floats">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="21" :lg="21">
                        <div style="float: right">
                            <button
                                @click="saveProductItem()"
                                type="button"
                                id="save-item"
                                title="Ledger"
                                class="btn default-btn ng-star-inserted gst"
                            >
                                <span class="box">
                                    <span class="shortcut ng-star-inserted"
                                        ><code>F8</code></span
                                    >
                                    <span class="ng-star-inserted">Save</span>
                                    <SaveOutlined />
                                </span>
                                <span class="effect"></span>
                            </button>
                            <button
                                type="button"
                                id="btn-Ledger"
                                title="Ledger"
                                class="btn default-btn ng-star-inserted"
                                @click="resetForm"
                            >
                                <span class="box">
                                    <span class="shortcut ng-star-inserted">
                                        <code>F9</code>
                                    </span>
                                    <span class="ng-star-inserted">Clear</span>
                                    <ClearOutlined />
                                </span>
                                <span class="effect"></span>
                            </button>
                            <button
                                type="button"
                                id="btn-Ledger"
                                title="Ledger"
                                class="btn default-btn ng-star-inserted"
                            >
                                <span class="box"
                                    ><span class="shortcut ng-star-inserted"
                                        ><code>Esc</code></span
                                    ><span class="ng-star-inserted"
                                        >Close X</span
                                    ></span
                                ><span class="effect"></span>
                            </button>
                        </div>
                    </a-col>
                </a-row>
            </div>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, reactive, onMounted, watch, ref } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    EditOutlined,
    DeleteOutlined,
    ClearOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import ExpenseCategoryModal from "../../stock-management/purchases/ExpenseCategoryModal.vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import DiscountModel from "../../stock-management/purchases/DiscountModel.vue";
import LimitModel from "../../stock-management/purchases/LimitModel.vue";
import OtherModel from "../../stock-management/purchases/OtherModel.vue";
import BillingModel from "../../stock-management/purchases/BillingModel.vue";
import CopyModel from "../../stock-management/purchases/CopyModel.vue";
import LocationModel from "../../stock-management/purchases/LocationModel.vue";
import BirthdayModel from "../../stock-management/purchases/BirthdayModel.vue";
import GSTModel from "../../stock-management/purchases/GSTModel.vue";
import fields from "./fields";
import { message, notification } from "ant-design-vue";
import ProductCompanySearchInput from "../../../../common/components/product/ProductCompanySearchInput.vue";
import TaxCategorySearchInput from "../../../../common/components/product/TaxCategorySearchInput.vue";
import UnitSearchInput from "../../../../common/components/product/UnitSearchInput.vue";
import HscSacSearchInput from "../../../../common/components/product/HscSacSearchInput.vue";
export default defineComponent({
    props: ["productData"],
    emits: ["valueSuccess", "valueChanged"],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        DeleteOutlined,
        EditOutlined,
        ClearOutlined,
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
        ProductCompanySearchInput,
        TaxCategorySearchInput,
        UnitSearchInput,
        HscSacSearchInput,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { adjustmentTypes } = fields();
        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        const ledgerSubmit = (formDataLedger) => {
            addEditRequestAdmin({
                url: `tax_catagories`,
                data: formDataLedger,
                success: (res) => {
                    notification.success({
                        placement: "bottomRight",
                        message: "Tax saved successfully",
                    });
                    document.getElementById("addproductform").reset();
                    setTimeout(function () {
                        emit("closed");
                        document
                            .getElementById("form_item_party_name_search")
                            .focus();
                    }, 2000);
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

            ledgerSubmit,
            // getStockValue,
            valueChanged,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
    data() {
        return {
            formDataLedger: {
                id: "",
                sales_type: "",
                local: "",
                central: "",
                igst: "",
                cgst: "",
                sgst: "",
                cess: "",
                nature_of_transaction: "",
                tax_ability: "",
                igst_ledger: "IGST Output",
                cgst_ledger: "CGST Output",
                sgst_ledger: "SGST Output",
                cess_ledger: "CESS On GST Output",
            },
            duplicate: false,
            inputColor: "",
            partyNameColor: "",
            accountGroupColor: "",
            stationsColor: "",
            tabPressCount: 0,
            showInput: true,
            isModalVisible: false,
            isModalVisibles: false,
            isModalVisibleing: false,
            isModalBillingVisible: false,
            isModalCopy: false,
            isLocationModal: false,
            isModalBirthday: false,
            isModaIGST: false,
            url: "your-url-here",
            addEditType: "add",
            pageTitle: "Modify Ledger",
            successMessage: "Operation successful!",
            activeKey: "taxdetails",
            currentFocusElement: null,

            rules: {
                id: null,
                sales_type: null,
                local: null,
                central: null,
                igst: null,
                cgst: null,
                sgst: null,
                cess: null,
                nature_of_transaction: null,
                tax_ability: null,
                igst_ledger: null,
                cgst_ledger: null,
                sgst_ledger: null,
                cess_ledger: null,
            },
            validationStatus: {
                id: "",
                sales_type: "",
                local: "",
                central: "",
                igst: "",
                cgst: "",
                sgst: "",
                cess: "",
                nature_of_transaction: "",
                tax_ability: "",
                igst_ledger: "",
                cgst_ledger: "",
                sgst_ledger: "",
                cess_ledger: "",
            },

            resetForm() {
                this.formDataLedger = {
                    id: "",
                    sales_type: "",
                    local: "",
                    central: "",
                    igst: "",
                    cgst: "",
                    sgst: "",
                    cess: "",
                    nature_of_transaction: "",
                    tax_ability: "",
                    igst_ledger: "",
                    cgst_ledger: "",
                    sgst_ledger: "",
                    cess_ledger: "",
                };
            },
        };
    },

    methods: {
        // Tax Type valdiation

        onInputigst(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.igst = value;

            const igstValue = parseFloat(this.formDataLedger.igst);
            if (!isNaN(igstValue)) {
                // Dynamically calculate CGST and SGST as half of IGST
                const halfIgst = (igstValue / 2).toFixed(2);
                this.formDataLedger.cgst = halfIgst;
                this.formDataLedger.sgst = halfIgst;
            }
        },

        onInputcgst(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.cgst = value;
        },

        onInputsgst(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.sgst = value;
        },

        onInputcess(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.cess = value;
        },

        // Tax Type valdiation End

        // Tex Ledger valdiation
        // onInputIGSTLedger(event) {
        //     let value = event.target.value;
        //     value = value.replace(/[^0-9]/g, "");
        //     if (value.length > 10) {
        //         value = value.slice(0, 10);
        //     }
        //     this.formDataLedger.igst_ledger = value;
        // },

        // onInputCGSTLedger(event) {
        //     let value = event.target.value;
        //     value = value.replace(/[^0-9]/g, "");
        //     if (value.length > 10) {
        //         value = value.slice(0, 10);
        //     }
        //     this.formDataLedger.cgst_ledger = value;
        // },

        // onInputSGSTLedger(event) {
        //     let value = event.target.value;
        //     value = value.replace(/[^0-9]/g, "");
        //     if (value.length > 10) {
        //         value = value.slice(0, 10);
        //     }
        //     this.formDataLedger.sgst_ledger = value;
        // },
        // onInputCESSLedger(event) {
        //     let value = event.target.value;
        //     value = value.replace(/[^0-9]/g, "");
        //     if (value.length > 10) {
        //         value = value.slice(0, 10);
        //     }
        //     this.formDataLedger.cess_ledger = value;
        // },

        // Tex Ledger valdiation End

        resetForm() {
            if (this.$refs.myForm1) {
                this.$refs.myForm1.resetFields();
            }
        },

        saveProductItem() {
            try {
                console.log("saveTaxItem");
                if (eventKey == "F8" && this.validateInput()) {
                    this.ledgerSubmit(this.formDataLedger);
                }
            } catch (error) {
                this.$message.error(this.$t("Issue while Save Tax Item!"));
                console.error(error);
            }
        },

        // saveProductItem() {
        //     try {
        //         console.log("saveTaxItem");
        //         if (this.validateInput()) {
        //             this.ledgerSubmit(this.formDataLedger);
        //         }
        //     } catch (error) {
        //         this.$message.error(this.$t("Issue while Save Tax Item!"));
        //         console.error(error);
        //     }
        // },

        async saveProductItem() {
            try {
                this.ledgerSubmit(this.formDataLedger);
            } catch (error) {
                this.$message.error(this.$t("Tax.updateFailed"));
                console.error(error);
            }
        },

        showGSTModal() {
            this.isModaIGST = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showBirthdayModal() {
            this.isModalBirthday = true;

            document.addEventListener("keydown", this.handleKeydown);
        },
        showLocationModal() {
            this.isLocationModal = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showCopyModal() {
            this.isModalCopy = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showBillingModal() {
            this.isModalBillingVisible = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showModal() {
            this.isModalVisible = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showModales() {
            this.isModalVisibles = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showOtherModal() {
            this.isModalVisibleing = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        handleLocationClose() {
            this.isLocationModal = false;
        },
        handleCopy() {
            this.isModalCopy = false;
        },
        handleClose() {
            this.isModalVisible = false;
            document.removeEventListener("keydown", this.handleKeydown);
        },
        handleCloses() {
            this.isModalVisibles = false;
            document.removeEventListener("keydown", this.handleKeydown);
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
            this.isModaIGST = false;
        },
        handleSuccess(xid) {
            this.isModalVisible = false;
            document.removeEventListener("keydown", this.handleKeydown);
            console.log("Success:", xid);
        },
        handleKeydown(event) {
            if (event.key === "Escape") {
                this.handleClose();
            } else if (event.key === "F9") {
                event.preventDefault();
                this.resetForm();
            } else if (event.key === "F5") {
                event.preventDefault();
                this.switchTab();
            } else if (event.key === "F8") {
                event.preventDefault();
                this.saveProductItem(event.key);
            }
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

        focusNext(e) {
            console.log("Test");
            this.currentFocusElement = e.target;
            const formElements = Array.from(
                e.target.form.querySelectorAll("input, select")
            );
            const currentIndex = formElements.indexOf(e.target);
            //console.log(e.target.id)
        },

        // @blur="checkproductDuplicate($event)"

        // checkproductDuplicate(event) {
        //     console.log("API call");
        //     this.duplicate = false;
        //     if (
        //         this.formDataLedger.name != undefined &&
        //         this.formDataLedger.name != null &&
        //         this.formDataLedger.name != "" &&
        //         this.formDataLedger.name.trim() != "" &&
        //         event.key != "F8"
        //     ) {
        //         axiosAdmin
        //             .get("productsDuplicate/" + this.formDataLedger.name)
        //             .then((response) => {
        //                 console.log(response);
        //                 // Toastr Notificaiton
        //                 if (response == 500) {
        //                     this.duplicate = true;
        //                     message.error("sales type already exist !");
        //                 }
        //             })
        //             .catch((errorResponse) => {
        //                 var err = errorResponse.data;
        //                 const errorCode = errorResponse.status;
        //                 var errorRules = {};

        //                 if (errorCode == 422) {
        //                     if (
        //                         err.error &&
        //                         typeof err.error.details != "undefined"
        //                     ) {
        //                         var keys = Object.keys(err.error.details);
        //                         for (var i = 0; i < keys.length; i++) {
        //                             // Escape dot that comes with error in array fields
        //                             var key = keys[i].replace(".", "\\.");

        //                             errorRules[key] = {
        //                                 required: true,
        //                                 message: err.error.details[keys[i]][0],
        //                             };
        //                         }
        //                     }

        //                     rules.value = errorRules;
        //                     message.error(t("common.fix_errors"));
        //                 }

        //                 if (err && err.message) {
        //                     message.error(err.message);
        //                     err = {
        //                         error: {
        //                             ...err,
        //                         },
        //                     };
        //                 }
        //             });
        //     }
        // },

        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.ProductInput.focus();
            });
        },

        // form validation//
        validateInput() {
            let isValid = true;

            if (
                !this.formDataLedger.sales_type ||
                this.formDataLedger.sales_type.trim() === ""
            ) {
                this.rules.sales_type = {
                    message: "Sales Type  is required.",
                };
                isValid = false;
            } else {
                this.rules.sales_type = null;
            }

            if (
                !this.formDataLedger.igst ||
                (typeof this.formDataLedger.igst === "string" &&
                    this.formDataLedger.igst.trim() === "")
            ) {
                this.rules.igst = {
                    message: "igst value is required.",
                };
                isValid = false;
            } else {
                this.rules.igst = null;
            }

            if (
                !this.formDataLedger.cgst ||
                (typeof this.formDataLedger.cgst === "string" &&
                    this.formDataLedger.cgst.trim() === "")
            ) {
                this.rules.cgst = {
                    message: "cgst value is required.",
                };
                isValid = false;
            } else {
                this.rules.cgst = null;
            }

            if (
                !this.formDataLedger.sgst ||
                this.formDataLedger.sgst.trim() === ""
            ) {
                this.rules.sgst = {
                    message: "sgst value is required.",
                };
                isValid = false;
            } else {
                this.rules.sgst = null;
            }

            if (
                !this.formDataLedger.cess ||
                (typeof this.formDataLedger.cess === "string" &&
                    this.formDataLedger.cess.trim() === "")
            ) {
                this.rules.cess = {
                    message: "cess value is required.",
                };
                isValid = false;
            } else {
                this.rules.cess = null;
            }

            if (
                !this.formDataLedger.igst_ledger ||
                this.formDataLedger.igst_ledger.trim() === ""
            ) {
                this.rules.igst_ledger = {
                    message: "igst ledger is required.",
                };
                isValid = false;
            } else {
                this.rules.igst_ledger = null;
            }

            if (
                !this.formDataLedger.cgst_ledger ||
                this.formDataLedger.cgst_ledger.trim() === ""
            ) {
                this.rules.cgst_ledger = {
                    message: "cgst ledger is required.",
                };
                isValid = false;
            } else {
                this.rules.cgst_ledger = null;
            }

            if (
                !this.formDataLedger.sgst_ledger ||
                this.formDataLedger.sgst_ledger.trim() === ""
            ) {
                this.rules.sgst_ledger = {
                    message: "sgst ledger is required.",
                };
                isValid = false;
            } else {
                this.rules.sgst_ledger = null;
            }

            if (
                !this.formDataLedger.cess_ledger ||
                this.formDataLedger.cess_ledger.trim() === ""
            ) {
                this.rules.cess_ledger = {
                    message: "cess ledger is required.",
                };
                isValid = false;
            } else {
                this.rules.cess_ledger = null;
            }

            return isValid;
        },
    },
    mounted() {
        // Add keydown event listener to capture F9 key
        window.addEventListener("keyup", this.handleKeydown);
        this.autoFocusInput();
    },
    beforeDestroy() {
        // Remove event listener when component is destroyed
        window.removeEventListener("keydown", this.handleKeydown);
    },
});
</script>

<style>
.ant-form-item {
    margin-bottom: 5px !important;
}

.popups {
    width: 65% !important;
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

indiannumber .responsive-table {
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

.productnumber {
    padding: 2px 6px !important;
    background: #f6f6f6;
    border-color: #c2c2c2;
    color: black;

    text-align: center;
    height: 25px;
    font-size: 12px;
    border: 1px solid #ccc;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs
    .ant-tabs-tab
    + .ant-tabs-tab {
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

.error-message {
    color: green;
    font-size: 12px;
    margin-top: 4px;
}

button.btn {
    cursor: pointer;
}

.ant-form-item-explain-error {
    font-size: 13px;
}
.select-focused {
    background-color: #e0f7fa; /* Light blue */
}
.ant-select-selection-search-input {
    padding: 3% !important;
}
.ant-select-selection-search-input:focus {
    background-color: #ffd451 !important;
}
.ant-select-selection-search {
    margin-left: -10px !important;
}
</style>
