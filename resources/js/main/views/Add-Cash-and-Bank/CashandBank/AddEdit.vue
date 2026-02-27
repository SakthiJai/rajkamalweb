<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="'Add Bank Account'"
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
                            ref="firstInputRef"
                            @keydown="handleEnterNavigation"
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
                            @keydown="handleEnterNavigation"
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
                            @keydown="handleEnterNavigation"
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
                            @keydown="handleEnterNavigation"
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
        </template>
    </a-modal>
</template>
<script>
import { defineComponent, ref, watch, nextTick } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
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
        const firstInputRef = ref(null);
        const handleEnterNavigation = (event) => {
            if (event.key !== "Enter" && event.key !== "Tab") return;

            const value = event.target.value?.trim();

            if (!value) {
                event.preventDefault();
                return;
            }

            event.preventDefault();

           const inputs = document.querySelectorAll(
            ".ant-modal input:not([disabled]), .ant-modal textarea:not([disabled])"
            );
            const index = Array.from(inputs).indexOf(event.target);

            if (index !== -1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
                return;
            }

            const primaryBtn = document.querySelector(".ant-modal .ant-btn-primary");
            primaryBtn?.focus();6
        };
        const handleKeyDown = (event) => {
            const confirmModal = document.querySelector(".ant-modal-confirm");

            if ((event.key === "Escape" || event.code === "Escape") && !confirmModal) {
                event.preventDefault();
                onClose();
                return;
            }
            const modal = document.querySelector(".ant-modal-root");
            if (!modal) return;
            // Confirm modal arrow navigation
            if (confirmModal && (event.key === "ArrowLeft" || event.key === "ArrowRight")) {
                event.preventDefault();

                const buttons = confirmModal.querySelectorAll(".ant-btn");

                const cancelBtn = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-default")
                );

                const okBtn = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-primary")
                );

                if (event.key === "ArrowLeft") cancelBtn?.focus();
                if (event.key === "ArrowRight") okBtn?.focus();

                return;
            }



            if (event.key === "F8") {
                event.preventDefault();
                onSubmit();
                return;
            }
            if (!confirmModal && (event.key === "ArrowLeft" || event.key === "ArrowRight")) {
                event.preventDefault();

                const buttons = modal.querySelectorAll(".ant-modal-footer .ant-btn");

                const primary = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-primary")
                );

                const cancel = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-default")
                );

                if (event.key === "ArrowLeft") primary?.focus();
                else cancel?.focus();
            }
        };
        watch(
            () => props.visible,
            async (val) => {
                if (val) {
                    await nextTick();

                    firstInputRef.value?.input?.focus();

                    document.addEventListener("keydown", handleKeyDown, true);
                } else {
                    document.removeEventListener("keydown", handleKeyDown, true);
                }
            }
        );
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
                            let finalUrl = props.url;

    if (props.addEditType === "add" || !finalUrl) {
        finalUrl = "receiptbank";
    }
            addEditRequestAdmin({
                url: finalUrl,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                    emit("closed"); 
                },
            });
        };

        const onClose = () => {
            const modalExists = document.querySelector(".ant-modal-confirm");
            if (modalExists) return;

            Modal.confirm({
                title: "Confirmation",
                content: "Bank account data will be lost. Are you sure you want to close?",
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",
                onOk() {
                    rules.value = {};
                    emit("closed");
                }
            });
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            handleEnterNavigation,
            firstInputRef,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
