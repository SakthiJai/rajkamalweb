          <template>
    <a-modal 
    v-model:open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        :keyboard="false"
        @ok="onSubmit"
        @after-open-change="onModalAfterOpenChange"
    >
        <a-form layout="vertical">
               <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('product.product')"
                        name="orderSearchTerm"
                        :help="rules.product_id ? rules.product_id.message : null"
                        :validateStatus="rules.product_id ? 'error' : null"
                        class="required"
                    >
                        <span style="display: flex">
                            <ProductSearchInput
                            ref="productSearchInputRef"
                        @valueChanged="(productId) => (formData.product_id = productId)"
                        @valueSuccess="getStockValue"
                        :productData="formData.product"
                        @keydown="handleEnterNavigation"
                        />
                            
                            <!-- <ProductSearchInput
                                
                                @valueChanged="(productId) => (formData.product_id = productId)"
                                @valueSuccess="getStockValue"
                                @keydown.enter="handleEnterNavigation"
                                @keydown.tab.prevent="handleEnterNavigation"
                                :productData="data"
                            /> -->
                        </span>
                    </a-form-item>
                </a-col>
            </a-row>


            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Quantity')"
                        name="quantity"
                        :help="rules.quantity ? rules.quantity.message : null"
                        :validateStatus="rules.quantity ? 'error' : null"
                        class="required"
                    >
                        <a-input-number
                            ref="quantityInputRef"
                            v-model:value="formData.quantity"
                            @input="clearValidationError('quantity')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('quantity')])"
                            style="width:100%"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Packaging')"
                        name="packaging"
                        :help="rules.packaging ? rules.packaging.message : null"
                        :validateStatus="rules.packaging ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="packageInputRef"
                            v-model:value="formData.packaging"
                            @input="clearValidationError('packaging')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('Packaging')])"
                            style="width:100%"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('Enter Reason')"
                        name="reason"
                        :help="rules.reason ? rules.reason.message : null"
                        :validateStatus="rules.reason ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            ref="reasonInputRef"
                            v-model:value="formData.reason"
                            @input="clearValidationError('reason')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('reason')])"
                            style="width:100%"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
             
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                     <a-form-item
                        :label="$t('Enter Value')"
                        name="value"
                        :help="rules.value ? rules.value.message : null"
                        :validateStatus="rules.value ? 'error' : null"
                        class="required"
                    >
                        <a-input-number
                            ref="valueInputRef"
                            v-model:value="formData.value"
                            @input="clearValidationError('value')"
                            @keydown.enter="handleEnterNavigation"
                            @keydown.tab.prevent="handleEnterNavigation"
                            :placeholder="$t('common.placeholder_default_text', [$t('value')])"
                            style="width:100% ; border-color: rgb(218 218 218) !important;"
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
import { defineComponent,computed, ref, onMounted, onUnmounted, watch, nextTick, createVNode } from "vue";
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
        "open",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    emits: ["addEditSuccess", "closed", "update:open"],
    components: {
        SaveOutlined,
        ProductAddButton,
        ProductSearchInput,
    },
        
    setup(props, { emit }) {
        const visible = computed({
    get: () => props.open,
    set: (val) => emit("update:open", val),
});

        const { permsArray } = common();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const productSearchInputRef = ref(null);
        const productNameInputRef = ref(null);
        const reasonInputRef = ref(null);
        const packageInputRef = ref(null);
        const quantityInputRef = ref(null);
        const valueInputRef = ref(null);

        
        let handleKeyDown = null;
        let footerNavHandler = null;

const onModalAfterOpenChange = (open) => {
    if (open) {
        nextTick(() => {
            setTimeout(() => {
                const input = productSearchInputRef.value?.$el?.querySelector("input");

                if (input) {
                    try { input.id = 'product_id'; } catch (e) {}
                    input.focus();
                    input.select();
                    console.log("Auto-focus on product_id field");
                } else {
                    console.log("Could not find input element to focus");
                }

                try {
                    const pInput = reasonInputRef.value?.$el?.querySelector('input');
                    if (pInput) pInput.id = 'reason';
                } catch (e) {}
                try {
                    const packInput = packageInputRef.value?.$el?.querySelector('input');
                    if (packInput) packInput.id = 'packaging';
                } catch (e) {}
                try {
                    const mInput = quantityInputRef.value?.$el?.querySelector('input');
                    if (mInput) mInput.id = 'quantity';
                } catch (e) {}
                try {
                    const vInput = valueInputRef.value?.$el?.querySelector('input');
                    if (vInput) vInput.id = 'value';
                } catch (e) {}
                const stopCheck = setInterval(() => {
                    if (!props.open) {
                        document.removeEventListener('keydown', footerNavHandler);
                        clearInterval(stopCheck);
                    }
                }, 100);
            }, 100);
        });
    }
};
        watch(
            () => props.open,
            (newVal) => {
                if (newVal) {
                    nextTick(() => {
                        setTimeout(() => {
                            const input = productSearchInputRef.value?.$el?.querySelector('input');
                            if (input) {
                                try { input.id = 'product_id'; } catch (e) {}
                                input.focus();
                                input.select();
                                try {
                                    input.setSelectionRange(input.value.length, input.value.length);
                                } catch (e) {}
                                console.log('Watcher: focused product_id input');
                            } else {
                                console.log('Watcher: product_id input not found');
                            }

                            try {
                                const pInput = reasonInputRef.value?.$el?.querySelector('input');
                                if (pInput) pInput.id = 'reason';
                            } catch (e) {}
                            try {
                                const packInput = packageInputRef.value?.$el?.querySelector('input');
                                if (packInput) packInput.id = 'packaging';
                            } catch (e) {}
                            try {
                                const mInput = quantityInputRef.value?.$el?.querySelector('input');
                                if (mInput) mInput.id = 'quantity';
                            } catch (e) {}
                            try {
                                const vInput = valueInputRef.value?.$el?.querySelector('input');
                                if (vInput) vInput.id = 'value';
                            } catch (e) {}
                        }, 120);
                    });
                }
            }
        );

   onMounted(() => {
    handleKeyDown = (event) => {
        if (!props.open) return;

        const modal = document.querySelector('.ant-modal');
        if (!modal) return;

        const active = document.activeElement;
        const isInput =
            active?.tagName === 'INPUT' ||
            active?.tagName === 'TEXTAREA';

        if (event.key === 'Escape') {
            if (document.querySelector('.ant-modal-confirm')) {
                return;
            }

            if (props.open) {
                event.preventDefault();
                event.stopPropagation();
                onClose();
            }

            return;
        }

        if (event.key === 'F8' || event.keyCode === 119) {
            event.preventDefault();
            event.stopPropagation();
            onSubmit();
            return;
        }

if (document.querySelector('.ant-modal-confirm')) {
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
    window.addEventListener('keydown', handleKeyDown);
});


        onUnmounted(() => {
            if (handleKeyDown) {
                window.removeEventListener('keydown', handleKeyDown);
            }
        });

  const validateForm = () => {
    let isValid = true;

    Object.keys(rules).forEach(key => delete rules[key]);

    if (!props.formData.product_id) {
        rules.product_id = { required: true, message: "Product is required" };
        isValid = false;
    }

    if (!props.formData.reason) {
        rules.reason = { required: true, message: "Reason is required" };
        isValid = false;
    }

    if (!props.formData.quantity) {
        rules.quantity = { required: true, message: "Quantity is required" };
        isValid = false;
    }

    if (!props.formData.packaging) {
        rules.packaging = { required: true, message: "Packaging is required" };
        isValid = false;
    }

    if (!props.formData.value) {
        rules.value = { required: true, message: "Value is required" };
        isValid = false;
    }

    return isValid;
};


        const clearValidationError = (field) => {
            const fieldValue = rules[field];
            
            if (fieldValue) {
                const currentValue = props.formData[field];
                
                if (currentValue !== null && currentValue !== undefined && currentValue !== "") {
                    delete rules[field];
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
                    'product_id': 'product_id',
                    'reason': 'reason',
                    'quantity': 'quantity',
                    'packaging': 'packaging',
                    'value': 'value',
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
                       rules[formDataKey] = {
                            required: true, 
                            message: `${fieldId.replace(/_/g, ' ')} is required` 
                        };
                    }
                }
            }
        };
const onSubmit = () => {
    if (!validateForm()) return;

    const parsedQuantity = Number(props.formData.quantity);
    const normalizedQuantity = Math.abs(parsedQuantity);
    const normalizedAdjustmentType =
        parsedQuantity < 0 ? "subtract" : "add";

    const payload = {
        product_id: props.formData.product_id,
        quantity: normalizedQuantity,
        reason: props.formData.reason,
        packaging: props.formData.packaging,
        value: props.formData.value,
        adjustment_type: normalizedAdjustmentType,
    };
const httpMethod = props.addEditType === "add" ? "post" : "put";

    addEditRequestAdmin({
        url: props.url,
        data: payload,
        method:httpMethod,
        successMessage: props.successMessage,
       success: (res) => {

            props.formData.product_id = null;
            props.formData.quantity = null;
            props.formData.reason = null;
            props.formData.packaging = null;
            props.formData.value = null;
            Object.keys(rules).forEach(key => delete rules[key]);
            emit("addEditSuccess", res.xid);
            emit("update:open", false);
        }

    });
};


const onClose = () => {
    if (document.querySelector('.ant-modal-confirm')) return;

    const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Transaction data will be lost. Are you sure you want to close?",
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",

        onOk() {
            cleanup();
            emit("update:open", false);
            emit("closed");
        },

        onCancel() {
            cleanup();
        }
    });

    // 🔥 Arrow key navigation
    const handleArrowKeys = (e) => {
        if (!document.querySelector('.ant-modal-confirm')) return;

        const buttons = document.querySelectorAll('.ant-modal-confirm .ant-btn');
        const cancelBtn = Array.from(buttons).find(btn =>
            btn.classList.contains('ant-btn-default')
        );
        const okBtn = Array.from(buttons).find(btn =>
            btn.classList.contains('ant-btn-primary')
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



        return {
            visible,
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
            productNameInputRef,
            reasonInputRef,
            packageInputRef,
            quantityInputRef,
            valueInputRef,
        };
    },
});
</script>
