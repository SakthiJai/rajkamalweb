<template>
    <a-row>
        <a-col :span="24">
            <div class="table-responsive" ref="tableWrapper" @keydown.enter.prevent="focusNextInput">
                <a-table
                    :columns="settleInvoiceColumns"
                    :row-key="(record) => record.xid"
                    :data-source="invoices"
                    :pagination="false"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'date'">
                            {{ formatDate(record.order_date) }}
                        </template>
                        <template v-if="column.dataIndex === 'amount'">
                            {{ formatAmountCurrency(record.total) }} <br />
                            <small>
                                <a-typography-text type="danger">
                                    ({{ $t("common.pending") }}:
                                    {{ formatAmountCurrency(record.due_amount) }})
                                </a-typography-text>
                            </small>
                        </template>
                        <template v-if="column.dataIndex === 'action'">
                            <a-input-number
                                :addonBefore="appSetting.currency.symbol"
                                v-model:value="record.paying_amount"
                                placeholder="0"
                                :min="0"
                                @blur="inputValueChanged(record)"
                            />
                        </template>
                    </template>
                    <template #summary>
                        <a-table-summary fixed>
                            <a-table-summary-row>
                                <a-table-summary-cell :col-span="3">
                                    <a-typography-text strong>
                                        {{ $t("payments.unused_amount") }} :
                                    </a-typography-text>
                                    {{
                                        formatAmountCurrency(
                                            unUsedAmount ? unUsedAmount : 0
                                        )
                                    }}
                                </a-table-summary-cell>
                                <a-table-summary-cell :col-span="2">
                                    {{
                                        formatAmountCurrency(
                                            setteledAmount ? setteledAmount : 0
                                        )
                                    }}
                                </a-table-summary-cell>
                            </a-table-summary-row>
                        </a-table-summary>
                    </template>
                </a-table>
                <br />
            </div>
        </a-col>
    </a-row>
</template>
<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import { forEach } from "lodash-es";
import apiAdmin from "../../../../common/composable/apiAdmin";
import common from "../../../../common/composable/common";
import fields from "./fields";

export default defineComponent({
    props: ["userId", "amount"],
    setup(props, { emit }) {
        const { settleInvoiceColumns, paymentType } = fields();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { formatDate, disabledDate, appSetting, formatAmountCurrency } = common();
        const invoices = ref([]);
        const setteledAmount = ref(0);
        const unUsedAmount = ref(0);

        onMounted(() => {
            getInvoices();
        });

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

        const getInvoices = () => {
            axiosAdmin
                .post("user-invoices", {
                    user_id: props.userId,
                    payment_type: paymentType.value,
                })
                .then((response) => {
                    const allInvoices = response.data.invoices;
                    var enteredAmount = props.amount;
                    var setteledAmt = 0;

                    forEach(allInvoices, (allInvoice) => {
                        if (allInvoice.due_amount <= enteredAmount) {
                            allInvoice.paying_amount = allInvoice.due_amount;
                            enteredAmount =
                                parseFloat(enteredAmount) -
                                parseFloat(allInvoice.due_amount);
                            setteledAmt =
                                parseFloat(setteledAmt) +
                                parseFloat(allInvoice.due_amount);
                        } else if (allInvoice.due_amount > enteredAmount) {
                            allInvoice.paying_amount = enteredAmount;
                            setteledAmt =
                                parseFloat(setteledAmt) + parseFloat(enteredAmount);
                            enteredAmount = 0;
                        }
                    });

                    setteledAmount.value = setteledAmt;
                    unUsedAmount.value =
                        parseFloat(props.amount) - parseFloat(setteledAmt);
                    invoices.value = allInvoices;
                });
        };
        const tableWrapper = ref(null);

        const focusNextInput = (event) => { 
            const inputs = tableWrapper.value.querySelectorAll("input.ant-input-number-input");

            const index = Array.from(inputs).indexOf(event.target);
            if (index !== -1 && index < inputs.length - 1) { 
                inputs[index + 1].focus();
                inputs[index + 1].select?.();  
            } else { 
        emit("lastFieldEnter");
    }
        };

        const inputValueChanged = (record) => {
            const newValues = invoices.value;
            var totalEnteredAmount = 0;

            forEach(newValues, (newValue) => {
                totalEnteredAmount += parseFloat(newValue.paying_amount);
            });

            if (record.paying_amount > record.due_amount) {
                record.paying_amount = record.due_amount;
            } else if (totalEnteredAmount > props.amount) {
                record.paying_amount =
                    parseFloat(props.amount) -
                    (totalEnteredAmount - parseFloat(record.paying_amount));
            }

            if (!record.paying_amount) {
                record.paying_amount = 0;
            }

            var newtotalEnteredAmount = 0;
            forEach(newValues, (newValue) => {
                newtotalEnteredAmount += parseFloat(newValue.paying_amount);
            });
            setteledAmount.value = newtotalEnteredAmount;
            unUsedAmount.value =
                parseFloat(props.amount) - parseFloat(newtotalEnteredAmount);
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        watch(props, (newVal, oldVal) => {
            getInvoices();
        });

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            invoices,

            settleInvoiceColumns,
            setteledAmount,
            unUsedAmount,
            inputValueChanged,

            appSetting,
            formatDate,
            disabledDate,
            formatAmountCurrency,
        };
    },
});
</script>
