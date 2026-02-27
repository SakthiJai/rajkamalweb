<template>
    <div id="companysearch">
        <b hidden>{{ searchTerm }}</b>


        <a-select
            v-model:value="searchTerm"
            show-search
            id="company"
            :filter-option="false"
            style="width: 100%"
            :not-found-content="fetching ? undefined : null"
            @search="fetchProducts"
            option-label-prop="label"
            @change="valueChanged"
            :allowClear="true"
             @keydown="handleproductDown"
        >
            <template #suffixIcon><SearchOutlined /></template>
            <template v-if="fetching" #notFoundContent>
                <a-spin size="small" />
            </template>
            <a-select-option
                v-for="newProduct in products"
                :key="newProduct.id"
                :value="newProduct.id"
                :label="newProduct.name"
                :product="newProduct"
                @click="handleOptionClick(newProduct)"
            >
                {{ newProduct.name }}
            </a-select-option>
        </a-select>
    </div>

    <!--- Hsn create-->
    <Companysearchcreate
        v-if="iscompanyModalVisible"
        :visible="iscompanyModalVisible"
        :formDataLedger="formDataLedger"
        :url="url"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handlecompanyCancel"
    />
    <!-- end hsn create-->
</template>

<script>
import { defineComponent, toRefs, reactive, watch, onMounted } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import { debounce } from "lodash-es";
import { Modal, Input } from "ant-design-vue";
import Companysearchcreate from "../../../../js/main/views/stock-management/purchases/Serachcreate/Companysearchcreate.vue";

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
    emits: ["valueSuccess", "valueChanged"],
    components: {
        SearchOutlined,
        "a-modal": Modal,
        "a-input": Input,
        Companysearchcreate,
    },
    setup(props, { emit }) {
        let newHsnId = null;
        const state = reactive({
            searchTerm: [],
            fetching: false,
            products: [],
            iscompanyModalVisible: false,
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
                        name: propVal.productData.product.name,
                    },
                ];
                state.searchTerm = propVal.productData.x_product_id;
            } else {
                state.searchTerm = [];
                state.products = [];
            }

            emit("valueSuccess");
        };

        const valueChanged = (value) => {
            console.log("newValue", value, state.searchTerm);
            if (value == "F2") {
                state.iscompanyModalVisible = true;
            } else {
                emit("valueChanged", value);
            }
        };

        const fetchProducts = debounce((value) => {
            state.products = [];
            console.log("value", value);
            let filterString = `name lk "%${value}%"`;
            if (value == undefined) {
                filterString = "";
            } else if (typeof value === "object" || value.trim() == "") {
                filterString = "";
            }

            state.fetching = true;

            let url = `product-company?fields=id,xid,name,&filters=${encodeURIComponent(
                filterString
            )}&limit=1000`;

            axiosAdmin.get(url).then((response) => {
                response.data.push({ id: null, name: "" });
                response.data.push({ id: "F2", name: "F2 - Add new" });
                response.data.splice(0, 0, { id: null, name: "-- Blank --" });
                state.products = response.data;
                state.fetching = false;
                valueChanged(newHsnId);
            });
        }, 300);

        watch(props, (newVal) => {
            resetSearchInput(newVal);
        });

        const handleOptionClick = (newProduct) => {
            if (newProduct.name === "F2 - Add new") {
                response.data.push({ id: null, name: "" });
                state.iscompanyModalVisible = true;
                state.searchTerm = [];
            } else {
                state.searchTerm = newProduct.id;
            }
        };

        const handleAddNewProduct = () => {
            console.log("Adding new product: ", state.newProductName);

            state.iscompanyModalVisible = false;
        };
        const handlecompanyCancel = (value) => {
            console.log(value);
            state.iscompanyModalVisible = false;
            if (value) {
                newHsnId = value.id;
                state.searchTerm = value.name;
                fetchProducts(state.searchTerm);
            } else {
                console.log("No valid value provided");
                state.searchTerm = "";
                state.iscompanyModalVisible = false;
                fetchProducts(state.searchTerm);
            }
        };

        const handleKeydown = (event) => {
            console.log("ttetetet");
            if (event.key === "F2") {
                state.iscompanyModalVisible = true;
            }
        };


        const handleproductDown = (event) => {
             if(event.key === "Tab"){
        if (!state.searchTerm || state.searchTerm.length === 0) {
      event.preventDefault(); 
    }
         console.log("jhgfdcvbnm",state.searchTerm)
      } 
      if (event.key === "Delete" || event.key === "Backspace") {
        state.searchTerm = [];
        state.products = [];
        emit("valueChanged", null);

        fetchProducts("");
      } else {
        if (props.productData && props.productData.product) {
          state.searchTerm = props.productData.x_product_id;
          state.products = [
            {
              id: props.productData.x_product_id,
              name: props.productData.product.name,
            },
          ];
        }
      }
    };
        return {
            ...toRefs(state),
            fetchProducts,
            valueChanged,
            handleOptionClick,
            handleAddNewProduct,
            handlecompanyCancel,
            newHsnId,
            handleKeydown,
            handleproductDown,
        };
    },

    mounted() {
        document
            .getElementById("companysearch")
            .addEventListener("keydown", this.handleKeydown);
    },

    beforeUnmount() {
        window.removeEventListener("keydown", this.handleKeydown);
    },
});
</script>
