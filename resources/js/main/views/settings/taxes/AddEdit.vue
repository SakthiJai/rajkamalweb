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
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('Sales Name')"
                        name="sales_type"
                        :help="rules.sales_type ? rules.sales_type.message : null"
                        :validateStatus="rules.sales_type ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.sales_type"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t(''),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('IGST')"
                        name="sales_type"
                        :help="rules.lgst ? rules.lgst.message : null"
                        :validateStatus="rules.lgst ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.lgst"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t(''),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('CGST')"
                        name="cgst"
                        :help="rules.cgst ? rules.cgst.message : null"
                        :validateStatus="rules.cgst ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.cgst"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t(''),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('SGST')"
                        name="sgst"
                        :help="rules.sgst ? rules.sgst.message : null"
                        :validateStatus="rules.sgst ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.sgst"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t(''),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
                <template #icon>
                    <SaveOutlined />
                </template>
                {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-modal>
</template>
<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
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
