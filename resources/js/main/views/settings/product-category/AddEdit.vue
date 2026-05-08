<template>
    <a-modal
        :open="visible"
        :closable="false"
        :keyboard="false"
        :maskClosable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
        @after-open-change="handleAfterOpen"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Product Category Name')"
                        name="product_category_name"
                        :help="
                            rules.product_category_name
                                ? rules.product_category_name.message
                                : null
                        "
                        :validateStatus="
                            rules.product_category_name ? 'error' : null
                        "
                        class="required"
                    >
                        <a-input
                        ref="nameInput"
                            v-model:value="formData.product_category_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [$t('')])
                            "
                             @keydown="handleEnterNavigation"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button
                ref="submitBtn"
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
            <a-button ref="cancelBtn" key="back" @click="onClose"  >
                {{ $t("common.cancel") }} / Esc
            </a-button>
        </template>
    </a-modal>
</template>
<script>
import { defineComponent, ref, createVNode, nextTick, watch } from "vue";
import { Modal } from "ant-design-vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    ExclamationCircleOutlined,
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
    const submitBtn = ref(null);
    const cancelBtn = ref(null);
    const handleAfterOpen = (open) => {
        if (open) {
            nextTick(() => {
                nameInput.value?.focus();
            });
        }
    };
    const nameInput = ref(null);

    const handleEnterNavigation = (event) => {
        if (event.key !== "Enter" && event.key !== "Tab") return;

        const value = event.target.value?.trim();

        if (!value) {
            event.preventDefault();
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const modal = document.querySelector(".ant-modal");
        const inputs = modal.querySelectorAll("input:not([disabled])");

        const index = Array.from(inputs).indexOf(event.target);

        if (index !== -1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
            return;
        }

        // If last input → focus primary button
        const primaryBtn = modal.querySelector(
            ".ant-modal-footer .ant-btn-primary"
        );

        primaryBtn?.focus();
    };
        let handleKeyDown = null;
        handleKeyDown = (event) => {

            if (document.querySelector(".ant-modal-confirm")) return;

            if (!props.visible) return;

            if (event.key === "Escape") {
                event.preventDefault();
                event.stopPropagation();
                onClose();
                return;
            }
 
            if (event.key === "F8") {
                event.preventDefault();
                event.stopPropagation();
                onSubmit();
                return;
            } 
            if (event.key === "ArrowLeft" || event.key === "ArrowRight") {
                event.preventDefault();

                const buttons = document.querySelectorAll(
                    ".ant-modal-footer .ant-btn"
                );

                const primary = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-primary")
                );

                const cancel = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-default")
                );

                if (event.key === "ArrowLeft") {
                    primary?.focus();
                } else {
                    cancel?.focus();
                }
            }
        };
        const { addEditRequestAdmin,     loading, rules } = apiAdmin();

        const onSubmit = () => {
            let finalUrl = props.url;

            if (props.addEditType === "add" || !finalUrl) {
                finalUrl = "product-category";
            }
            addEditRequestAdmin({
                url: finalUrl,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => { 
            if (document.querySelector(".ant-modal-confirm")) return;

            const modal = Modal.confirm({
                title: "Confirmation",
                icon: createVNode(ExclamationCircleOutlined),
                content:
                    "Transaction data will be lost. Are you sure you want to close?",
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",

                onOk() {
                    cleanup();
                    rules.value = {};
                    emit("closed");
                },

                onCancel() {
                    cleanup();
                },
            }); 
            const handleArrowKeys = (e) => {
                if (!document.querySelector(".ant-modal-confirm")) return;

                const buttons = document.querySelectorAll(
                    ".ant-modal-confirm .ant-btn"
                );

                const cancelBtn = Array.from(buttons).find((btn) =>
                    btn.classList.contains("ant-btn-default")
                );

                const okBtn = Array.from(buttons).find((btn) =>
                    btn.classList.contains("ant-btn-primary")
                );

                if (e.key === "ArrowRight") {
                    e.preventDefault();
                    okBtn?.focus();
                }

                if (e.key === "ArrowLeft") {
                    e.preventDefault();
                    cancelBtn?.focus();
                }
            };

            document.addEventListener("keydown", handleArrowKeys);

            const cleanup = () => {
                document.removeEventListener("keydown", handleArrowKeys);
            };
        };
        watch(
            () => props.visible,
            async (val) => {
                if (val) {
                    await nextTick();

                    nameInput.value?.input?.focus();

                    window.addEventListener("keydown", handleKeyDown);
                } else {
                    window.removeEventListener("keydown", handleKeyDown);
                }
            }
        );
        return {
            loading,
            rules,
            onClose,
            onSubmit,
            nameInput,
            handleEnterNavigation,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
