<template>
<a-modal
    :open="visible"
    :closable="false"
    :centered="true"
    :title="pageTitle"
    :keyboard="true"
    @cancel="onClose"
    @after-open-change="onModalAfterOpenChange"
>
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Brand Name')"
                        name="brands_name"
                        :help="rules.brands_name ? rules.brands_name.message : null"
                        :validateStatus="rules.brands_name ? 'error' : null"
                        class="required"
                    >
                    <a-input
                        ref="nameInputRef"
                        id="brands_name"
                        v-model:value="formData.brands_name"
                        @keyup="formData.slug = slugify($event.target.value)"
                        @keydown="handleEnterNavigation"
                    />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('brand.slug')"
                        name="slug"
                        :help="rules.slug ? rules.slug.message : null"
                        :validateStatus="rules.slug ? 'error' : null"
                        class="required"
                    >
                    <a-input
                        ref="slugInputRef"
                        id="slug"
                        v-model:value="formData.slug"
                        @keydown="handleEnterNavigation"
                    />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('brand.logo')"
                        name="image"
                        :help="rules.image ? rules.image.message : null"
                        :validateStatus="rules.image ? 'error' : null"
                    >
                        <Upload
                            ref="logoUploadRef"
                            :formData="formData"
                            folder="brand"
                            imageField="image"
                            tabindex="0"
                            @keydown="handleEnterNavigation"

                            @onFileUploaded="(file) => {
                                    formData.image = file.file;
                                    formData.image_url = file.file_url;
                                if (rules.image) {
                                    delete rules.image;
                                }
                                nextTick(() => {
                                    const modal = document.querySelector('.ant-modal');
                                    const primaryBtn = modal?.querySelector(
                                        '.ant-modal-footer .ant-btn-primary'
                                    );
                                    primaryBtn?.focus();
                                });
                            }"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="loading" @click="onSubmit" style="background-color: #1f6d70;">
                <template #icon>
                    <SaveOutlined />
                </template>
                {{  addEditType == "add" ? $t("common.create") : $t("common.update") }} / F8
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }} / Esc
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, ref, onMounted, onBeforeUnmount, nextTick, watch } from "vue";
import { Modal } from "ant-design-vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import common from "../../../../common/composable/common";

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
        Upload,
    },
    setup(props, { emit }) {
        const nameInputRef = ref(null);
        const slugInputRef = ref(null);
        const logoUploadRef = ref(null);
        const onModalAfterOpenChange = (open) => {
            if (!open) return;

            nextTick(() => {
                nameInputRef.value?.input?.focus();
            });
        };
   
        const focusSlug = () => {
            slugInputRef.value?.focus();
        };
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { slugify } = common();
        const handleEnterNavigation = (event) => {
            if (event.key !== "Enter") return;

            event.preventDefault();

            const activeElement = document.activeElement;
 
            if (activeElement?.id === "brands_name") {
                if (!props.formData.brands_name?.trim()) return;
                slugInputRef.value?.input?.focus();
                return;
            }
 
            if (activeElement?.id === "slug") {
                if (!props.formData.slug?.trim()) return;

                nextTick(() => {
                    logoUploadRef.value?.$el?.focus();
                });

                return;
            }
 
            if (logoUploadRef.value?.$el?.contains(activeElement)) {
                const fileInput = logoUploadRef.value?.$el?.querySelector(
                    'input[type="file"]'
                );

                fileInput?.click();
                return;
            }
        };
        let handleKeyDown = null;
        handleKeyDown = (event) => {
            const modal = document.querySelector(".ant-modal");
            if (!modal) return;
 
            if (event.key === "F8") {
                event.preventDefault();
                event.stopPropagation();
                onSubmit();
                return;
            }
 
            if (event.key === "Enter") {
                const active = document.activeElement;
                const isFooterButton = active?.closest(".ant-modal-footer");

                if (isFooterButton) {
                    event.preventDefault();
                    active.click();
                    return;
                }
            }
 
            if (
                !document.querySelector(".ant-modal-confirm") &&
                (event.key === "ArrowLeft" || event.key === "ArrowRight")
            ) {
                event.preventDefault();

                const buttons = modal.querySelectorAll(
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

        const onSubmit = () => {
            let finalUrl = props.url;

            if (props.addEditType === "add" || !finalUrl) {
                finalUrl = "brands";
            }
            addEditRequestAdmin({
                url: finalUrl,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
                
            });
            console.log("ADD EDIT URL:", props.url);
        };

        const onClose = () => {
            if (document.querySelector(".ant-modal-confirm")) return;

            const handleArrowKeys = (e) => {
                if (!document.querySelector(".ant-modal-confirm")) return;

                const buttons = document.querySelectorAll(".ant-modal-confirm .ant-btn");

                const cancelBtn = Array.from(buttons).find(btn =>
                    btn.classList.contains("ant-btn-default")
                );

                const okBtn = Array.from(buttons).find(btn =>
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

            Modal.confirm({
                title: "Confirmation",
                content: "Brand data will be lost. Are you sure you want to close?",
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",
                onOk() {
                    cleanup();
                    emit("closed");
                },
                onCancel() {
                    cleanup();
                }
            });
        };
        watch(
            () => props.visible,
            (val) => {
                if (val) {
                    nextTick(() => {
                        nameInputRef.value?.input?.focus();
                    });

                    window.addEventListener("keydown", handleKeyDown);
                } else {
                    window.removeEventListener("keydown", handleKeyDown);
                }
            }
        );
console.log("PROPS URL:", props.url);
        return {
            loading,
            rules,
            onClose,
            onSubmit,
            slugify,
            nameInputRef,
            slugInputRef,
            focusSlug,
            handleEnterNavigation,
            logoUploadRef,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
