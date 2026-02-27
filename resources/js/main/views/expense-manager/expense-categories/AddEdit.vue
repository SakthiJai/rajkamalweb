<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="'Add Expense Category'"
        @ok="onSubmit"
        @cancel="onClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('expense_category.name')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.name"
                            ref="nameInputRef"
                            @keydown="handleEnterNavigation"
                            :placeholder="
                                $t('common.placeholder_default_text', [$t('')])
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('expense_category.description')"
                        name="description"
                        @keydown="handleEnterNavigation"
                        :help="
                            rules.description ? rules.description.message : null
                        "
                        :validateStatus="rules.description ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.description"
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
import { Modal } from "ant-design-vue";
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
        const nameInputRef = ref(null);
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const handleEnterNavigation = (event) => {
    if (event.key !== "Enter" && event.key !== "Tab") return;

    const value = event.target.value?.trim();
    if (!value) {
        event.preventDefault();
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    const inputs = document.querySelectorAll(".ant-modal input:not([disabled])");
    const index = Array.from(inputs).indexOf(event.target);

    if (index !== -1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
        return;
    }

    const primaryBtn = document.querySelector(".ant-modal .ant-btn-primary");
    primaryBtn?.focus();
};
let handleKeyDown = null;

handleKeyDown = (event) => {
    const modal = document.querySelector(".ant-modal");
    if (!modal) return;

    const confirmModal = document.querySelector(".ant-modal-confirm");

    // Confirm modal arrow handling
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

    if (event.key === "Escape") {
        event.preventDefault();
        onClose();
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

        const onSubmit = () => {
            
                let finalUrl = props.url;

    if (props.addEditType === "add" || !finalUrl) {
        finalUrl = "expense-categories";
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
    const modalExists = document.querySelector(".ant-modal-confirm");
    if (modalExists) return;

    Modal.confirm({
        title: "Confirmation",
        content: "Expense category data will be lost. Are you sure you want to close?",
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
        onOk() {
            rules.value = {};
            emit("closed");
        }
    });
};
watch(
    () => props.visible,
    async (val) => {
        if (val) {
            await nextTick();
            nameInputRef.value?.input?.focus();
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
            nameInputRef,
            handleEnterNavigation,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
