<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="12">
                    <a-form-item
                        :label="$t('Account Number')"
                        name="account_number"
                        :help="
                            rules.account_number
                                ? rules.account_number.message
                                : null
                        "
                        :validateStatus="rules.account_number ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.account_number"
                            :placeholder="
                                $t('common.placeholder_default_text', [$t('')])
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="24" :lg="12">
                    <a-form-item
                        :label="$t('Accountant Name')"
                        name="accountant_name"
                        :help="
                            rules.accountant_name
                                ? rules.accountant_name.message
                                : null
                        "
                        :validateStatus="rules.accountant_name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.accountant_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [$t('')])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <!-- IFSC Code and Account Number -->
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="12">
                    <a-form-item
                        :label="$t('IFSC Code ')"
                        name="ifsc_code"
                        :help="rules.ifsc_code ? rules.ifsc_code.message : null"
                        :validateStatus="rules.ifsc_code ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.ifsc_code"
                            :placeholder="
                                $t('common.placeholder_default_text', [$t('')])
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="24" :lg="12">
                    <a-form-item
                        :label="$t('Bank Name')"
                        name="bank_name"
                        :help="rules.bank_name ? rules.bank_name.message : null"
                        :validateStatus="rules.bank_name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.bank_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [$t('')])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button
                key="submit"
                type="primary"
                :loading="loading"
                @click="onSubmit"
            >
                <template #icon>
                    <SaveOutlined />
                </template>
                {{
                    addEditType == "add"
                        ? $t("common.create")
                        : $t("common.update")
                }}
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
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
});
</script>
