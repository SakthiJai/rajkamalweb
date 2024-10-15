<template>
    <a-modal
        :open="visible"
        :closable="false"
        @close="handleClose"
        :centered="true"
        :class="popup"
        @ok="onSubmit"
        @keydown.esc="onClose"
    >
        <template v-slot:title>
            <div
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                "
            >
                <span>Add Bank Account</span>
            </div>
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
        </template>

        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('cash_bank.account_name')"
                        name="account_name"
                        :help="
                            rules.account_name
                                ? rules.account_name.message
                                : null
                        "
                        :validateStatus="rules.account_name ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model:value="formDataLedger.account_name"
                            placeholder="ex: Account Name"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <!-- opeing balance From -->
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.opening_balance')"
                        name="openingBalance"
                        :help="
                            rules.opening_balance
                                ? rules.opening_balance.message
                                : null
                        "
                        :validateStatus="rules.opening_balance ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model="formDataLedger.opening_balance"
                            @input="onInputOpeningBalance"
                            placeholder="ex: ₹10,000"
                        />
                    </a-form-item>
                </a-col>
                <!-- date  -->
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.as_of_date')"
                        name="as_of_date"
                        :help="
                            rules.as_of_date ? rules.as_of_date.message : null
                        "
                        :validateStatus="rules.as_of_date ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input
                            type="date"
                            v-model:value="formDataLedger.as_of_date"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <!-- //toogle swtich goes heere ji -->
            <div
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-weight: 600;
                    font-size: 15px;
                    margin-top: 10px;
                    border-top: 1px solid #e8e8e8;
                    padding-top: 15px;
                    background-color: #ffff;
                "
            >
                <span>Add Bank Details</span>
                <a-switch v-model="showBankDetails" @change="onSwitchChange" />
            </div>

            <!-- <div v-if="showBankDetails"> -->
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.bank_account_number')"
                        name="bankAccountNumber"
                        :help="
                            rules.bank_account_number
                                ? rules.bank_account_number.message
                                : null
                        "
                        :validateStatus="
                            rules.bank_account_number ? 'error' : null
                        "
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model:value="formDataLedger.bank_account_number"
                            @input="onInputAccountNumber"
                            placeholder="ex: 123456789157950"
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.re_enter_bank_account_number')"
                        name="reEnterBankAccountNumber"
                        :help="
                            rules.re_enter_bank_account_number
                                ? rules.re_enter_bank_account_number.message
                                : null
                        "
                        :validateStatus="
                            rules.re_enter_bank_account_number ? 'error' : null
                        "
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model:value="
                                formDataLedger.re_enter_bank_account_number
                            "
                            @input="onInputReEnterAccountNumber"
                            placeholder="ex: 123456789157950"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.ifsc_code')"
                        name="ifscCode"
                        :help="rules.ifsc_code ? rules.ifsc_code.message : null"
                        :validateStatus="rules.ifsc_code ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model:value="formDataLedger.ifsc_code"
                            @input="onInputIfscCode"
                            placeholder="ex: HDFC000075"
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.bank_&_branch_name')"
                        name="branchName"
                        :help="
                            rules.bank_branch_name
                                ? rules.bank_branch_name.message
                                : null
                        "
                        :validateStatus="
                            rules.bank_branch_name ? 'error' : null
                        "
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model:value="formDataLedger.bank_branch_name"
                            @input="text"
                            placeholder="ex: HDFC, Old Madras"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.account_holders_name')"
                        name="accountHoldersName"
                        :help="
                            rules.account_holder_name
                                ? rules.account_holder_name.message
                                : null
                        "
                        :validateStatus="
                            rules.account_holder_name ? 'error' : null
                        "
                        class="required compact-form-item"
                        ><a-input
                            v-model:value="formDataLedger.account_holder_name"
                            @input="text"
                            placeholder="ex: Elisa wolf"
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('cash_bank.upi_id')"
                        name="upiID"
                        :help="rules.upi_id ? rules.upi_id.message : null"
                        :validateStatus="rules.upi_id ? 'error' : null"
                        class="compact-form-item"
                    >
                        <a-input
                            v-model:value="formDataLedger.upi_id"
                            @input="text"
                            placeholder="ex: elisa@okhdfc"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <!-- </div> -->
        </a-form>

        <template #footer>
            <button
                type="button"
                id="btn-Ledger"
                title="Ledger"
                @click="onClose"
                class="btn default-btn ng-star-inserted"
            >
                <span class="box">
                    <span class="ng-star-inserted">Cancel</span> </span
                ><span class="effect"></span>
            </button>

            <button
                @click="onSubmitLedger"
                type="submit"
                id="btn-Ledger"
                title="Ledger"
                class="btn default-btn ng-star-inserted gst"
            >
                <span class="box">
                    <span class="ng-star-inserted">Submit</span>
                </span>
                <span class="effect"></span>
            </button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";

export default defineComponent({
    props: [
        "formDataLedger",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "onClose",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        StaffMemberAddButton,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        const cashbank = (formDataLedger) => {
            addEditRequestAdmin({
                url: `cashbank`,
                data: formDataLedger,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        return {
            loading,
            rules,
            onClose,
            cashbank,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
    data() {
        return {
            showBankDetails: true, // " ji " turn of thie and inport switch its working
            formDataLedger: {
                account_name: "",
                opening_balance: "",
                as_of_date: "",
                bank_account_number: "",
                re_enter_bank_account_number: "",
                ifsc_code: "",
                bank_branch_name: "",
                account_holder_name: "",
                upi_id: "",
            },
        };
    },

    methods: {
        // toggleBankDetails() {
        //     this.showBankDetails = !this.showBankDetails;
        // },

        onSwitchChange() {
            console.log("Switch toggled", this.showBankDetails);
        },

        async onSubmitLedger() {
            try {
                this.cashbank(this.formDataLedger);
            } catch (error) {
                this.$message.error(this.$t("company.updateFailed"));
                console.error("Form validation failed");
            }
        },

        onClose() {
            this.isModalVisible = false;
        },

        handleSuccess(xid) {
            this.isModalVisible = false;
            document.removeEventListener("keydown", this.handleKeydown);
            console.log("Success:", xid);
        },
        handleKeydown(event) {
            if (event.key === "Escape") {
                this.handleClose();
            }
        },

        mounted() {
            window.addEventListener("keydown", this.handleKeydown);
        },
        beforeDestroy() {
            window.removeEventListener("keydown", this.handleleKeydown);
        },

        resetForm() {
            if (this.$refs.myForm1) {
                this.$refs.myForm1.resetFields();
            }
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
        handlecashclose() {
            this.isBankAccountModalVisible = false;
        },
        onInputAccountNumber(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 16) {
                value = value.slice(0, 16);
            }
            this.formDataLedger.bank_account_number = value;
        },
        onInputReEnterAccountNumber(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 16) {
                value = value.slice(0, 16);
            }
            this.formDataLedger.re_enter_bank_account_number = value;
        },

        onInputIfscCode(event) {
            let value = event.target.value.toUpperCase();
            value = value.replace(/[^A-Z0-9]/g, "");
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            this.formDataLedger.ifsc_code = value;
        },
        onInputOpeningBalance(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formDataLedger.opening_balance = value;
        },
    },
});
</script>

<style>
.compact-form-item {
    margin-bottom: 8px;
}

.compact-form-item .ant-form-item-label {
    margin-bottom: 4px;
}
.compact-form-item .ant-form-item-control {
    margin-top: 0;
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

#ledger {
    display: flex !important;
    flex-flow: revert !important;
    min-width: 0 !important;
}

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

.gst {
    background-color: #1f6d70 !important;
    color: white !important;
}

button.btn {
    padding: 4px 7px !important;
}
</style>
