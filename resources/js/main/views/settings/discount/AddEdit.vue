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
                        :label="$t('discount.name')"
                        name="discount_type"
                        :help="
                            rules.discount_type
                                ? rules.discount_type.message
                                : null
                        "
                        :validateStatus="rules.discount_type ? 'error' : null"
                        class="required"
                    >
                        <a-select
                            v-model:value="formData.discount_type"
                            :placeholder="$t('select')"
                            :options="[
                                { label: $t('PerUnit'), value: 'perunit' },
                                { label: $t('No'), value: 'no' },
                                { label: $t('Lumpsum'), value: 'lumpsum' },
                                {
                                    label: $t('Percentage'),
                                    value: 'Percentage',
                                },
                            ]"
                        />
                    </a-form-item>
                </a-col>

                <!-- Status -->
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('Status')"
                        name="status"
                        :help="rules.status ? rules.status.message : null"
                        :validateStatus="rules.status ? 'error' : null"
                    >
                        <a-select
                            v-model:value="formData.status"
                            :placeholder="$t('select status')"
                            :options="[
                                { label: $t('Active'), value: 'active' },
                                { label: $t('Inactive'), value: 'inactive' },
                            ]"
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
