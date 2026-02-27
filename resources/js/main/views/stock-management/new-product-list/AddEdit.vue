<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
        @after-open-change="onModalAfterOpenChange"
    >
        <a-form layout="vertical">
              <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Code')"
                        name="code"
                        :help="rules.code ? rules.code.message : null"
                        :validateStatus="rules.code ? 'error' : null"
                        class="required"
                    >
                        <a-input-number
                            ref="codeInputRef"
                            v-model:value="formData.code"
                            @input="clearValidationError('code')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('code')])"
                            :min="0"
                            auto-focus
                            style="width:100%"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Product Name')"
                        name="product_name"
                        :help="rules.product_name ? rules.product_name.message : null"
                        :validateStatus="rules.product_name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="productNameInputRef"
                            v-model:value="formData.product_name"
                            @input="clearValidationError('product_name')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('product_name')])"
                            style="width:100%"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Package')"
                        name="package"
                        :help="rules.package ? rules.package.message : null"
                        :validateStatus="rules.package ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="packageInputRef"
                            v-model:value="formData.package"
                            @input="clearValidationError('package')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('package')])"
                            style="width:100%"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
             <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Manufacturer')"
                        name="manufacturer"
                        :help="rules.manufacturer ? rules.manufacturer.message : null"
                        :validateStatus="rules.manufacturer ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="manufacturerInputRef"
                            v-model:value="formData.manufacturer"
                            @input="clearValidationError('manufacturer')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('manufacturer')])"
                            style="width:100%"
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
                {{ addEditType == "add" ? $t("Save / F8") : $t("update / F8") }}
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("cancel / esc") }}
            </a-button>
        </template>
    </a-modal>
</template>
<script>
import { defineComponent, ref, onMounted, onUnmounted, watch, nextTick, createVNode } from "vue";
import { SaveOutlined, ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { Modal } from 'ant-design-vue';
import apiAdmin from "../../../../common/composable/apiAdmin";
import ProductAddButton from "../../product-manager/products/AddButton.vue";
import ProductSearchInput from "../../../../common/components/product/ProductSearchInput.vue";
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
    emits: ["addEditSuccess", "closed"],
    components: {
        SaveOutlined,
        ProductAddButton,
        ProductSearchInput,
    },
        autoFocusInput() {
      this.$nextTick(() => {
        const gstInput = this.$refs.ProductInput;

        if (gstInput) {
          gstInput.focus();
          console.log("ProductInput focused");
        } else {
          console.error("ProductInputnot found after mount/update.");
        }
      });
    },
        
    setup(props, { emit }) {
        const { permsArray } = common();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const productSearchInputRef = ref(null);
        const codeInputRef = ref(null);
        const productNameInputRef = ref(null);
        const packageInputRef = ref(null);
        const manufacturerInputRef = ref(null);

        let handleKeyDown = null;
        let footerNavHandler = null;

const onModalAfterOpenChange = (open) => {
    if (open) {
        nextTick(() => {
            setTimeout(() => {
                const input = codeInputRef.value?.$el?.querySelector("input");

                if (input) {
                    try { input.id = 'code'; } catch (e) {}
                    input.focus();
                    input.select();
                    console.log("Auto-focus on code field");
                } else {
                    console.log("Could not find input element to focus");
                }

                try {
                    const pInput = productNameInputRef.value?.$el?.querySelector('input');
                    if (pInput) pInput.id = 'product_name';
                } catch (e) {}
                try {
                    const packInput = packageInputRef.value?.$el?.querySelector('input');
                    if (packInput) packInput.id = 'package';
                } catch (e) {}
                try {
                    const mInput = manufacturerInputRef.value?.$el?.querySelector('input');
                    if (mInput) mInput.id = 'manufacturer';
                } catch (e) {}
                const stopCheck = setInterval(() => {
                    if (!props.visible) {
                        document.removeEventListener('keydown', footerNavHandler);
                        clearInterval(stopCheck);
                    }
                }, 100);
            }, 100);
        });
    }
};
        watch(
            () => props.visible,
            (newVal) => {
                if (newVal) {
                    nextTick(() => {
                        setTimeout(() => {
                            const input = codeInputRef.value?.$el?.querySelector('input');
                            if (input) {
                                try { input.id = 'code'; } catch (e) {}
                                input.focus();
                                input.select();
                                try {
                                    input.setSelectionRange(input.value.length, input.value.length);
                                } catch (e) {}
                                console.log('Watcher: focused code input');
                            } else {
                                console.log('Watcher: code input not found');
                            }

                            try {
                                const pInput = productNameInputRef.value?.$el?.querySelector('input');
                                if (pInput) pInput.id = 'product_name';
                            } catch (e) {}
                            try {
                                const packInput = packageInputRef.value?.$el?.querySelector('input');
                                if (packInput) packInput.id = 'package';
                            } catch (e) {}
                            try {
                                const mInput = manufacturerInputRef.value?.$el?.querySelector('input');
                                if (mInput) mInput.id = 'manufacturer';
                            } catch (e) {}
                        }, 120);
                    });
                }
            }
        );

   onMounted(() => {
    handleKeyDown = (event) => {
        if (!props.visible) return;

        const modal = document.querySelector('.ant-modal');
        if (!modal) return;

        const active = document.activeElement;
        const isInput =
            active?.tagName === 'INPUT' ||
            active?.tagName === 'TEXTAREA';

        if (event.key === 'Escape') {
            event.preventDefault();
            onClose();
            return;
        }

        if (event.key === 'F8' || event.keyCode === 119) {
            event.preventDefault();
            event.stopPropagation();
            onSubmit();
            return;
        }

        if (!isInput && (event.key === 'ArrowRight' || event.key === 'ArrowLeft')) {
            event.preventDefault();

            const buttons = modal.querySelectorAll('.ant-modal-footer .ant-btn');
            if (!buttons.length) return;

            const primary = Array.from(buttons).find(b =>
                b.classList.contains('ant-btn-primary')
            );
            const cancel = Array.from(buttons).find(b =>
                b.classList.contains('ant-btn-default')
            );

            if (event.key === 'ArrowLeft') {
                primary?.focus();
            } else {
                cancel?.focus();
            }
        }
    };

    window.addEventListener('keydown', handleKeyDown, true);
});


        onUnmounted(() => {
            if (handleKeyDown) {
                window.removeEventListener('keydown', handleKeyDown, true);
            }
        });

        const validateForm = () => {
            let isValid = true;
            const errorRules = {};

            if (props.formData.code === null || props.formData.code === undefined || props.formData.code === "") {
                errorRules.code = { required: true, message: "Code is required" };
                isValid = false;
            }

            if (props.formData.product_name === null || props.formData.product_name === undefined || props.formData.product_name === "") {
                errorRules.product_name = { required: true, message: "Product Name is required" };
                isValid = false;
            }

            if (props.formData.package === null || props.formData.package === undefined || props.formData.package === "") {
                errorRules.package = { required: true, message: "Package is required" };
                isValid = false;
            }

            if (props.formData.manufacturer === null || props.formData.manufacturer === undefined || props.formData.manufacturer === "") {
                errorRules.manufacturer = { required: true, message: "Manufacturer is required" };
                isValid = false;
            }

            if (!isValid) {
                rules.value = errorRules;
                return false;
            }

            rules.value = {};
            return true;
        };

        const clearValidationError = (field) => {
            const fieldValue = rules.value[field];
            
            if (fieldValue) {
                const currentValue = props.formData[field];
                
                if (currentValue !== null && currentValue !== undefined && currentValue !== "") {
                    delete rules.value[field];
                }
            }
        };

        const handleEnterNavigation = (event) => {
            if (event.key === 'Enter' || event.key === 'Tab' || event.keyCode === 13 || event.keyCode === 9) {
                event.preventDefault();
                event.stopPropagation();

                const currentField = event.target;
                const modalContent = document.querySelector('.ant-modal-content');
                
                if (!modalContent) return;

                const formElements = Array.from(
                    modalContent.querySelectorAll("input, select, textarea")
                );
                
                const currentIndex = formElements.indexOf(currentField);
                
                if (currentIndex === -1) return;

                const fieldMap = {
                    'code': 'code',
                    'product_name': 'product_name',
                    'package': 'package',
                    'manufacturer': 'manufacturer'
                };

                const fieldId = currentField.id;
                const formDataKey = fieldMap[fieldId];
                
                let fieldValue = formDataKey ? props.formData[formDataKey] : currentField.value;
                
                const isFieldValid = fieldValue !== null && fieldValue !== undefined && 
                                   fieldValue.toString().trim() !== "";

                if (isFieldValid) {
                    if (currentIndex < formElements.length - 1) {
                        formElements[currentIndex + 1].focus();
                    } else {
                        const primaryBtn = document.querySelector('.ant-modal-footer .ant-btn-primary');
                        
                        (() => {
                            primaryBtn?.focus();
                        })();
                        !primaryBtn && console.log("Reached the last field, but Save button not found.");
                    }
                } else {
                    console.log(`Validation failed: ${fieldId} is required`);
                    
                    if (formDataKey) {
                        rules.value[formDataKey] = { 
                            required: true, 
                            message: `${fieldId.replace(/_/g, ' ')} is required` 
                        };
                    }
                }
            }
        };

        const onSubmit = () => {
            if (!validateForm()) {
                return;
            }

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
            const modal = Modal.confirm({
                title: "Confirmation",
                icon: createVNode(ExclamationCircleOutlined),
                content: "Transaction data will be lost. Are you sure you want to close?",
                onOk() {
                    props.formData.code = null;
                    props.formData.product_name = null;
                    props.formData.package = null;
                    props.formData.manufacturer = null;
                    rules.value = {};
                    emit("closed");
                },
                onCancel() {
                    codeInputRef.value?.$el?.querySelector?.('input')?.focus();
                },
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",
            });

            setTimeout(() => {
                const handleKeydown = (e) => {
                    if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
                        e.preventDefault();
                        const buttons = document.querySelectorAll(".ant-modal-confirm .ant-btn");
                        const cancelBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-default")
                        );
                        const okBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-primary")
                        );

                        if (e.key === "ArrowRight") {
                            if (okBtn) {
                                okBtn.focus();
                            }
                        } else if (e.key === "ArrowLeft") {
                            if (cancelBtn) {
                                cancelBtn.focus();
                            }
                        }
                    }
                };
                document.addEventListener("keydown", handleKeydown);

                const checkModalClosed = setInterval(() => {
                    if (!document.querySelector(".ant-modal-confirm")) {
                        document.removeEventListener("keydown", handleKeydown);
                        clearInterval(checkModalClosed);
                    }
                }, 100);
            }, 100);
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            onModalAfterOpenChange,
            permsArray,
            validateForm,
            clearValidationError,
            handleEnterNavigation,
            productSearchInputRef,
            codeInputRef,
            productNameInputRef,
            packageInputRef,
            manufacturerInputRef,
        };
    },
});
</script>
