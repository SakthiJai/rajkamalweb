<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
        @cancel="onClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('variation.variation_name')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="nameInputRef"
                            id="variation_name"
                            v-model:value="formData.name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('variation.variation_name'),
                                ])
                            "
                            @keydown="handleEnterNavigation"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <form-item-heading>
                {{ $t("variation.variation_values") }}
            </form-item-heading>
            <a-row
                :gutter="16"
                v-for="(formField, index) in formFields"
                :key="formField.id"
                style="display: flex; align-items: center"
            >
                <a-col :xs="24" :sm="24" :md="23" :lg="23">
                    <a-form-item :label="$t('variation.value')" name="name">
                        <a-input
                            v-model:value="formField.name"
                            :placeholder="$t('common.placeholder_default_text', [$t('variation.value')])"
                            @keydown="handleEnterNavigation"
                        />
                    </a-form-item>
                </a-col>
                <a-col v-if="addEditType == 'add'" :span="1" style="margin-top: 6px">
                    <MinusSquareOutlined @click="removeFormField(formField)" />
                </a-col>
            </a-row>
            <p v-if="rules.value" style="color: red">
                {{ rules.value.message }}
            </p>
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-form-item>
                    <a-button
                        type="dashed"
                        block
                        @click="addFormField"
                        :disabled="addFormButtonStatus"
                    >
                        <PlusOutlined />

                        {{ $t("variation.add_new_value") }}
                    </a-button>
                </a-form-item>
            </a-col>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="loading" @click="onSubmit" style="background-color: #1f6d70;">
                <template #icon>
                    <SaveOutlined />
                </template>
                {{ addEditType == "add" ? $t("common.create") : $t("common.update") }} / F8
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }} / Esc
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, ref, computed, watch, nextTick, onBeforeUnmount } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    MinusSquareOutlined,
} from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import common from "../../../../common/composable/common";
import FormItemHeading from "../../../../common/components/common/typography/FormItemHeading.vue";
import { some, forEach, filter } from "lodash-es";

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
        MinusSquareOutlined,
        FormItemHeading,
    },
    setup(props, { emit }) {
        const nameInputRef = ref(null);
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { slugify } = common();
        const formFields = ref([
            {
                name: "",
                id: "",
            },
        ]);
        const removedVariations = ref([]);

        const onSubmit = () => {
            var newFormData = {
                ...props.formData,
                value: formFieldFilter(),
                removed_variations: removedVariations.value,
            };
            let finalUrl = props.url;

                if (props.addEditType === "add" || !finalUrl) {
                    finalUrl = "variations";
                }
            addEditRequestAdmin({
                url: finalUrl,
                data: newFormData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };
        const addFormField = () => {
            formFields.value.push({
                name: "",
                id: "",
            });
        };
const handleEnterNavigation = (event) => {
    if (event.key !== "Enter" && event.key !== "Tab") return;

    const value = event.target.value?.trim();

    if (!value) {
        event.preventDefault();
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    // Get all visible inputs inside modal
    const inputs = document.querySelectorAll(".ant-modal input:not([disabled])");
    const index = Array.from(inputs).indexOf(event.target);

    if (index !== -1 && index < inputs.length - 1) {
        // Move to next input
        inputs[index + 1].focus();
        return;
    }

    // If this is the last input, focus the "Add New Value" button
    const addButton = document.querySelector(
        ".ant-modal .ant-btn-dashed"
    );
    if (addButton) {
        addButton.focus();
    }
};
        let handleKeyDown = null;

        handleKeyDown = (event) => {
            const modal = document.querySelector(".ant-modal");
            if (!modal) return;

            // Arrow navigation
const confirmModal = document.querySelector(".ant-modal-confirm");

if (confirmModal && (event.key === "ArrowLeft" || event.key === "ArrowRight")) {
    event.preventDefault();

    const buttons = confirmModal.querySelectorAll(".ant-btn");

    const cancelBtn = Array.from(buttons).find(btn =>
        btn.classList.contains("ant-btn-default")
    );

    const okBtn = Array.from(buttons).find(btn =>
        btn.classList.contains("ant-btn-primary")
    );

    if (event.key === "ArrowLeft") {
        cancelBtn?.focus();
    }

    if (event.key === "ArrowRight") {
        okBtn?.focus();
    }

    return;
}
            if (event.key === "Escape") {
                event.preventDefault();
                onClose();
                return;
            }

            // F8 Save
            if (event.key === "F8") {
                event.preventDefault();
                event.stopPropagation();
                onSubmit();
                return;
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

        const formFieldFilter = () => {
            var newFormField = [];

            forEach(formFields.value, (formField) => {
                if (formField.name != "") {
                    newFormField.push(formField);
                }
            });

            return newFormField;
        };

        const addFormButtonStatus = computed(() => {
            if (formFields.value.length == 0) {
                return false;
            } else {
                return (
                    some(formFields.value, { name: "" }) ||
                    some(formFields.value, { name: null })
                );
            }
        });

        const removeFormField = (item) => {
            let index = formFields.value.indexOf(item);
            if (index !== -1) {
                formFields.value.splice(index, 1);
            }

            if (item.id != "") {
                removedVariations.value.push(item.id);
            }
        };

            const onClose = () => {
                const modalExists = document.querySelector(".ant-modal-confirm");
                if (modalExists) return;

                Modal.confirm({
                    title: "Confirmation",
                    content: "Variation data will be lost. Are you sure you want to close?",
                    okText: "OK",
                    cancelText: "Cancel",
                    autoFocusButton: "cancel",
                    onOk() {
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

                    // IMPORTANT: Fill edit values
                    formFields.value = [];

                    if (props.addEditType === "edit") {
                        forEach(props.data.sub_variations, (subVariation) => {
                            formFields.value.push({
                                name: subVariation.name,
                                id: subVariation.xid,
                            });
                        });
                    } else {
                        formFields.value.push({
                            name: "",
                            id: "",
                        });
                    }

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
            slugify,
            formFields,
            removeFormField,
            addFormField,
            addFormButtonStatus,
            nameInputRef,
            handleEnterNavigation,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
