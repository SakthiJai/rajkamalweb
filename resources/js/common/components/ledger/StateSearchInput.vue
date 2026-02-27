<template>
  <b hidden>{{ searchTerm }}</b>
  <a-select
    id="state_search"
    v-model:value="searchTerm"
    show-search
    :filter-option="false"
    style="width: 100%"
    :not-found-content="fetching ? undefined : null"
    @search="fetchProducts"
    option-label-prop="label"
    @change="valueChanged"
    :allowClear="true"
    @keydown="handleKeyDown"
  >
    <template #suffixIcon><SearchOutlined /></template>
    <template v-if="fetching" #notFoundContent>
      <a-spin size="small" />
    </template>
    <a-select-option :value="null"> -- Select -- </a-select-option>
    <a-select-option
      v-for="newProduct in products"
      :key="newProduct.id"
      :value="newProduct.id"
      :label="newProduct.state_name"
      :product="newProduct"
      @click="handleOptionClick(newProduct)"
    >
      {{ newProduct.state_name }}
    </a-select-option>
  </a-select>
</template>

<script>
import { defineComponent, toRefs, reactive, watch, onMounted } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import { debounce } from "lodash-es";
import { Modal, Input } from "ant-design-vue";

export default defineComponent({
  props: ["productData", "selectedState"],
  emits: ["valueSuccess", "valueChanged"],
  created() {
    console.log("created:", this.selectedState);
    if (this.selectedState > 0) {
      this.updateSearchterm(this.selectedState);
    }
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
      selectedState: "",
    });

    onMounted(() => {
      fetchProducts();
      resetSearchInput(props);
    });

    const resetSearchInput = (propVal) => {
      console.log(propVal);
      if (propVal.productData && propVal.productData.product) {
        state.products = [
          {
            id: propVal.productData.x_product_id,
            state_name: propVal.productData.product.state_name,
          },
        ];
        state.searchTerm = propVal.productData.x_product_id;
      } else {
        state.searchTerm = propVal.selectedState;
        //state.products = [];
      }

      emit("valueSuccess");
    };

    const valueChanged = (value, option) => {
      emit("valueChanged", value);
      emit("valueSuccess");
    };

    const updateSearchterm = (state) => {
      state.selectedState = state;
    };

    const fetchProducts = debounce((value) => {
      state.products = [];
      console.log("value=>", state);
      let filterString = `state_name lk "%${value}%"`;
      if (value == undefined) {
        filterString = "";
      } else if (typeof value === "object" || (value != "" && value.trim() == "")) {
        filterString = "";
      }
      state.fetching = true;

      let url = `states?fields=id,xid,state_name&filters=${encodeURIComponent(
        filterString
      )}&limit=1000`;

      axiosAdmin.get(url).then((response) => {
        state.products = response.data;
        if (state.searchTerm > 0) {
          state.products.forEach((data) => {
            if (data.id == state.searchTerm) {
              state.searchTerm = data.state_name;
            }
          });
        }
        // response.data.splice(0, 0, {
        //   id: null,
        //   state_name: "",
        // });
        state.fetching = false;
      });
    }, 300);

    watch(props, (newVal, oldVal) => {
      resetSearchInput(newVal);
    });

    const handleOptionClick = (newProduct) => {
      if (newProduct.state_name === "F2 - Add new") {
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
        if (!state.searchTerm || state.searchTerm.length === 0) {
          event.preventDefault();
        }
        console.log("jhgfdcvbnm", state.searchTerm);
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
              state_name: props.productData.product.state_name,
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
      handleModalCancel,
      handleKeyDown,
      updateSearchterm,
    };
  },
});
</script>
