<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.${orderPageObject.menuKey}`)" @back="() => $router.go(-1)" class="p-0">
                <template #extra>
                    <!-- <a-button type="primary" :loading="loading" @click="onSubmit" block>
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        {{ $t("common.save") }}
                    </a-button> -->
                    <!-- <a-button :style="buttonStyle" @click="showPaymentModal" class="closing">
                        <PlusOutlined />
                       Show Payment Modal
                    </a-button> -->
                </template>
            </a-page-header><!--- @closed="handleClose"-->
            <ProductModal v-if="isProuctsModalVisible" :visible="isProuctsModalVisible" :formData="formData" :url="url"
                :successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess"  @closed="handleProductModal"
                 v-on:productclose-method="handleClose"  v-on:child-method="updateProduct" />
           <!-- payments modal-->
             <PaymentsModal v-if="isPaymentsModalVisible"  :visible="isPaymentsModalVisible" :formData="formData" :url="url":successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess" @closed="handleClosePayments"  v-on:child-method="updatePayment" :bill-value="formData.total"
               />
             <!-- end payments modal-->

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
                    <router-link :to="{
                        name: `admin.stock.${orderPageObject.type}.index`,
                    }">
                        {{ $t(`menu.${orderPageObject.menuKey}`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`common.create`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>
    <admin-page-table-content>
        <a-card class="page-content-container mt-20 mb-20">
            <a-form layout="vertical">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.party_name')" name="party_name" ref="input"
                            :help="rules.party_name ? rules.party_name.message : null"
                            :validateStatus="rules.party_name ? 'error' : null" class="required">
                            <a-input v-model:value="formData.party_name"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.party_name')])"
                                @focus="handleFocus" @blur="handleBlur" @keydown="handleKeydown"/>
                                <a-input hidden v-model="formData.party_id" id="party_id" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur="" /> 
                                <a-input hidden v-model="formData.tax_amount" id="tax_amount" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur=""/>
                                <a-input hidden v-model="formData.discount" id="discount" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur=""/>
                                <a-input hidden v-model="formData.subtotal" id="subtotal" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur=""/>
                                <a-input hidden v-model="formData.total" id="total"/>
                                <a-input hidden v-model="formData.due_amount" id="due_amount"/>
                                <a-input hidden v-model="formData.order_status" id="order_status"/>
                                <a-input hidden v-model="formData.total_items" id="total_items"/>
                                <a-input hidden v-model="formData.total_quantity" id="total_quantity"/>

                                <!-- <a-input text v-model="formData.payment_id" id="payment_id" @blur="" />  -->
                        </a-form-item>
                    </a-col>
                    <!--- modal-->
                    <SalesModel v-if="isModalVisible" :visible="isModalVisible" :formData="formData" :url="url"
                        :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                        @addEditSuccess="handleSuccess" @closed="handleCloseCustomer" v-on:child-method="updateParent"  v-on:close-method="focusEelment" />
                    <!-- end new modal-->

                    <!-- sales modal number -->
                    <SalesCustomerModel v-if="isCustomerVisible" :visible="isCustomerVisible" :formData="formData"
                        :url="url" :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                        @addEditSuccess="handleSuccess" @closed="handleSalesCustomer"
                        v-on:cutomer-method="updateCustomer" />
                    <!-- sales modal number -->

                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.bill_number')" name="bill_number"
                            :help="rules.bill_number ? rules.bill_number.message : null"
                            :validateStatus="rules.bill_number ? 'error' : null" class="required">

                            <input v-model="formData.bill_number" id="form_item_bill_number" readonly tabindex="-1" @keydown="validateMobile($event,index)"
                                @input="formData.bill_number = $event.target.value"  class="ant-input css-dev-only-do-not-override-wosfq4"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.bill_number')])" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                      <!---->  <a-form-item :label="$t('stock.order_date')" name="order_date"
                            :help="rules.order_date ? rules.order_date.message : null"
                            :validateStatus="rules.order_date ? 'error' : null">
                            <!--<a-date-picker :style="{ backgroundColor: stockDateColor }" ref="DateInput"
                                @focus="changeColorOnFocus('order_date')" @blur="resetColorOnBlur('order_date')"
                                v-model="formData.order_date" :format="appSetting.date_format" valueFormat="YYYY-MM-DD"
                                style="width: 100%"  @keydown="handleKeydownDatePicker" />-->
                                <input type="date" v-model="formData.order_date" class="ant-input css-dev-only-do-not-override-wosfq4" id="order_date" ref="DateInput" required @keydown="handleKeydownDatePicker"@focus="changeColorOnFocus('order_date')" @blur="resetColorOnBlur('order_date')" />
                        </a-form-item>
                        
                      </a-col>
                </a-row>
                <!-- sales modal number -->
                <SalesNumberModel v-if="isNumberVisible" :visible="isNumberVisible" :formData="formData" :url="url"
                    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage" @closed="handlenumberModel" 
                    @addEditSuccess="handleSuccess"  v-on:mobile-method="handleSalesNumber" v-on:cutomer-method="updateCustomer" />
                <!-- sales modal number -->
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        
                        <a-form-item :label="$t(`stock.mobile_number`)" name="mobile_number" :help="rules.mobile_number ? rules.mobile_number.message : null
                            " :validateStatus="rules.mobile_number ? 'error' : null" >
                            <input id="form_item_mobile_number" v-model="formData.party_customer_mobile" placeholder="Press SpaceBar"
                                @keydown.space.prevent="showNumberModal($event)" class="ant-input css-dev-only-do-not-override-wosfq4"   />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.customer_name`)" name="customer_name" :help="rules.customer_name ? rules.customer_name.message : null
                            " :validateStatus="rules.customer_name ? 'error' : null">
                            <a-input readonly v-model:value="formData.customer_name" placeholder="Press SpaceBar"
                                @keydown.space.prevent="showCustomerModal" @blur="" />

                            <a-input hidden v-model:value="formData.party_customer_id" id="party_customer_id"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])" @blur="" />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.address`)" name="address" :help="rules.address ? rules.address.message : null
                            " :validateStatus="rules.address ? 'error' : null" >
                            <a-input v-model:value="formData.address" :placeholder="$t('common.placeholder_default_text', [
                                $t('stock.address'),
                            ])
                                " />
                        </a-form-item>
                    </a-col>
                </a-row>


                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">

                        <div id="app" class="table-container">
                                <table class="responsive-table">
                                    <thead>
                                        <tr>
                                            <th v-for="(header, index) in headers" :key="index" class="tableheading">{{ header }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(header, index) in formData.items" :key="index">
                                            <td style="width:30%">  
                                                <input autocomplete="off"
                                                v-model="formData.items[index].item_name"
                                                :ref="`input-${index}`"
                                                :id="`item_product_name_${index}`"
                                                name="party_name" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                @keydown="showProductModal($event,index)" style="color:black;font-weight:bolder;"
                                                >
                                                <a-input hidden :id="`item_product_id_${index}`" v-model="formData.items[index].item_id" />
                                                <a-input hidden :id="`igst_item_${index}`"/>
                                            </td>

                                            <td style="width:15%">{{  }}
                                                <input autocomplete="off" tabindex="-1"
                                                :id="`item_product_packing_${index}`"
                                                v-model="formData.items[index].packing"
                                                 style="color:black;font-weight:bolder;"
                                                readonly class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >
                                            </td>

                                            <td style="width:15%">
                                                <input autocomplete="off"
                                                :id="`item_product_quantity_${index}`"
                                                v-model="formData.items[index].quantity"
                                                @input="getQuantity(index,$event)" @focus="getQuantity(index,$event)" @keydown="checkMaxQuantity(index,$event)"
                                                name="quantity[]" style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"
                                                @keypress="onlyForCurrency"
                                                >
                                            </td>

                                            <td style="width:15%">
                                                <input autocomplete="off"
                                                v-model="formData.items[index].single_unit_price"
                                                :ref="`input-${index}`" @input="getQuantity(index,$event)"
                                                :id="`item_product_price_${index}`" @keydown="checkSingleItemPrice(index,$event)"
                                                name="single_unit_price"class="ant-input css-dev-only-do-not-override-wosfq4"
                                                 style="color:black;font-weight:bolder;"
                                                  @blur="checkSingleItemPrice(index,$event)"
                                                >
                                                
                                            </td>

                                            <td style="width:15%">
                                                <a-input autocomplete="off"
                                                :id="`item_product_disc_${index}`"
                                                @input="getDiscount(index,$event)"
                                                @keydown="checkDisc(index,$event)"
                                                v-model="formData.items[index].discount_rate"
                                                name="party_name" style="color:black;font-weight:bolder;text-align-last:right;"
                                                ></a-input>
                                                <a-input hidden :id="`item_discount_total_${index}`"/>
                                            </td>

                                            <td style="width:15%">
                                                <a-input
                                                disabled=true
                                                v-model="formData.items[index].amount"
                                                @input="formData.items[index].amount = $event.target.value"
                                                :id="`item_product_amount_${index}`"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;"
                                                ></a-input>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <fieldset>
                                    <legend class="basicdetalis">
                                        Discount Info
                                    </legend>
                                    <a-row :gutter="16" class="mt-10">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;">
                                            {{ $t("stock.total_item") }}

                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="3" :lg="3" style="color:black;font-weight:bolder;">

                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                            <span id="discount_info_amount" style="color:black;font-weight:bolder;text-align-last:right;">0.00</span>
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16" class="mt-10">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;">
                                            {{ $t("stock.scheme") }}
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="3" :lg="3" style="color:black;font-weight:bolder;">

                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="4" :lg="4" style="color:black;font-weight:bolder;text-align-last:right;">

                                            <span>0.00</span>
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16" class="mt-10">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;">
                                            Volume Disc.
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="2" :lg="2" style="color:black;font-weight:bolder;">
                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="5" :lg="5" style="color:black;font-weight:bolder;text-align-last:right;">
                                            <span>0.00</span>
                                        </a-col>
                                    </a-row>
                                </fieldset>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <fieldset>
                                    <legend class="basicdetalis">
                                        Tax Info
                                    </legend>
                                    <a-row :gutter="16" class="mt-10">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;">
                                            CGST (2%)
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="3" :lg="3" style="color:black;font-weight:bolder;">

                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                            <span id="cgst_total_text" style="color:black;font-weight:bolder;text-align-last:right;">0.00</span>
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16" class="mt-10">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;">
                                            SGST (2%)
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="3" :lg="3" style="color:black;font-weight:bolder;">

                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                            <span id="sgst_total_text" style="color:black;font-weight:bolder;text-align-last:right;">0.00</span>
                                        </a-col>
                                    </a-row>
                                </fieldset>
                            </a-col>
                        </a-row>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12"/>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-row :gutter="16" class="mt-5">
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;">
                                        {{ $t("stock.totals_disc") }} :
                                    </a-col>
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;text-align-last:right;">
                                        <span>₹</span>&nbsp;<span id="total_discount_text">0.00</span>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16" class="mt-5">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <table class="responsive-table">
                                    <thead>
                                        <tr>
                                            <th v-for="(header, index) in addtionaldetalisheader" :key="index"class="tableheading">
                                                {{ header }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in additems" :key="index" :class="{ highlight: index === selectedIndex }">
                                            <td style="font-weight:bolder;">{{ item.name }}</td>
                                            <td style="text-align:center;font-weight:bolder;">{{ item.location }}</td>
                                            <td>
                                                <a-input disabled=true v-model="formData.igst_amount" :id="`igst_amount_${index}`"  style="text-align:right;color:black;font-weight:bolder;" name="table_total_amount[]"></a-input>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="7" :lg="7"/>
                            <a-col :xs="24" :sm="24" :md="17" :lg="17">
                                <a-row :gutter="16" class="mt-10">
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12" style="font-size: 20px;font-weight:bolder;">
                                        Invoice Value &nbsp;&nbsp;&nbsp; :
                                    </a-col>
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12" style="text-align-last:right;font-size: 20px;font-weight:bolder;">
                                        <span > ₹</span>&nbsp; <span id="grand_total">0.00</span>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </a-col>
                </a-row>
                <a-row :gutter="16" class="mt-20 mb-20">
                    <a-col :xs="24" :sm="24" :md="9" :lg="9"></a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-button  class="backgrounds" :loading="loading" @click="saveSalesEntry" block>
                                <span class="shortcut">
                                    <code>F10 / End</code>
                                  </span> 
                                <span class="savebutton">{{ $t("common.save") }}  <SaveOutlined />
                                </span>
                              </a-button>
                              
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="1" :lg="1">
                        <div class="dropdown">
                            <a-button class="dropdown-toggle" @click="toggleDropdowns">
                              {{ buttonText }} 
                              <span class="arrow-icon">▼</span>
                            </a-button>
                            <ul v-if="isOpensave" class="dropdown-menus">
                              <li v-for="(item, index) in dropsitems" :key="index" class="dropdown-items" >
                                <span v-if="item.isSvg" v-html="item.icon" class="item-icon"></span> <!-- Render SVG -->
                                <i v-else :class="item.icon" class="item-icon"></i> <!-- Render FontAwesome Icon -->
                                {{ item.text }}
                              </li>
                            </ul>
                          </div>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="draft"   block>
                                Save As Draft
                              </a-button>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="savecolse" @click="onclose"   block>
                                <span class="shortcut">
                                    <code>Esc</code>
                                  </span> 
                                <span class="savebutton">{{ $t("common.close") }}  <svg height="10px" width="10px" viewBox="0 0 611.96 611.96"><path d="M378.71,306,596.9,87.79a51.43,51.43,0,0,0-72.73-72.73L306,233.25,87.79,15.06A51.43,51.43,0,0,0,15.06,87.79L233.25,306,15.06,524.17a51.43,51.43,0,1,0,72.73,72.73L306,378.71,524.17,596.89a51.43,51.43,0,1,0,72.73-72.73Z"></path></svg>
                                </span>
                              </a-button>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                        <a-button  class="savecolse" block>
                            <span class="shortcut">
                                <code>Alt+D</code>
                              </span> 
                            <span class="savebutton">{{ $t("common.last_deal") }}  
                            </span>
                          </a-button>
                </a-col>

                    
                </a-row>
            </a-form>
        </a-card>
    </admin-page-table-content>

    <a-modal :open="addEditVisible" :closable="false" :centered="true" :title="addEditPageTitle" @ok="onAddEditSubmit">
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('product.unit_price')" name="unit_price" :help="addEditRules.unit_price
                        ? addEditRules.unit_price.message
                        : null
                        " :validateStatus="addEditRules.unit_price ? 'error' : null">
                        <a-input-number v-model:value="addEditFormData.unit_price" :placeholder="$t('common.placeholder_default_text', [
                            $t('product.unit_price'),
                        ])
                            " min="0" style="width: 100%">
                            <template #addonBefore>
                                {{ appSetting.currency.symbol }}
                            </template>
                        </a-input-number>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('product.discount')" name="discount_rate" :help="addEditRules.discount_rate
                        ? addEditRules.discount_rate.message
                        : null
                        " :validateStatus="addEditRules.discount_rate ? 'error' : null">
                        <a-input-number v-model:value="addEditFormData.discount_rate" :placeholder="$t('common.placeholder_default_text', [
                            $t('product.discount'),
                        ])
                            " min="0" style="width: 100%">
                            <template #addonAfter>%</template>
                        </a-input-number>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('product.tax')" name="tax_id"
                        :help="addEditRules.tax_id ? addEditRules.tax_id.message : null"
                        :validateStatus="addEditRules.tax_id ? 'error' : null">
                        <span style="display: flex">
                            <a-select v-model:value="addEditFormData.tax_id" :placeholder="$t('common.select_default_text', [$t('product.tax')])
                                " :allowClear="true" optionFilterProp="title" show-search>
                                <a-select-option v-for="tax in taxes" :key="tax.xid" :value="tax.xid" :title="tax.name">
                                    {{ tax.name }} ({{ tax.rate }}%)
                                </a-select-option>
                            </a-select>
                            <TaxAddButton @onAddSuccess="taxAdded" />
                        </span>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('product.tax_type')" name="tax_type" :help="addEditRules.tax_type ? addEditRules.tax_type.message : null
                        " :validateStatus="addEditRules.tax_type ? 'error' : null">
                        <a-select v-model:value="addEditFormData.tax_type" :placeholder="$t('common.select_default_text', [$t('product.tax_type')])
                            " :allowClear="true">
                            <a-select-option v-for="taxType in taxTypes" :key="taxType.key" :value="taxType.key">
                                {{ taxType.value }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="addEditFormSubmitting" @click="onAddEditSubmit">
                <span class="shortcut ng-star-inserted"><code>F10</code></span>
                <template #icon>
                    <SaveOutlined />
                </template>
                {{ $t("common.update") }}
            </a-button>
            <a-button key="back" @click="onAddEditClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-modal>



    <!-- Popup Modal -->
    <!-- <a-modal
  v-model:visible="isPopupVisible"
  title="Party Name Information"
  @ok="handleOk"
  @cancel="handleCancel"
>
  <p>Your popup content goes here...</p>
</a-modal> -->
    <!--- end new popup-->
</template>

<script>
import { onMounted, ref, toRefs, computed } from "vue";
import {
    EyeOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    SaveOutlined,
    LoadingOutlined,
    MinusSquareOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import apiAdmin from "../../../../common/composable/apiAdmin";
import stockManagement from "../../../../main/views/stock-management/purchases/stockManagement";
import common from "../../../../common/composable/common";
import fields from "./fields";
import ProductModal from '../../stock-management/purchases/Product/ProductModal.vue';
import TaxAddButton from "../../settings/taxes/AddButton.vue";
import WarehouseAddButton from "../../settings/warehouses/AddButton.vue";
import ProductAddButton from "../../product-manager/products/AddButton.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DateTimePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import UserSearch from "../../../views/stock-management/purchases/UserSearch.vue";
import FormItemHeading from "../../../../common/components/common/typography/FormItemHeading.vue";
import { some, forEach, find } from "lodash-es";
import PaymentModeAddButton from "../../stock-management/payments/AddButton.vue";
import SalesModel from "../../stock-management/purchases/SalesModel.vue";
import SalesNumberModel from "../../stock-management/purchases/SalesNumberModel.vue";
import SalesCustomerModel from "../../stock-management/purchases/SalesCustomerModel.vue";
import PaymentsModal from "../../stock-management/purchases/PaymentsModal.vue";
import { message, notification } from "ant-design-vue";


export default {
    components: {
        EyeOutlined,
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        ExclamationCircleOutlined,
        SearchOutlined,
        SaveOutlined,
        LoadingOutlined,
        SalesModel,
        TaxAddButton,
        WarehouseAddButton,
        ProductAddButton,
        DateTimePicker,
        AdminPageHeader,
        UserSearch,
        MinusSquareOutlined,
        FormItemHeading,
        PaymentModeAddButton,
        ProductModal,
        SalesNumberModel,
        PaymentsModal,
        SalesCustomerModel,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading,handlePaymentSubmit, rules } = apiAdmin();
        const {
            appSetting,
            formatAmount,
            formatAmountCurrency,
            taxTypes,
            orderStatus,
            purchaseOrderStatus,
            salesOrderStatus,
            salesReturnStatus,
            purchaseReturnStatus,
            permsArray,
            selectedWarehouse,
        } = common();
        let selectedItermIndex = ref('');
        let formData = ref("Unknown")
        const { orderItemColumns } = fields();
        const { salesItemColumns } = fields();
          formData=({
                order_date: new Date().toISOString().split('T')[0],
                party_id:0,
                product_id: '',
                party_product_name: '',
                party_name:'',
                party_customer_id:0,
                party_customer_name:null,
                party_customer_mobile:null,
                bill_number:"",
                invoice_number:"",
                tax_amount:0,
                discount:0,
                total:0,
                due_amount:0,
                order_status:"processing",
                total_items:0,
                total_quantity:0,
                subtotal:0,
                payment_id:0,
                items:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:3,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:4,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:5,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:6,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:7,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:8,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:9,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                    {index:10,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
                ],

                additems: [
                    { index: 1, item_id: null, item_name: null, unit_id: null,  },
                    { index: 2, item_id: null, item_name: null, unit_id: null,},
                    { index: 3, item_id: null, item_name: null, unit_id: null,}
                ],
                total_discount:0,
                invoice_value:0,
                index: 0,
            additems: [
                { index: 1, item_id: null, item_name: null, unit_id: null, },
                { index: 2, item_id: null, item_name: null, unit_id: null, },
                { index: 3, item_id: null, item_name: null, unit_id: null, }
            ],
            total_discount: 0,
            invoice_value: 0,
            //selectedPartyIds: [] ,
            index: 0,
        });


        const {
            state,
            orderType,
            orderPageObject,
            selectedProducts,
            // formData,
            productsAmount,
            taxes,

            recalculateValues,
            fetchProducts,
            searchValueSelected,
            quantityChanged,
            recalculateFinalTotal,
            showDeleteConfirm,
            taxChanged,
            editItem,

            // Add Edit
            addEditVisible,
            addEditFormData,
            addEditFormSubmitting,
            addEditRules,
            addEditPageTitle,
            onAddEditSubmit,
            onAddEditClose,

            inputValueChanged,
        } = stockManagement();
        const { t } = useI18n();
        const warehouses = ref([]);
        const allUnits = ref([]);
        const router = useRouter();
        const allOrderStatus = ref([]);
        const paymentModes = ref([]);
        const paymentModeUrl = "payment-modes?limit=10000";
        const taxUrl = "taxes?limit=10000";
        const unitUrl = "units?limit=10000";
        const warehouseUrl = `warehouses?filters=id ne "${selectedWarehouse.value.xid}"&hashable=${selectedWarehouse.value.xid}&limit=10000`;
        
        const payment = ref({
            amount: 0,
            payment_mode_id: undefined,
        });
       
        const allPayments = ref([]);
        let partyId = 0;

        onMounted(() => {});


        const onSubmit = () => {
            //console.log('formData=>', formData)
            allPayments.value = [];
            allPayments.value.push(payment.value);

            const newFormDataObject = {
                ...formData.value,
                order_type: orderPageObject.value.type,
                total: formData.subtotal,
                total_items: selectedProducts.value.length,
                product_items: selectedProducts.value,
                pay_object: formFields.value,
                all_payments: allPayments.value,
            };

            addEditRequestAdmin({
                url: 'sales/store',
                data: formData,
                successMessage: t(`${orderPageObject.value.langKey}.created`),
                success: (res) => { 
                    router.push({
                        name: `admin.stock.${orderType.value}.index`,
                    });
                },
            });
        };

        const unitAdded = () => {
            axiosAdmin.get(unitUrl).then((response) => {
                allUnits.value = response.data;
            });
        };

        const taxAdded = () => {
            axiosAdmin.get(taxUrl).then((response) => {
                taxes.value = response.data;
            });
        };

        const warehouseAdded = () => {
            axiosAdmin.get(warehouseUrl).then((response) => {
                warehouses.value = response.data;
            });
        };

        // const paymentModeAdded = () => {
        //     axiosAdmin.get(paymentModeUrl).then((response) => {
        //         paymentModes.value = response.data;
        //     });
        // };

        const formFields = ref([
            {
                pay_amount: 0,
                payment_mode_id: undefined,
            },
        ]);
        const removedDescriptions = ref([]);

        const formFieldFilter = () => {
            var newFormField = [];

            forEach(formFields.value, (formField) => {
                if (formField.description != "") {
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
                    some(formFields.value, { description: "" }) ||
                    some(formFields.value, { description: null })
                );
            }
        });
        const payAmountTotal = () => {
            let payAmount = 0;
            forEach(formFields.value, (formField) => {
                payAmount += Number(formField.pay_amount);
            });
            return {
                payAmount,
            };
        };

        const addFormField = () => {
            formFields.value.push({
                pay_amount: 0,
                payment_mode_id: undefined,
            });
        };

        const selectProduct = (record) => {
            //console.log(record);
        };

        const removeFormField = (item) => {
            let index = formFields.value.indexOf(item);
            if (index !== -1) {
                formFields.value.splice(index, 1);
            }

            if (item.id != "") {
                removedDescriptions.value.push(item.id);
            }
            payAmountTotal();
        };

        return {
            ...toRefs(state),
            formData,
            productsAmount,
            rules,
            loading,
            warehouses,
            taxes,
            onSubmit,
            fetchProducts,
            searchValueSelected,
            selectedProducts,
            showDeleteConfirm,
            quantityChanged,
            formatAmountCurrency,
            taxChanged,
            recalculateFinalTotal,
            appSetting,
            editItem,
            orderPageObject,
            selectProduct,       
            orderItemColumns,
            salesItemColumns,
            // Add Edit
            addEditVisible,
            addEditFormData,
            addEditFormSubmitting,
            addEditRules,
            addEditPageTitle,
            onAddEditSubmit,
            onAddEditClose,
            allOrderStatus,
            taxTypes,
            permsArray,

            unitAdded,
            taxAdded,
            warehouseAdded,

            inputValueChanged,
            addFormField,
            removeFormField,
            addFormButtonStatus,
            formFields,
            paymentModes,
            payAmountTotal,
            // paymentModeAdded,
            payment,
        };
    },
    data() {
        return {

            additems: [
                { name: 'CGST Output', location: '', value: '' },
                { name: 'SGST Output', location: '', value: '' },
                { name: '', location: '', value: '' }],
            isNumberVisible: false,
            isPaymentsModalVisible:false,
            isModalPopup:false,
            isCustomerVisible: false,
            isModalVisible: false,
            isProuctsModalVisible: false,
            stockDateColor: '',
			isNumberVisible:false,
            items: [
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
            ],

            isOpensave: false,
    
            dropsitems: [
        { text: "Save & SMS", icon: `<svg height="14" viewBox="0 0 1024 1024"><path fill="currentColor" d="M256 448h512v128h-512zM256 256h512v128h-512zM960 0h-896c-35.376 0-64 28.624-64 64v704c0 35.376 28.624 64 64 64h128v192l288-192h480c35.376 0 64-28.624 64-64v-704c0-35.376-28.624-64-64-64zM896 704h-416l-160 96v-96h-192v-576h768v576z"></path></svg>`, isSvg: true },
        { text: "Save & Email",icon: `<svg height="14" viewBox="0 0 1024 1024"><path fill="currentColor" d="M959.998 128h-895.998c-35.342 0-64 28.624-64 64v640c0 35.376 28.658 64 64 64h895.998c35.344 0 64.002-28.624 64.002-64v-640c0-35.376-28.658-64-64.002-64zM832 256l-320 256-320-256h640zM896 768h-768v-448l384 320 384-320v448z"></path></svg>`, 
        isSvg: true },
        { text: "Save & Print", icon:`<svg height="14" viewBox="0 0 135.57 125.14"><path fill="currentColor" d="M131,56.75a15.06,15.06,0,0,0-11-4.6h-5.21V31.28a18.81,18.81,0,0,0-1.63-7.17,18.94,18.94,0,0,0-3.91-6.19L96.79,5.54A19,19,0,0,0,90.6,1.63,18.81,18.81,0,0,0,83.43,0H28.68a7.54,7.54,0,0,0-5.54,2.28,7.54,7.54,0,0,0-2.28,5.54V52.14H15.64a15.06,15.06,0,0,0-11,4.6,15.06,15.06,0,0,0-4.6,11v33.89a2.51,2.51,0,0,0,.77,1.83,2.51,2.51,0,0,0,1.83.77H20.86v13a7.79,7.79,0,0,0,7.82,7.82h78.21a7.79,7.79,0,0,0,7.82-7.82v-13H133a2.64,2.64,0,0,0,2.6-2.61V67.78A15.05,15.05,0,0,0,131,56.75Zm-26.68,58h-73V93.86h73Zm0-52.14h-73V10.43H83.43v13a7.79,7.79,0,0,0,7.82,7.82h13Zm19.31,8.88a5.11,5.11,0,1,1,1.55-3.67A5,5,0,0,1,123.59,71.45Z"></path></svg>`, 
        isSvg: true , isSvg: true },
        { text: "Save, SMS & Email", icon:`<svg height="14" viewBox="0 0 512 512"><path fill="currentColor" d="M341 299h128v128h-42v-43q-64 85-171 85-77 0-135.5-48.5t-73.5-121.5h44q14 55 60 91.5t105 36.5q46 0 85.5-23.5t61.5-62.5h-62v-42zM171 213h-128v-128h42v43q64-85 171-85 77 0 135.5 48.5t73.5 121.5h-44q-14-55-60-91.5t-105-36.5q-46 0-85.5 23.5t-61.5 62.5h62v42zM192 256q0-26 19-45t45-19 45 19 19 45-19 45-45 19-45-19-19-45z"></path></svg>`, 
        isSvg: true  }
      ],
            totalAmount:0,
            totalIgstAmount:0,
            totalDiscountAmount:0,
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Select Party',
            successMessage: 'Operation successful!',
            headers: ['Product', 'Packing', 'Qty', 'Rate', 'Disc', '₹ Amount'],
            addtionaldetalisheader:['Additional Details', '%', '₹ Amount'],
        };
    },

    mounted() {
       
        document.addEventListener('keyup', this.handleKeyDown);
        this.autoFocusInput();
        
    },
    beforeDestroy() {
       // document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
        formatNumber (num) {
       return parseFloat(num).toFixed(2)
      },

      toggleDropdowns() {
      this.isOpensave = !this.isOpensave;
    },
  

    handleEnterKey(event) {
      // Check if Enter or Space key is pressed
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent default scrolling behavior for the Space key
        this.toggleDropdowns();
      }
    },
        onlyForCurrency($event) {
            // //console.log($event.keyCode); //keyCodes value
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

            // only allow number and one dot
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.price.indexOf('.') != -1)) { // 46 is dot
                $event.preventDefault();
            }

            // restrict to 2 decimal places
            if (this.price != null && this.price.indexOf(".") > -1 && (this.price.split('.')[1].length > 1)) {
                $event.preventDefault();
            }
        },

        handleFocus() {
      this.$nextTick(() => {
        const firstInput = document.getElementById('form_item_party_name');
        if (firstInput) {
          firstInput.focus();
          setTimeout(() => {
            this.showModal(); 
          }, 600); 
        }
      });
    },

    autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.DateInput.focus();  
            });
        },

    handleBlur() {
      //console.log('Focus removed from input');
      this.showMessage = true; 
    },
    handleKeyDown(event) {
      if (event.key === 'Escape' || event.keyCode === 27) {
    //    this.isProuctsModalVisible = false;
    //     this.isModalVisible = false;
    //     this.isNumberVisible =false;
    //    this. isCustomerVisible=false;
    //this.$router.push({ name: 'admin.stock.sales.index' });
     
      }
      else if (event.key === 'F10') {
        //this.onSubmit();
        this.saveSalesEntry();
      }
    },

    handleKeydownDatePicker(event) { //console.log("Date picker event",event.target.value);
      if ((event.key === 'Tab' || event.key === 'Enter') && event.target.value=="") {
        this.formData.order_date =  new Date().toISOString().split('T')[0];
        
        document.getElementById("form_item_order_date").value =this.formData.order_date;
       //ssss this.$refs.dateInput.popupVisible = false
        event.preventDefault();
        return false;
        //this.handleFocus(); 
      }
      else{
        
        this.handleFocus(); 

      }
    },
    
    showPaymentModal(){
        //console.log("Payment called");
        this.isPaymentsModalVisible = true;
    },
    handleClosePayments(){
        this.isPaymentsModalVisible = false;
    },
 
        inputData($event) {
            // Value will update reactively
            $event.target.value;
        },
        showNumberModal(event) {
            //console.log("Open Model",event.target.value);
            if((event.key=="Enter" || event.key=="Tab" ) && (this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""))
            {
                document.getElementById("form_item_mobile_number").focus();
                return false;
            }
            else
            {
                this.isNumberVisible = true;
            }
            
        },
        handleSalesNumber() {
            //console.log("close Model",this.selectedItermIndex);
            this.isNumberVisible = false;
            if(this.selectedItermIndex==undefined){
                document.getElementById("item_product_name_1").focus();
            }
            else{
            document.getElementById("item_product_name_"+(this.selectedItermIndex)).focus();
            }
            return false;

        },

        getCurrentDate() {
            // returns the current date in the format YYYY-MM-DD
            return new Date().toISOString().split('T')[0];
        },
        showModal() {
            this.isModalVisible = true;
            //console.log(33);
            //this.$refs.dummykeyboard.focus();
        },
       
        handleCloseCustomer() { //console.log("Close 1",this.formData.customer_name);

            this.isModalVisible = false;
           // this.isProuctsModalVisible = false;
           this.focusEelment();
        },
        focusSearchInput() {
            this.$refs.searchInput.focus(); // Focus on the input field
        },

        showCustomerModal() {
            this.isCustomerVisible = true;
        },

        handleSalesCustomer() {
            this.isCustomerVisible = false;
        },

        handleSuccess(xid) {
            this.isProuctsModalVisible = false;
            this.isModalVisible = false;
            //console.log('Success:', xid);
        },
        updateParent(selectedParty) {
            this.formData.party_id = selectedParty.id;
            this.formData.party_name = selectedParty.name;
            //console.log("hello child" + this.formData.party_id)
            document.getElementById("party_id").value = this.formData.party_id;
            document.getElementById("form_item_name").value = this.formData.name;
            document.getElementById("form_item_party_name").value = this.formData.party_name;

            if(this.formData.bill_number==undefined || this.formData.bill_number=="")
            {
                this.loading= true;
                const billNumberUrl = `sales/billNumber`;
                axiosAdmin.get(billNumberUrl).then((response) => {
                console.log(response)
                this.formData.bill_number = response;
                document.getElementById("form_item_bill_number").value = this.formData.bill_number;
                this.loading= false;
                 });
            }

        },

        updatePayment(selectedParty){
            this.formData.payment_id = selectedParty.id;
            //console.log("hello child" + this.formData.payment_id)
            document.getElementById("payment_id").value = this.formData.payment_id;
        },
        focusEelment()
        {
            //console.log('Success:');
            document.getElementById("item_product_name_0").focus();
        },

        focusproductEelment()
        {
            //console.log('Success pr:');
            //document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
            document.getElementById("item_product_quantity_0").focus();
        },


        updateCustomer(selectedParty) {
            //console.log(selectedParty)
            this.formData.party_customer_id = selectedParty.id;
            this.formData.party_customer_name = selectedParty.name;
            this.formData.party_customer_mobile = selectedParty.mobile_number;
            //console.log("hello child" + this.formData)
            document.getElementById("party_customer_id").value = this.formData.party_customer_id;
            document.getElementById("form_item_customer_name").value = this.formData.party_customer_name;
            document.getElementById("form_item_mobile_number").value = this.formData.party_customer_mobile;
        },
        updateProduct(selectedParty) {
            //console.log(selectedParty);
            this.formData.items[this.selectedItermIndex].item_id= selectedParty.id
                 this.formData.items[this.selectedItermIndex].item_name= selectedParty.name
                 this.formData.items[this.selectedItermIndex].single_unit_price= selectedParty.single_unit_price;
                 this.formData.items[this.selectedItermIndex].max_single_unit_price= selectedParty.single_unit_price;
                 this.formData.items[this.selectedItermIndex].quantity= selectedParty.quantity;
                 this.formData.items[this.selectedItermIndex].maxquantity= selectedParty.quantity;
                 this.formData.items[this.selectedItermIndex].packing= selectedParty.packing;
                 //console.log(this.formData.items[this.selectedItermIndex].packing);
                 document.getElementById("item_product_name_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_name;
                 document.getElementById("item_product_packing_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].packing;
                 document.getElementById("item_product_id_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_id;
                 document.getElementById("item_product_price_"+this.selectedItermIndex).value = this.formatNumber(this.formData.items[this.selectedItermIndex].single_unit_price);

                 document.getElementById("item_product_quantity_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].quantity;
                 document.getElementById("item_product_amount_"+this.selectedItermIndex).value =0.0
                 document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                 
        },

        showProductModal(event,index) {
            //console.log('=>', index,event.key,event.keyCode )
                 if(this.formData.party_name!="" && index>0 &&  event.key == 'Tab')
                {
                    //console.log("Tab Key Press",this.formData.party_customer_mobile);
                    this.selectedItermIndex =  index;
                    if(this.formData.bill_number==undefined || this.formData.bill_number=="" || this.formData.bill_number.trim()==""){ //console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_bill_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){ //console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_mobile_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else
                    {
                        console.log("POP Pay");
                        //this.isPaymentsModalVisible= true; 
                        //this.onSubmit();
                        this.saveSalesEntry();
                    }
                    
                    
                }
                else if(this.formData.party_name!="" && index==0)
                { //console.log("POP Pay 1");
                this.selectedItermIndex =  index;
                this.isProuctsModalVisible = true;
                    
                }
                else if(this.formData.party_name!="" && index>0 && (event.keyCode !== 9))
                { //console.log("POP Pay 2");
                    this.selectedItermIndex =  index;
                    this.isProuctsModalVisible = true;
                    event.preventDefault();
                    return false;
                    
                }
                
        },
        validateMobile(event,index)
        {
            //console.log(event.key)
            if(this.formData.party_name!=""  &&  event.key == 'Enter')
                {
                    this.selectedItermIndex =  index;
                    if(this.formData.bill_number==undefined || this.formData.bill_number=="" || this.formData.bill_number.trim()==""){ //console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_bill_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){ //console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_mobile_number").focus();
                    }
                    else
                    {
                       //console.log('=> mobile',this.formData.party_customer_mobile); 
                    }
                    return false;
                    
                }
        },

   
    onclose()
    {
    this.$router.push({ name: 'admin.dashboard.index' });
   }, 
          
   handleClose() { //console.log("Close 2");
            this.isModalVisible = false;
            this.isProuctsModalVisible = false;
            if(this.selectedItermIndex!=undefined){
            document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
            }
        },
        focusSearchInput() {
            this.$refs.searchInput.focus(); // Focus on the input field
        },
        handleSuccess(xid) {
            this.isProuctsModalVisible = false;
            this.isModalVisible = false;
            //console.log('Success:', xid);
        },

        changeColorOnFocus(inputField) {
            if (inputField === 'order_date') {
                this.stockDateColor = '#ffd451';
            }
        },
        resetColorOnBlur(inputField) {
            if (inputField === 'order_date') {
                this.stockDateColor = '';
            }
        },
        handleProductModal(){
        this.isProuctsModalVisible  =false;
        },

        handlenumberModel(){
this.isNumberVisible=false;
        },
        getQuantity(index,event) {
            //formData.items[index].quantity = $event.target.value
            const quantity = document.getElementById("item_product_quantity_"+index).value;
            const price    = document.getElementById("item_product_price_"+index).value;
            const discount = document.getElementById("item_product_disc_"+index).value;

            if(discount != "")
            {
                this.totalDiscountAmount  = Number(Number(this.totalDiscountAmount) - Number(document.getElementById("item_discount_total_"+index).value));
                document.getElementById("total_discount_text").innerHTML   = this.formatCurrency(Number(this.totalDiscountAmount));
                document.getElementById("discount_info_amount").innerHTML  = this.formatCurrency(Number(this.totalDiscountAmount));

                this.totalAmount = Number(Number(this.totalAmount) + Number(document.getElementById("item_discount_total_"+index).value));
                document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

                this.formData.discount   = Number(this.totalDiscountAmount)
                this.formData.total      = Number(this.totalAmount);
                this.formData.subtotal   = Number(this.totalAmount);
                this.formData.due_amount = Number(this.totalAmount);
            }

            this.totalAmount = Number(Number(this.totalAmount) - Number(document.getElementById("item_product_amount_"+index).value));
            document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

            this.totalIgstAmount = Number(Number(this.totalIgstAmount) - Number(document.getElementById("igst_item_"+index).value));

            this.formData.tax_amount = Number(Number(this.totalIgstAmount) * 2);

            this.totalAmount = Number(Number(this.totalAmount) - Number(document.getElementById("igst_item_"+index).value) * 2);
            document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

            document.getElementById("sgst_total_text").innerHTML = this.formatCurrency(Number(this.totalIgstAmount));
            document.getElementById("cgst_total_text").innerHTML = this.formatCurrency(Number(this.totalIgstAmount));
            document.getElementById("igst_amount_0").value       = this.formatCurrency(Number(this.totalIgstAmount));
            document.getElementById("igst_amount_1").value       = this.formatCurrency(Number(this.totalIgstAmount));

            this.formData.total      = Number(this.totalAmount);
            this.formData.subtotal   = Number(this.totalAmount);
            this.formData.due_amount = Number(this.totalAmount);

            if(quantity == "")
            {
                document.getElementById("item_product_amount_"+index).value = "";
                document.getElementById("igst_item_"+index).value = "";
                document.getElementById("item_discount_total_"+index).value = "";
            }
            else{
                //Calculate the total amount

                document.getElementById("item_product_amount_"+index).value = this.formatNumber(Number(Number(quantity) * Number(this.revertFormattedNumber(price))));
                this.totalAmount = Number(Number(this.totalAmount) + Number(document.getElementById("item_product_amount_"+index).value));
                document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

                //Calculate the sgst and cgst amount

                let percentage     = (2 / 100);
                let gstAmount      = Number(percentage) * Number(price);
                let totalGstAmount = Number(gstAmount) * Number(quantity);

                this.totalIgstAmount = Number(Number(this.totalIgstAmount) + Number(totalGstAmount));

                this.totalAmount = Number(Number(this.totalAmount) + Number(totalGstAmount) * 2);
                document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

                document.getElementById("sgst_total_text").innerHTML = this.formatCurrency(Number(this.totalIgstAmount));
                document.getElementById("cgst_total_text").innerHTML = this.formatCurrency(Number(this.totalIgstAmount));
                document.getElementById("igst_amount_0").value       = this.formatCurrency(Number(this.totalIgstAmount));
                document.getElementById("igst_amount_1").value       = this.formatCurrency(Number(this.totalIgstAmount));
                document.getElementById("igst_item_"+index).value    = Number(totalGstAmount);

                this.formData.tax_amount = Number(Number(this.totalIgstAmount) * 2);
                this.formData.total      = Number(this.totalAmount);
                this.formData.subtotal   = Number(this.totalAmount);
                this.formData.due_amount = Number(this.totalAmount);

                if(discount != "")
                {
                    let discountPercentage    = (discount / 100);
                    let discountAmount        = Number(discountPercentage) * Number(document.getElementById("item_product_amount_"+index).value);

                    this.totalDiscountAmount  = Number(Number(this.totalDiscountAmount) + Number(discountAmount));

                    document.getElementById("item_discount_total_"+index).value = Number(discountAmount);
                    document.getElementById("total_discount_text").innerHTML    = this.formatCurrency(Number(this.totalDiscountAmount));
                    document.getElementById("discount_info_amount").innerHTML    = this.formatCurrency(Number(this.totalDiscountAmount));

                    this.totalAmount = Number(Number(this.totalAmount) - Number(discountAmount));
                    document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

                    this.formData.discount   = Number(this.totalDiscountAmount);
                    this.formData.total      = Number(this.totalAmount);
                    this.formData.subtotal   = Number(this.totalAmount);
                    this.formData.due_amount = Number(this.totalAmount);
                }
            }
            this.formData.items[index].quantity = event.target.value
        },
        getDiscount(index,event)
        {
            this.getQuantity(index,event);
            const singleProductAmount = document.getElementById("item_product_amount_"+index).value;
            const discount            = document.getElementById("item_product_disc_"+index).value;

            this.totalDiscountAmount  = Number(Number(this.totalDiscountAmount) - Number(document.getElementById("item_discount_total_"+index).value));
            document.getElementById("total_discount_text").innerHTML   = this.formatCurrency(Number(this.totalDiscountAmount));
            document.getElementById("discount_info_amount").innerHTML  = this.formatCurrency(Number(this.totalDiscountAmount));

            this.totalAmount = Number(Number(this.totalAmount) + Number(document.getElementById("item_discount_total_"+index).value));
            document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

            this.formData.discount   = Number(this.totalDiscountAmount);
            this.formData.total      = Number(this.totalAmount);
            this.formData.subtotal   = Number(this.totalAmount);
            this.formData.due_amount = Number(this.totalAmount);

            if(discount == ""){
                document.getElementById("item_discount_total_"+index).value = "";
            }else{
                let discountPercentage    = (discount / 100);
                let discountAmount        = Number(discountPercentage) * Number(singleProductAmount);

                this.totalDiscountAmount  = Number(Number(this.totalDiscountAmount) + Number(discountAmount));

                document.getElementById("item_discount_total_"+index).value = Number(discountAmount);
                document.getElementById("total_discount_text").innerHTML    = this.formatCurrency(Number(this.totalDiscountAmount));
                document.getElementById("discount_info_amount").innerHTML   = this.formatCurrency(Number(this.totalDiscountAmount));

                this.totalAmount = Number(Number(this.totalAmount) - Number(discountAmount));
                document.getElementById("grand_total").innerHTML = this.formatCurrency(this.totalAmount);

                this.formData.discount   = Number(this.totalDiscountAmount);
                this.formData.total      = Number(this.totalAmount);
                this.formData.subtotal   = Number(this.totalAmount);
                this.formData.due_amount = Number(this.totalAmount);
            }

            this.formData.items[index].discount_rate = event.target.value
        },
        formatCurrency(value) {
            return value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        formatOfAmount(value)
        {
            var res = new Intl.NumberFormat('en-IN').format(value);
            return res;
        },
        revertFormattedNumber(formattedValue) {
            return parseFloat(formattedValue.replace(/,/g, ''));
        },
        checkMaxQuantity(index,event)
        {
            //console.log(this.formData.items[index].maxquantity,event.target.value,event.key)
            if(Number(this.formData.items[index].quantity)>Number(this.formData.items[index].maxquantity))
            {
                //console.log("Show Stock Going to Negative Toaster");
               // const $toast = useToast();
                //$toast.warning('Stock Going to be Negative!');
                notification.warning({
                        placement:  "bottomRight",
                        message: "Stock Going to be Negative!",
                        //description: configObject.successMessage
                    });
            }
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                //console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)>0))
            {
               
                document.getElementById("item_product_price_"+this.selectedItermIndex).focus();
                return false;
            }
            
            
        },
        checkSingleItemPrice(index,event)
        {
            //console.log('checkSingleItemPrice',this.formData.items[index].single_unit_price,this.formData.items[index].max_single_unit_price)
            //console.log( document.getElementById("item_product_price_"+this.selectedItermIndex).value)
           if(Number(this.formData.items[index].single_unit_price)>Number(this.formData.items[index].max_single_unit_price))
            {
                //console.log("As Per M.R.P Allowed Upto : "+this.formData.items[index].max_single_unit_price);
                notification.warning({
                        placement:  "bottomRight",
                        message: "All sale rates should not be more than from M.R.P. and should not be less than from cost Per unit",
                        //description: configObject.successMessage
                    });


                notification.warning({
                        placement:  "bottomRight",
                        message: "As Per M.R.P Allowed Upto : "+this.formData.items[index].max_single_unit_price,
                        //description: configObject.successMessage
                    });
                //const $toast = useToast();
                //$toast.warning("As Per M.R.P Allowed Upto : "+this.formData.items[index].max_single_unit_price);
            }
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                //console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if(( event.key=="Enter") && (Number(event.target.value)>0))
            {
               
               document.getElementById("item_product_disc_"+this.selectedItermIndex).focus();
                return false;
            }
            
            
        },
        checkDisc(index,event)
        {
            //console.log('checkDisc',this.formData.items[index].discount_rate)
            if(( event.key=="Enter") )
            {
               
               document.getElementById("item_product_name_"+(this.selectedItermIndex+1)).focus();
                return false;
            }
        },
        success(response)
        { 
            
            console.log('rely=>>',response);
            this.isPaymentsModalVisible= true;
                notification.success({
                        placement:  "bottomRight",
                        message: t("common.success"),
                        description: response.message
                    });
        },
        saveSalesEntry()
        {
            this.loading= true;
            axiosAdmin
            .post("sales/store", this.formData)
            .then(response => {  
                // Toastr Notificaiton
                this.success(response);
            })
            .catch(errorResponse => {
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
                            ...err
                        }
                    }
                }

               

                //loading.value = false;
            });
        }
    },
    computed: {
        grandTotal() {
            return this.formData.items.reduce((total, item) => total + Number(item.amount), 0);
        },
    },
};
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

legend {
    width: auto !important;
}

.basicdetalis {
    border-bottom: none !important;
    color: black !important;
    margin-bottom: 0px !important;
    padding: 5px !important;
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
.total_igst_amt{
    text-align-last:right;
    font-weight:bolder;
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
  
  .savebutton{
    padding: 1px 11px;
  }
  .backgrounds{
    background-color:#1f6d70;
    color:white;
    font-size:13px !important;
    margin-left: 16px;
  }
  .draft{
    background-color:white;
    font-size:13px;
    border: solid 1px #00000033;
  }
  .savecolse{
    background-color:white;
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
</style>