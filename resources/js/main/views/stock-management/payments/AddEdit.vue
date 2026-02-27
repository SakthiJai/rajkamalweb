<template>
    <a-drawer
        :title="pageTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="onClose"
        @afterOpenChange="onAfterOpenChange"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('payments.user')"
                        name="user_id"
                        :help="rules.user_id ? rules.user_id.message : null"
                        :validateStatus="rules.user_id ? 'error' : null"
                        class="required"
                    >
                        <a-select
                        ref="firstInputRef"
                        @keydown="handleEnterNavigation"
                        @keyup.enter="handleUserEnter"
                            v-model:value="newFormData.user_id"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('payments.user'),
                                ])
                            "
                            :allowClear="true"
                            option-label-prop="label"
                            optionFilterProp="label"
                            show-search
                        >
                            <a-select-option
                                v-for="user in users"
                                :key="user.xid"
                                :value="user.xid"
                                :label="user.name"
                            >
                                <UserInfo :user="user" size="small" />
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16" v-if="addEditType == 'add'">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('payments.amount')"
                        name="amount"
                        :help="rules.amount ? rules.amount.message : null"
                        :validateStatus="rules.amount ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="amountInputRef"
                            @keydown="handleEnterNavigation"
                            @keyup.enter="handleAmountEnter"
                            :prefix="appSetting.currency.symbol"
                            v-model:value="newFormData.amount"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('payments.amount'),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('payments.date')"
                        name="date"
                        :help="rules.date ? rules.date.message : null"
                        :validateStatus="rules.date ? 'error' : null"
                        class="required"
                    >
                    <div ref="dateWrapper" >
                        <DateTimePicker
                            ref="dateRef"
                            :dateTime="newFormData.date"
                            @dateTimeChanged="(val) => (newFormData.date = val)"
                            @enterPressed="focusPaymentMode"
                        />
                        </div>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('payments.payment_mode')"
                        name="payment_mode_id"
                        :help="
                            rules.payment_mode_id
                                ? rules.payment_mode_id.message
                                : null
                        "
                        :validateStatus="rules.payment_mode_id ? 'error' : null"
                        class="required"
                    >
                        <span style="display: flex">
                            <a-select
                                ref="paymentModeRef"
                                @keyup.enter="handlePaymentModeEnter"
                                v-model:value="newFormData.payment_mode_id"
                                :placeholder="
                                    $t('common.select_default_text', [
                                        $t('payments.payment_mode'),
                                    ])
                                "
                                :allowClear="true"
                            >
                                <a-select-option
                                    v-for="paymentMode in paymentModes"
                                    :key="paymentMode.xid"
                                    :value="paymentMode.xid"
                                >
                                    {{ paymentMode.name }}
                                </a-select-option>
                            </a-select>
                            <PaymentModeAddButton
                                @onAddSuccess="paymentModeAdded"
                            />
                        </span>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('payments.notes')"
                        name="notes"
                        :help="rules.notes ? rules.notes.message : null"
                        :validateStatus="rules.notes ? 'error' : null"
                    >
                        <a-textarea
                            ref="notesRef"
                            @keydown="handleEnterNavigation"
                            @keyup.enter="focusSubmit"
                            v-model:value="newFormData.notes"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('payments.notes'),
                                ])
                            "
                            :auto-size="{ minRows: 2, maxRows: 3 }"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-divider class="mt-0" />

            <div v-if="addEditType == 'add'">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-form-item>
                            <a-typography-paragraph type="warning" strong>
                                <blockquote>
                                    {{
                                        $t(
                                            "payments.settle_invoice_using_payment"
                                        )
                                    }}
                                </blockquote>
                            </a-typography-paragraph>
                        </a-form-item>
                    </a-col>
                </a-row>
                <SettleInvoices
                    v-if="newFormData.user_id"
                    ref="settleInvoiceRef"
                    :userId="newFormData.user_id"
                    :amount="newFormData.amount"
                    @lastFieldEnter="focusSubmit"
                />
            </div>
            <div v-else></div>
        </a-form>
        <template #footer>
            <a-space>
                <a-button
                    key="submit"
                    type="primary"
                    :loading="loading"
                    @click="onSubmit"
                    ref="submitBtnRef"
                    style="background-color: #1f6d70;"
                >
                    <template #icon>
                        <SaveOutlined />
                    </template>
                    {{
                        addEditType == "add"
                            ? $t("common.create")
                            : $t("common.update")
                    }} / F8
                </a-button>
                <a-button key="back" @click="onClose">
                    {{ $t("common.cancel") }} / Esc
                </a-button>
            </a-space>
        </template>
    </a-drawer>
</template>
<script>
import { defineComponent, onMounted, nextTick, onUnmounted, ref, watch } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import { forEach } from "lodash-es";
import apiAdmin from "../../../../common/composable/apiAdmin";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import common from "../../../../common/composable/common";
import PaymentModeAddButton from "../../settings/payment-modes/AddButton.vue";
import SettleInvoices from "./SettleInvoices.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DateTimePicker.vue";

export default defineComponent({
    props: [
        "formData",
        "data",
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

        UserInfo,
        PaymentModeAddButton,
        SettleInvoices,
        DateTimePicker,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { disabledDate, appSetting, formatAmountCurrency, dayjs } =
            common();
        const paymentModes = ref([]);
        const users = ref([]);
        const usersUrl = "customer-suppliers";
        const paymentModesUrl = "payment-modes?limit=10000";
        const settleInvoiceRef = ref(null);
        const newFormData = ref({});
        const firstInputRef = ref(null);
        const amountInputRef = ref(null);
        const dateWrapper = ref(null);
        const paymentModeRef = ref(null);
        const notesRef = ref(null);
        const submitBtnRef = ref(null);
        const handleGlobalKeydown = (event) => { 
            if (event.key === "F8") {
                event.preventDefault();
                onSubmit();
            }
        
            if (event.key === "Escape") {
                event.preventDefault();
                onClose();
            }
        };
        const initEmptyFormData = () => ({
            user_id: null,
            amount: null,
            date: dayjs(),
            payment_mode_id: null,
            notes: "",
        });
        const focusDate = () => {
            nextTick(() => {
                const input = dateWrapper.value?.querySelector("input");
                input?.focus();
            });
        };
        const handleAmountEnter = () => {
            const amount = newFormData.value.amount;

            if (!amount || Number(amount) <= 0) return;

            focusDate();
        };
        const focusPaymentMode = () => {
            nextTick(() => {
                paymentModeRef.value?.focus?.();
            });
        };
        const handlePaymentModeEnter = () => {
            if (!newFormData.value.payment_mode_id) return;
            focusNotes();
        };

        const focusNotes = () => {
            nextTick(() => {
                notesRef.value?.focus?.();
            });
        };

        const focusSubmit = () => {
            nextTick(() => {
                submitBtnRef.value?.focus?.();
            });
        };

        const handleUserEnter = () => {
            if (!newFormData.value.user_id) return;

            nextTick(() => {
                amountInputRef.value?.focus();
            });
        };
        const handleEnterNavigation = (event) => {
            if (event.key !== "Enter") return;

            event.preventDefault();
            event.stopPropagation();

            const name = event.target.getAttribute("name");

            let isValid = true;

            switch (name) {
                case "user_id":
                    isValid = !!newFormData.value.user_id;
                    break;

                case "amount":
                    isValid = !!newFormData.value.amount && Number(newFormData.value.amount) > 0;
                    break;

                case "notes":
                    isValid = true; 
                    break;

                default:
                    isValid = event.target.value?.trim();
            }

            if (!isValid) return; 

            const inputs = document.querySelectorAll(
                ".ant-drawer input:not([disabled]), .ant-drawer textarea:not([disabled])"
            );

            const index = Array.from(inputs).indexOf(event.target);

            if (index !== -1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
                return;
            }

            submitBtnRef.value?.focus?.();
        };
        onMounted(async () => {
            try {
                const [usersResponse, paymentModesResponse] = await Promise.all([
                    axiosAdmin.post(usersUrl),
                    axiosAdmin.get(paymentModesUrl),
                ]);
                users.value = usersResponse.data;
                paymentModes.value = paymentModesResponse.data;
            } catch (error) {
                console.error("Error fetching users or payment modes:", error);
            }
        });
        const onSubmit = () => {
            const invoices = [];

            if (
                props.addEditType == "add" &&
                settleInvoiceRef.value &&
                settleInvoiceRef.value.invoices
            ) {
                forEach(settleInvoiceRef.value.invoices, (invoice) => {
                    invoices.push({
                        order_id: invoice.xid,
                        amount: invoice.paying_amount,
                    });
                });
            }
                        let finalUrl = props.url;

            if (props.addEditType === "add" || !finalUrl) {
                finalUrl = "payment-in";
            }

            addEditRequestAdmin({
                url: finalUrl,
                method: props.addEditType === "edit" ? "put" : "post",
                data: { ...newFormData.value, invoices },
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };
        const onAfterOpenChange = (open) => {
            if (!open) return;

            nextTick(() => {
                setTimeout(() => { 
                    firstInputRef.value?.focus?.();
 
                    const input =
                        firstInputRef.value?.$el?.querySelector("input");

                    input?.focus();
                }, 150);  
            });
        };
        const paymentModeAdded = () => {
            axiosAdmin.get("payment-modes?limit=10000").then((response) => {
                paymentModes.value = response.data;
            });
        };
        const onClose = () => {
            const confirmExists = document.querySelector(".ant-modal-confirm");
            if (confirmExists) return;

            Modal.confirm({
                title: "Confirmation",
                content: "Payment data will be lost. Are you sure you want to close?",
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",
                onOk() {
                    rules.value = {};
                    emit("closed");
                },
            });
        };
        watch(
            () => props.visible,
            (isVisible) => {
                if (isVisible) {
                    window.addEventListener("keydown", handleGlobalKeydown);

                    rules.value = {};

                    if (props.addEditType === "add") {
                        newFormData.value = { ...initEmptyFormData() };
                    }

                    if (props.addEditType === "edit" && props.data) {
                        newFormData.value = {
                            user_id: props.data.x_user_id,
                            amount: props.data.amount,
                            date: dayjs(props.data.date),
                            payment_mode_id: props.data.x_payment_mode_id,
                            notes: props.data.notes || "",
                        };
                    }
                } else {
                    window.removeEventListener("keydown", handleGlobalKeydown);
                }
            },
            { immediate: true }
        );


        onUnmounted(() => {
            window.removeEventListener("keydown", handleGlobalKeydown);
        });
        return {
            loading,
            rules,
            onClose,
            onSubmit,
            users,
            paymentModes,
            paymentModeAdded,
            settleInvoiceRef,
            firstInputRef,
            appSetting,
            disabledDate,
            formatAmountCurrency,
            amountInputRef,
            handleUserEnter,
             focusDate,
            focusNotes,
            focusSubmit,
            paymentModeRef,
            notesRef,
            submitBtnRef,
            dateWrapper,
            onAfterOpenChange,
            handleAmountEnter,
            handlePaymentModeEnter,
            handleUserEnter,
            handleEnterNavigation,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",

            newFormData,
        };
    },
});
</script>
