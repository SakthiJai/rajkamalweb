<template>
  <b hidden>{{ searchTerm }}</b>
  <a-select
    id="country_search"
    v-model:value="searchTerm"
    show-search
    :filter-option="false"
    style="width: 100%"
    ref="searchRef"
    :not-found-content="fetching ? undefined : null"
    @search="fetchProducts"
    option-label-prop="label"
    @change="valueChanged"
    :allowClear="true"
    @keydown="handleKeyDown"
    @keyup="handleKeyUp" 
  >
    <template #suffixIcon><SearchOutlined /></template>
    <template v-if="fetching" #notFoundContent>
      <a-spin size="small" />
    </template>
    <a-select-option :value="null">
      -- Select --
    </a-select-option>
    <a-select-option
      v-for="newProduct in products"
      :key="newProduct.bank_name"
      :value="newProduct.bank_name"
      :label="newProduct.bank_name"
      :product="newProduct"
      @click="handleOptionClick(newProduct)"
    >
      {{ newProduct.bank_name }}
    </a-select-option>
  </a-select>
</template>

<script>
import { defineComponent, toRefs, reactive, watch, onMounted, ref, nextTick } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import { debounce } from "lodash-es";
import { Modal, Input } from "ant-design-vue";

export default defineComponent({
  props: ["productData"],
  emits: ["valueSuccess", "valueChanged"],
  created() {
    console.log("created:", this.productData);
  },
  components: {
    SearchOutlined,
    "a-modal": Modal,
    "a-input": Input,
  },

  setup(props, { emit }) {
    const state = reactive({
      searchTerm: [],
      fetching: false,
      products: [],
      isModalVisible: false,
      newProductName: "",
    });
    const searchRef = ref(null);

    onMounted(async () => {
      await nextTick(); // Ensures DOM is fully rendered before applying focus
      autoFocusInput();
      fetchProducts();
      resetSearchInput(props);
    });

    const autoFocusInput = () => {
      if (searchRef.value) {
        searchRef.value.focus(); // Apply focus to the select component
      } else {
        console.log("searchRef is not available");
      }
    };

    const resetSearchInput = (propVal) => {
      if (propVal.productData && propVal.productData.product) {
        state.products1 = [
          {
            id: propVal.productData.x_product_id,
            bank_name: propVal.productData.product.bank_name,
          },
        ];
        state.searchTerm = propVal.productData.x_product_id;
      } else {
        state.searchTerm = [];
        state.products = [];
      }

      emit("valueSuccess");
    };

    const valueChanged = (value, option) => {
      emit("valueChanged", value);
      emit("valueSuccess");
    };

    const fetchProducts = debounce((value) => {
      state.products = [];
      console.log("value", value);
      let filterString = `bank_name lk "%${value}%"`;
      if (value == undefined) {
        filterString = "";
      } else if (typeof value === "object" || (value != "" && value.trim() == "")) {
        filterString = "";
      }
      state.fetching = true;

      let url = `receiptbank?fields=id,xid,bank_name&filters=${encodeURIComponent(
        filterString
      )}&limit=1000`;

      axiosAdmin.get(url).then((response) => {
        state.products = response.data;
        state.fetching = false;
      });
    }, 300);

    watch(props, (newVal, oldVal) => {
      resetSearchInput(newVal);
    });

    const handleOptionClick = (newProduct) => {
      if (newProduct.bank_name === "F2 - Add new") {
        state.isModalVisible = true;
      }
    };

    const handleAddNewProduct = () => {
      console.log("Adding new product: ", state.newProductName);
      state.isModalVisible = false;
    };

    const handleModalCancel = () => {
      state.isModalVisible = false;
    };

    const handleKeyDown = (event) => {
      if (event.key === "Tab") {
        event.preventDefault(); 
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
              bank_name: props.productData.product.bank_name,
            },
          ];
        }
      }
    };

    const handleKeyUp = (event) => {
      if (event.key === "Enter" && state.searchTerm != null) {
        const targetElement = document.getElementById("item_product_name_0");
        if (targetElement) {
          targetElement.focus();
        }
      }
    };

    return {
      ...toRefs(state),
      fetchProducts,
      valueChanged,
      handleOptionClick,
      handleAddNewProduct,
      handleModalCancel,
      handleKeyDown,
      handleKeyUp, // Add handleKeyUp to the return object
      searchRef,
      autoFocusInput,
    };
  },
});
</script>