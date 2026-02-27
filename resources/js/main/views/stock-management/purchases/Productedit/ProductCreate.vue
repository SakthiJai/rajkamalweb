<template>
  <AdminPageHeader>
    <template #header>
      <!--- @closed="handleClose"-->
      <ProductModal
        v-if="isProuctsModalVisible"
        :visible="isProuctsModalVisible"
        :formData="formData"
        :url="url"
        :successMessage="successMessage"
        :addEditType="addEditType"
        @addEditSuccess="handleSuccess"
        @closed="handleProductModal"
        v-on:productclose-method="handleClose"
        v-on:child-method="updateProduct"
      />

      <!-- Print modal-->
      <PrintModel
        v-if="isPrintModalVisible"
        :visible="isPrintModalVisible"
        :formData="formData"
        :url="url"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handleClosePrint"
      />
      <!-- print modal-->

      <!--- cusomer delete modal -->

      <DeleteConfirmationPopupModel
        v-if="deleteConfirmationPopup"
        :visible="deleteConfirmationPopup"
        :formData="formData"
        :url="url"
        :modelId="salesentryDeleteModel"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handleClosepopup"
        @confirmDeletion="deleteItem"
      />
      <!-- end  customer delete -->
    </template>
    <template #breadcrumb>
      <a-breadcrumb separator="-" style="font-size: 12px">
        <a-breadcrumb-item>
          <router-link :to="{ name: 'admin.dashboard.index' }">
            {{ $t(`menu.dashboard`) }}
          </router-link>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          {{
            orderPageObject.type == "sales" ||
            orderPageObject.type == "sales-returns" ||
            orderPageObject.type == "quotations"
              ? $t(`menu.sales`)
              : $t(`menu.purchases`)
          }}
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <router-link
            :to="{
              name: `admin.stock.productitem.create`,
            }"
          >
            {{ $t(`menu.${orderPageObject.menuKey}`) }}
          </router-link>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          {{ $t(`common.create`) }}
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>
  </AdminPageHeader>

  <a-spin :spinning="spinning">
    <admin-page-table-content>
      <a-card class="page-content-container mt-5 mb-5">
        <form ref="myForm1" id="addproductform" layout="vertical" @submit.prevent="false">
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="16" :lg="16" class="info">
              <fieldset class="">
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
                          v-model:value="formDataLedger.name"
                          @blur="checkproductDuplicate($event)"
                          @input="clearValidationError('name')"
                          value=""
                          @keyup.enter="focusNext"
                          @keydown="tabFocusNext"
                        >
                        </a-input>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-select
                          id="product_type"
                          name="product_type"
                          v-model:value="formDataLedger.product_type"
                          style="width: 100%"
                          @keyup.enter="focusNext"
                        >
                          <a-select-option key="Goods" value="Goods" aria-selected="true">
                            Goods
                          </a-select-option>
                          <a-select-option
                            key="Service"
                            value="Service"
                            aria-selected="true"
                          >
                            Service
                          </a-select-option>
                        </a-select>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
                <!-- accountsgroup-->

                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item
                      :label="$t('Short Code')"
                      name="short_code"
                      :help="rules.short_code ? rules.short_code.message : null"
                      :validateStatus="rules.short_code ? 'error' : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-input
                      name="short_code"
                      id="short_code"
                      v-model:value="formDataLedger.short_code"
                      value=""
                      @input="onInputShortCode"
                      maxlength="10"
                      @keyup.enter="focusNext"
                      autocomplete="off"
                    >
                    </a-input>
                  </a-col>
                </a-row>

                <!-- end -->

                <!-- product -ID  -->
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('Product ID')"
                          name="product_id"
                          :help="rules.product_id ? rules.product_id.message : null"
                          :validateStatus="rules.product_id ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-input
                          autocomplete="off"
                          name="product_id"
                          id="product_id"
                          v-model:value="formDataLedger.product_id"
                          value=""
                          maxlength="10"
                          :placeholder="'0'"
                          @input="onInputProductId"
                          @keyup.enter="focusNext"
                        >
                        </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <!-- End Product -ID -->

                <!-- Brand Name -->
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Brand Name')"
                          name="name"
                          :help="rules.brand_name ? rules.brand_name.message : null"
                          :validateStatus="rules.brand_name ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <BrandSearchInput
                          id="brandsing"
                          name="brands"
                          @valueChanged="
                            (productId) => (formDataLedger.brand_name = productId)
                          "
                          @valueSuccess="getStockValue"
                          :productData="data"
                          v-model:value="formDataLedger.brand_name"
                          @keydown.enter="focusNext"
                          @input="validateField('brand_name')"
                        />
                      </a-col>
                    </a-row>

                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.category')" name="name">
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <CategorySearchInput
                          id="category_field"
                          name="category_field"
                          @valueChanged="
                            (productId) => (formDataLedger.category_field = productId)
                          "
                          @valueSuccess="getStockValue"
                          :productData="data"
                          @keydown.enter="focusNext"
                          v-model:value="formDataLedger.category_field"
                        />
                      </a-col>
                    </a-row>

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
                          name="packing"
                          id="packing"
                          v-model:value="formDataLedger.packing"
                          value=""
                          @keyup.enter="focusNext"
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
                              class="required"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <UnitSearchInput
                              id="unit_1st"
                              name="unit_1st"
                              @valueChanged="
                                (productId) => (formDataLedger.unit_1st = productId)
                              "
                              @valueSuccess="getStockValue"
                              :productData="data"
                              v-model:value="formDataLedger.unit_1st"
                              @keyup.enter="focusNext"
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
                                rules.unit_in_decimal
                                  ? rules.unit_in_decimal.message
                                  : null
                              "
                              :validateStatus="rules.unit_in_decimal ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-select
                              id="unit_in_decimal"
                              v-model:value="formDataLedger.unit_in_decimal"
                              style="width: 100%"
                              @keyup.enter="focusNext"
                            >
                              <a-select-option
                                key=""
                                value=""
                                disable
                                aria-selected="true"
                              >
                              </a-select-option>
                              <a-select-option key="Yes" value="Yes" aria-selected="true">
                                Yes
                              </a-select-option>
                              <a-select-option key="No" value="No" aria-selected="true">
                                No
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
                              name="hsn_sac"
                              :help="rules.hsn_sac ? rules.hsn_sac.message : null"
                              :validateStatus="rules.hsn_sac ? 'error' : null"
                              class="required"
                            >
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <HscSacSearchInput
                              id="hsn_sac"
                              name="hsn_sac"
                              @gstValueChanged="
                                (gstValue) => (formDataLedger.gst = gstValue)"
                              @valueChanged="
                                (productId) => (formDataLedger.hsn_sac = productId)"
                              @valueSuccess="getStockValue"
                              :productData="data"
                              v-model:value="formDataLedger.hsn_sac"
                              @keyup.enter="focusNext"
                              @input="validateField('hsn_sac')"
                            />
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>

                    <!-- Tax Rate -->

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item
                              :label="$t('Tax Rate')"
                              name="gst"
                              :help="rules.gst ? rules.gst.message : null"
                              :validateStatus="rules.gst ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-input
                              autocomplete="off"
                              id="gst"
                              v-model:value="formDataLedger.gst"
                              value=""
                              maxlength="10"
                              @input="onInputProductId"
                              @keyup.enter="focusNext"
                              readonly
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
                              :label="$t('stock.Company')"
                              name="company"
                              :help="rules.company ? rules.company.message : null"
                              :validateStatus="rules.company ? 'error' : null"
                              class="required"
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
                              v-model:value="formDataLedger.company"
                              @keyup.enter="focusNext"
                              @input="validateField('company')"
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
                              class="required"
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
                              v-model:value="formDataLedger.god_owns"
                              @valueSuccess="getStockValue"
                              :productData="data"
                              @input="validateField('god_owns')"
                              @keydown.enter="focusNext"
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
                              :help="
                                rules.opening_stock ? rules.opening_stock.message : null
                              "
                              :validateStatus="rules.opening_stock ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-input
                              autocomplete="off"
                              name="opening_stock"
                              id="opening_stock"
                              v-model:value="formDataLedger.opening_stock"
                              value=""
                              @input="onInputOpeningStock"
                              maxlength="5"
                              :placeholder="'0'"
                              @keyup.enter="focusNext"
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
                              id="as_on_date"
                              v-model="formDataLedger.as_on_date"
                              @keyup.enter="focusNext"
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
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-input
                          autocomplete="off"
                          id="low_stock"
                          name="name"
                          v-model:value="formDataLedger.low_stock"
                          value=""
                          @input="onInputLowStock"
                          @keyup.enter="focusNext"
                        >
                        </a-input>
                      </a-col>
                    </a-row>
                     <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Manufacturer')"
                          name="manufacturer"
                          :help="rules.manufacturer ? rules.manufacturer.message : null"
                          :validateStatus="rules.manufacturer ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="12" :lg="12" style="padding-left: 10px;padding-right: 10px;">
                        <a-input
                          name="manufacturer"
                          id="manufacturer"
                          v-model:value="formDataLedger.manufacturer"
                          value=""
                          @keyup.enter="focusNext"
                        >
                        </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                  <!-- second colunm -->
                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('M_R_P ')"
                          name="mrp"
                          :validateStatus="rules.mrp ? 'error' : ''"
                          :help="rules.mrp ? rules.mrp.message : ''"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-input-group compact style="display: flex">
                          <a-span type="primary" class="productnumber inputing">
                            INR
                          </a-span>

                          <a-input
                            autocomplete="off"
                            v-model:value="formDataLedger.mrp"
                            style="width: 79%"
                            type="text"
                            maxlength="10"
                            :placeholder="'0.00'"
                            @blur="validateMRP"
                            @input="onInputMrp"
                            @keydown.enter="focusNext"
                            @keydown.tab="TabFocusNext"
                            @focus="focusinputvalue($event)"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>

                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Purchase_Rate')"
                          name="purchase_rate"
                          class="required"
                          :validateStatus="rules.purchase_rate ? 'error' : null"
                          :help="rules.purchase_rate ? rules.purchase_rate.message : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-input-group compact style="display: flex">
                          <a-span type="primary" class="productnumber"> INR </a-span>
                          <a-input
                            id="Purchase_Rate"
                            autocomplete="off"
                            v-model:value="formDataLedger.purchase_rate"
                            style="width: 79%"
                            type="number"
                            maxlength="10"
                            :placeholder="'0.00'"
                            @blur="validatePurchaseRate"
                            @input="onInputPurchaserate"
                            @keyup.enter="focusNext"
                            @keydown.tab="TabFocusNext1"
                            @focus="focusinputvalue($event)"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>

                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Cost')"
                          name="cost"
                          :validate-status="rules.cost ? 'error' : ''"
                          :help="rules.cost"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-input-group compact style="display: flex">
                          <a-span type="primary" class="productnumber"> INR </a-span>
                          <a-input
                            autocomplete="off"
                            v-model:value="formDataLedger.cost"
                            style="width: 79%"
                            type="text"
                            maxlength="10"
                            id="cost"
                            :placeholder="'0.00'"
                            @blur="validateCostRate"
                            @input="onInputCost"
                            @keyup.enter="focusNext"
                            @focus="focusinputvalue($event)"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>

                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Sale_Rate')"
                          name="sale_rate"
                          :help="rules.sale_rate ? rules.sale_rate.message : null"
                          :validateStatus="rules.sale_rate ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-input-group compact style="display: flex">
                          <a-span type="primary" class="productnumber"> INR </a-span>
                          <a-input
                            autocomplete="off"
                            v-model:value="formDataLedger.sale_rate"
                            style="width: 79%"
                            value=""
                            type="text"
                            maxlength="10"
                            :placeholder="'0.00'"
                            @blur="validateSaleRate"
                            @input="onInputSaleRate"
                            @keyup.enter="focusNext"
                            @keydown.tab="TabFocusNext2"
                            @focus="focusinputvalue($event)"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>

                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Rate_B')"
                          name="rate_b"
                          :validateStatus="rules.rate_b ? 'error' : ''"
                          :help="rules.rate_b"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-input-group compact style="display: flex">
                          <a-span type="primary" class="productnumber"> INR </a-span>
                          <a-input
                            autocomplete="off"
                            v-model:value="formDataLedger.rate_b"
                            style="width: 79%"
                            type="Number"
                            maxlength="10"
                            :placeholder="'0.00'"
                            @blur="validateRateB"
                            @input="onInputRateB"
                            @keyup.enter="focusNext"
                            @focus="focusinputvalue($event)"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Free_Scheme')"
                          value=""
                          :validateStatus="rules.Free_Scheme ? 'error' : ''"
                          :help="rules.Free_Scheme"
                        >
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="6" :lg="6">
                        <a-input
                          autocomplete="off"
                          name="free_scheme_1"
                          v-model:value="formDataLedger.free_scheme_1"
                          value=""
                          style="width: 127%"
                          type="text"
                          maxlength="10"
                          @blur="validateFreeScheme1"
                          @input="onInputFreeScheme1"
                          @keyup.enter="focusNext"
                          @focus="focusinputvalue($event)"
                        >
                        </a-input>
                      </a-col>
                      +
                      <a-col :xs="24" :sm="24" :md="6" :lg="6">
                        <a-input
                          autocomplete="off"
                          name="free_scheme_2"
                          v-model:value="formDataLedger.free_scheme_2"
                          value=""
                          style="width: 127%"
                          type="text"
                          maxlength="10"
                          @blur="validateFreeScheme2"
                          @input="onInputFreeScheme2"
                          @keyup.enter="focusNext"
                          @focus="focusinputvalue($event)"
                        >
                        </a-input>
                      </a-col>
                    </a-row>
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Scheme_Type')"
                          name="scheme_type"
                          :help="rules.Scheme_Type ? rules.Scheme_Type.message : null"
                          :validateStatus="rules.Scheme_Type ? 'error' : null"
                        ></a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-select
                          id="scheme_type"
                          v-model:value="formDataLedger.scheme_type"
                          class="balance"
                          @keyup.enter="focusNext"
                        >
                          <a-select-option
                            key="Full Scheme"
                            value="Full Scheme"
                            aria-selected="true"
                          >
                            Full Scheme
                          </a-select-option>
                          <a-select-option
                            key="Half Scheme"
                            value="Half Scheme"
                            aria-selected="true"
                          >
                            Half Scheme
                          </a-select-option>
                          <a-select-option
                            key="1/3 Scheme"
                            value="1/3 Scheme"
                            aria-selected="true"
                          >
                            1/3 Scheme
                          </a-select-option>
                          <a-select-option
                            key="Qtr. Scheme"
                            value="Qtr. Scheme"
                            aria-selected="true"
                          >
                            Qtr. Scheme
                          </a-select-option>
                          <a-select-option
                            key="All Scheme"
                            value="All Scheme"
                            aria-selected="true"
                          >
                            All Scheme
                          </a-select-option>
                          <a-select-option
                            key="Whole Scheme"
                            value="Whole Scheme"
                            aria-selected="true"
                          >
                            Whole Scheme
                          </a-select-option>
                          <a-select-option
                            key="No Scheme"
                            value="No Scheme"
                            aria-selected="true"
                          >
                            No Scheme
                          </a-select-option>
                        </a-select>
                      </a-col>
                    </a-row>
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('Status')"
                          name="status"
                          :help="rules.Status ? rules.Status.message : null"
                          :validateStatus="rules.Status ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-select
                          id="stock_status"
                          v-model:value="formDataLedger.status"
                          class="balance"
                          @keyup.enter="focusNext"
                        >
                          <a-select-option
                            key="Continue"
                            value="Continue"
                            aria-selected="true"
                          >
                            Continue
                          </a-select-option>
                          <a-select-option
                            key="Discontinue"
                            value="Discontinue"
                            aria-selected="true"
                          >
                            Discontinue
                          </a-select-option>
                        </a-select>
                      </a-col>
                    </a-row>
                    <a-row :gutter="24">
                      <a-col :xs="24" :sm="24" :md="10" :lg="10">
                        <a-form-item
                          :label="$t('stock.Color_Type')"
                          name="color_type"
                          :help="rules.Color_Type ? rules.Color_Type.message : null"
                          :validateStatus="rules.Color_Type ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="14" :lg="14">
                        <a-select
                          id="color_type"
                          v-model:value="formDataLedger.color_type"
                          class="balance"
                          @keyup.enter="focusNext"
                        >
                          <a-select-option
                            key=""
                            value=""
                            aria-selected="true"
                            style="background-color: skyblue"
                          >
                            --Blank--
                          </a-select-option>
                          <a-select-option
                            key="RED"
                            value="RED"
                            aria-selected="true"
                            style="background-color: red"
                          >
                            RED
                          </a-select-option>
                          <a-select-option
                            key="Yellow"
                            value="Yellow"
                            aria-selected="true"
                            style="background-color: yellow"
                          >
                            Yellow
                          </a-select-option>
                          <a-select-option
                            key="Blue"
                            value="Blue"
                            aria-selected="true"
                            style="background-color: blue"
                          >
                            Blue
                          </a-select-option>
                          <a-select-option
                            key="Green"
                            value="Green"
                            aria-selected="true"
                            style="background-color: green"
                          >
                            Green
                          </a-select-option>
                        </a-select>
                      </a-col>
                    </a-row>
                  </a-col>

                  <!--  end second colunm -->
                </a-row>
                <!-- end category field-->
              </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="8" :lg="8" class="info">
              <fieldset class="fieldheightsss">
                <legend class="basicdetalis">Advance Info</legend>
                <a-tabs v-model:activeKey="activeKey" @change="handleTabChange">
                  <a-tab-pane key="taxdetails" tab="Rate" class="tabing">
                    <template #tab class="actingtabs" id="activity">
                      <span @click="focusTab('taxdetails')"> Rate </span>
                    </template>
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Rate_D')"
                              name="rate_d"
                              :validateStatus="rules.rate_d ? 'error' : ''"
                              :help="rules.rate_d"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input-group compact>
                              <a-span type="primary" class="productnumber"> INR </a-span>
                              <a-input
                                autocomplete="off"
                                id="rate_d"
                                ref="taxdetailsFirstInput"
                                v-model:value="formDataLedger.rate_d"
                                style="width: 78%"
                                type="text"
                                maxlength="10"
                                :placeholder="'0.00'"
                                @blur="validateRateD"
                                @input="onInputRateD"
                                @keyup.enter="focusNext"
                                @focus="focusinputvalue($event)"
                              />
                            </a-input-group>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Rate_F')"
                              name="rate_f"
                              :validateStatus="rules.rate_f ? 'error' : ''"
                              :help="rules.rate_f"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input-group compact>
                              <a-span type="primary" class="productnumber"> INR </a-span>
                              <a-input
                                autocomplete="off"
                                v-model:value="formDataLedger.rate_f"
                                style="width: 78%"
                                type="text"
                                maxlength="10"
                                :placeholder="'0.00'"
                                @blur="validateRateF"
                                @input="onInputRateF"
                                @keyup.enter="focusNext"
                                @focus="focusinputvalue($event)"
                                @keydown.enter="handleEnterKey"
                              />
                            </a-input-group>
                          </a-col>
                        </a-row>
                        <a-form-item>
                          <span style="display: flex">
                            <ProductSearchInput
                              @valueChanged="
                                (productId) => (formData.product_id = productId)
                              "
                              @valueSuccess="getStockValue"
                              :productData="data"
                              @keyup.enter="focusNext"
                            />
                          </span>
                        </a-form-item>
                      </a-col>
                    </a-row>
                  </a-tab-pane>
                  <a-tab-pane key="contactinfo" tab="Discount">
                    <template #tab>
                      <span @click="focusTab('contactinfo')"> Discount </span>
                    </template>
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item :label="$t('Item Disc. 1 ')" name="item_disc">
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              id="item_disc_1_percent"
                              autocomplete="off"
                              name="item_disc"
                              ref="contactinfoFirstInput"
                              v-model:value="formDataLedger.item_disc_1_percent"
                              value=""
                              @keyup.enter="focusNext"
                              @focus="focusinputvalue($event)"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Discount')"
                              name="discount"
                              :help="rules.ledger_type ? rules.ledger_type.message : null"
                              :validateStatus="rules.ledger_type ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-select
                              id="discount"
                              v-model:value="formDataLedger.discount"
                              style="width: 100%"
                              @keyup.enter="focusNext"
                            >
                              <a-select-option
                                key="Applicable"
                                value="Applicable"
                                aria-selected="true"
                              >
                                Applicable
                              </a-select-option>
                              <a-select-option
                                key="No Services"
                                value="No Services"
                                aria-selected="true"
                              >
                                No Services
                              </a-select-option>
                            </a-select>
                          </a-col>
                        </a-row>

                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item :label="$t('stock.Volume_Dis_1')"> </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="volume_dis"
                              id="volume_disc_1"
                              v-model:value="formDataLedger.volume_disc_1"
                              value=""
                              @keyup.enter="focusNext"
                              @focus="focusinputvalue($event)"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item :label="$t('stock.Max_Disc')" name="max_disc">
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="max_disc"
                              v-model:value="formDataLedger.max_disc_percent"
                              value=""
                              @keyup.enter="focusNext"
                              @focus="focusinputvalue($event)"
                              @keydown.enter="handleEnterKey"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-form-item>
                          <span style="display: flex">
                            <ProductSearchInput
                              @valueChanged="
                                (productId) => (formData.product_id = productId)
                              "
                              @valueSuccess="getStockValue"
                              :productData="data"
                              @keyup.enter="focusNext"
                            />
                          </span>
                        </a-form-item>
                      </a-col>
                    </a-row>
                  </a-tab-pane>
                  <a-tab-pane key="bankdetails" tab="Quantity">
                    <template class="actingtabs">
                      <span @click="focusTab('bankdetails')"> Quantity </span>
                    </template>
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Min_Quantity')"
                              name="min_quantity"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="min_quantity"
                              v-model:value="formDataLedger.min_quantity"
                              value=""
                              @keyup.enter="focusNext"
                              ref="bankdetailsFirstInput"
                              @focus="focusinputvalue($event)"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Max_Quantity')"
                              name="max_quantity"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="max_quantity"
                              v-model:value="formDataLedger.max_quantity"
                              @keyup.enter="focusNext"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.ReorderDays')"
                              name="reorder_days"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="reorder_days"
                              v-model:value="formDataLedger.reorder_days"
                              value=""
                              @keyup.enter="focusNext"
                              @focus="focusinputvalue($event)"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.ReordeQty')"
                              name="reorder_qty"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="reorder_qty"
                              v-model:value="formDataLedger.reorder_qty"
                              value=""
                              @keyup.enter="focusNext"
                              @focus="focusinputvalue($event)"
                              @keydown.enter="handleEnterKey"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-form-item>
                          <span style="display: flex">
                            <ProductSearchInput
                              @valueChanged="
                                (productId) => (formData.product_id = productId)
                              "
                              @valueSuccess="getStockValue"
                              :productData="data"
                              @keyup.enter="focusNext"
                            />
                          </span>
                        </a-form-item>
                      </a-col>
                    </a-row>
                  </a-tab-pane>
                  <a-tab-pane key="visibility" tab="Others Info">
                    <template #tab>
                      <span @click="focusTab('visibility')"> Others Info </span>
                    </template>
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Min_Margin_others')"
                              name="min_margin_others"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              type="Number"
                              autocomplete="off"
                              name="min_margin_others"
                              id="margin"
                              v-model:value="formDataLedger.margin"
                              value="0.00"
                              ref="visibilityFirstInput"
                              @keyup.enter="focusNext"
                              @focus="focusinputvalue($event)"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Prohibited')"
                              name="prohibited"
                              :help="rules.pan_number ? rules.pan_number.message : null"
                              :validateStatus="rules.pan_number ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-select
                              v-model:value="formDataLedger.prohibited"
                              style="width: 100%"
                              @keyup.enter="focusNext"
                              id="prohibited"
                            >
                              <a-select-option
                                key="Registered"
                                value="Registered"
                                aria-selected="true"
                              >
                                No
                              </a-select-option>
                              <a-select-option
                                key="Composition"
                                value="Composition"
                                aria-selected="true"
                              >
                                Yes
                              </a-select-option>
                            </a-select>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item
                              :label="$t('stock.Visibility')"
                              name="visibility"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-select
                              id="visibility"
                              v-model:value="formDataLedger.visibility"
                              style="width: 100%"
                              @keyup.enter="focusNext"
                            >
                              <a-select-option
                                key="Registered"
                                value="Registered"
                                aria-selected="true"
                              >
                                Show
                              </a-select-option>
                              <a-select-option
                                key="Composition"
                                value="Composition"
                                aria-selected="true"
                              >
                                Hide
                              </a-select-option>
                            </a-select>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item :label="$t('stock.MFR_Name')" name="mfr_name">
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <a-input
                              autocomplete="off"
                              name="mfr_name"
                              v-model:value="formDataLedger.mfr_name"
                              value=""
                              @keyup.enter="focusNext"
                            >
                            </a-input>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-form-item :label="$t('stock.Upload_Image')" name="image">
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <!-- <a-input
                              autocomplete="off"
                              name="image"
                              v-model:value="formDataLedger.image"
                              value=""
                              @keyup.enter="focusNext"
                            >
                            </a-input> -->
                            <UploadProduct
                              ref="uploadComponent"
                              :formDataLedger="formDataLedger"
                              folder="product"
                              imageField="logo"
                              @onFileUploaded="
                                (file) => {
                                  formDataLedger.image = file.file;
                                  formDataLedger.image_url = file.file_url;
                                }
                              "
                            />
                          </a-col>
                        </a-row>
                        <a-form-item>
                          <span style="display: flex">
                            <ProductSearchInput
                              @valueChanged="
                                (productId) => (formData.product_id = productId)
                              "
                              @valueSuccess="getStockValue"
                              :productData="data"
                              @keyup.enter="focusNext"
                            />
                          </span>
                        </a-form-item>
                      </a-col>
                    </a-row>
                  </a-tab-pane>
                </a-tabs>
              </fieldset>
            </a-col>
          </a-row>
          <div class="floats">
            <a-row :gutter="16" style="margin-top: 20px">
              <a-col :xs="24" :sm="24" :md="7" :lg="7">
                <button
                  type="button"
                  id="switch_tab"
                  @click="switchTab"
                  title="Ledger"
                  class="btn default-btn"
                >
                  <span class="box">
                    <span class="shortcut"><code>F4</code></span>
                    <span>Switch Tab</span>
                  </span>
                </button>
              </a-col>
              <a-col :xs="24" :sm="24" :md="7" :lg="7"> </a-col>
              <a-col :xs="24" :sm="24" :md="10" :lg="10">
                <div style="float: right">
                  <button
                    @click="saveProductItem($event)"
                    type="button"
                    id="save-item"
                    title="Ledger"
                    class="btn default-btn ng-star-inserted gst"
                  >
                    <span class="box">
                      <span class="shortcut ng-star-inserted"><code>F8</code></span>
                      <span class="ng-star-inserted">Save</span>
                    </span>
                    <span class="effect"></span>
                  </button>
                  <button
                    type="button"
                    id="btn-Ledger"
                    title="Ledger"
                    class="btn default-btn ng-star-inserted"
                    @click="resetForm"
                  >
                    <span class="box">
                      <span class="shortcut ng-star-inserted">
                        <code>F9</code>
                      </span>
                      <span class="ng-star-inserted">Clear</span>
                    </span>
                    <span class="effect"></span>
                  </button>
                  <button
                    type="button"
                    id="btn-Ledger"
                    title="Ledger"
                    class="btn default-btn ng-star-inserted"
                    @click="showconfirm"
                  >
                    <span class="box"
                      ><span class="shortcut ng-star-inserted"><code>Esc</code></span
                      ><span class="ng-star-inserted">Close</span></span
                    ><span class="effect"></span>
                  </button>
                </div>
              </a-col>
            </a-row>
          </div>
        </form>
        <template #footer class="floats" style="display: none !important"> </template>
      </a-card>
    </admin-page-table-content>
  </a-spin>
</template>

<script>
import { defineComponent, reactive, onMounted, watch, ref } from "vue";
import {
  PlusOutlined,
  LoadingOutlined,
  SaveOutlined,
  EditOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import ExpenseCategoryModal from "../ExpenseCategoryModal.vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import fields from "../../../stock-management/purchases/ProductItem/fields";
import DiscountModel from "../DiscountModel.vue";
import LimitModel from "../LimitModel.vue";
import OtherModel from "../OtherModel.vue";
import BillingModel from "../BillingModel.vue";
import CopyModel from "../CopyModel.vue";
import LocationModel from "../LocationModel.vue";
import BirthdayModel from "../BirthdayModel.vue";
import GSTModel from "../../purchases/GSTModel.vue";

import { message, notification } from "ant-design-vue";
import ProductCompanySearchInput from "../../.../../../../../common/components/product/ProductCompanySearchInput.vue";
import UploadProduct from "../../../../../common/core/ui/file/UploadProduct.vue";
import UploadExpenses from "../../../../../common/core/ui/file/UploadExpenses.vue";

import TaxCategorySearchInput from "../.../../../../../../common/components/product/TaxCategorySearchInput.vue";
import UnitSearchInput from "../.../../../../../../common/components/product/UnitSearchInput.vue";
import HscSacSearchInput from "../.../../../../../../common/components/product/HscSacSearchInput.vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";
import GodownsSearchInput from "../.../../../../../../common/components/product/GodownsSearchInput.vue";
import CategorySearchInput from "../.../../../../../../common/components/product/CategorySearchInput.vue";

import BrandSearchInput from "../.../../../../../../common/components/product/BrandSearchInput.vue";
import { useRouter, useRoute } from "vue-router";
export default defineComponent({
  props: ["productData"],
  emits: ["valueSuccess", "valueChanged"],
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    DeleteOutlined,
    EditOutlined,
    ExpenseCategoryModal,
    DiscountModel,
    LimitModel,
    OtherModel,
    BillingModel,
    CopyModel,
    LocationModel,
    BirthdayModel,
    GSTModel,
    SearchOutlined,
    ProductCompanySearchInput,
    TaxCategorySearchInput,
    UnitSearchInput,
    HscSacSearchInput,
    ExclamationCircleOutlined,
    UploadProduct,
    CategorySearchInput,
    GodownsSearchInput,
    BrandSearchInput,
    UploadExpenses,
  },

  setup(props, { emit }) {
    const { addEditRequestAdmin, loading, rules } = apiAdmin();
    const { adjustmentTypes } = fields();
    const onClose = () => {
      rules.value = {};
      emit("closed");
    };

    const ledgerSubmit = (formDataLedger) => {
      // Save reference to Vue component instance
      addEditRequestAdmin({
        url: `products-store`,
        data: formDataLedger,
        success: (res) => {
          // You can also use `that` here
          notification.success({
            placement: "bottomRight",
            message: "Product saved successfully",
          });
          navigateAndFocus(); // No need to bind 'this' explicitly here
        },
      });
    };

    const navigateAndFocus = () => {
      setTimeout(() => {
        // Ensure the router instance is available
        if (router) {
          router.push({ name: "admin.productedit.index" });
          const searchInput = document.getElementById("searchInput");
          if (searchInput) {
            searchInput.focus();
          }
        } else {
          console.error("Router is not defined");
        }
      }, 2000);
    };

    const router = useRouter();

    //console.log(router.params.id);

    const valueChanged = (value, option) => {
      emit("valueChanged", value);
      emit("valueSuccess");
    };

    return {
      loading,
      rules,
      onClose,
      //formDataLedger,
      ledgerSubmit,
      // getStockValue,
      valueChanged,
      navigateAndFocus,
      drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
    };
  },

  data() {
    return {
      formDataLedger: {
        id: "",
        company_id: "",
        warehouse_id: "",
        product_type: "Goods",
        parent_id: "",
        parent_item_code: "",
        name: "",
        slug: "",
        barcode_symbology: "",
        item_code: "",
        image: "",
        category_id: "",
        brand_id: "",
        unit_id: "",

        god_owns_id: "",
        opening_stock: "",
        product_id: "",
        short_code: "",
        description: "",
        user_id: "",
        created_at: "",
        updated_at: "",
        unit_1st: "",
        unit_in_decimal: "",
        hsn_sac: "",
        gst: "",
        company: null,
        mrp: "0.00",
        purchase_rate: "0.00",
        cost: "0.00",
        sale_rate: "0.00",
        rate_b: "0.00",
        free_scheme_1: 0,
        free_scheme_2: 0,
        scheme_type: "Full Scheme",
        status: "Continue",
        color_type: "",
        rate_d: "0.00",
        rate_f: "0.00",
        discount: "Applicable",
        item_disc_1_percent: "0.00",
        volume_disc_1: "0.00",
        max_disc_percent: "0.00",
        min_quantity: 0,
        max_quantity: 0,
        reorder_days: 0,
        reorder_qty: 0,
        margin: "0.00",
        prohibited: "Yes",
        visibility: "Show",
        mfr_name: "",
        upload_image: "",
        packing: "",
        manufacturer: "",
        sales_rate: "",
        as_on_date: "",
      },
      duplicate: false,
      inputColor: "",
      partyNameColor: "",
      accountGroupColor: "",
      stationsColor: "",
      tabPressCount: 0,
      showInput: true,
      isModalVisible: false,
      isModalVisibles: false,
      isModalVisibleing: false,
      isModalBillingVisible: false,
      isModalCopy: false,
      isLocationModal: false,
      isModalBirthday: false,
      isModalGST: false,
      spinning: false,
      url: "your-url-here",
      addEditType: "add",
      pageTitle: "Modify Ledger",
      successMessage: "Operation successful!",
      activeKey: "taxdetails",
      currentFocusElement: null,

      rules: {
        mrp: null,
        purchase_rate: null,
        cost: null,
        sale_rate: null,
        rate_b: null,
        rate_d: null,
        rate_f: null,
      },
      validationStatus: {
        mrp: "",
        purchase_rate: "",
        cost: "",
        sale_rate: "",
        rate_b: "",
        rate_d: "",
        rate_f: "",
      },

      resetForm() {
        this.formDataLedger = {
          product_type: "Goods",
        };
      },
    };
  },

  cancelAddProduct() {
    let that = this;

    Modal.confirm({
      title: "Confirmation",
      icon: createVNode(ExclamationCircleOutlined),
      content: "Transaction data will be lost. Are you sure you want to close?",
      autoFocusButton: "ok",
      onOk() {
        that.$emit("closed");
      },
    });
  },

  methods: {
    validateInput() {
      let isValid = true;
      const fieldsToValidate = [
        { key: "name", label: "This Field is required." },
        { key: "god_owns", label: "This Field is required." },
        { key: "hsn_sac", label: "This Field is required." },

        { key: "unit_1st", label: "This Field is required." },
        { key: "company", label: "This Field is required." },
        { key: "brand_name", label: "This Field is required." },

        //call by methide extra valdiatone
        { key: "mrp", label: "This Field is required." },
        { key: "purchase_rate", label: "This Field is required." },
        { key: "sale_rate", label: "This Field is required." },
      ];

      fieldsToValidate.forEach((field) => {
        const value = this.formDataLedger[field.key];
        console.log(`Validating ${field.key}: `, value);

        let isFieldValid = true;
        if (
          field.key === "mrp" ||
          field.key === "purchase_rate" ||
          field.key === "sale_rate"
        ) {
          const numericValue = parseFloat(value);
          if (isNaN(numericValue) || numericValue <= 0) {
            isFieldValid = false;
          }
        } else {
          if (!value || (typeof value === "string" && value.trim() === "")) {
            isFieldValid = false;
          }
        }

        if (!isFieldValid) {
          this.rules[field.key] = { message: field.label };
          isValid = false;
        } else {
          this.rules[field.key] = null;
        }
      });

      return isValid;
    },

    //for productname clear
    clearValidationError(field) {
      const fieldValue = this.formDataLedger[field];

      const fieldLabel = this.rules[field]?.message || field;

      if (!fieldValue || fieldValue.trim() === "") {
        this.rules[field] = {
          message: "This Field is required.",
        };
      } else {
        this.rules[field] = null;
      }
    },

    //for search clear
    validateField(field) {
      const value = this.formDataLedger[field];
      const valueed = this.rules[field]?.message || field;

      // if (!value) {
      //   this.rules[field] = {
      //     message: "This Field is requireddddddddd.",
      //   };
      // } else {
      //   this.rules[field] = null;
      // }
    },

    EnterfocusNext(event) {},

    validateMRP() {
      let value = this.formDataLedger.mrp;

      if (!value) {
        this.formDataLedger.mrp = "0.00";
        return;
      }
      if (!value.includes(".")) {
        this.formDataLedger.mrp = parseFloat(value).toFixed(2);
      } else {
        this.formDataLedger.mrp = parseFloat(value).toFixed(2);
      }
      const mrp = parseFloat(this.formDataLedger.mrp);
      const purchaseRate = parseFloat(this.formDataLedger.purchase_rate);
      this.rules.mrp = null;
      if (mrp > 0 && purchaseRate > 0 && mrp <= purchaseRate) {
        notification.error({
          placement: "bottomRight",
          message: "Validation Error",
          description: `MRP should be greater than or equal to PR.`,
        });
        return false;
      }
      return true;
    },

    getStockValue(value) {
      console.log();
      document.getElementById("hsn_sac").innerHtml = "demo";
      //this.formDataLedger.hsn_sac=2;
    },
    validatePurchaseRate() {
      let value = this.formDataLedger.purchase_rate;

      if (!value) {
        this.formDataLedger.purchase_rate = "0.00";
        return;
      }

      if (!value.includes(".")) {
        this.formDataLedger.purchase_rate = parseFloat(value).toFixed(2);
      } else {
        this.formDataLedger.purchase_rate = parseFloat(value).toFixed(2);
      }

      const purchaseRate = parseFloat(this.formDataLedger.purchase_rate);
      const mrp = parseFloat(this.formDataLedger.mrp);
      this.rules.purchase_rate = null;
      if (mrp > 0 && purchaseRate > 0 && purchaseRate > mrp) {
        this.rules.purchase_rate = "Purchase Rate should be less than or equal to MRP.";
        notification.error({
          placement: "bottomRight",
          message: "Validation Error",
          description: this.rules.purchase_rate,
        });
        return false;
      }
      return true;
    },

    focusNextField() {
      if (this.validatePurchaseRate()) {
        this.$nextTick(() => {
          this.$refs.costInput.focus();
        });
      }
    },
    //focus input//
    focusNextFields() {
      if (this.validateCostRate()) {
        this.$nextTick(() => {
          this.$refs.salesrate.focus();
        });
      }
    },

    //end foucs input//

    focusNextFieldsales() {
      console.log("testttt");
      if (this.validateSaleRate()) {
        this.$nextTick(() => {
          this.$refs.free_scheme_1.focus();
        });
      }
    },

    validateRateD() {
      let value = this.formDataLedger.rate_d;

      if (!value) {
        this.formDataLedger.rate_d = "0.00";
        return;
      }

      if (!value.includes(".")) {
        this.formDataLedger.rate_d = parseFloat(value).toFixed(2);
      } else {
        this.formDataLedger.rate_d = parseFloat(value).toFixed(2);
      }
    },

    validateRateB() {
      let value = this.formDataLedger.rate_b;

      if (!value) {
        this.formDataLedger.rate_b = "0.00";
        return;
      }

      if (!value.includes(".")) {
        this.formDataLedger.rate_b = parseFloat(value).toFixed(2);
      } else {
        this.formDataLedger.rate_b = parseFloat(value).toFixed(2);
      }
    },

    // Validate Cost
    validateSaleRate() {
      let value = this.formDataLedger.sale_rate;

      if (!value) {
        this.formDataLedger.sale_rate = "0.00";
        return;
      }

      if (!value.includes(".")) {
        this.formDataLedger.sale_rate = parseFloat(value).toFixed(2);
      } else {
        this.formDataLedger.sale_rate = parseFloat(value).toFixed(2);
      }

      const mrp = parseFloat(this.formDataLedger.mrp);
      const saleRate = parseFloat(this.formDataLedger.sale_rate);

      this.rules.sale_rate = null;

      // Check if saleRate is greater than or equal to MRP
      if (saleRate >= mrp) {
        this.rules.sale_rate = "Sale rate should be less than MRP.";
        notification.error({
          placement: "bottomRight",
          message: "Validation Error",
          description: this.rules.sale_rate,
        });
        return false;
      }

      return true;
    },

    validateCostRate() {
      let value = this.formDataLedger.cost;

      if (!value) {
        this.formDataLedger.cost = "0.00";
        return;
      }

      if (!value.includes(".")) {
        this.formDataLedger.cost = parseFloat(value).toFixed(2);
      } else {
        this.formDataLedger.cost = parseFloat(value).toFixed(2);
      }

      const purchaseRate = parseFloat(this.formDataLedger.purchase_rate);
      const cost = parseFloat(this.formDataLedger.cost);
      this.rules.cost = null;
      if (cost < purchaseRate) {
        this.rules.cost = "Cost Rate should be greater than or equal to purchaseRate.";
        notification.error({
          placement: "bottomRight",
          message: "Validation Error",
          description: this.rules.cost,
        });
        return false;
      }
      return true;
    },

    onInputMrp() {
      this.validateMRP();
    },

    onInputPurchaserate() {
      this.validatePurchaseRate();
    },
    onInputCost() {
      this.validateCostRate();
    },
    onInputSaleRate() {
      this.validateSaleRate();
    },

    onInputRateB() {
      // this.validateRateB();
    },

    onInputRateD() {
      ///this.validateRateD();
    },

    onInputRateF() {
      //this.validateRateF();
    },

    switchTab() {
  const tabs = ["taxdetails", "contactinfo", "bankdetails", "visibility"];
  const currentIndex = tabs.indexOf(this.activeKey);
  this.activeKey = tabs[(currentIndex + 1) % tabs.length];
  this.$nextTick(() => {
    setTimeout(() => {
      const refName = `${this.activeKey}FirstInput`;
      if (this.$refs[refName]) {
        this.$refs[refName].focus();
      }
    }, 100);
  });
},


    focusTab(tabKey) {
      console.log(`Switching to tab: ${tabKey}`);
      this.activeKey = tabKey;
      this.$nextTick(() => {
        this.focusFirstInput(tabKey);
      });
    },
    handleTabChange(activeKey) {
      this.activeKey = activeKey;
      console.log(`Switched to tab: ${activeKey}`);

      this.$nextTick(() => {
        this.focusFirstInput(activeKey);
      });
    },

    focusFirstInput(activeTab) {
      const firstInputRefs = {
        taxdetails: "taxdetailsFirstInput",
        contactinfo: "contactinfoFirstInput",
        bankdetails: "bankdetailsFirstInput",
        visibility: "visibilityFirstInput",
      };

      const firstInputRef = firstInputRefs[activeTab];
      console.log(`Focusing input for tab: ${activeTab}, ref: ${firstInputRef}`);

      if (this.$refs[firstInputRef]) {
        this.$refs[firstInputRef].focus();
        console.log(`Selected Heading : ${firstInputRef}`);
      } else {
        console.warn(`Input ref not found for tab: ${activeTab}`);
      }
    },

    // inr or Rupees valdiation
    onInputMrp(event) {
      let value = event.target.value;

      value = value.replace(/[^0-9.]/g, "");

      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }

      this.formDataLedger.mrp = value;
    },

    onInputPurchaserate(event) {
      let value = event.target.value;

      value = value.replace(/[^0-9.]/g, "");

      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }
      this.formDataLedger.purchase_rate = value;
    },

    onInputCost(event) {
      let value = event.target.value;

      value = value.replace(/[^0-9.]/g, "");

      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }
      this.formDataLedger.cost = value;
    },
    onInputSaleRate(event) {
      let value = event.target.value;

      value = value.replace(/[^0-9.]/g, "");

      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }
      this.formDataLedger.sale_rate = value;
    },

    onInputRateB(event) {
      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }
      this.formDataLedger.rate_b = value;
    },

    onInputRateD(event) {
      let value = event.target.value;

      value = value.replace(/[^0-9.]/g, "");

      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }
      this.formDataLedger.rate_d = value;
    },

    onInputOpeningStock(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.opening_stock = value;
    },

    onInputLowStock(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.low_stock = value;
    },

    onInputShortCode(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.short_code = value;
    },

    onInputProductId(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.product_id = value;
    },

    onInputRateF(event) {
      let value = event.target.value;

      value = value.replace(/[^0-9.]/g, "");

      if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf("."));
      }

      // Ensure only two decimal places
      if (value.includes(".")) {
        let parts = value.split(".");
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts.join(".");
      }
      this.formDataLedger.rate_f = value;
    },

    onInputFreeScheme1(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.free_scheme_1 = value;
    },

    onInputFreeScheme2(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.free_scheme_2 = value;
    },

    resetForm() {
      if (this.$refs.myForm1) {
        this.$refs.myForm1.resetFields();
      }
    },
    onSubmitLedger(eventKey) {
      try {
        console.log(eventKey, "<>", this.formDataLedger);
        if (eventKey == "F8" && this.validateInput()) {
          this.ledgerSubmit(this.formDataLedger);
        }
      } catch (error) {
        this.$message.error(this.$t("Issue while Product Save !"));
        console.error(error);
      }
    },
    saveProductItem(eventKey) {
      try {
        console.log("saveProductItem");
        if (this.validateInput()) {
          this.ledgerSubmit(this.formDataLedger);
        }
      } catch (error) {
        this.$message.error(this.$t("Issue while Product Save !"));
        console.error(error);
      }
    },

    handleLocationClose() {
      this.isLocationModal = false;
    },
    handleCopy() {
      this.isModalCopy = false;
    },
    handleClose() {
      this.showconfirm();
    },

    showconfirm() {
      console.log("Esc called");
      let that = this;
      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Transaction data will be lost. Are you sure you want to close?",
        onOk() {
          document.removeEventListener("keydown", that.handleKeydown);
          that.$router.push({
            name: `admin.productedit.index`,
          });
        },
        onCancel() {
          that.$refs.ProductInput && that.$refs.ProductInput.focus();
        },
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
      });

      this.$nextTick(() => {
        console.log("test");
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

            const focusedElement = document.activeElement;

            if (e.key === "ArrowLeft") {
              if (focusedElement === okBtn) {
                cancelBtn?.focus();
              } else {
                cancelBtn?.focus();
              }
            } else if (e.key === "ArrowRight") {
              if (focusedElement === cancelBtn) {
                okBtn?.focus();
              } else {
                okBtn?.focus();
              }
            }
          }
        };
        document.addEventListener("keydown", handleKeydown);
      });
    },

    handleCloses() {
      this.showconfirm();
    },
    handleClosing() {
      this.isModalVisibleing = false;
    },
    handlebillingClose() {
      this.isModalBillingVisible = false;
    },
    handleBirthday() {
      this.isModalBirthday = false;
    },
    handleGST() {
      this.isModalGST = false;
    },
    handleSuccess(xid) {
      this.isModalVisible = false;
      document.removeEventListener("keydown", this.handleKeydown);
      console.log("Success:", xid);
    },
    handleKeydown(event) {
      console.log("Product event", event.target.id);

      if (
        event.keyCode === 13 &&
        (event.target.id === "hsn_sac" ||
          event.target.id === "company" ||
          event.target.id === "god_owns" ||
          event.target.id === "category_field" ||
          event.target.id == "brandsing" ||
          event.target.id == "unit_1st")
      ) {
        this.focusNext(event);
      } else if (event.key === "Escape" || event.key === 27) {
        console.log(
          "ESC pressed on element:",
          event.target.id,
          "Tag:",
          event.target.tagName
        );
        if (
          event.target.id &&
          [
            "unit_in_decimal",
            "low_stock",
            "unit_1st",
            "color_type",
            "category_field",
            "hsn_sac",
            "brandsing",
          ].includes(event.target.id)
        ) {
          console.log("ESC ignored for:", event.target.id);
        } else if (["INPUT", "SELECT", "TEXTAREA"].includes(event.target.tagName)) {
          console.log("ESC ignored inside input field.");
          this.showconfirm();
        } else {
          console.log("ESC allowed, triggering showconfirm()");
        }
      } else if (event.key === "F9") {
        event.preventDefault();
        this.resetForm();
      } else if (event.key === "F4") {
        event.preventDefault();
        this.switchTab();
        this.focusinputvalue();
      } else if (event.key === "F8") {
        event.preventDefault();
        if (this.validateInput()) {
          this.onSubmitLedger(event.key);
        }
      }
    },

    changeColorOnFocus(inputField) {
      if (inputField === "partyName") {
        this.partyNameColor = "#ffd451";
      } else if (inputField === "accountGroup") {
        this.accountGroupColor = "#ffd451";
      } else if (inputField === "stations") {
        this.stationsColor = "#ffd451";
      }
    },
    resetColorOnBlur(inputField) {
      if (inputField === "partyName") {
        this.partyNameColor = "";
      } else if (inputField === "accountGroup") {
        this.accountGroupColor = "";
      } else if (inputField === "stations") {
        this.stationsColor = "";
      }
    },

    tabFocusNext(event) {
      if (event.key === "Tab") {
        console.log("Tab focusNext", this.formDataLedger.name);
        if (!this.formDataLedger.name) {
          event.preventDefault();
        }
      }
    },

    TabFocusNext(e) {
      if (!this.formDataLedger.mrp || this.formDataLedger.mrp === "0.00") {
        e.preventDefault();
      }
    },

    TabFocusNext1(e) {
      if (
        !this.formDataLedger.purchase_rate ||
        this.formDataLedger.purchase_rate === "0.00"
      ) {
        e.preventDefault();
      }
    },

    TabFocusNext2(e) {
      if (!this.formDataLedger.sale_rate || this.formDataLedger.sale_rate === "0.00") {
        e.preventDefault();
      }
    },

    handleEnterKey(event) {
      event.preventDefault();
      this.switchTab();
    },
    // DiscountHeaderFocus(e){
    //  var a = document.getElementById("discount_header")
    //  console.log("akashakkkkk",a)
    // },

    focusNext(e) {
      console.log(this.formDataLedger.brand_name);
      const currentField = e.target;

      console.log("focusNext", e.target.id);
      const formElements = Array.from(
        currentField.form.querySelectorAll("input, select,date")
      );

      const currentIndex = formElements.indexOf(currentField);

      const nonMandatoryFieldsArray = [
        "product_type",
        "packing",
        "manufacturer",
        "unit_in_decimal",
        "as_on_date",
        "low_stock",
        "opening_stock",
        "cost",
        "color_type",
        "category_field",
        "rate_d",
        "item_disc_1_percent",
        "volume_disc_1",
        "margin",
        "prohibited",
      ];
      //console.log(e.target.id)

      if (currentIndex < formElements.length - 1) {
        if (
          e.target.id == "product_type" &&
          this.formDataLedger.product_type != undefined &&
          this.formDataLedger.product_type != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "unit_1st" &&
          this.formDataLedger.unit_1st != undefined &&
          this.formDataLedger.unit_1st != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "opening_stock" &&
          this.formDataLedger.opening_stock != undefined &&
          this.formDataLedger.opening_stock != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "rate_d" &&
          this.formDataLedger.rate_d != undefined &&
          this.formDataLedger.rate_d != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "item_disc_1_percent" &&
          this.formDataLedger.item_disc_1_percent != undefined &&
          this.formDataLedger.item_disc_1_percent != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "packing" &&
          this.formDataLedger.packing != undefined &&
          this.formDataLedger.packing != ""
        ){
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "manufacturer" &&
          this.formDataLedger.manufacturer != undefined &&
          this.formDataLedger.manufacturer != ""
        )
         {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "category_field" &&
          this.formDataLedger.category_field != undefined &&
          this.formDataLedger.category_field != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (e.target.id == "short_code") {
          formElements[currentIndex + 1].focus();
        } else if (e.target.id == "product_id") {
          formElements[currentIndex + 1].focus();
        }

        // else if (
        //   e.target.id == "hsn_sac" &&
        //   this.formDataLedger.hsn_sac != undefined &&
        //   this.formDataLedger.hsn_sac != ""
        // ) {
        //   formElements[currentIndex + 1].focus();
        // }
        else if (e.target.id == "taxRate") {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "unit_in_decimal" &&
          this.formDataLedger.unit_in_decimal != undefined &&
          this.formDataLedger.unit_in_decimal != "" &&
          this.formDataLedger.unit_in_decimal.trim() != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "company" &&
          this.formDataLedger.company != undefined &&
          this.formDataLedger.company != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "god_owns" &&
          this.formDataLedger.god_owns != undefined &&
          this.formDataLedger.god_owns != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "scheme_type" &&
          this.formDataLedger.scheme_type != undefined &&
          this.formDataLedger.scheme_type != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "stock_status" &&
          this.formDataLedger.status != undefined &&
          this.formDataLedger.status != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "color_type" &&
          this.formDataLedger.color_type != undefined &&
          this.formDataLedger.color_type != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "discount" &&
          this.formDataLedger.discount != undefined &&
          this.formDataLedger.discount != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "visibility" &&
          this.formDataLedger.visibility != undefined &&
          this.formDataLedger.visibility != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "rate_d" &&
          this.formDataLedger.rate_d != undefined &&
          this.formDataLedger.rate_d != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "brandsing" &&
          this.formDataLedger.brand_name != undefined &&
          this.formDataLedger.brand_name != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "hsn_sac" &&
          this.formDataLedger.hsn_sac != undefined &&
          this.formDataLedger.hsn_sac != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "rate_f" &&
          this.formDataLedger.rate_f != undefined &&
          this.formDataLedger.rate_f != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          formElements[currentIndex].value != undefined &&
          formElements[currentIndex].value != "" &&
          formElements[currentIndex].value != "0.00" &&
          formElements[currentIndex].value.trim() != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (nonMandatoryFieldsArray.indexOf(e.target.id) != -1) {
          formElements[currentIndex + 1].focus();
        } else {
          console.log(e.target.id, this.formDataLedger.hsn_sac);
          // document.getElementById('save-item').focus();
        }
      }
    },

    checkproductDuplicate(event) {
      console.log("API call");
      this.duplicate = false;
      if (
        this.formDataLedger.name != undefined &&
        this.formDataLedger.name != null &&
        this.formDataLedger.name != "" &&
        this.formDataLedger.name.trim() != "" &&
        event.key != "F8"
      ) {
        axiosAdmin
          .get("productsDuplicate/" + this.formDataLedger.name)
          .then((response) => {
            console.log(response);
            // Toastr Notificaiton
            if (response == 500) {
              this.duplicate = true;
              message.error("Product name already exist !");
            }
          })
          .catch((errorResponse) => {
            var err = errorResponse.data;
            const errorCode = errorResponse.status;
            var errorRules = {};

            if (errorCode == 422) {
              if (err.error && typeof err.error.details != "undefined") {
                var keys = Object.keys(err.error.details);
                for (var i = 0; i < keys.length; i++) {
                  // Escape dot that comes with error in array fields
                  var key = keys[i].replace(".", "\\.");

                  errorRules[key] = {
                    required: true,
                    message: err.error.details[keys[i]][0],
                  };
                }
              }

              rules.value = errorRules;
              message.error(t("common.fix_errors"));
            }

            if (err && err.message) {
              message.error(err.message);
              err = {
                error: {
                  ...err,
                },
              };
            }
          });
      }
    },

    focusinputvalue(event) {
      if (event.target) {
        event.target.select();
        console.log(`Input focused and text selected: ${event.target.name}`);
      }
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

    // form validation//
  },

  watch: {
    "formDataLedger.god_owns"(newValue) {
      this.validateField("god_owns");
    },
    "formDataLedger.unit_1st"(newValue) {
      this.validateField("unit_1st");
    },
    "formDataLedger.company"(newValue) {
      this.validateField("company");
    },
    "formDataLedger.brand_name"(newValue) {
      this.validateField("brand_name");
    },
    "formDataLedger.hsn_sac"(newValue) {
      this.validateField("hsn_sac");
    },
  },

  mounted() {
    document
      .getElementById("addproductform")
      .addEventListener("keyup", this.handleKeydown);
    this.autoFocusInput();

    // Correct the access to route params
    //console.log("mounted", this.$route.params.id);
    // this.getProductData(this.$route.params.id);
  },
  beforeDestroy() {
    window.removeEventListener("keydown", this.handleKeydown);
  },
});
</script>

<style>
fieldset {
  display: block !important;
  margin-inline-start: 2px !important;
  margin-inline-end: 2px !important;
  padding-block-start: 0.35em !important;
  padding-inline-start: 0.75em !important;
  padding-inline-end: 0.75em !important;
  padding-block-end: 0.625em !important;
  min-inline-size: min-content !important;
  border-width: 2px !important;
  border-style: groove !important;

  border-image: initial !important;
}

/* newqly added styles */

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
.ant-select-selection-search {
  margin-left: -10px !important;
}

/* already here code s */
legend {
  width: auto !important;
}

.basicdetalis {
  border-bottom: none !important;
  color: black !important;
  margin-bottom: 0px !important;
  padding: 2px !important;
}
.disc {
  float: right !important;
}

.tables {
  margin-top: 5% !important;
}

.popup {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  border: 1px solid #ccc;
  padding: 10px;
}

.popup.visible {
  display: block;
}

.table-container {
  overflow-x: auto;
  margin: 0 auto;
}

.responsive-table {
  width: 100%;
}
.total_igst_amt {
  text-align-last: right;
  font-weight: bolder;
}
.ant-input:focus {
  background-color: #ffd451 !important;
}
.shortcut {
  position: relative; /* Ensure relative positioning for the child pseudo-element */
  display: inline-block; /* Ensure the span behaves like an inline element */
}

.shortcut:after {
  position: absolute;
  right: -5px; /* Adjust this based on your layout */
  top: 0;
  width: 1px;
  height: 100%;
  background-color: #cacaca; /* Ensure background color is applied */
  content: "";
}

.savebutton {
  padding: 1px 11px;
}
.backgrounds {
  background-color: #1f6d70;
  color: white;
  font-size: 13px !important;
  margin-left: 16px;
}
.draft {
  background-color: white;
  font-size: 13px;
  border: solid 1px #00000033;
}
.savecolse {
  background-color: white;
  border: solid 1px #00000033;
}

.dropdown {
  position: relative;
  display: inline-block;
}
.dropdown-menus li {
  padding: 2px 14px !important;
  cursor: pointer;
}

.dropdown-toggle {
  background-color: #1f6d70;
  color: white;
  padding: 2px 5px;
  border: none;
  cursor: pointer;
  font-size: 16px;
  height: 31px;
  border-top-right-radius: 9px;
}

.dropdown-toggle .arrow-icon {
  margin-left: 1px;
  text-align: center;
  font-size: 14px;
}

.dropdown-menus {
  display: block;
  position: absolute;
  background-color: #fff;
  border: 1px solid #ccc;
  padding: 10px;
  border-radius: 5px;
  list-style: none;
  margin: 0;
  top: -134px;
  left: 0;
  z-index: 1;
  width: 205px;
}

.dropdown-menus {
  left: 50%;
  transform: translateX(-50%);
}

.dropdown-items:hover {
  background-color: #f1f1f1;
}

.item-icon {
  margin-right: 10px;
}
.ant-notification-notice {
  background-color: rgb(233, 236, 141) !important;
}
.ant-notification-notice-message > span {
  color: #fff !important;
}
.page-content-sub-header {
  padding: 0px !important;
}
.ant-card-body {
  padding-top: 10px !important;
}
.ant-form-item .ant-form-item-label > label {
  font-size: 12px !important;
}
.ant-form-vertical .ant-form-item-label {
  padding: 0px !important;
}
.ant-input {
  font-size: 13px !important;
  line-height: 1.2 !important;
}
td > .ant-input {
  border: none !important;
  border-radius: 0px !important;
}
.responsive-table th,
.responsive-table td {
  padding: 0px !important;
}
.invoicevalues {
  background-color: #92b4b5 !important;
}

.fieldheightsss {
  margin-top: 15px;
}
</style>
