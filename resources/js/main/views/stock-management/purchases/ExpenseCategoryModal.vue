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
                <span>GST Invoice Filters</span>
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
                        :label="$t('expense_category.multi_series')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-select v-model:value="formData.business_type">
                            <a-select-option key="1" value="1">
                                Default
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Default
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <!-- Bill Number From -->
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.bill_number_from')"
                        name="billNumberFrom"
                        :help="
                            rules.billNumberFrom
                                ? rules.billNumberFrom.message
                                : null
                        "
                        :validateStatus="rules.billNumberFrom ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input v-model="formData.billNumberFrom" />
                    </a-form-item>
                </a-col>

                <!-- Bill Number To -->
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.bill_number_to')"
                        name="billNumberTo"
                        :help="
                            rules.billNumberTo
                                ? rules.billNumberTo.message
                                : null
                        "
                        :validateStatus="rules.billNumberTo ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input v-model="formData.billNumberTo" />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.customer_name')"
                        name="customerName"
                        :help="
                            rules.customerName
                                ? rules.customerName.message
                                : null
                        "
                        :validateStatus="rules.customerName ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input v-model:value="formData.customerName" />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.customer_mobile_number')"
                        name="customerMobileNumber"
                        :help="
                            rules.customerMobileNumber
                                ? rules.customerMobileNumber.message
                                : null
                        "
                        :validateStatus="
                            rules.customerMobileNumber ? 'error' : null
                        "
                        class="required compact-form-item"
                    >
                        <a-input
                            v-model:value="formData.customerMobileNumber"
                            @input="onInputMobileNumber"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.status')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-select v-model:value="formData.status">
                            <a-select-option selected key="1" value="1">
                                All
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Pending
                            </a-select-option>
                            <a-select-option key="3" value="3">
                                Paid Partial
                            </a-select-option>
                            <a-select-option key="4" value="4">
                                Partial
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.user_name')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-select v-model:value="formData.username">
                            <a-select-option selected key="1" value="1">
                                All Users
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.station')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-select v-model:value="formData.station">
                            <a-select-option selected key="1" value="1">
                                All Station
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('expense_category.print')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-select v-model:value="formData.print">
                            <a-select-option selected key="1" value="1">
                                All
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Pending
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('expense_category.sale_above')"
                        name="gstin"
                        :help="rules.gstin ? rules.gstin.message : null"
                        :validateStatus="rules.gstin ? 'error' : null"
                        class="required compact-form-item"
                    >
                        <a-input v-model:value="formData.gstin" />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>

        <template #footer>
            <button
                @click="onSubmit"
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

            <button
                type="button"
                id="btn-Ledger"
                title="Ledger"
                @click="resetForm"
                class="btn default-btn ng-star-inserted"
            >
                <span class="box">
                    <span class="ng-star-inserted">Reset</span>
                </span>
                <span class="effect"></span>
            </button>

            <button
                type="button"
                id="btn-Ledger"
                title="Ledger"
                @click="onClose"
                class="btn default-btn ng-star-inserted"
            >
                <span class="box"
                    ><span class="shortcut ng-star-inserted"
                        ><code>Esc</code></span
                    ><span class="ng-star-inserted">Close</span>
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
                        ></path></svg></span
                ><span class="effect"></span>
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
    business_type: "2",
    props: [
        "formData",
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
            formData: {
                party: "1",
                status: "1",
                username: "1",
                station: "1",
                print: "1",
                // Set the default value to '2'
            },
            formData: {
                billNumberFrom: "", // Separate field for "Bill Number From"
                billNumberTo: "", // Separate field for "Bill Number To"
            },
            rules: {
                billNumberFrom: null,
                billNumberTo: null,
            },
        };
    },

    methods: {
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

        onInputMobileNumber(event) {
            let value = event.target.value;
            value = value.replace(/[^0-9]/g, "");
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            this.formData.customerMobileNumber = value;
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
