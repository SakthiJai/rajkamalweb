<template>
    <div id="hscsearch">
        <b hidden>{{ searchTerm }}</b>
        <a-select
            v-model:value="searchTerm"
            show-search
            id="hsn_sac"
            :filter-option="false"
            style="width: 100%"
            :not-found-content="fetching ? undefined : null"
            @search="fetchProducts"
            option-label-prop="label"
            @change="valueChanged"
            :allowClear="true"
            @keydown="handleKeyDowning"
        >
            <template #suffixIcon><SearchOutlined /></template>
            <template v-if="fetching" #notFoundContent>
                <a-spin size="small" />
            </template>
            <a-select-option
                v-for="newProduct in products"
                :key="newProduct.id"
                :value="newProduct.id"
                :label="newProduct.code"
                :product="newProduct"
                @click="handleOptionClick(newProduct)"
            >
                {{ newProduct.code }}
            </a-select-option>
        </a-select>
    </div>

    <!--- Hsn create-->
    <HscCreateModal
        v-if="isModalVisible"
        :visible="isModalVisible"
        :formDataLedger="formDataLedger"
        :url="url"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handleModalCancel"
    />
    <!-- end hsn create-->
</template>

<script>
import { defineComponent, toRefs, reactive, watch, onMounted } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import { debounce } from "lodash-es";
import { Modal, Input } from "ant-design-vue";
import HscCreateModal from "../../../../js/main/views/stock-management/purchases/Serachcreate/HscCreateModal.vue";

export default defineComponent({
    props: [
        "productData",
        "formData",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    emits: ["valueSuccess", "valueChanged", "gstValueChanged"], // Add new emit for GST value
    components: {
        SearchOutlined,
        "a-modal": Modal,
        "a-input": Input,
        HscCreateModal,
    },
    setup(props, { emit }) {
        let newHsnId = null;
        const state = reactive({
            searchTerm: [],
            fetching: false,
            products: [],
            isModalVisible: false,
            newProductName: "",
        });

        onMounted(() => {
            fetchProducts();
            resetSearchInput(props);
        });

        const resetSearchInput = (propVal) => {
            if (propVal.productData && propVal.productData.product) {
                state.products = [
                    {
                        id: propVal.productData.x_product_id,
                        code: propVal.productData.product.code,
                        gst: propVal.productData.product.gst, // Assuming gst is part of product data
                    },
                ];
                state.searchTerm = propVal.productData.x_product_id;
                emit("gstValueChanged", propVal.productData.product.gst); // Emit GST value
            } else {
                state.searchTerm = [];
                state.products = [];
                emit("gstValueChanged", ""); // Emit empty GST value
            }

            emit("valueSuccess");
        };

        const valueChanged = (value) => {
            console.log("newValue", value, state.searchTerm);
            if (value == "F2") {
                state.isModalVisible = true;
            } else {
                emit("valueChanged", value);
                const selectedProduct = state.products.find(
                    (product) => product.id === value
                );
                if (selectedProduct) {
                    emit("gstValueChanged", selectedProduct.gst); // Emit GST value
                } else {
                    emit("gstValueChanged", ""); // Emit empty GST value
                }
            }
        };

        const fetchProducts = debounce((value) => {
            state.products = [];
            console.log("value", value);
            let filterString = `code lk "%${value}%"`;
            if (value == undefined) {
                filterString = "";
            } else if (
                typeof value === "object" ||
                (value != "" && value.trim() == "")
            ) {
                filterString = "";
            }

            state.fetching = true;
            let url = `hsn-sac?fields=id,xid,code,gst,&filters=${encodeURIComponent(
                filterString
            )}&limit=1000`;

            axiosAdmin.get(url).then((response) => {
                response.data.push({ id: null, code: "" });
                response.data.push({ id: "F2", code: "F2 - Add new" });
                response.data.splice(0, 0, {
                    id: null,
                    code: "-- Blank --",
                });
                state.products = response.data;
                state.fetching = false;
                valueChanged(newHsnId);
            });
        }, 300);

        watch(props, (newVal) => {
            resetSearchInput(newVal);
        });

        const handleKeyDowning = (event) => {
            if (event.key === "Tab") {
                if (!state.searchTerm || state.searchTerm.length === 0) {
                    event.preventDefault();
                }
                console.log("jhgfdcvbnm", state.searchTerm);
            }
            if (event.key === "Delete" || event.key === "Backspace") {
                state.searchTerm = [];
                state.products = [];
                emit("gstValueChanged", ""); // Emit empty GST value
                emit("valueChanged", null);

                fetchProducts("");
            } else {
                if (props.productData && props.productData.product) {
                    state.searchTerm = props.productData.x_product_id;
                    state.products = [
                        {
                            id: props.productData.x_product_id,
                            code: props.productData.product.code,
                            gst: props.productData.product.gst, // Assuming gst is part of product data
                        },
                    ];
                    emit("gstValueChanged", props.productData.product.gst); // Emit GST value
                }
            }
        };

        const handleOptionClick = (newProduct) => {
            if (newProduct.code === "F2 - Add new") {
                state.isModalVisible = true;
                state.searchTerm = [];
                emit("gstValueChanged", ""); // Emit empty GST value
            } else {
                state.searchTerm = newProduct.id;
                emit("gstValueChanged", newProduct.gst); // Emit GST value
            }
        };

        const handleModalCancel = (value) => {
            console.log(value);
            state.isModalVisible = false;
            if (value) {
                newHsnId = value.id;
                state.searchTerm = value.code;
                emit("gstValueChanged", value.gst); // Emit GST value
                fetchProducts(state.searchTerm);
            } else {
                console.log("No valid value provided");
                state.searchTerm = "";
                emit("gstValueChanged", ""); // Emit empty GST value
                state.isModalVisible = false;
                fetchProducts(state.searchTerm);
            }
        };

        const handleKeydown = (event) => {
            if (event.key === "F2") {
                state.isModalVisible = true;
            }
        };

        return {
            ...toRefs(state),
            fetchProducts,
            valueChanged,
            handleOptionClick,
            handleModalCancel,
            newHsnId,
            handleKeydown,
            handleKeyDowning,
        };
    },

    mounted() {
        document
            .getElementById("hscsearch")
            .addEventListener("keydown", this.handleKeydown);
    },

    beforeUnmount() {
        window.removeEventListener("keydown", this.handleKeydown);
    },
});
</script>