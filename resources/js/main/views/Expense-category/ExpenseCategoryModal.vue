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
                <span>Expense Entry</span>
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
        <form
            ref="myForm1"
            id="addproductform"
            layout="vertical"
            @submit.prevent="false"
        >
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24" class="info">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">Basic Info</legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Tax Type')"
                                            name="tax_type"
                                            :help="
                                                rules.tax_type
                                                    ? rules.tax_type.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.tax_type ? 'error' : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-select
                                            id="product_type"
                                            name="product_type"
                                            ref="ProductInput"
                                            v-model:value="formData.tax_type"
                                            style="width: 100%"
                                            @input="
                                                validateAndClearError(
                                                    'tax_type'
                                                )
                                            "
                                            @keyup.enter="focusNext"
                                        >
                                            <a-select-option
                                                key="Goods"
                                                value="Goods"
                                                aria-selected="true"
                                            >
                                                With GST
                                            </a-select-option>
                                            <a-select-option
                                                key="Service"
                                                value="Service"
                                                aria-selected="true"
                                            >
                                                With out GST
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Select Party')"
                                            name="party_type"
                                            :help="
                                                rules.party_type
                                                    ? rules.party_type.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.party_type
                                                    ? 'error'
                                                    : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-input
                                            name="packing"
                                            id="party_type"
                                            v-model:value="formData.party_type"
                                            value=""
                                            @input="
                                                validateAndClearError(
                                                    'party_type'
                                                )
                                            "
                                            @keyup.enter="focusNext"
                                        >
                                        </a-input>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Expense Category')"
                                            name="name"
                                            :help="
                                                rules.expense_category
                                                    ? rules.expense_category
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.expense_category
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <ExpenseCategorySearchInput
                                            id="expense_search"
                                            name="expense_category"
                                            @valueChanged="
                                                (productId) =>
                                                    (formData.expense_category =
                                                        productId)
                                            "
                                            @valueSuccess="getStockValue"
                                            :productData="data"
                                            v-model:value="
                                                formData.expense_category
                                            "
                                            @keyup.enter="focusNext"
                                        />
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Entry Date')"
                                            name="name"
                                            :help="
                                                rules.entry_date
                                                    ? rules.entry_date.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.entry_date
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-input
                                            name="entry_date"
                                            id="entry_date"
                                            type="date"
                                            v-model:value="formData.entry_date"
                                            value=""
                                            @keyup.enter="focusNext"
                                        >
                                        </a-input>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Expense Date')"
                                            name="name"
                                            :help="
                                                rules.expense_date
                                                    ? rules.expense_date.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.expense_date
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="expense_date"
                                            id="expense_date"
                                            type="date"
                                            v-model:value="
                                                formData.expense_date
                                            "
                                            value=""
                                            @keyup.enter="focusNext"
                                        >
                                        </a-input>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Original Bill Number')"
                                            name="original_bill_number"
                                            :help="
                                                rules.original_bill_number
                                                    ? rules.original_bill_number
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.original_bill_number
                                                    ? 'error'
                                                    : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-input
                                            name="original_bill_number"
                                            id="original_bill_number"
                                            v-model:value="
                                                formData.original_bill_number
                                            "
                                            value=""
                                            @keyup.enter="focusNext"
                                            @input="
                                                validateAndClearError(
                                                    'original_bill_number'
                                                )
                                            "
                                        >
                                        </a-input>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Bill Amount ')"
                                            name="name"
                                            :help="
                                                rules.bill_amount
                                                    ? rules.bill_amount.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.bill_amount
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="bill_amount"
                                            id="bill_amount"
                                            v-model:value="formData.bill_amount"
                                            value=""
                                            @keyup.enter="focusNext"
                                            placeholder="0.00"
                                        >
                                        </a-input>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Tax Amount')"
                                            name="name"
                                            :help="
                                                rules.tax_amount
                                                    ? rules.tax_amount.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.tax_amount
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-input
                                            name="tax_amount"
                                            id="tax_amount"
                                            v-model:value="formData.tax_amount"
                                            value=""
                                            @keyup.enter="focusNext"
                                            placeholder="0.00"
                                        >
                                        </a-input>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Payment Mode')"
                                            name="name"
                                            :help="
                                                rules.payment_mode
                                                    ? rules.payment_mode.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.payment_mode
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="payment_mode"
                                            id="payment_mode"
                                            v-model:value="
                                                formData.payment_mode
                                            "
                                            value=""
                                            @keyup.enter="focusNext"
                                        >
                                        </a-input>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Notes')"
                                            name="note"
                                            :help="
                                                rules.note
                                                    ? rules.note.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.note ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-textarea
                                            name="note"
                                            id="note"
                                            v-model:value="formData.note"
                                            value=""
                                            @keyup.enter="focusNext"
                                        >
                                        </a-textarea>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('Upload Bill')"
                                            name="image"
                                            :help="
                                                rules.image
                                                    ? rules.image.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.image ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <Upload
                                            :formData="formData"
                                            folder="expenses"
                                            @onFileUploaded="
                                                (file) => {
                                                    formData.image = file.file;
                                                    formData.image_url =
                                                        file.file_url;
                                                }
                                            "
                                        />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                </a-col>
            </a-row>
            <div class="floats">
                <a-row :gutter="16" style="margin-top: 20px">
                    <a-col :xs="24" :sm="24" :md="7" :lg="7"> </a-col>

                    <GSTModel
                        v-if="isModalGST"
                        :visible="isModalGST"
                        :formData="formData"
                        :url="url"
                        :addEditType="addEditType"
                        :pageTitle="pageTitle"
                        :successMessage="successMessage"
                        @addEditSuccess="handleSuccess"
                        @closed="handleGST"
                    />

                    <a-col :xs="24" :sm="24" :md="7" :lg="7"> </a-col>
                    <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <div style="float: right">
                            <button
                                @click="saveProductItem($event)"
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
                                </span>
                                <span class="effect"></span>
                            </button>
                            <button
                                type="button"
                                id="btn-Ledger"
                                title="Ledger"
                                class="btn default-btn ng-star-inserted"
                                @click="showconfirm"
                            >
                                <span class="box"
                                    ><span class="shortcut ng-star-inserted"
                                        ><code>Esc</code></span
                                    ><span class="ng-star-inserted"
                                        >Close</span
                                    ></span
                                ><span class="effect"></span>
                            </button>
                        </div>
                    </a-col>
                </a-row>
            </div>
        </form>
        <template #footer class="floats" style="display: none !important">
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
    ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import { SearchOutlined } from "@ant-design/icons-vue";

import fields from "../../views/Expense-category/fields";
import { message, notification } from "ant-design-vue";
import ExpenseCategorySearchInput from "../../../common/components/expense/ExpenseCategorySearchInput.vue";
import PaymodeSearchInput from "../../../common/components/expense/PaymodeSearchInput.vue";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";
import Upload from "../../../common/core/ui/file/Upload.vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";

export default defineComponent({
    props: ["productData"],
    emits: ["valueSuccess", "valueChanged"],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        DeleteOutlined,
        EditOutlined,
        SearchOutlined,
        PaymodeSearchInput,
        ExpenseCategorySearchInput,
        ExclamationCircleOutlined,
        DateTimePicker,
        Upload,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { adjustmentTypes } = fields();
        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        const ledgerSubmit = (formData) => {
            addEditRequestAdmin({
                url: `expense-store`,
                data: formData,
                success: (res) => {
                    notification.success({
                        placement: "bottomRight",
                        message: "Expenses saved successfully",
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
            //formData,
            ledgerSubmit,
            // getStockValue,
            valueChanged,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },

    data() {
        return {
            formData: {
                id: "",
                company_id: "",
                warehouse_id: "",
                tax_type: "With GST",
                entry_date: new Date().toISOString().split("T")[0],
                image: "",
                date: "",
                created_at: "",
                updated_at: "",
                tax_type: "",
                party_type: "",
                expense_category: "",
                expense_date: "",
                original_bill_number: "",
                bill_amount: "",
                tax_amount: "",
                payment_mode: "",
                note: "",
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
            isModalGST: false,
            url: "your-url-here",
            addEditType: "add",
            pageTitle: "Modify Ledger",
            successMessage: "Operation successful!",
            activeKey: "taxdetails",
            currentFocusElement: null,

            rules: {
                mrp: null,
                purchase_rate: null,
                cost: null,
                sale_rate: null,
                rate_b: null,
                rate_d: null,
                rate_f: null,
            },
            validationStatus: {
                mrp: "",
                purchase_rate: "",
                cost: "",
                sale_rate: "",
                rate_b: "",
                rate_d: "",
                rate_f: "",
            },

            resetForm() {
                this.formData = {
                    tax_type: "Goods",
                };
            },
        };
    },

    methods: {
        //All vladiation occurs here
        validateInput() {
            let isValid = true;
            const fieldsToValidate = [
                { key: "tax_type", label: "This Field is required." },
                { key: "party_type", label: "This Field is required." },
                {
                    key: "original_bill_number",
                    label: "This Field is required.",
                },
            ];

            fieldsToValidate.forEach((field) => {
                const value = this.formData[field.key];
                console.log(`Validating ${field.key}: `, value);

                let isFieldValid = true;
                if (
                    field.key === "mrp" ||
                    field.key === "purchase_rate" ||
                    field.key === "sale_rate"
                ) {
                    const numericValue = parseFloat(value);
                    if (isNaN(numericValue) || numericValue <= 0) {
                        isFieldValid = false;
                    }
                } else {
                    if (
                        !value ||
                        (typeof value === "string" && value.trim() === "")
                    ) {
                        isFieldValid = false;
                    }
                }

                if (!isFieldValid) {
                    this.rules[field.key] = { message: field.label };
                    isValid = false;
                } else {
                    this.rules[field.key] = null;
                }
            });

            return isValid;
        },

        validateAndClearError(field) {
            const value = this.formData[field];

            if (!value || value.trim() === "") {
                this.rules[field] = {
                    message: "This Field is required.",
                };
            } else {
                this.rules[field] = null;
            }
        },

        validateField(field) {
            const value = this.formData[field];
            const valueed = this.rules[field]?.message || field;

            if (!value) {
                this.rules[field] = {
                    message: "This Field is required.",
                };
            } else {
                this.rules[field] = null;
            }
        },

        focusNextField() {
            if (this.validatePurchaseRate()) {
                this.$nextTick(() => {
                    this.$refs.costInput.focus();
                });
            }
        },
        //focus input//
        focusNextFields() {
            if (this.validateCostRate()) {
                this.$nextTick(() => {
                    this.$refs.salesrate.focus();
                });
            }
        },

        //end foucs input//

        focusNextFieldsales() {
            console.log("testttt");
            if (this.validateSaleRate()) {
                this.$nextTick(() => {
                    this.$refs.free_scheme_1.focus();
                });
            }
        },

        switchTab() {
            const tabs = [
                "taxdetails",
                "contactinfo",
                "bankdetails",
                "visibility",
            ];
            const currentIndex = tabs.indexOf(this.activeKey);
            this.activeKey = tabs[(currentIndex + 1) % tabs.length];

            this.$nextTick(() => {
                const refName = `${this.activeKey}FirstInput`;
                if (this.$refs[refName]) {
                    this.$refs[refName].focus();
                }
            });
        },

        focusTab(tabKey) {
            console.log(`Switching to tab: ${tabKey}`);
            this.activeKey = tabKey;
            this.$nextTick(() => {
                this.focusFirstInput(tabKey);
            });
        },

        handleTabChange(activeKey) {
            this.activeKey = activeKey;
            console.log(`Switched to tab: ${activeKey}`);

            this.$nextTick(() => {
                this.focusFirstInput(activeKey);
            });
        },

        // inr or Rupees valdiation

        resetForm() {
            if (this.$refs.myForm1) {
                this.$refs.myForm1.resetFields();
            }
        },
        onSubmitLedger(eventKey) {
            try {
                console.log(eventKey, "<>", this.formData);
                if (eventKey == "F8" && this.validateInput()) {
                    this.ledgerSubmit(this.formData);
                }
            } catch (error) {
                this.$message.error(this.$t("Issue while Expense Save !"));
                console.error(error);
            }
        },
        saveProductItem(eventKey) {
            try {
                console.log("saveProductItem");
                if (this.validateInput()) {
                    this.ledgerSubmit(this.formData);
                }
            } catch (error) {
                this.$message.error(this.$t("Issue while Expense Save !"));
                console.error(error);
            }
        },

        handleLocationClose() {
            this.isLocationModal = false;
        },
        handleCopy() {
            this.isModalCopy = false;
        },
        handleClose() {
            this.showconfirm();
        },

        showconfirm() {
            console.log("Esc called");
            let that = this;
            const modal = Modal.confirm({
                title: "Confirmation",
                icon: createVNode(ExclamationCircleOutlined),
                content:
                    "Transaction data will be lost. Are you sure you want to close?",
                onOk() {
                    that.$emit("closed");
                },
                onCancel() {
                    that.$refs.ProductInput && that.$refs.ProductInput.focus();
                },
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",
            });

            this.$nextTick(() => {
                const handleKeydown = (e) => {
                    if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
                        e.preventDefault();

                        const buttons = document.querySelectorAll(
                            ".ant-modal-confirm .ant-btn"
                        );
                        const cancelBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-default")
                        );
                        const okBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-primary")
                        );

                        const focusedElement = document.activeElement;

                        if (e.key === "ArrowLeft") {
                            if (focusedElement === okBtn) {
                                cancelBtn?.focus();
                            } else {
                                cancelBtn?.focus();
                            }
                        } else if (e.key === "ArrowRight") {
                            if (focusedElement === cancelBtn) {
                                okBtn?.focus();
                            } else {
                                okBtn?.focus();
                            }
                        }
                    }
                };
                document.addEventListener("keydown", handleKeydown);
                modal.afterClose(() => {
                    document.removeEventListener("keydown", handleKeydown);
                });
            });
        },

        handleCloses() {
            this.showconfirm();
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
            document.removeEventListener("keydown", this.handleKeydown);
            console.log("Success:", xid);
        },
        handleKeydown(event) {
            console.log("Product event", event.target.id);

            if (event.keyCode === 13 && event.target.id === "expense_search") {
                this.focusNext(event);
            } else if (event.key === "Escape" || event.key === 27) {
                console.log(
                    "ESC pressed on element:",
                    event.target.id,
                    "Tag:",
                    event.target.tagName
                );
                if (
                    event.target.id &&
                    [
                        "unit_in_decimal",
                        "low_stock",
                        "expense_search",
                        "color_type",
                        "category_field",
                        "hsn_sac",
                        "brandsing",
                    ].includes(event.target.id)
                ) {
                    console.log("ESC ignored for:", event.target.id);
                }
            } else if (event.key === "F9") {
                event.preventDefault();
                this.resetForm();
            } else if (event.key === "F4") {
                event.preventDefault();
                this.switchTab();
                this.focusinputvalue();
            } else if (event.key === "F8" || event.keyCode === 119) {
                event.preventDefault();
                if (this.validateInput()) {
                    this.onSubmitLedger(event.key);
                }
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
            const currentField = e.target;

            console.log("focusNext", e.target.id);
            const formElements = Array.from(
                currentField.form.querySelectorAll(
                    "input, select,date,textarea"
                )
            );

            const currentIndex = formElements.indexOf(currentField);

            const nonMandatoryFieldsArray = [
                "product_type",
                "party_type",
                "unit_in_decimal",
                "as_on_date",
                "low_stock",
                "entry_date",
                "cost",
                "color_type",
                "category_field",
                "hsn_sac",
                "expense_search",
                "expense_date",
                "original_bill_number",
                "bill_amount",
                "tax_amount",
                "payment_mode",
                "note",
            ];
            //console.log(e.target.id)
            if (currentIndex < formElements.length - 1) {
                if (
                    e.target.id == "party_type" &&
                    this.formData.party_type != undefined &&
                    this.formData.party_type != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "expense_search" &&
                    this.formData.expense_category != undefined &&
                    this.formData.expense_category != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "entry_date" &&
                    this.formData.entry_date != undefined &&
                    this.formData.entry_date != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "category_field" &&
                    this.formData.category_field != undefined &&
                    this.formData.category_field != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "expense_date" &&
                    this.formData.expense_date != undefined &&
                    this.formData.expense_date != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "original_bill_number" &&
                    this.formData.original_bill_number != undefined &&
                    this.formData.original_bill_number != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "bill_amount" &&
                    this.formData.bill_amount != undefined &&
                    this.formData.bill_amount != "" &&
                    this.formData.bill_amount.trim() != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "tax_amount" &&
                    this.formData.tax_amount != undefined &&
                    this.formData.tax_amount != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "payment_mode" &&
                    this.formData.payment_mode != undefined &&
                    this.formData.payment_mode != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "note" &&
                    this.formData.note != undefined &&
                    this.formData.note != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "stock_status" &&
                    this.formData.status != undefined &&
                    this.formData.status != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "color_type" &&
                    this.formData.color_type != undefined &&
                    this.formData.color_type != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "discount" &&
                    this.formData.discount != undefined &&
                    this.formData.discount != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "visibility" &&
                    this.formData.visibility != undefined &&
                    this.formData.visibility != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "rate_d" &&
                    this.formData.rate_d != undefined &&
                    this.formData.rate_d != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "brand_name" &&
                    this.formData.brand_name != undefined &&
                    this.formData.brand_name != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    e.target.id == "rate_f" &&
                    this.formData.rate_f != undefined &&
                    this.formData.rate_f != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (
                    formElements[currentIndex].value != undefined &&
                    formElements[currentIndex].value != "" &&
                    formElements[currentIndex].value.trim() != ""
                ) {
                    formElements[currentIndex + 1].focus();
                } else if (nonMandatoryFieldsArray.indexOf(e.target.id) != -1) {
                    formElements[currentIndex + 1].focus();
                } else {
                    console.log(e.target.id, this.formData.hsn_sac);
                    // document.getElementById('save-item').focus();
                }
            }
        },

        checkproductDuplicate(event) {
            console.log("API call");
            this.duplicate = false;
            if (
                this.formData.name != undefined &&
                this.formData.name != null &&
                this.formData.name != "" &&
                this.formData.name.trim() != "" &&
                event.key != "F8"
            ) {
                axiosAdmin
                    .get("productsDuplicate/" + this.formData.name)
                    .then((response) => {
                        console.log(response);
                        // Toastr Notificaiton
                        if (response == 500) {
                            this.duplicate = true;
                            message.error("Product name already exist !");
                        }
                    })
                    .catch((errorResponse) => {
                        var err = errorResponse.data;
                        const errorCode = errorResponse.status;
                        var errorRules = {};

                        if (errorCode == 422) {
                            if (
                                err.error &&
                                typeof err.error.details != "undefined"
                            ) {
                                var keys = Object.keys(err.error.details);
                                for (var i = 0; i < keys.length; i++) {
                                    // Escape dot that comes with error in array fields
                                    var key = keys[i].replace(".", "\\.");

                                    errorRules[key] = {
                                        required: true,
                                        message: err.error.details[keys[i]][0],
                                    };
                                }
                            }

                            rules.value = errorRules;
                            message.error(t("common.fix_errors"));
                        }

                        if (err && err.message) {
                            message.error(err.message);
                            err = {
                                error: {
                                    ...err,
                                },
                            };
                        }
                    });
            }
        },

        focusinputvalue(event) {
            if (event.target) {
                event.target.select();
                console.log(
                    `Input focused and text selected: ${event.target.name}`
                );
            }
        },

        autoFocusInput() {
            this.$nextTick(() => {
                const gstInput = this.$refs.ProductInput;

                if (gstInput) {
                    gstInput.focus();
                    console.log("ProductInput focused");
                } else {
                    console.error("ProductInputnot found after mount/update.");
                }
            });
        },
    },

    watch: {
        formData: {
            handler(newVal) {
                ["tax_type", "party_type", "original_bill_number"].forEach(
                    (field) => {
                        this.validateField(field);
                    }
                );
            },
            deep: true,
        },
    },

    mounted() {
        // Add keydown event listener to capture F9 key
        document
            .getElementById("addproductform")
            .addEventListener("keyup", this.handleKeydown);
        this.autoFocusInput();
    },
    beforeDestroy() {
        // Remove event listener when component is destroyed
        window.removeEventListener("keydown", this.handleKeydown);
    },
});
</script>

<style>
fieldset {
    display: block !important;
    margin-inline-start: 2px !important;
    margin-inline-end: 2px !important;
    padding-block-start: 0.35em !important;
    padding-inline-start: 0.75em !important;
    padding-inline-end: 0.75em !important;
    padding-block-end: 0.625em !important;
    min-inline-size: min-content !important;
    border-width: 2px !important;
    border-style: groove !important;

    border-image: initial !important;
}

/* newqly added styles */

.ant-form-item {
    margin-bottom: 5px !important;
}

.popups {
    width: 81% !important;
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

/* already here code s */
legend {
    width: auto !important;
}

.basicdetalis {
    border-bottom: none !important;
    color: black !important;
    margin-bottom: 0px !important;
    padding: 2px !important;
}
.disc {
    float: right !important;
}

.tables {
    margin-top: 5% !important;
}

.popup {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    padding: 10px;
}

.popup.visible {
    display: block;
}

.table-container {
    overflow-x: auto;
    margin: 0 auto;
}

.responsive-table {
    width: 100%;
}
.total_igst_amt {
    text-align-last: right;
    font-weight: bolder;
}
.ant-input:focus {
    background-color: #ffd451 !important;
}
.shortcut {
    position: relative; /* Ensure relative positioning for the child pseudo-element */
    display: inline-block; /* Ensure the span behaves like an inline element */
}

.shortcut:after {
    position: absolute;
    right: -5px; /* Adjust this based on your layout */
    top: 0;
    width: 1px;
    height: 100%;
    background-color: #cacaca; /* Ensure background color is applied */
    content: "";
}

.savebutton {
    padding: 1px 11px;
}
.backgrounds {
    background-color: #1f6d70;
    color: white;
    font-size: 13px !important;
    margin-left: 16px;
}
.draft {
    background-color: white;
    font-size: 13px;
    border: solid 1px #00000033;
}
.savecolse {
    background-color: white;
    border: solid 1px #00000033;
}

.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-menus li {
    padding: 2px 14px !important;
    cursor: pointer;
}

.dropdown-toggle {
    background-color: #1f6d70;
    color: white;
    padding: 2px 5px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    height: 31px;
    border-top-right-radius: 9px;
}

.dropdown-toggle .arrow-icon {
    margin-left: 1px;
    text-align: center;
    font-size: 14px;
}

.dropdown-menus {
    display: block;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    list-style: none;
    margin: 0;
    top: -134px;
    left: 0;
    z-index: 1;
    width: 205px;
}

.dropdown-menus {
    left: 50%;
    transform: translateX(-50%);
}

.dropdown-items:hover {
    background-color: #f1f1f1;
}

.item-icon {
    margin-right: 10px;
}
.ant-notification-notice {
    background-color: rgb(233, 236, 141) !important;
}
.ant-notification-notice-message > span {
    color: #fff !important;
}
.page-content-sub-header {
    padding: 0px !important;
}
.ant-card-body {
    padding-top: 10px !important;
}
.ant-form-item .ant-form-item-label > label {
    font-size: 12px !important;
}
.ant-form-vertical .ant-form-item-label {
    padding: 0px !important;
}
.ant-input {
    font-size: 13px !important;
    line-height: 1.2 !important;
}
td > .ant-input {
    border: none !important;
    border-radius: 0px !important;
}
.responsive-table th,
.responsive-table td {
    padding: 0px !important;
}
.invoicevalues {
    background-color: #92b4b5 !important;
}

.fieldheightsss {
    margin-top: 15px;
}
.ant-upload-select div img {
    max-width: 90px !important;
}
</style>
