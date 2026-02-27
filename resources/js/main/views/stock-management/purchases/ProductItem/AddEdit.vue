<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
        class="addedit"
    >
        <a-form ref="myForm1" id="addproductform" layout="vertical" @submit.prevent="false">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="16" :lg="16" class="info">
                  <fieldset class="fieldheightsss">
                    <legend class="basicdetalis">Basic Info</legend>
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item
                              :label="$t('stock.add_product_name')"
                              name="name"
                              :help="rules.name ? rules.name.message : null"
                              :validateStatus="rules.name ? 'error' : null"
                              class="required"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              autocomplete="off"
                              ref="ProductInput"
                              id="ProductInput"
                           
                              name="name"
                              v-model:value="formData.name"
                            
                              value=""
                            
                            >
                            </a-input>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select
                              id="product_type"
                              name="product_type"
                              v-model:value="formData.product_type"
                              style="width: 100%"
                            
                            >
                              <a-select-option key="Goods" value="Goods" aria-selected="true">
                                Goods
                              </a-select-option>
                              <a-select-option key="Service" value="Service" aria-selected="true">
                                Service
                              </a-select-option>
                            </a-select>
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>
                    <!-- accountsgroup-->
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-row :gutter="24">
                          <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item
                              :label="$t('stock.packing')"
                              name="packing"
                              :help="rules.packing ? rules.packing.message : null"
                              :validateStatus="rules.packing ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-input
                              autocomplete="off"
                              name="packing"
                              :id="packing"
                              v-model:value="formData.packing"
                              value=""
                          
                            >
                            </a-input>
                          </a-col>
                        </a-row>
        
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.Unit_1st')"
                                  name="unit_1st"
                                  :help="rules.unit_1st ? rules.unit_1st.message : null"
                                  :validateStatus="rules.unit_1st ? 'error' : null"
                                >
                                </a-form-item>
                              </a-col>
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <UnitSearchInput
                                  id="unit_1st"
                                  name="unit_1st"
                                  @valueChanged="(productId) => (formData.product_id = productId)"
                                  @valueSuccess="getStockValue"
                                  :productData="data"
                                  v-model:value="formData.unit_1st"
                                  placeholder="PCS"
                                
                                />
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.Unit_in_Decimal')"
                                  name="unit_in_decimal"
                                  :help="
                                    rules.unit_in_decimal ? rules.unit_in_decimal.message : null
                                  "
                                  :validateStatus="rules.unit_in_decimal ? 'error' : null"
                                >
                                </a-form-item>
                              </a-col>
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-select
                                  id="unit_in_decimal"
                                  v-model:value="formData.unit_in_decimal"
                                  style="width: 100%"
                                 
                                >
                                  <a-select-option key="No" value="No" aria-selected="true">
                                    No
                                  </a-select-option>
                                  <a-select-option key="Yes" value="Yes" aria-selected="true">
                                    Yes
                                  </a-select-option>
                                </a-select>
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
        
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.HSN_SAC')"
                                  name="hsn_sac1"
                                  :help="rules.hsn_sac ? rules.hsn_sac.message : null"
                                  :validateStatus="rules.hsn_sac ? 'error' : null"
                                >
                                </a-form-item>
                              </a-col>
        
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <HscSacSearchInput
                                  id="hsn_sac"
                                  name="hsn_sac"
                                  @valueChanged="
                                    (productId) => (formDataLedger.hsn_sac = productId)
                                  "
                                  @valueSuccess="getStockValue"
                                  :productData="data"
                                  v-model:value="formData.hsn_sac"
                               
                                />
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
        
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.Tax_Category')"
                                  name="tax_category"
                                  :help="rules.tax_category ? rules.tax_category.message : null"
                                  :validateStatus="rules.tax_category ? 'error' : null"
                                >
                                </a-form-item>
                              </a-col>
        
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <TaxCategorySearchInput
                                  id="tax_category"
                                  name="tax_category"
                                  @valueChanged="
                                    (productId) => (formDataLedger.product_id = productId)
                                  "
                                  @valueSuccess="getStockValue"
                                  :productData="data"
                                  v-model:value="formData.tax_category"
                                
                                />
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.Company')"
                                  name="company"
                                  :validateStatus="rules.company ? 'error' : ''"
                                  :help="rules.company"
                                >
                                </a-form-item>
                              </a-col>
        
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <ProductCompanySearchInput
                                  id="company"
                                  name="company"
                                  @valueChanged="
                                    (productId) => (formDataLedger.company = productId)
                                  "
                                  @valueSuccess="getStockValue"
                                  :productData="data"
                                  v-model:value="formData.company"
                                 
                                />
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
        
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.godowns')"
                                  name="god_owns"
                                  :help="rules.god_owns ? rules.god_owns.message : null"
                                  :validateStatus="rules.god_owns ? 'error' : null"
                                >
                                </a-form-item>
                              </a-col>
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <GodownsSearchInput
                                  id="god_owns"
                                  name="god_owns"
                                  ref="godowns"
                                  @valueChanged="
                                    (productId) => (formDataLedger.god_owns = productId)
                                  "
                                  @valueSuccess="getStockValue"
                                  :productData="data"
                                 
                                />
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.opening_stock')"
                                  name="opening_stock"
                                  :help="rules.opening_stock ? rules.opening_stock.message : null"
                                  :validateStatus="rules.opening_stock ? 'error' : null"
                                  class="required"
                                >
                                </a-form-item>
                              </a-col>
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-input
                                  autocomplete="off"
                                  name="opening_stock"
                                  :id="packing"
                                  v-model:value="formData.opening_stock"
                                  value=""
                                 
                                >
                                </a-input>
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                <a-form-item
                                  :label="$t('stock.as_date')"
                                  name="packing"
                                  :help="rules.packing ? rules.packing.message : null"
                                  :validateStatus="rules.packing ? 'error' : null"
                                >
                                </a-form-item>
                              </a-col>
                              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <input
                                  type="date"
                                  v-model="formData.as_on_date"
                                
                                  class="ant-input css-dev-only-do-not-override-wosfq4"
                                />
                              </a-col>
                            </a-row>
                          </a-col>
                        </a-row>
        
                        <!--- category field-->
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item
                              :label="$t('stock.low_Stock')"
                              name="name"
                              :help="rules.low_stock ? rules.low_stock.message : null"
                              :validateStatus="rules.low_stock ? 'error' : null"
                              class="required"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-input
                              autocomplete="off"
                              id="ProductInput"
                              name="name"
                              v-model:value="formData.low_stock"
                            
                              value=""
                             
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                      </a-col>
                      <!--  end second colunm -->
                    </a-row>
                    <!-- end category field-->
                  </fieldset>
                </a-col>
        
              </a-row>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
                <template #icon>
                    <SaveOutlined />
                </template>
                {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-modal>
</template>
<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";
import { message, notification } from "ant-design-vue";

import ProductCompanySearchInput from "../../../../../common/components/product/ProductCompanySearchInput.vue";
import TaxCategorySearchInput from "../../../../../common/components/product/TaxCategorySearchInput.vue";
import CategorySearchInput from "../../../../../common/components/product/CategorySearchInput.vue";
import UnitSearchInput from "../../../../../common/components/product/UnitSearchInput.vue";
import HscSacSearchInput from "../../../../../common/components/product/HscSacSearchInput.vue";


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
        ProductCompanySearchInput,
        TaxCategorySearchInput,
        CategorySearchInput,
        UnitSearchInput,
        HscSacSearchInput,


    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
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
            rules.value = {};
            emit("closed");
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,

            //drawerWidth: window.innerWidth <= 991 ? "90%" : "90%",
        };
    },
});
</script>

<style>
.addedit{
    width: 80% !important;
}
.ant-form-item {
  margin-bottom: 5px !important;
}

.popups {
  width: 81% !important;
  /*vertical-align: top !important;
    top:15px !important;*/
}

.ant-modal-footer {
  background-color: #e4e4e4 !important;
}

.ant-modal-body {
  background-color: #f6f6f6 !important;
}

.table-container {
  overflow-x: auto;
  margin: 0 auto;
}

indiannumber .responsive-table {
  width: 100%;
}

table,
th,
td {
  border-collapse: collapse !important;
  border: 1px solid #0000003d;
}

tr {
  background-color: white;
}

.tableheading {
  background-color: #7da5a7;
}

.responsive-table th,
.responsive-table td {
  padding: 4px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

@media (max-width: 600px) {
  .responsive-table thead {
    display: none;
  }

  .responsive-table,
  .responsive-table tbody,
  .responsive-table tr,
  .responsive-table td {
    display: block;
    width: 100%;
  }

  .responsive-table tr {
    margin-bottom: 15px;
  }

  .responsive-table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
  }

  .responsive-table td::before {
    content: attr(data-label);
    position: absolute;
    left: 0;
    width: 50%;
    padding-left: 15px;
    text-align: left;
    font-weight: bold;
  }
}

.buttons {
  margin-left: 5px;
}

.basicdetalis {
  font-size: 15px !important;
  font-weight: bolder;
}

.fieldheight {
  height: 142px;
}

.fieldheights {
  height: 143px;
}

button.btn .box {
  display: flex;
  align-items: center;
}

button.btn span.shortcut {
  display: inline-block;
  border-right: 1px solid #cacaca;
  padding-right: 4px;
  position: relative;
}

button.btn span.shortcut:after {
  position: absolute;
  right: -2px;
  top: 0;
  width: 1px;
  height: 100%;
  background: #fff;
  content: "";
}

button.btn .box span {
  padding: 0.01rem 0.5rem;
  display: flex;
  align-items: center;
}

button.btn {
  padding: 0.375rem 0.3rem;
  border: none;
  border: solid rgba(0, 0, 0, 0.2);
  border-width: 1px 1px 2px;
  text-transform: capitalize;
  font-size: 1rem;
  background: #fff;
  margin: 0 2px;
  font-size: 14px;
  height: 32px;
  padding: 4px 15px;
  border-radius: 4px;
}

.floats {
  text-align: start !important;
}

.createf2 {
  background-color: #1f6d70;
  color: white;
}

#ledger {
  display: flex !important;
  flex-flow: revert !important;
  min-width: 0 !important;
}

/*:where(.css-dev-only-do-not-override-wosfq4).ant-form-item .ant-form-item-label >label {
    width: 400px !important;
}*/
.ant-modal-header {
  padding: 7px 10px !important;
}

.balance {
  width: 100% !important;
}

.opening {
  display: flex;
}

.selecting {
  height: 37px !important;
}

.buttonstype {
  background-color: #ffffff;
  border-color: #d9d9d9;
  box-shadow: 0 2px 0 rgba(0, 0, 0, 0.02);
  margin: 1px;
  color: black;
  padding: 3px 7px !important;
}

.info {
  margin-top: -24px;
}

#closeicon {
  top: 14px !important;
}

.productnumber {
  padding: 2px 6px !important;
  background: #f6f6f6;
  border-color: #c2c2c2;
  color: black;

  text-align: center;
  height: 25px;
  font-size: 12px;
  border: 1px solid #ccc;
}

.gst {
  background-color: #1f6d70 !important;
  color: white !important;
}

button.btn {
  padding: 4px 7px !important;
}

/*.borderright {
    border-right: 1px solid #c2c2c2;
}*/
.ant-tabs-tab {
  position: relative !important;
  background: #fff !important;
  border-radius: 0 !important;
  border-bottom: 0 !important;
  border-top: 0 !important;
  display: block !important;
  padding: 10px 12px !important;
  line-height: 1 !important;
}

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs .ant-tabs-tab + .ant-tabs-tab {
  margin: 0 0 0 0px !important;
  border-left: 1px solid #e4e4e4 !important;
}

.ant-tabs-nav-wrap {
  background-color: #e4e4e4 !important;
  margin-top: 12px !important;
}

.ant-tabs-content-holder {
  border: 1px solid #c2c2c2 !important;
  background: #f6f6f6 !important;
  border-radius: 0 0 4px 4px !important;
  padding: 15px 12px 5px !important;
  position: relative !important;
  top: -16px !important;
  z-index: 0 !important;
  box-shadow: inset 0 0 0 1px #fff !important;
}

.error-message {
  color: green;
  font-size: 12px;
  margin-top: 4px;
}

button.btn {
  cursor: pointer;
}

.ant-form-item-explain-error {
  font-size: 13px;
}
.select-focused {
  background-color: #e0f7fa; /* Light blue */
}
.ant-select-selection-search-input {
  padding: 3% !important;
}
.ant-select-selection-search-input:focus {
  background-color: #ffd451 !important;
}

.inputing .ant-input:focus {
  border: 2px solid red !important;
  outline: none !important; /* Remove default outline */
}
.a-span:focus {
  border: 1px solid red !important;
  outline: none !important; /* Remove default outline */
}
.ant-select-selection-search {
  margin-left: -10px !important;
}
.css-dev-only-do-not-override-wosfq4.ant-select-dropdown
  .ant-select-item-option-selected:not(.ant-select-item-option-disabled) {
  color: rgb(244, 241, 241) !important;
  background-color: #5fa6c9 !important;
}
</style>