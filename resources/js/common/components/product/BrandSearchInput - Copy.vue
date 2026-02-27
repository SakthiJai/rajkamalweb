<template>
  <a-select
    v-model:value="searchTerm"
    show-search
    :filter-option="false"
    class="prodinput"
    style="width: 100%"
    :not-found-content="fetching ? undefined : null"
    @search="fetchProducts"
    @focus="fetchProducts"
    option-label-prop="label"
    @change="valueChanged"
    :allowClear="true"
  >
    <template #suffixIcon><SearchOutlined /></template>
    <template v-if="fetching" #notFoundContent>
      <a-spin size="small" />
    </template>
    <!-- <a-select-option :value="null" >
      -- Select --
    </a-select-option> -->
    <a-select-option
      v-for="newProduct in products"
      :key="newProduct.brands_name"
      :value="newProduct.brands_name"
      :label="newProduct.brands_name"
      :product="newProduct"
    >
      {{ newProduct.brands_name }}
    </a-select-option>
  </a-select>
</template>

<script>
import { defineComponent, toRefs, reactive, watch, onMounted } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import { debounce } from "lodash-es";

export default defineComponent({
  props: ["productData"],
  emits: ["valueSuccess", "valueChanged"],
  components: {
    SearchOutlined,
  },
  setup(props, { emit }) {
    const state = reactive({
      searchTerm: [],
      fetching: false,
      products: [],
    });

    onMounted(() => {
      resetSearchInput(props);
    });

    const resetSearchInput = (propVal) => {
      if (propVal.productData && propVal.productData.product) {
        state.products = [
          {
            id: propVal.productData.x_product_id,
            brands_name: propVal.productData.product.brands_name,
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
      console.log("fetch");
      state.products = [];
      let filterString = `brands_name lk "%${value}%"`;
      if (typeof value === "object" || value.trim() == "") {
        filterString = "";
      } else if (value == undefined || value.trim() == "") {
        filterString = "";
      }

      state.fetching = true;

      let url = `brand-search?fields=id,xid,brands_name,&filters=${encodeURIComponent(
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

    return {
      ...toRefs(state),
      fetchProducts,
      valueChanged,
    };
  },
});
</script>
