<template>
    <AdminPageHeader>
        <template #header>
            <PrintModel
                v-if="isPrintModalVisible"
                :visible="isPrintModalVisible"
                :formData="formData"
                :url="url"
                :addEditType="addEditType"
                :pageTitle="pageTitle"
                :successMessage="successMessage"
                @addEditSuccess="handleSuccess"
                @closed="handleClosePrint"
            />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    <router-link
                        :to="{
                            name: `admin.expenses1.index`,
                        }"
                    >
                        {{ $t(`Expenses`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`common.create`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>
<div id="expensecreate">


    <a-spin :spinning="spinning">
        <admin-page-table-content>
            <a-card class="page-content-container mt-5 mb-5">
                <a-form layout="vertical">
                    <a-row :gutter="16">
                        <a-col
                            :xs="24"
                            :sm="24"
                            :md="taxTypeLayoutValue"
                            :lg="taxTypeLayoutValue"
                        >
                            <a-form-item
                                :label="$t('Expenditure Type')"
                                class="required"
                                :help="
                                    rules.tax_type
                                        ? rules.tax_type.message
                                        : null
                                "
                                :validateStatus="
                                    rules.tax_type ? 'error' : null
                                "
                                :allowClear="true"
                            >
                                <select
                                    v-model="formData.tax_type"
                                    ref="taxtype"
                                    name="tax_type"
                                    class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                                    @change="handleTaxTypeChange"
                                    :tabindex="1"
                                    @keydown="handleTaxTypeKeydown($event)"
                                >
                                    <option value=""></option>
                                    <option value="with_gst">With GST</option>
                                    <option value="without_gst">
                                        Without GST
                                    </option>
                                </select>
                            </a-form-item>
                        </a-col>
                        <a-col
                            v-show="isPartyDetailsVisible"
                            :xs="24"
                            :sm="24"
                            :md="6"
                            :lg="6"
                        >
                            <a-form-item
                                :label="$t('expense1.party')"
                                name="party_name"
                                ref="input"
                                :help="
                                    rules.party_name
                                        ? rules.party_name.message
                                        : null
                                "
                                :validateStatus="
                                    rules.party_name ? 'error' : null
                                "
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.party_name"
                                    autocomplete="off"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('stock.party_name'),
                                        ])
                                    "
                                    @keydown="
                                        customerhandleKeydown(
                                            $event,
                                            'party_name'
                                        )
                                    "
                                    @focus="customerhandleKeydown($event)"
                                    :tabindex="
                                        formData.tax_type === 'with_gst'
                                            ? 2
                                            : -1
                                    "
                                    @keydown.enter="focusNext($event)"
                                    ref="party_name"
                                    @blur="clearError('party_name')"
                                />
                                <a-input
                                    hidden
                                    v-model="formData.party_id"
                                    id="party_id"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('stock.party_'),
                                        ])
                                    "
                                />
                            </a-form-item>
                            <!--- party modal      @keydown="focusNext($event, 'tax_type')"     focusNext-->
                            <SalesModel
                                v-if="isModalVisible"
                                :visible="isModalVisible"
                                :formData="formData"
                                :url="url"
                                :addEditType="addEditType"
                                :pageTitle="pageTitle"
                                :successMessage="successMessage"
                                @addEditSuccess="handleSuccess"
                                @closed="handleCloseCustomer"
                                v-on:child-method="updateParent"
                                v-on:close-method="focusEelment"
                            />
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.expense_category')"
                                class="required"
                                :help="
                                    rules.expense_category_id
                                        ? rules.expense_category_id.message
                                        : null
                                "
                                :validateStatus="
                                    rules.expense_category_id ? 'error' : null
                                "
                            >
                                <select
                                    v-model="formData.expense_category_id"
                                    name="expense_category_id"
                                    id="expense_category_id"
                                    :allowClear="true"
                                    optionFilterProp="label"
                                    class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                                    ref="expense_category_id"
                                    @keydown="
                                        focusNext($event, 'expense_category_id')
                                    "
                                    @change="clearError('expense_category_id')"
                                >
                                    <option
                                        v-for="expenseCategory in expenseCategories"
                                        :key="expenseCategory.id"
                                        :value="expenseCategory.id"
                                        :label="expenseCategory.name"
                                    >
                                        {{ expenseCategory.name }}
                                    </option>
                                </select>
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.entry_date')"
                                name="bill_number"
                                :help="
                                    rules.order_date
                                        ? rules.order_date.message
                                        : null
                                "
                                :validateStatus="
                                    rules.order_date ? 'error' : null
                                "
                            >
                                <input
                                    type="date"
                                    v-model="formData.order_date"
                                    class="ant-input css-dev-only-do-not-override-wosfq4"
                                    id="order_date"
                                    required
                                    ref="order_date"
                                    @keydown="focusNext($event, 'order_date')"
                                    @change="clearError('order_date')"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.expense_date')"
                                name="expense_date"
                                :help="
                                    rules.expense_date
                                        ? rules.expense_date.message
                                        : null
                                "
                                :validateStatus="
                                    rules.expense_date ? 'error' : null
                                "
                            >
                                <input
                                    type="date"
                                    v-model="formData.expense_date"
                                    class="ant-input css-dev-only-do-not-override-wosfq4"
                                    id="expense_date"
                                    required
                                    ref="expense_date"
                                    @keydown="focusNext($event, 'expense_date')"
                                    @change="clearError('expense_date')"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.orginal_bill_number')"
                                name="orginalinvoice"
                                :help="
                                    rules.orginal_bill_number
                                        ? rules.orginal_bill_number.message
                                        : null
                                "
                                :validateStatus="
                                    rules.orginal_bill_number ? 'error' : null
                                "
                                class="required"
                            >
                                <input
                                    v-model="formData.orginal_bill_number"
                                    id="orginal_bill_number"
                                    autocomplete="off"
                                    ref="orginal_bill_number"
                                    @input="
                                        formData.orginal_bill_number =
                                            $event.target.value
                                    "
                                    @keydown="
                                        focusNext($event, 'orginal_bill_number')
                                    "
                                    @blur="clearError('orginal_bill_number')"
                                    class="ant-input css-dev-only-do-not-override-wosfq4"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.bill_amount')"
                                name="bill_amount"
                                :help="
                                    rules.bill_amount
                                        ? rules.bill_amount.message
                                        : null
                                "
                                :validateStatus="
                                    rules.bill_amount ? 'error' : null
                                "
                                class="required"
                            >
                                <input
                                    autocomplete="off"
                                    v-model="formData.bill_amount"
                                    id="bill_amount"
                                    ref="bill_amount"
                                    @keydown="focusNext($event, 'bill_amount')"
                                    @keypress="onKeydownchkNumeric"
                                    @blur="clearError('bill_amount')"
                                    class="ant-input css-dev-only-do-not-override-wosfq4"
                                    min="0"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('Taxable Amount')"
                                name="orginalinvoice"
                                :help="
                                    rules.tax_amount
                                        ? rules.tax_amount.message
                                        : null
                                "
                                :validateStatus="
                                    rules.tax_amount ? 'error' : null
                                "
                            >
                                <input
                                    autocomplete="off"
                                    v-model="formData.tax_amount"
                                    id="tax_amount"
                                    ref="tax_amount"
                                    @keydown="focusNext($event, 'tax_amount')"
                                    @keypress="onKeydownchkNumeric"
                                    class="ant-input css-dev-only-do-not-override-wosfq4"
                                    min="0"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.payment_mode')"
                                name="payment_mode_id"
                                class="required"
                                :help="
                                    rules.payment_mode_id
                                        ? rules.payment_mode_id.message
                                        : null
                                "
                                :validateStatus="
                                    rules.payment_mode_id ? 'error' : null
                                "
                            >
                                <select
                                    v-model="formData.payment_mode_id"
                                    :allowClear="true"
                                    optionFilterProp="label"
                                    @keydown="
                                        focusNext($event, 'payment_mode_id')
                                    "
                                    @change="clearError('payment_mode_id')"
                                    class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                                    ref="payment_mode_id"
                                >
                                    <option
                                        v-for="paymentMode in PaymentModes"
                                        :key="paymentMode.id"
                                        :value="paymentMode.id"
                                        :label="paymentMode.name"
                                    >
                                        {{ paymentMode.name }}
                                    </option>
                                </select>
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('expense1.note')"
                                name="notes"
                            >
                                <textarea
                                    v-model="formData.notes"
                                    :rows="6"
                                    @keydown="focusNext($event, 'notes')"
                                    id="notes"
                                    ref="notes"
                                    @input="
                                        formData.notes = $event.target.value
                                    "
                                    class="ant-input css-dev-only-do-not-override-wosfq4"
                                    :tabindex="
                                        formData.tax_type === 'with_gst'
                                            ? 10
                                            : 9
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-form-item
                                :label="$t('expense1.upload')"
                                name="image"
                                id="img"
                            >
                                <UploadExpenses
                                    ref="uploadComponent"
                                    :formData="formData"
                                    folder="expenses"
                                    imageField="logo"
                                    @onFileUploaded="
                                        (file) => {
                                            formData.image = file.file;
                                            formData.image_url = file.file_url;
                                        }
                                    "
                                    :tabindex="
                                        formData.tax_type === 'with_gst'
                                            ? 11
                                            : 10
                                    "
                                />

                                <!-- <Upload
                          :formData="formData"
                          folder="company"
                          imageField="logo"
                          @onFileUploaded="
                            (file) => {
                              formData.logo = file.file;
                              formData.logo_url = file.file_url;
                            }
                          "
                        /> -->
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16" class="mt-20 mb-20">
                        <a-col :xs="24" :sm="24" :md="13" :lg="13"></a-col>
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-button
                                type="button"
                                id="saveF8"
                                ref="savebtn"
                                class="backgrounds"
                                :loading="loading"
                                @click="saveExpensesEntry()"
                                @keydown.enter="focusNext($event)"
                                block
                            >
                                <span class="shortcut" id="shortcut">
                                    <code>F8 / End</code>
                                </span>
                                <span class="savebutton"
                                    >{{ $t("common.save") }} <SaveOutlined />
                                </span>
                            </a-button>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button
                                class="savecolse"
                                @click="onclose"
                                :tabindex="
                                    formData.tax_type === 'with_gst' ? 13 : 12
                                "
                                block
                            >
                                <span class="shortcut">
                                    <code>Esc</code>
                                </span>
                                <span class="savebutton"
                                    >{{ $t("common.close") }}
                                    <svg
                                        height="10px"
                                        width="10px"
                                        viewBox="0 0 611.96 611.96"
                                    >
                                        <path
                                            d="M378.71,306,596.9,87.79a51.43,51.43,0,0,0-72.73-72.73L306,233.25,87.79,15.06A51.43,51.43,0,0,0,15.06,87.79L233.25,306,15.06,524.17a51.43,51.43,0,1,0,72.73,72.73L306,378.71,524.17,596.89a51.43,51.43,0,1,0,72.73-72.73Z"
                                        ></path>
                                    </svg>
                                </span>
                            </a-button>
                        </a-col>
                    </a-row>
                </a-form>
            </a-card>

        </admin-page-table-content>

    </a-spin>
      </div>
</template>

<script>
import { onMounted, ref } from "vue";
import {
    EyeOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    SaveOutlined,
    LoadingOutlined,
    MinusSquareOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import common from "../../../../../common/composable/common";
import Upload from "../../../../../common/core/ui/file/Upload.vue";
import UploadExpenses from "../../../../../common/core/ui/file/UploadExpenses.vue";
import DateTimePicker from "../../../../../common/components/common/calendar/DateTimePicker.vue";
import ParentSearchInput from "../../../../../common/components/ledger/ParentSearchInput.vue";
import AdminPageHeader from "../../../../../common/layouts/AdminPageHeader.vue";
import FormItemHeading from "../../../../../common/components/common/typography/FormItemHeading.vue";
import ExpensePrintModel from "../../../stock-management/purchases/ExpensePrintModel.vue";
import SalesModel from "../../../../../main/views/stock-management/purchases/SalesModel.vue";
import { message, notification } from "ant-design-vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";

export default {
    props: ["username"],
    inject: ["globalVar"],
    components: {
        EyeOutlined,
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        ExclamationCircleOutlined,
        SearchOutlined,
        SaveOutlined,
        LoadingOutlined,
        SalesModel,
        DateTimePicker,
        AdminPageHeader,
        MinusSquareOutlined,
        ParentSearchInput,
        FormItemHeading,
        ExpensePrintModel,
        Upload,
        UploadExpenses,
    },
    setup(props, { emit }) {
        const { loading, rules } = apiAdmin();
        const { formatAmountCurrency } = common();

        let spinning = ref(false);
        let selectedItermIndex = ref("");
        let formData = ref("Unknown");

        formData = {
            order_date: new Date().toISOString().split("T")[0],
            expense_date: new Date().toISOString().split("T")[0],
            tax_type: "",
            image_url: "", //image
            category: null,
            notes: "",
            bill_amount: "",
            bill_number: "",
            customer_name: "",
            orginal_bill_number: "",
            tax_amount: "",
            party_name: "",
            expense_category_id: "",
            payment_mode_id: "",
            party_id: 0,
            selectedExpense: localStorage.getItem("selectedExpense"),

        };

        const { t } = useI18n();

        onMounted(() => {});

        const onSubmit = () => {};

        return {
            formData,
            rules,
            loading,
            onSubmit,
            formatAmountCurrency,
        };
    },
    data() {
        return {
            spinning: false,
            stockDateColor: "",
            newFormData: {
                expense_category_id: null,
                payment_mode_id: null,
            },
            expenseCategories: [],
            PaymentModes: [],
            selectedExpense: localStorage.getItem("selectedExpense"),
            isModalVisible: false,
            expense_date: "",
            isPartyDetailsVisible: true,
            isPrintModalVisible: false,
            taxTypeLayoutValue: 6,
        };
    },

    mounted() {
        // window.addEventListener("keydown", this.handleKeyPress);
        document
      .getElementById("expensecreate")
      .addEventListener("keydown", this.handleKeyPress);
        this.autoFocusInput();
        this.fetchExpenseCategories();
        this.fetchPaymentModes();
        this.getExpenseDetails();
    },


    // beforeDestroy() {
    //     // document.removeEventListener('keydown', this.handleKeyDown);
    // },
    // beforeUnmount() {
    //     window.removeEventListener("keydown", this.handleKeyPress);
    // },


    methods: {
        focusNext(event, currentField) {
            if (event.key === "Enter" || event.key === "Tab") {
                event.preventDefault();

                // Handle tax_type separately
                if (currentField === "tax_type") {
                    this.handleTaxTypeKeydown(event);
                    return;
                }

                // Existing validation and focus logic for other fields
                if (!this.validateField(currentField)) {
                    return;
                }

                const currentElement = event.target;
                const formElements = Array.from(currentElement.form.elements);
                const index = formElements.indexOf(currentElement);
                let nextElement = formElements[index + 1];

                if (nextElement) {
                    if (nextElement.id === "form_item_image") {
                        const uploadInput =
                            this.$refs.uploadComponent.$el.querySelector(
                                'input[type="file"]'
                            );
                        if (uploadInput) {
                            this.$refs.savebtn.focus();
                            uploadInput.click();
                        }
                    } else {
                        nextElement.focus();
                    }
                }
            }
        },

            focusNext(event, currentField) {
          if (event.key === "Enter" || event.key === "Tab") {
            if (event.key === "Enter" || event.key === "Tab") {
              event.preventDefault();

              // Validate the current field before moving to the next
              if (!this.validateField(currentField)) {
                console.log("Validation failed for the current field");
                return;
              }
            }

            const currentElement = event.target;
            const formElements = Array.from(currentElement.form.elements);
            const index = formElements.indexOf(currentElement);
            let nextElement = formElements[index + 1];

            if (this.formData.tax_type === "with_gst") {
              nextElement = formElements[index + 1];
            } else if (this.formData.tax_type === "without_gst") {
              const nextElementCandidate = formElements[index + 3];
              if (
                nextElementCandidate &&
                nextElementCandidate.name === "expense_category_id"
              ) {
                nextElement = nextElementCandidate;
              }
            }

            if (nextElement) {
              if (nextElement.id === "form_item_image") {
                const uploadInput = this.$refs.uploadComponent.$el.querySelector(
                  'input[type="file"]'
                );
                if (uploadInput) {
                  this.$refs.savebtn.focus();
                  uploadInput.click();
                }
              } else {
                nextElement.focus();
              }
            } else {
              console.log("No more fields to focus");
            }
          }
        },

        validateField(fieldName) {
            let isValid = true;

            switch (fieldName) {
                case "party_name":
                    if (
                        !this.formData.party_name ||
                        this.formData.party_name.trim() === ""
                    ) {
                        this.rules.party_name = {
                            message: "This Field is required.",
                        };
                        isValid = false;
                    } else {
                        this.rules.party_name = null;
                    }
                    break;

                case "expense_category_id":
                    if (!this.formData.expense_category_id) {
                        this.rules.expense_category_id = {
                            message: "This Field is required.",
                        };
                        isValid = false;
                    } else {
                        this.rules.expense_category_id = null;
                    }
                    break;

                case "tax_type":
                    if (this.formData.tax_type) {
                        if (
                            !this.formData.tax_type ||
                            this.formData.tax_type.trim() === ""
                        ) {
                            this.rules.tax_type = {
                                message: "This Field is required.",
                            };
                            isValid = false;
                        } else {
                            this.rules.tax_type = null;
                        }
                    } else {
                        this.rules.tax_type = null;
                    }
                    break;

                case "orginal_bill_number":
                    if (
                        !this.formData.orginal_bill_number ||
                        this.formData.orginal_bill_number.trim() === ""
                    ) {
                        this.rules.orginal_bill_number = {
                            message: "This Field is required.",
                        };
                        isValid = false;
                    } else {
                        this.rules.orginal_bill_number = null;
                    }
                    break;

                case "bill_amount":
                    if (!this.formData.bill_amount) {
                        this.rules.bill_amount = {
                            message: "This Field is required.",
                        };
                        isValid = false;
                    } else {
                        this.rules.bill_amount = null;
                    }
                    break;

                case "payment_mode_id":
                    if (!this.formData.payment_mode_id) {
                        this.rules.payment_mode_id = {
                            message: "This Field is required.",
                        };
                        isValid = false;
                    } else {
                        this.rules.payment_mode_id = null;
                    }
                    break;

                default:
                    isValid = true;
            }

            return isValid;
        },

        validateLedger() {
            let isValid = true;
            if (
                this.formData.tax_type !== "without_gst" &&
                (!this.formData.party_name || this.formData.party_name.trim() === "")
            ) {
                this.rules.party_name = {
                    message: "This Field is required.",
                };
                isValid = false;
            } else {
                this.rules.party_name = null;
            }


            if (!this.formData.expense_category_id) {
                this.rules.expense_category_id = {
                    message: "This Field is required.",
                };
                isValid = false;
            } else {
                this.rules.expense_category_id = null;
            }

            if (this.formData.tax_type) {
                if (
                    !this.formData.tax_type ||
                    this.formData.tax_type.trim() === ""
                ) {
                    this.rules.tax_type = {
                        message: "This Field is required.",
                    };
                    isValid = false;
                } else {
                    this.rules.tax_type = null;
                }
            } else {
                this.rules.tax_type = null;
            }

            if (
                !this.formData.orginal_bill_number ||
                this.formData.orginal_bill_number.trim() === ""
            ) {
                this.rules.orginal_bill_number = {
                    message: "This Field is required.",
                };
                isValid = false;
            } else {
                this.rules.orginal_bill_number = null;
            }

            if (!this.formData.bill_amount) {
                this.rules.bill_amount = {
                    message: "This Field is required.",
                };
                isValid = false;
            } else {
                this.rules.bill_amount = null;
            }

            if (!this.formData.payment_mode_id) {
                this.rules.payment_mode_id = {
                    message: "This Field is required.",
                };
                isValid = false;
            } else {
                this.rules.payment_mode_id = null;
            }
            return isValid;
        },

        moveToNextField(event) {
            const elem = event.target;
            if (event.key === "Enter" && elem.value.trim() !== "") {
                const currentIndex = Array.from(elem.form.elements).indexOf(
                    elem
                );
                elem.form.elements
                    .item(
                        currentIndex < elem.form.elements.length - 1
                            ? currentIndex + 1
                            : 0
                    )
                    .focus();
                event.preventDefault();
                console.log(
                    "Moved to the next field after pressing Enter with value."
                );
            } else if (event.key === "Enter") {
                console.log("Field is empty, staying on the current field.");
                event.preventDefault();
            }
        },
        handleVerticalNavigation(event, currentField) {
            // Wait for Vue to update the DOM
            this.$nextTick(() => {
                const fields = this.getOrderedFields();
                const currentIndex = fields.indexOf(currentField);

                if (event.keyCode === 40) {
                    // Down arrow
                    if (currentIndex < fields.length - 1) {
                        const nextField = fields[currentIndex + 1];
                        this.$nextTick(() => this.$refs[nextField].focus());
                    }
                } else if (event.keyCode === 38) {
                    // Up arrow
                    if (currentIndex > 0) {
                        const previousField = fields[currentIndex - 1];
                        this.$nextTick(() => this.$refs[previousField].focus());
                    }
                }
            });
        },

        handleHorizontalNavigation(event, currentField) {
            this.$nextTick(() => {
                const rows = this.getFieldRows();
                let currentRowIndex, currentColIndex;

                // Find the current field in the rows structure
                for (let i = 0; i < rows.length; i++) {
                    const colIndex = rows[i].indexOf(currentField);
                    if (colIndex !== -1) {
                        currentRowIndex = i;
                        currentColIndex = colIndex;
                        break;
                    }
                }

                // Handle right arrow key (move to next field in the same row)
                if (event.keyCode === 39) {
                    // Right arrow
                    if (currentColIndex < rows[currentRowIndex].length - 1) {
                        const nextField =
                            rows[currentRowIndex][currentColIndex + 1];
                        this.$nextTick(() => this.$refs[nextField].focus());
                    }
                }

                // Handle left arrow key (move to previous field in the same row)
                else if (event.keyCode === 37) {
                    // Left arrow
                    if (currentColIndex > 0) {
                        const previousField =
                            rows[currentRowIndex][currentColIndex - 1];
                        this.$nextTick(() => this.$refs[previousField].focus());
                    }
                }
            });
        },
        getOrderedFields() {
            // Define the vertical order of the fields (in a flat array)
            return [
                "tax_type",
                "party_name",
                "expense_category_id",
                "order_date",
                "expense_date",
                "orginal_bill_number",
                "bill_amount",
                "tax_amount",
                "payment_mode_id",
                "notes",
                "uploads",
            ];
        },

        getFieldRows() {
            // Define the rows of fields for horizontal navigation
            return [
                [
                    "tax_type",
                    "party_name",
                    "expense_category_id",
                    "order_date",
                    "expense_date",
                    "orginal_bill_number",
                    "bill_amount",
                    "tax_amount",
                    "payment_mode_id",
                    "notes",
                    "uploads",
                ],
            ];
        },

        focusNextButton() {
            this.$refs.savebtn.focus();
        },
        handleKeyPress(event) {
            if (event.key === "F8" || event.key === "End") {
                event.preventDefault();
                this.saveExpensesEntry();
            } else if (event.key === "Escape") {
                this.onclose();
            }
        },

        showPrintModal() {
            //console.log("Print called");
            this.isPrintModalVisible = true;
        },

        handleClosePrint(input) {
            this.isPrintModalVisible = false;
            if (input == 27) {
                this.focusElement("order_date");
            } else {
                this.isPrintModalVisible = false;
            }
            setTimeout(function () {
                document.getElementById("btn-Ledger").focus();
            }, 100);
        },

        onKeydownchkNumeric(event) {
            const key = event.key;

            if (
                [
                    "Backspace",
                    "Delete",
                    "Tab",
                    "ArrowLeft",
                    "ArrowRight",
                ].includes(key) ||
                (key >= "0" && key <= "9") ||
                key === "."
            ) {
                return; // Allow these keys
            }
            event.preventDefault();
        },
        clearError(field) {
            //alert('1');
            // alert(field);
            this.rules[field] = null;
        },

        handleTaxTypeKeydown(event) {
            if (event.key === "Enter" || event.key === "Tab") {
                event.preventDefault();

                if (!this.formData.tax_type) {
                    return; // Stay on current field if no selection
                }

                // Focus next field based on tax_type value
                if (this.formData.tax_type === "with_gst") {
                    this.$refs.party_name.focus();
                } else if (this.formData.tax_type === "without_gst") {
                    this.$refs.expense_category_id.focus();
                }
            }
        },

        handleTaxTypeChange(event) {
            const taxType = this.formData.tax_type;

            if (taxType === "with_gst") {
                this.isPartyDetailsVisible = true;
                this.taxTypeLayoutValue = 6;
                // Don't focus here - wait for Enter/Tab
            } else if (taxType === "without_gst") {
                this.isPartyDetailsVisible = false;
                this.formData.party_name = "";
                this.taxTypeLayoutValue = 12;
                // Don't focus here - wait for Enter/Tab
            } else {
                this.isPartyDetailsVisible = false;
                this.taxTypeLayoutValue = 12;
            }
        },

        //     handleTaxTypeChange(event) {
        //     const taxType = this.formData.tax_type || "";

        //     if (taxType === "with_gst") {
        //       this.isPartyDetailsVisible = true;
        //       this.taxTypeLayoutValue = 6;
        //     } else if (taxType === "without_gst") {
        //       this.isPartyDetailsVisible = false;
        //       this.formData.party_name = "";
        //       this.taxTypeLayoutValue = 12;
        //     } else if (taxType.trim() === "") {
        //       this.isPartyDetailsVisible = false;
        //       console.log("demooooo");
        //       this.taxTypeLayoutValue = 12;
        //     } else {
        //       this.isPartyDetailsVisible = false;
        //       this.taxTypeLayoutValue = 12;
        //     }

        //     console.log("Tax Type:", taxType);
        //     console.log("isPartyDetailsVisible:", this.isPartyDetailsVisible);
        //     console.log("taxTypeLayoutValue:", this.taxTypeLayoutValue);
        // },

        fetchExpenseCategories() {
            try {
                axiosAdmin
                    .get(`expense-categories?fields=id,xid,name`)
                    .then((response) => {
                        this.expenseCategories = response.data; // Assuming the API returns the expense categories
                    })
                    .catch((error) => {
                        console.error("Error fetching expense details:", error);
                    });
            } catch (error) {
                console.error("Error fetching expense categories:", error);
            }
        },
        fetchPaymentModes() {
            try {
                axiosAdmin
                    .get(`payment-modes?fields=id,xid,name`)
                    .then((response) => {
                        this.PaymentModes = response.data; // Assuming the API returns the expense categories
                    })
                    .catch((error) => {
                        console.error("Error fetching Payment Modes:", error);
                    });
            } catch (error) {
                console.error("Error fetching  Payment Modes:", error);
            }
        },
        getExpenseDetails() {
            var selectedExpense = localStorage.getItem("selectedExpense");
            console.log("selectedExpense inside method", selectedExpense);
            if (selectedExpense != "null") {
                this.spinning = true;
                axiosAdmin
                    .get("expenses1/getExpensesDetails/" + selectedExpense)
                    .then((response) => {
                        // console.log(response);
                        if (response.data.expenseData.party_id != "") {
                            this.formData.party_id =
                                response.data.expenseData.party_id;
                            this.formData.party_name =
                                response.data.partyData.party_name;
                        }

                        this.formData.image = response.data.expenseData.image;
                        this.formData.image_url =
                            response.data.expenseData.image_url;
                        this.formData.orginal_bill_number =
                            response.data.expenseData.orginal_bill_number;
                        this.formData.bill_amount =
                            response.data.expenseData.bill_amount;
                        this.formData.tax_amount =
                            response.data.expenseData.tax_amount;
                        this.formData.payment_mode_id =
                            response.data.expenseData.payment_mode_id;
                        this.formData.notes = response.data.expenseData.notes;
                        this.formData.order_date =
                            response.data.expenseData.order_date;
                        this.formData.expense_date =
                            response.data.expenseData.expense_date;
                        this.formData.tax_type =
                            response.data.expenseData.tax_type;
                        this.isPartyDetailsVisible =
                            this.formData.tax_type === "with_gst";
                        if (this.formData.tax_type === "with_gst") {
                            this.taxTypeLayoutValue = 6;
                        } else {
                            this.taxTypeLayoutValue = 12;
                        }

                        this.formData.expense_category_id =
                            response.data.expenseData.expense_category_id;
                        this.spinning = false;
                    })
                    .catch((errorResponse) => {
                        this.spinning = false;
                    });
            }
        },
        formatNumber(num) {
            return parseFloat(num).toFixed(2);
        },
        toggleDropdowns() {
            this.isOpensave = !this.isOpensave;
        },
        handleEnterKey(event) {
            // Check if Enter or Space key is pressed
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault(); // Prevent default scrolling behavior for the Space key
                this.toggleDropdowns();
            }
        },
        onlyForCurrency($event) {
            // ////console.log($event.keyCode); //keyCodes value
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;

            // only allow number and one dot
            if (
                (keyCode < 48 || keyCode > 57) &&
                (keyCode !== 46 || this.price.indexOf(".") != -1)
            ) {
                // 46 is dot
                $event.preventDefault();
            }

            // restrict to 2 decimal places
            if (
                this.price != null &&
                this.price.indexOf(".") > -1 &&
                this.price.split(".")[1].length > 1
            ) {
                $event.preventDefault();
            }
        },
        handleFocus() {
            console.log("handlefocus");
            this.$nextTick(() => {
                const firstInput = document.getElementById(
                    "form_item_party_name"
                );
                if (firstInput) {
                    firstInput.focus();
                    var that = this;
                    setTimeout(() => {
                        if (that.selectedExpense == "null") {
                            this.showModal();
                        }
                    }, 600);
                }
            });
        },
        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.taxtype.focus();
            });
        },
        handleBlur() {
            ////console.log('Focus removed from input');
            this.showMessage = true;
        },
        customerhandleKeydown(event) {
            // var that = this;
            //alert('test');
            console.log(this.isModalVisible);

            this.isModalVisible = true;
            console.log(this.isModalVisible);
        },
        handleKeyDown(event) {
            console.log(event.key);
            if (event.key === "Escape" || event.keyCode === 27) {
                this.isModalVisible = false;
                this.autoFocusInput();
            } else if (event.key === "F8") {
                this.saveExpensesEntry();
            } else if (
                (event.keyCode === 13 || event.keyCode === 9) &&
                this.formData.party_id > 0
            ) {
                this.isModalVisible = false;
                document
                    .getElementById(
                        "item_product_name_" + this.selectedItermIndex
                    )
                    .focus();
                ////console.log("sele ",this.selectedItermIndex)
            } else if (
                event.keyCode !== 13 &&
                event.keyCode !== 9 &&
                this.formData.party_id > 0
            ) {
                this.isModalVisible = true;
            }
        },

        handleKeydownDatePicker(event) {
            ////console.log("Date picker event",event.target.value);
            console.log("event.keyCode ", event.keyCode);
            this.recentEventId = event;
            if (event.keyCode == 27) {
                this.showconfirm();
            } else if (
                (event.key === "Tab" || event.key === "Enter") &&
                event.target.value == ""
            ) {
                this.formData.order_date = new Date()
                    .toISOString()
                    .split("T")[0];

                document.getElementById("form_item_order_date").value =
                    this.formData.order_date;
                //ssss this.$refs.dateInput.popupVisible = false
                event.preventDefault();
                return false;
                //this.handleFocus();
            } else {
                this.handleFocus();
            }
        },

        inputData($event) {
            // Value will update reactively
            $event.target.value;
        },

        getCurrentDate() {
            return new Date().toISOString().split("T")[0];
        },
        showModal() {
            this.isModalVisible = true;
        },

        handleCloseCustomer() {
            this.isModalVisible = false;
            this.$refs.expense_category_id.focus();
            this.spinning = false;
        },

        handleSuccess(xid) {
            this.isModalVisible = false;
        },
        updateParent(selectedParty) {
            this.formData.party_id = selectedParty.id.toString();
            this.formData.party_name = selectedParty.name;
            document.getElementById("party_id").value = this.formData.party_id;
            document.getElementById("form_item_name").value =
                this.formData.name;
            document.getElementById("form_item_party_name").value =
                this.formData.party_name;
        },
onclose() {
  console.log("Esc called");
  let that = this;

  const modal = Modal.confirm({
    title: "Confirmation",
    icon: createVNode(ExclamationCircleOutlined),
    content: "Transaction data will be lost. Are you sure you want to close?",
    onOk() {
      window.removeEventListener("keydown", handleKeydown);

      window.removeEventListener("keydown", that.handleKeyPress);
      window.removeEventListener("keyup", that.handleKeyPress);

      that.$router.push({
        name: "admin.expenses1.index",
      });
    },
    onCancel() {
      window.removeEventListener("keydown", handleKeydown);

      that.$refs.taxtype && that.$refs.taxtype.focus();
    },
    okText: "OK",
    cancelText: "Cancel",
    autoFocusButton: "cancel",
  });

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

      if (e.key === "ArrowRight" && okBtn) {
        okBtn.focus();
      }

      if (e.key === "ArrowLeft" && cancelBtn) {
        cancelBtn.focus();
      }
    }
  };

  window.addEventListener("keydown", handleKeydown);
},

        changeColorOnFocus(inputField) {
            if (inputField === "tax_type") {
                this.stockDateColor = "#ffd451";
            }
        },
        resetColorOnBlur(inputField) {
            if (inputField === "tax_type") {
                this.stockDateColor = "";
            }
        },
        focusinputvalue(event) {
            event.target.select();
        },
        formatCurrency(value) {
            return value.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },
        formatOfAmount(value) {
            var res = new Intl.NumberFormat("en-IN").format(value);
            return res;
        },
        revertFormattedNumber(formattedValue) {
            return parseFloat(formattedValue.replace(/,/g, ""));
        },
        success(response) {
            this.loading = false;
            if (response[0] == 201) {
                notification.success({
                    placement: "bottomRight",
                    message: response.message,
                    //description: response.message
                });
                this.isPrintModalVisible = true;
            }
            if (response[0] == 202) {
                notification.error({
                    placement: "bottomRight",
                    message: response.message,
                    //description: response.message
                });
            }
        },

        saveExpensesEntry(event) {
            if (this.validateLedger()) {
                // console.log('submit', event);
                this.spinning = true;
                axiosAdmin
                    .post("expenses1/expenseSave", this.formData)
                    .then((response) => {
                        this.spinning = false;
                        this.success(response);

                        setTimeout(() => {
                            this.$router.push({
                                name: "admin.expenses1.index",
                            });
                        }, 500);
                    })

                    .catch((errorResponse) => {
                        this.spinning = false;
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
                    });
            }
        },

        // form validation//
    },
    computed: {},
};
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
    margin-left: 6px !important;
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
</style>
