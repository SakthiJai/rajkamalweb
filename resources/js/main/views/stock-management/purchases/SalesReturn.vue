<template>
    <AdminPageHeader>
        <template #header>
            <!--- @closed="handleClose"-->
            <ProductModal v-if="isProuctsModalVisible" :visible="isProuctsModalVisible" :formData="formData" :url="url"
                :successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess"  @closed="handleProductModal"
                 v-on:productclose-method="handleClose"  v-on:child-method="updateProduct" />
           <!-- payments modal-->
             <SalesReturnRecent v-if="isSalesReturnRecentVisible"  :visible="isSalesReturnRecentVisible" :formData="invoiceData" :url="url":successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess" @closed="handleClosePayments"  v-on:product-method="getProducts" :bill-value="formData.total" :bill-number="invoiceData"
               />
             <!-- end payments modal-->
              <!-- Print modal-->
            <SalesReturnPrintModel v-if="isPrintModalVisible" :visible="isPrintModalVisible" :formData="formData"
            :url="url" :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
            :items="formData.items"
            @addEditSuccess="handleSuccess"  @closed="handleClosePrint"
        />
        <!-- print modal-->
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
            <div style="float:right;font-size: 12px;margin-top:-2%"> <b>Balance&nbsp; ₹  :&nbsp;<span id="balance_amt" ></span></b>&nbsp;&nbsp;<b>Due&nbsp; ₹ :&nbsp;<span id="Due_amt" style="color:red"></span></b>&nbsp;&nbsp;&nbsp;
            </div>
        </template>
    </AdminPageHeader>
    <a-spin :spinning="spinning">
    <div id="salesreturnvue">
    <admin-page-table-content>
        <a-card class="page-content-container mt-5 mb-5">
            <a-form layout="vertical">
                <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.party_name1')" name="party_name" ref="input"
                            :help="rules.party_name ? rules.party_name.message : null"
                            :validateStatus="rules.party_name ? 'error' : null" class="required">
                            <a-input  v-model:value="formData.party_name" autocomplete="off"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.party_name')])"
                                @focus="handleFocus($event)" @blur="handleBlur" @keydown="customerhandleKeydown($event)"/>
                                <a-input hidden v-model="formData.party_id" id="party_id" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur="" />
                                 <a-input hidden v-model="formData.party_state" id="party_state" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur="" />
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

                    <InvoiceItemSelectModel
                        v-if="isInvoiceItemSelectModel"
                        :visible="isInvoiceItemSelectModel"
                        :invoice-list="invoiceData.invoiceItems"
                        :url="url"
                        :addEditType="addEditType"
                        :pageTitle="pageTitle"
                        :successMessage="successMessage"
                        @addEditSuccess="handleSuccess"
                        @closed="() => { isInvoiceItemSelectModel = false; }"   
                        v-on:select-method="updateItems"
                        v-on:close-method="focusEelment"
                    />
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

                                <input type="date" v-model="formData.order_date" class="ant-input css-dev-only-do-not-override-wosfq4" id="order_date" ref="DateInput"    @wheel.prevent="adjustDate($event)"   required @keydown="handleKeydownDatePicker"@focus="changeColorOnFocus('order_date')" @blur="resetColorOnBlur('order_date')" />
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
                            <input disabled autocomplete="off" id="form_item_mobile_number" v-model="formData.party_customer_mobile" placeholder="Press SpaceBar"
                                @keydown="showNumberModal($event)" class="ant-input css-dev-only-do-not-override-wosfq4"   />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.customer_name`)" name="customer_name" :help="rules.customer_name ? rules.customer_name.message : null
                            " :validateStatus="rules.customer_name ? 'error' : null">
                            <a-input disabled v-model:value="formData.customer_name" placeholder="Press SpaceBar"
                                @keydown.space.prevent="showCustomerModal" @blur="" />

                            <a-input hidden v-model:value="formData.party_customer_id" id="party_customer_id"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])" @blur="" />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.address`)" name="address" :help="rules.address ? rules.address.message : null
                            " :validateStatus="rules.address ? 'error' : null" >
                            <a-input disabled v-model:value="formData.address" autocomplete="off" :placeholder="$t('common.placeholder_default_text', [
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
                                            <th v-for="(header, index) in headers" :key="index"
                                            v-bind:style=" index>1 ? 'text-align:right' : 'text-align:left' "
                                            class="tableheading">&nbsp;{{ header }}&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemtable">
                                        <tr v-for="(prod, index) in formData.items" :key="prod.unique" :id="prod.unique">
                                            <!-- product -->
                                            <td style="width:18%">
                                                <input autocomplete="off" readonly
                                                v-model="formData.items[index].item_name"
                                                :ref="`input-${index}`"
                                                :id="`item_product_name_${index}`"
                                                @focus="updateTotalProd(index)"
                                                name="party_name" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                @keydown="showProductModal($event,index)"  style="color:black;font-weight:bolder;"
                                                >
                                                <a-input hidden :id="`item_product_id_${index}`" v-model="formData.items[index].item_id" />
                                                <a-input hidden  :id="`igst_item_${index}`"/>
                                                <input  hidden autocomplete="off" tabindex="-1" :id="`cgst_tax_${index}`" v-model="formData.items[index].cgst">
                                                <input  hidden autocomplete="off" tabindex="-1" :id="`sgst_tax_${index}`" v-model="formData.items[index].sgst">
                                            </td>
                                            <!-- invocice -->
                                            <td style="width:8%">
                                                <input autocomplete="off" tabindex="-1"
                                                :id="`item_product_packing_${index}`"
                                                v-model="formData.items[index].invoice"
                                                 style="color:black;font-weight:bolder;"
                                                readonly class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >
                                            </td>
                                            <!-- previouse qty -->
                                            <td style="width:7%">
                                                <input autocomplete="off" readonly
                                                :id="`item_product_quantity_${index}`"
                                                v-model="formData.items[index].quantity"

                                                name="quantity[]"  style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"

                                                @keypress="onlyForCurrency"
                                                >
                                            </td>
                                            <!-- return qty -->
                                            <td style="width:7%">
                                                <input autocomplete="off"
                                                :id="`item_product_return_qty_${index}`"
                                                v-model="formData.items[index].return_qty"  @keydown="gotoReason(index,$event)"
                                                @input="validateReturnQty(index, $event), getQuantity(index, $event)"
                                                @focus="getQuantity(index, $event), focusinputvalue($event)"
                                                name="quantity[]" @blur="updateAgg(),checkMaxQuantity(index,$event)" style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"

                                                @keypress="onlyForCurrency"
                                                >
                                            </td>
                                            <!-- return reason -->
                                             <td style="width:8%">

                                                <select class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                                                v-model="formData.items[index].return_reason_code"
                                                :id="`item_product_return_reason_code_${index}`"placeholder="Discount" :allowClear="false"
                                                 optionFilterProp="title" show-search @change="gotoRate(index,$event)"
                                                 >

                                                    <option v-for="tax in returnTypes" :key="tax.id" :value="tax.id" :title="tax.reason">
                                                        {{ tax.reason }}
                                                    </option>
                                                </select>

                                            </td>
                                            
                                            <!--  rate -->
                                            <td style="width:9%">
                                                <input readonly autocomplete="off"
                                                v-model="formData.items[index].single_unit_price" @focus="focusinputvalue($event)"
                                                :ref="`input-${index}`" @input="getQuantity(index,$event)"
                                                :id="`item_product_max_price_${index}`"
                                                @keydown="checkSingleItemPrice(index,$event)"
                                                name="single_unit_price" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                 style="color:black;font-weight:bolder;text-align-last:right;"
                                                >
                                            </td>

                                            <!-- Discount Rate -->
                                            <td style="width:9%">
                                                <input readonly autocomplete="off"
                                                v-model="formData.items[index].max_single_unit_price" @focus="focusinputvalue($event)"
                                                :ref="`input-${index}`" @input="getQuantity(index,$event)"
                                                :id="`item_product_price_${index}`"
                                                @keydown="checkSingleItemPrice(index,$event)"
                                                name="single_unit_price" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                 style="color:black;font-weight:bolder;text-align-last:right;"
                                                >
                                            </td>

                                            <!-- taxxx -->
                                            <td style="text-align:right;width:7%">
                                               <b style="font-size:12px;" :id="`item_product_tax_${index}`"></b>&nbsp;&nbsp;
                                            </td>

                                            <!-- amount -->
                                            <td style="width:10%">
                                                <input
                                                disabled=true :id="`item_product_amount_${index}_${formData.items[index].unique}`"
                                                v-model="formData.items[index].amount"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                />
                                            </td>
                                        </tr>

                                        <!-- new bottom row for toatls  -->
                                            <tr v-for="(header, index) in formData.invoiceitems" :key="index" class="invoicevalues">
                                            <td style="width:18%">
                                                <input id="total_prod_count"
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:8%">
                                                <input
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:7%">
                                                
                                            </td>
                                            <td style="width:7%">
                                                
                                            </td>
                                            <td style="width:7%">
                                                
                                            </td>
                                            

                                            <td style="width:9%">
                                                <input
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>

                                            </td>
                                            <td style="width:7%">
                                               

                                            </td>


                                            <td style="width:8%;text-align:right">
                                               <!-- <input
                                                disabled=true value="Goods Value"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>-->
                                                <b><span id="total_discount_text">0.00</span></b>&nbsp;&nbsp;
                                            </td>

                                            <td style="width:10%">
                                                <input id="total_goods_value"
                                                disabled=true value="0.00"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                        </tr>
                                        <!--- new row-->

                                        <!-- end of row-->
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
                                    <a-row :gutter="16" class="mt-3">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;font-size: 13px">
                                            {{ $t("stock.total_item") }}

                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="3" :lg="3" style="color:black;font-weight:bolder;font-size: 13px">

                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                            <span id="discount_info_amount" style="color:black;font-weight:bolder;text-align-last:right;font-size: 13px">0.00</span>
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16" class="mt-3">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;font-size: 13px">
                                            {{ $t("stock.scheme") }}
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="3" :lg="3" style="color:black;font-weight:bolder;font-size: 13px">

                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="4" :lg="4" style="color:black;font-weight:bolder;font-size: 13px;text-align-last:right;">

                                            <span>0.00</span>
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16" class="mt-3">
                                        <a-col :xs="12" :sm="12" :md="12" :lg="12" style="color:black;font-weight:bolder;font-size: 13px">
                                            Volume Disc.
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="2" :lg="2" style="color:black;font-weight:bolder;font-size: 13px">
                                            <span>:</span>
                                        </a-col>
                                        <a-col :xs="12" :sm="12" :md="5" :lg="5" style="color:black;font-weight:bolder;text-align-last:right;">
                                            <span>0.00</span>
                                        </a-col>
                                    </a-row>
                                </fieldset>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" hidden>
                                <fieldset>
                                    <legend class="basicdetalis">
                                        Tax Info
                                    </legend>
                                    <a-row :gutter="16" class="mt-5">
                                        <a-col :xs="12" :sm="12" :md="8" :lg="8" style="color:black;font-weight:bolder;">
                                            CGST &nbsp;:
                                        </a-col>

                                        <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                            <span id="cgst_total_text" style="color:black;font-weight:bolder;text-align-last:right;">0.00</span>
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16" class="mt-5">
                                        <a-col :xs="12" :sm="12" :md="8" :lg="8" style="color:black;font-weight:bolder;">
                                            SGST &nbsp;:
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
                        <!--<a-row :gutter="16">
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
                        </a-row>-->
                        <a-row :gutter="16" class="mt-5">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <table class="responsive-table">
                                    <thead>
                                        <tr>
                                            <th v-for="(header, index) in addtionaldetalisheader" :key="index"class="tableheading">
                                                &nbsp;{{ header }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr :id="item.name" v-for="(item, index) in additems" :key="index" :class="{ highlight: index === selectedIndex }">
                                            <td style="font-weight:bolder;font-size: 13px;width:40%">&nbsp;{{ item.name }}</td>
                                         <!-- <td style="text-align:right;font-weight:bolder;width:10%">{{ item.location }}</td> -->
                                            <td>
                                                <input readonly tabindex="-1"   class="ant-input css-dev-only-do-not-override-wosfq4" :id="`igst_amount_${index}`"  style="text-align:right;color:black;font-weight:bolder;" name="table_total_amount[]"></input>
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
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12" style="font-size:16px;font-weight:bolder;">
                                        Invoice Value &nbsp;&nbsp;&nbsp; :
                                    </a-col>
                                    <a-col :xs="12" :sm="12" :md="12" :lg="12" style="text-align-last:right;font-size:16px;font-weight:bolder;">
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
                            <a-button type="button" id="saveF8" class="backgrounds" :loading="loading" @click="saveSalesReturnEntry($event)" @keydown="handleSaveKeydown($event)" block>
                                <span class="shortcut" id="shortcut">
                                    <code>F8 / End</code>
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

                    <!-- <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="draft"   block>
                                Save As Draft
                              </a-button>
                        </a-col> -->
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="savecolse" @click="cancelSalesReturn()"   block>
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
    </div>
    </a-spin>


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
                <span  class="shortcut ng-star-inserted"><code>F8</code></span>
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
import { onMounted, ref, toRefs, computed,getCurrentInstance,inject } from "vue";
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
import stockManagement from "./stockManagement";
import common from "../../../../common/composable/common";
import fields from "./fields";
import ProductModal from './Product/ProductModal.vue';
import TaxAddButton from "../../settings/taxes/AddButton.vue";
import WarehouseAddButton from "../../settings/warehouses/AddButton.vue";
import ProductAddButton from "../../product-manager/products/AddButton.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DateTimePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import UserSearch from "./UserSearch.vue";
import FormItemHeading from "../../../../common/components/common/typography/FormItemHeading.vue";
import { some, forEach, find } from "lodash-es";
import PaymentModeAddButton from "../payments/AddButton.vue";
import SalesModel from "./SalesModel.vue";
import SalesNumberModel from "./SalesNumberModel.vue";
import SalesCustomerModel from "./SalesCustomerModel.vue";
import SalesReturnRecent from "./SalesReturnRecent.vue";
import { message, notification } from "ant-design-vue";
//import {useToast} from 'vue-toast-notification';
//import 'vue-toast-notification/dist/theme-sugar.css';
import SalesReturnPrintModel from "./SalesReturnPrintModel.vue";
import InvoiceItemSelectModel from "./InvoiceItemSelectModel.vue";
import DeleteConfirmationPopupModel from "./DeleteConfirmationPopupModel.vue";
import { Modal } from 'ant-design-vue';
import { createVNode } from 'vue';



export default {
    props:['username'],
    inject:['globalVar'],
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
        InvoiceItemSelectModel,
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
        SalesReturnRecent,
        SalesCustomerModel,
        SalesReturnPrintModel,
        DeleteConfirmationPopupModel,
    },
    setup(props, { emit }) {

        const { loading,handlePaymentSubmit, rules } = apiAdmin();
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
const company = appSetting.value;
       let spinning=ref(false)
        let selectedItermIndex = ref('');
        let formData = ref("Unknown")
        let invoiceData = ref("Unknown")
        const { orderItemColumns } = fields();
        const { salesItemColumns } = fields();
        formData=({
                order_date: new Date().toISOString().split('T')[0],
            party_id: 0,
                party_state:"",
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
                salesReturnType:null,
                selectedInvoice:localStorage.getItem("selectedInvoice"),
                items:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null},
                    {index:2,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:3,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:4,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:5,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:6,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:7,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:8,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:9,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:10,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                ],
                invoiceitems:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null},
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

        onMounted(() => {

        });


        const onSubmit = () => {};

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
            ////console.log(record);
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
            invoiceData,
            productsAmount,
            rules,
            loading,
            warehouses,
            company,
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
            spinning:false,
            //selectedInvoice:localStorage.getItem("cr_number"),
            additems: [
                {id:"CGST" ,name: 'CGST', location: '', value: '' },
                { id: "SGST", name: 'SGST', location: '', value: '' },
                { id: "IGST", name: 'IGST', location: '', value: '' },
                { id:"CESS" ,name: 'CESS', location: '', value: '' },

                { id:"Total" ,name: 'Total', location: '', value: '' }],
            isNumberVisible: false,
            isSalesReturnRecentVisible:false,
            isModalPopup:false,
            isCustomerVisible: false,
            isModalVisible: false,
            isInvoiceItemSelectModel:false,
            isProuctsModalVisible: false,
            stockDateColor: '',
            deleteConfirmationPopup:false,
			isNumberVisible:false,
            isPrintModalVisible: false,
            items: [
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
            ],
            invoiceitems:[
                { quantity: "0.00",  }
            ],

            isOpensave: false,

            dropsitems: [
        { text: "Save & SMS", icon: `<svg height="14" viewBox="0 0 1024 1024"><path fill="currentColor" d="M256 448h512v128h-512zM256 256h512v128h-512zM960 0h-896c-35.376 0-64 28.624-64 64v704c0 35.376 28.658 64 64 64h895.998c35.344 0 64.002-28.624 64.002-64v-640c0-35.376-28.658-64-64.002-64zM896 704h-416l-160 96v-96h-192v-576h768v576z"></path></svg>`, isSvg: true },
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
            headers: ['Product', 'Invoice', 'Previous Qty',"Return Qty", "Return Reason",'Rate (per qty)',"Discount (per qty)",'Tax %', '₹ Amount'],
            addtionaldetalisheader:['Additional Details', '₹ Amount'],
        };
    },

    mounted() {
        document.getElementById("salesreturnvue").addEventListener('keydown', this.handleKeyDown);
        this.autoFocusInput();
        this.getInvoiceDetails();
    if (this.formData.selectedInvoice) {
        this.formData.bill_number = this.formData.selectedInvoice;
        const billElMount = document.getElementById("form_item_bill_number");
        if (billElMount) billElMount.value = this.formData.bill_number;
    }
console.log("selectedInvoice", this.formData.selectedInvoice);  
    },
    beforeDestroy() {
    },


    methods: {
    updateDiscount(index,event)
        {

            this.formData.items[index].discount_type_id = document.getElementById('item_product_disc_type_'+index).value;

            if(  document.getElementById('item_product_disc_type_'+index).value==1){
                this.formData.items[index].discount_rate=0;
                /*document.getElementById('item_product_name_'+(index+1)).focus()
                document.getElementById('item_product_disc_'+index).value=0*/
            }
            else
            {

                //document.getElementById('item_product_disc_'+index).focus()
            }
            this.getQuantity(index,event)
        },

        deleteItem()
{
    console.log(this.selectedItermIndex);
    if (this.selectedItermIndex!=null || this.selectedItermIndex!=undefined) {
        // Remove the selected item
        console.log(this.selectedItermIndex);

        let temp = this.formData;
        temp.items.forEach((item, index) => {

            if(item.index == (this.selectedItermIndex+1)){ console.log(item)
                this.formData.items.splice(index, 1);
                document.getElementById(item.unique).remove();
                //this.getQuantity(1,null);

            }

        });


        this.formData.items.push({
            index: null,
            item_id: null,
            item_name: null,
            unit_id: null,
            quantity: '',
            mrp: null,
            single_unit_price: null,
            discount_rate: null,
            amount: null,
            maxquantity: 0,
            max_single_unit_price: 0,
            packing: null,
            cgst: null,
            sgst: null,
            cess: null,
            unique:Math.random().toString(36).substring(2,7)
        });
        this.formData.items.forEach((items,listindex)=>{
           items.index=listindex+1;
        })
        console.log(this.formData.items[8].unique);
        var T = document.getElementById('itemtablesalesreturn');
        var R = document.getElementById( this.formData.items[8].unique);
        var C = R.cloneNode(true);
        T.appendChild(C);

       // console.log(this.formData.items)

        this.deleteConfirmationPopup = false; // Hide the delete confirmation popup
        var that = this;
        setTimeout(function(){ that.getQuantity(0,null);},1000)


    }

},

        confirmDeletion(event, index) {
      if (event.key === "Backspace" || event.key === "Delete") {
        event.preventDefault();

        if (confirm("Do you want to delete this row?")) {
          this.formData.items.splice(index, 1);
        } 
      }
    },


    handleClosepopup(){
        this.deleteConfirmationPopup=false;
        },



getInvoiceDetails() {

    console.log("selectedInvoice inside method", this.formData.selectedInvoice);

    if (!this.formData.selectedInvoice || this.formData.selectedInvoice === "null") {
        console.warn("No selected invoice available");
        return;
    }

    this.formData.bill_number = this.formData.selectedInvoice;
    this.spinning = true;

    axiosAdmin
        .get("sales/getReturnInvoiceDetails/" + this.formData.selectedInvoice)
        .then(response => {

            const data = response.data;

            if (!data) {
                this.spinning = false;
                return;
            }

            console.log("Invoice details response", data);
 

            this.formData.party_id = data.invoiceData?.party_id || data.partyDetails?.id || '';
            this.formData.party_name = data.partyDetails?.party_name || data.invoiceData?.party_name || '';
            this.formData.party_customer_id = data.invoiceData?.party_customer_id || data.customerData?.id || '';
            this.formData.customer_name = data.customerData?.cus_name || '';
            this.formData.party_customer_mobile = data.customerData?.mobile_number || '';
            this.formData.address = data.customerData?.address || data.partyDetails?.Address || '';
 // Map stock_state from partyDetails
            this.formData.stock_state = data.partyDetails?.stock_state || '';
 

            this.formData.order_date = data.invoiceData?.order_date
                ? data.invoiceData.order_date.split(' ')[0]
                : '';

            this.formData.tax_amount = data.invoiceData?.tax_amount || '';
            this.formData.discount = data.invoiceData?.total_discount || '';
            this.formData.subtotal = data.invoiceData?.total_amount || '';
            this.formData.due_amount = data.invoiceData?.due_amount || '';
            this.formData.order_status = data.invoiceData?.order_status || '';
            this.formData.total_items = data.invoiceData?.total_items || '';

            this.formData.total_quantity =
                Array.isArray(data.invoiceItems)
                    ? data.invoiceItems.reduce((sum, item) => sum + (item.quantity || 0), 0)
                    : '';

            this.formData.bill_number = data.invoiceData?.cr_number || '';
            this.formData.invoice_number = data.invoiceData?.order_id || '';
 

            this.formData.items = Array.isArray(data.invoiceItems)
                ? data.invoiceItems.map((item, idx) => {

                    const discValue = item.disc_value || 0;
                    const discountedPrice =
                        item.single_unit_price - ((item.single_unit_price / 100) * discValue);

                    // User calculation logic (consistent with getQuantity)
                    const max_single_unit_price = discountedPrice;
                    const cgst = parseFloat(item.cgst || 0);
                    const sgst = parseFloat(item.sgst || 0);
                    const return_qty = item.return_qty || 0;
                    // 1) Subtotal calculation
                    const subtotal = max_single_unit_price * return_qty;
                    // 2) Tax calculation
                    const tax = (max_single_unit_price / 100) * (cgst + sgst);
                    // 3) Total amount calculation
                    const totalamount = subtotal + tax;

                    return {
                        item_id: item.product_id,
                        item_name: item.product_name,
                        unit_id: '',
                        quantity: item.quantity,
                        mrp: item.mrp,
                        single_unit_price: item.single_unit_price,
                        discount_rate: item.discount_rate || discValue,
                        amount: totalamount,
                        maxquantity: item.stock,
                        return_qty: item.return_qty,
                        return_reason_code: item.return_reason_code != null ? String(item.return_reason_code) : '',
                        disc_type: item.disc_type,
                        disc_value: discValue,
                        totalTaxRate: parseFloat(item.cgst || 0) + parseFloat(item.sgst || 0),
                        freeQty: item.freeQty || 0,
                        remQty: item.quantity - (item.freeQty || 0),
                        max_single_unit_price: max_single_unit_price,
                        packing: item.pack,
                        cgst: item.cgst,
                        sgst: item.sgst,
                        cess: item.cess,
                        discount_type_id: item.discount_type_id,
                        hsnCode: item.hsnCode,
                        qtyUnit: item.qtyUnit,
                        index: idx + 1,
                        unique: Math.random().toString(36).substring(2, 7),
                        calculated_tax: tax,
                        calculated_subtotal: subtotal,
                        calculated_totalamount: totalamount
                    };

                })
                : [];

            console.log("Formatted items data", this.formData.items);
 

            let totalcgst = this.getTotalAmount ? this.getTotalAmount('cgst', null) : 0;
            let totalsgst = this.getTotalAmount ? this.getTotalAmount('sgst', null) : 0;
            let cessAmount = this.getTotalAmount ? this.getTotalAmount('cess', null) : 0;

            let grand_total = 0;

            if (this.formData.items.length > 0) {
                this.formData.items.forEach(item => {
                    grand_total += Number(item.amount) || 0;
                });
            }

            this.formData.total = grand_total + cessAmount;
 

            if (document.getElementById('total_goods_value'))
                document.getElementById('total_goods_value').value =
                    this.formatCurrency ? this.formatCurrency(grand_total) : grand_total;

            if (document.getElementById('igst_amount_0'))
                document.getElementById('igst_amount_0').value =
                    this.formatCurrency ? this.formatCurrency(totalcgst) : totalcgst;

            if (document.getElementById('igst_amount_1'))
                document.getElementById('igst_amount_1').value =
                    this.formatCurrency ? this.formatCurrency(totalsgst) : totalsgst;

            if (document.getElementById('igst_amount_2'))
                document.getElementById('igst_amount_2').value =
                    this.formatCurrency ? this.formatCurrency(totalcgst + totalsgst) : (totalcgst + totalsgst);

            if (document.getElementById('igst_amount_3'))
                document.getElementById('igst_amount_3').value =
                    this.formatCurrency ? this.formatCurrency(cessAmount) : cessAmount;

            if (document.getElementById('grand_total'))
                document.getElementById('grand_total').innerHTML =
                    this.formatCurrency
                        ? this.formatCurrency(grand_total + cessAmount)
                        : (grand_total + cessAmount);

            if (document.getElementById('cgst_total_text'))
                document.getElementById('cgst_total_text').innerHTML =
                    this.formatCurrency
                        ? this.formatCurrency(totalcgst > 0 ? totalcgst : 0)
                        : (totalcgst > 0 ? totalcgst : 0);

            if (document.getElementById('sgst_total_text'))
                document.getElementById('sgst_total_text').innerHTML =
                    this.formatCurrency
                        ? this.formatCurrency(totalsgst > 0 ? totalsgst : 0)
                        : (totalsgst > 0 ? totalsgst : 0);

                        console.log("Company State:", this.company.state);
                        console.log("Party State:", this.formData.stock_state);

 

            if (this.formData.stock_state == this.company.state) {

                if (document.getElementById("IGST"))
                    document.getElementById("IGST").style.display = "none";

                if (document.getElementById("CGST"))
                    document.getElementById("CGST").style.display = "table-row";

                if (document.getElementById("SGST"))
                    document.getElementById("SGST").style.display = "table-row";

            } else {

                if (document.getElementById("IGST"))
                    document.getElementById("IGST").style.display = "table-row";

                if (document.getElementById("CGST"))
                    document.getElementById("CGST").style.display = "none";

                if (document.getElementById("SGST"))
                    document.getElementById("SGST").style.display = "none";
            }


            this.returnTypes = Array.isArray(data.returnTypes)
                ? data.returnTypes.map(rt => ({
                    id: String(rt.id),
                    reason: rt.reason
                }))
                : [];

            this.spinning = false;
              // Focus the first return_qty input after data loads
            this.$nextTick(() => {
                const firstReturnQty = document.getElementById('item_product_return_qty_0');
                if (firstReturnQty) firstReturnQty.focus();
            });

        })
        .catch(error => {

            console.error("Invoice fetch error", error);
            this.spinning = false;

        });
},
        formatNumber (num) {
       return parseFloat(num).toFixed(2)
      },

      toggleDropdowns() {
      this.isOpensave = !this.isOpensave;
    },

    showPrintModal() {
            //console.log("Print called");
            //this.isPrintModalVisible = true;
        },

        handleClosePrint() {
            console.log("Print called");
            this.resetFormData();
            this.isPrintModalVisible = false;

        },

    handleEnterKey(event) {
      // Check if Enter or Space key is pressed
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent default scrolling behavior for the Space key
        this.toggleDropdowns();
      }
    },
        onlyForCurrency($event) {
            // ////console.log($event.keyCode); //keyCodes value
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
          var that =this;
          setTimeout(() => {
            if(that.selectedInvoice=='null'){
            this.showModal();
            }
          }, 600);
        }
      });
    },

    autoFocusInput() {
        let datas = this.$route.params.data;
        //console.log("data is", datas,this.data);
            this.$nextTick(() => {
                this.$refs.DateInput.focus();
            });
        },

    handleBlur() {
      ////console.log('Focus removed from input');
      this.showMessage = true;
    },
    customerhandleKeydown(event)
    {
        console.log('calling every where',event.keyCode)
        if(this.formData.party_id==undefined || this.formData.party_id=="" || this.formData.party_id<0)
        {
            this.isModalVisible = true;
            return false;
        }
        else if ((event.keyCode === 13 || event.keyCode === 9) && this.formData.party_id>0) {
         this.isModalVisible = false;
         document.getElementById('item_product_name_'+this.selectedItermIndex).focus();

        }
        else if ((event.keyCode !== 13 && event.keyCode !== 9) && this.formData.party_id>0) {
         this.isModalVisible = true;
         return false;

        }
    },
    handleKeyDown(event) {
        console.log(event.key,event.keyCode)
      if (event.key === 'Escape' || event.keyCode === 27) {
         this.cancelSalesReturn();
      }
      else if (event.key === 'F8') {
        this.saveSalesReturnEntry();
      }/*
      else if ((event.keyCode === 13 || event.keyCode === 9) && this.formData.party_id>0) {
         this.isModalVisible = false;
         document.getElementById('item_product_name_'+this.selectedItermIndex).focus();
        ////console.log("sele ",this.selectedItermIndex)
      }
      else if ((event.keyCode !== 13 && event.keyCode !== 9) && this.formData.party_id>0)
      {
        this.isModalVisible = true;
      }*/
    },

        cancelSalesReturn() {
            console.log("Esc called");
            let that = this;
            const modal = Modal.confirm({
                title: "Confirmation",
                icon: createVNode(ExclamationCircleOutlined),
                content:
                    "Transaction data will be lost. Are you sure you want to close?",
                onOk() {
                    localStorage.setItem("selectedInvoice", null);
                    that.$router.push({
                        name: `admin.stock.sales-returns.index`,
                    });
                },
                onCancel() {
                    that.$refs.partyinput && that.$refs.partyinput.focus();
                },
                okText: "OK",
                cancelText: "Cancel",
                autoFocusButton: "cancel",
            });

            this.$nextTick(() => {
                const handleKeydown = (e) => {
                    if (
                        modal &&
                        (e.key === "ArrowLeft" || e.key === "ArrowRight")
                    ) {
                        e.preventDefault();
                        const buttons = document.querySelectorAll(
                            ".ant-modal-confirm .ant-btn"
                        );
                        const cancelBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-default")
                        );
                        const okBtn = Array.from(buttons).find((btn) =>
                            btn.classList.contains("ant-btn-primary")
                        );

                        const focusedElement = document.activeElement;

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

                modal.afterClose(() => {
                    document.removeEventListener("keydown", handleKeydown);
                });
            });
        },



        recentbillconfirm() {
            this.formData.party_id = null;
      console.log("Esc called");
      let that = this;
      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "No recent bill available for selected party!",
        onOk() {
            that.isModalVisible = true;
        },
        onCancel() {


          that.$router.push({
            name: `admin.stock.sales-returns.index`,
          });
        },
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
      });

      this.$nextTick(() => {
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
        modal.afterClose(() => {
          document.removeEventListener("keydown", handleKeydown);
        });
      });
    },

        // end recent bill conformation//


    handleKeydownDatePicker(event) { ////console.log("Date picker event",event.target.value);
      if ((event.key === 'Tab' || event.key === 'Enter') && event.target.value=="") {
        this.formData.order_date =  new Date().toISOString().split('T')[0];

        const dateEl = document.getElementById("order_date") || document.getElementById("form_item_order_date");
        if (dateEl) dateEl.value = this.formData.order_date;
       //ssss this.$refs.dateInput.popupVisible = false
        event.preventDefault();
        return false;
        //this.handleFocus();
      }

      else if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
    const currentDate = new Date(this.formData.order_date || new Date());
    const increment = event.key === 'ArrowUp' ? 1 : -1; // Determine increment or decrement
    currentDate.setDate(currentDate.getDate() + increment);
    this.formData.order_date = currentDate.toISOString().split('T')[0];
    const dateEl2 = document.getElementById("order_date") || document.getElementById("form_item_order_date");
    if (dateEl2) dateEl2.value = this.formData.order_date;
    event.preventDefault();
} else if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
     this.isModalVisible = false;
    //event.preventDefault();
}
else if (!isNaN(Number(event.key)) && event.key !== ' ') {
        const increment = Number(event.key);
        const currentDate = new Date(this.formData.order_date || new Date());
        currentDate.setDate(currentDate.getDate() + increment);
        this.formData.order_date = currentDate.toISOString().split('T')[0];

        // Validate year length
        const year = currentDate.getFullYear().toString();
        if (year.length > 4) {
            console.warn('Year exceeds 4 digits. Restricting to 4 digits.');
            currentDate.setFullYear(Number(year.slice(0, 4)));
            this.formData.order_date = currentDate.toISOString().split('T')[0];
        }

        const dateEl3 = document.getElementById("order_date") || document.getElementById("form_item_order_date");
        if (dateEl3) dateEl3.value = this.formData.order_date;
        event.preventDefault();
    }
      else{

        this.handleFocus();

      }
    },

    adjustDate(event) {
    const currentDate = new Date(this.formData.order_date || new Date());
    const increment =
        event.deltaY < 0 || event.key === "ArrowUp" || event.key === "ArrowRight" ? 1 : -1;
    currentDate.setDate(currentDate.getDate() + increment);
    let adjustedDate = currentDate.toISOString().split("T")[0];
    const dateParts = adjustedDate.split("-");
    if (dateParts.length === 3) {
        const year = dateParts[0];
        if (year.length > 4) {
            dateParts[0] = year.slice(0, 4);
            adjustedDate = dateParts.join("-");
        }
    }
    this.formData.order_date = adjustedDate;
    const dateInput = document.getElementById("order_date") || document.getElementById("form_item_order_date");
    if (dateInput) {
        dateInput.value = this.formData.order_date;
    }
},

    showPaymentModal(event){
        ////console.log("Payment called");
        this.isSalesReturnRecentVisible = true;
        const amt = document.getElementById('amount_0');
        if (amt) amt.focus();
        if (event && event.preventDefault) event.preventDefault();
    },


    // handleClosePayments(){
    //     this.isSalesReturnRecentVisible = false;
    //     this.isPrintModalVisible= true;
    //     document.getElementById('billing_print').focus();
    // },

    handleClosePayments(input){
        this.isSalesReturnRecentVisible = false;
       // this.focusElement('order_date');
       // this.isPrintModalVisible= true;
        const dateEl = document.getElementById('order_date');
        if (dateEl) dateEl.focus();
        if(input==27)
        {  this.focusElement('order_date'); }
        else{
            // this.isPrintModalVisible= true;
        }
       // setTimeout(function(){document.getElementById('btn-Ledger').focus();},500);

    },

        inputData($event) {
            // Value will update reactively
            $event.target.value;
        },
        showNumberModal(event) {
            console.log("sal44444444");
            if((event.keyCode==13 || event.keyCode==32 || event.keyCode==9 ))
            {
                this.isNumberVisible = true;
            }
            event.preventDefault();
                        return false;


        },
        handleSalesNumber() {
            ////console.log("close Model",this.selectedItermIndex);
            this.isNumberVisible = false;
            if(this.selectedItermIndex==undefined){
                document.getElementById("item_product_name_1").focus();
            }
            else{
                let cf =0;
                //this.focusProductList();
                for (let i = 0; i < this.formData.items.length; i++) {
                    //console.log("Find Post",this.formData.items[i]);
                    if(cf== 0 && (this.formData.items[i].item_id!=undefined && this.formData.items[i].item_id>0 &&  this.formData.items[i].quantity>0))
                    {    cf=i;


                    break;
                    }
                }
                //console.log("cf=>",cf);
                if(cf>0)
                {
                    document.getElementById("item_product_name_"+(this.selectedItermIndex)).focus();
                }
                else{
                    document.getElementById("shortcut").focus();
                    //event.preventDefault();
                }

            }
            return false;

        },

        getCurrentDate() {
            // returns the current date in the format YYYY-MM-DD
            return new Date().toISOString().split('T')[0];
        },
        showModal() {
            this.isModalVisible = true;
            ////console.log(33);
            //this.$refs.dummykeyboard.focus();
        },

        focusElement(field) {
            if (field === 'order_date') {
                document.getElementById('order_date').focus();
            }
        },


        handleCloseCustomer() { ////console.log("Close 1",this.formData.customer_name);
          this.isModalVisible = false;
          this.focusElement('order_date');

            if (this.formData.party_id <=0) {
                return false;
            }
        this.invoiceData = null;

            this.spinning= true;
                const billNumberUrl = `sales/crNumber/`+this.formData.party_id;
                axiosAdmin.get(billNumberUrl).then((response) => {
                //console.log(response)
                    if (response.data.recentBill.length==0) {
                        notification.warning({
                        placement:  "bottomRight",
                        message: "No recent bill available for selected party!",

                    });
                   this.recentbillconfirm();

                    }
                this.formData.bill_number = response.data.cr;
                this.formData.invoice_number = response.data.recentBill[0].invoice_number;
                if( response.data.customer){
                    this.formData.party_customer_id=response.data.customer.id
                    this.formData.customer_name=response.data.customer.cus_name
                    this.formData.party_customer_mobile=response.data.customer.mobile_number
                    this.formData.address=response.data.customer.address
                }
                this.invoiceData = response.data;
                console.log(this.invoiceData)
                document.getElementById("form_item_bill_number").value = this.formData.bill_number;

                const that = this;
                setTimeout(function(){
                    that.isSalesReturnRecentVisible= true;

                    that.spinning= false;
                },2000);

                 }) .catch(errorResponse => {
                    this.spinning= false;
                 });

           // this.isProuctsModalVisible = false;
           //this.focusEelment();
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
            ////console.log('Success:', xid);
        },



        updateParent(selectedParty) {
            console.log('selectedParty=>', selectedParty)
            console.log("hello child" , this.company)
                const balanceEl = document.getElementById("balance_amt");
                if (balanceEl) balanceEl.innerHTML = 0.00;
                const dueEl = document.getElementById("Due_amt");
                if (dueEl) dueEl.innerHTML = 0.00;


            this.formData.party_id = selectedParty.id.toString();
            this.formData.party_name = selectedParty.name;

            console.log("party_state", selectedParty.state,"company_state", this.company.state);
            this.formData.party_state = selectedParty.state;
            if (this.formData.party_state === this.company.state) {
                document.getElementById("IGST").style.display = "none";
                document.getElementById("CGST").style.display = "table-row";
                document.getElementById("SGST").style.display = "table-row";

            }
            else
            {
                document.getElementById("IGST").style.display = "table-row";
                document.getElementById("CGST").style.display = "none";
                document.getElementById("SGST").style.display = "none";
            }
           // console.log("hello child" + this.formData.name)
            const partyIdEl1 = document.getElementById("party_id");
            if (partyIdEl1) partyIdEl1.value = this.formData.party_id;
            const partyStateEl1 = document.getElementById("party_state");
            if (partyStateEl1) partyStateEl1.value = selectedParty.state;
            this.formData.party_state = selectedParty.state;
           
           // console.log("hello child" + this.formData.name)
            const partyIdEl2 = document.getElementById("party_id");
            if (partyIdEl2) partyIdEl2.value = this.formData.party_id;
            const partyStateEl2 = document.getElementById("party_state");
            if (partyStateEl2) partyStateEl2.value = selectedParty.state;
           // console.log("hello child" + this.formData.name)
            const partyIdEl3 = document.getElementById("party_id");
            if (partyIdEl3) partyIdEl3.value = this.formData.party_id;
            const formItemNameEl = document.getElementById("form_item_name");
            if (formItemNameEl) formItemNameEl.value = this.formData.name;
            const formItemPartyNameEl = document.getElementById("form_item_party_name");
            if (formItemPartyNameEl) formItemPartyNameEl.value = this.formData.party_name;

            if(this.formData.party_id!=undefined && this.formData.party_id!="")
            {
                this.loading= true;
                const billNumberUrl = `sales/billNumber`+"/"+this.formData.party_id;
                axiosAdmin.get(billNumberUrl).then((response) => {
                //console.log(response)
                this.formData.bill_number = response.data.ref;
                    this.discountTypes = response.data.discountItems;
                    this.returnTypes = response.data.returnTypes;
                    document.getElementById("balance_amt").innerHTML = (response.data.balance).toFixed(2);
                     document.getElementById("Due_amt").innerHTML = (response.data.balance).toFixed(2);

                    const billEl = document.getElementById("form_item_bill_number");
                    if (billEl) billEl.value = this.formData.bill_number;
                this.loading= false;
                 });
            }
            this.isModalVisible=false;
            // this.focusEelment()

        },

        openProduct(data)
        {
            console.log("Open Product method",data);
            data.invoiceItems.forEach((data)=>{
                if(data.freeQty>0)
                { 
                    data.quantity= data.quantity-data.freeQty;
                }
                if(data.discount_type_id==2)
                {
                    let total = (data.quantity*data.product.sale_rate);
                    if(data.discount_rate>0){
                    let disctotal =(total-(total*(data.discount_rate/100)));
                    data.discount = disctotal/data.quantity;
                    }   
                }
                else if(data.discount_type_id==1)
                {
                   
                    data.discount = data.product.sale_rate;
                }
                else if(data.discount_type_id==3)
                {
                    let total = (data.quantity * data.product.sale_rate);
                    data.discount = Number((data.product.sale_rate - ( data.discount_rate/data.quantity)).toFixed(2));
                       
                }
                else if(data.discount_type_id==4)
                {
                    let total = (data.quantity * data.product.sale_rate);
                    data.discount = Number((data.product.sale_rate - data.discount_rate).toFixed(2));
                       
                }
            })
            this.invoiceData.invoiceItems = data.invoiceItems;
            console.log( this.invoiceData.invoiceItems);
            this.spinning = false;
            // Auto-populate table with all invoice items
            this.updateItems(data.invoiceItems);
        },
        getProducts(selectedInvoice) {
            selectedInvoice = typeof selectedInvoice === 'object' && selectedInvoice !== null && 'id' in selectedInvoice ? String(selectedInvoice.id) : String(selectedInvoice);
            console.log("getProducts selectedParty" , selectedInvoice);
            this.isSalesReturnRecentVisible = false;
            this.salesReturnType = selectedInvoice.invoice;
            console.log("getProducts selectedParty invoice", this.invoiceData);
                
           


            this.spinning= true;
            axiosAdmin
            .get("sales/getInvoiceItems/"+selectedInvoice, )
            .then(response => {  console.log(response)
                // Toastr Notificaiton

                this.openProduct(response.data);

            })
            .catch(errorResponse => {
                this.spinning= false;
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
        },
        async updateItems(selectedKeys) {
            console.log('updateItems called', selectedKeys, this.invoiceData && this.invoiceData.invoiceItems);

            // close if nothing selected
            if (!selectedKeys || (Array.isArray(selectedKeys) && selectedKeys.length === 0)) {
                this.isInvoiceItemSelectModel = false;
                this.isSalesReturnRecentVisible = false;
                message.warning('Please select at least one item from the invoice');
                this.$nextTick(() => {
                    const el = document.getElementById('item_product_name_0');
                    if (el) el.focus();
                });
                return false;
            }

            // normalize to array
            const list = Array.isArray(selectedKeys) ? selectedKeys : [selectedKeys];

            if (!list.length || !this.invoiceData?.invoiceItems?.length) {
                this.isInvoiceItemSelectModel = false;
                return false;
            }

            this.spinning = true;
            this.isInvoiceItemSelectModel = false;
            this.isSalesReturnRecentVisible = false;

            let writeIdx = 0;
            let firstFilledIdx = null;
            let lastFilledIdx = null;

            for (const sel of list) {
                let inv = sel;

                if ((typeof sel === 'string' || typeof sel === 'number')) {
                    // try to resolve from cache
                    if (this.invoiceData && Array.isArray(this.invoiceData.invoiceItems)) {
                        inv = this.invoiceData.invoiceItems.find(x =>
                            String(x.product_id) === String(sel) ||
                            String(x.id) === String(sel) ||
                            String(x.invoice_number) === String(sel)
                        );
                    }
                    // if still not found and an invoice is loaded, request fresh items
                    if (!inv && this.formData.selectedInvoice) {
                        try {
                            const resp = await axiosAdmin.get(`sales/getInvoiceItems/${this.formData.selectedInvoice}`);
                            if (resp && resp.data) {
                                // server may return array directly or {invoiceItems: []}
                                const arr = Array.isArray(resp.data) ? resp.data : (resp.data.invoiceItems || []);
                                if (arr.length) {
                                    this.invoiceData = this.invoiceData || {};
                                    this.invoiceData.invoiceItems = arr;
                                }
                                inv = arr.find(x =>
                                    String(x.product_id) === String(sel) ||
                                    String(x.id) === String(sel) ||
                                    String(x.invoice_number) === String(sel)
                                );
                            }
                        } catch (err) {
                            console.warn('updateItems: failed to fetch invoice items', err);
                        }
                    }
                } else if (inv && (inv.product_name === undefined || inv.product_name === null)) {
                    // was passed object but missing name, try local cache
                    if (this.invoiceData && Array.isArray(this.invoiceData.invoiceItems)) {
                        const found = this.invoiceData.invoiceItems.find(x =>
                            Number(x.product_id) === Number(inv.product_id) ||
                            Number(x.id) === Number(inv.id)
                        );
                        if (found) inv = found;
                    }
                }

                console.log('selected item resolved to inv', inv);
                if (!inv || (!inv.product_id && !inv.id)) continue;

                while (this.formData.items[writeIdx] && this.formData.items[writeIdx].selected) {
                    writeIdx++;
                }
                if (!this.formData.items[writeIdx]) break;

                                // populate form row from selected invoice/product object (robust mapping)
                                const row = this.formData.items[writeIdx] || {};
                                // determine price
                                const priceCandidates = [inv.single_unit_price, inv.product && inv.product.sale_rate, inv.sale_rate, inv.price, inv.rate];
                                const price = priceCandidates.find(v => v !== undefined && v !== null) || 0;
                                // Always map discount_rate from API if present
                                const discountRate = (typeof inv.discount_rate !== 'undefined' && inv.discount_rate !== null)
                                    ? Number(inv.discount_rate)
                                    : (inv.product && typeof inv.product.discount_rate !== 'undefined' ? Number(inv.product.discount_rate) : 0);
                                // For max_single_unit_price, use the discount amount per quantity if discount_rate is present
                                const discountAmount = price * (discountRate / 100);
                                const quantity = (inv.quantity || inv.qty || inv.return_qty || 0);
                                const sub_amount = (price - discountAmount);
                                console.log("Calculated sub_amount", sub_amount, "from price", price, "and discountAmount", discountAmount,"discountRate",discountRate);

                                row.index = writeIdx + 1;
                                row.selected = true;
                                row.item_id = inv.product_id || inv.id || (inv.product && inv.product.id) || row.item_id;
                                row.item_name = inv.product_name || (inv.product && inv.product.name) || inv.name || row.item_name;
                                row.packing = inv.packing || (inv.product && inv.product.packing) || row.packing;
                                row.quantity = quantity;
                                row.maxquantity = inv.maxquantity || inv.quantity || inv.qty || 0;
                                row.single_unit_price = Number(price) || 0;
                                row.discount_rate = discountRate; // always set from API if present
                                row.max_single_unit_price = sub_amount; // always calculated from discount_rate
                                row.discount_type_id = inv.discount_type_id || row.discount_type_id || null;
                                row.cgst = inv.product && inv.product.cgst ? inv.product.cgst : (inv.cgst || 0);
                                row.sgst = inv.product && inv.product.sgst ? inv.product.sgst : (inv.sgst || 0);
                                row.cess = inv.product && inv.product.cess ? inv.product.cess : (inv.cess || 0);
                                row.hsnCode = inv.hsnCode || (inv.product && inv.product.hsnCode) || row.hsnCode;
                                row.amount = 0;

                                // ensure reactive assignment
                                this.$set ? this.$set(this.formData.items, writeIdx, row) : (this.formData.items[writeIdx] = row);
                                console.log(" row.cgst", row.cgst, "row.sgst", row.sgst, "row.cess", row.cess);
                                console.log('after map rows[', writeIdx, ']=', JSON.parse(JSON.stringify(this.formData.items[writeIdx])));
                                if (firstFilledIdx === null) firstFilledIdx = writeIdx;
                                lastFilledIdx = writeIdx;
                                writeIdx++;
            }

            this.$nextTick(() => {
                const setIf = (id, value, inner = false) => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    // Always use innerHTML for the tax field
                    if (id.startsWith('item_product_tax_')) {
                        el.innerHTML = value;
                    } else if (inner) {
                        el.innerHTML = value;
                    } else {
                        el.value = value;
                    }
                };

                for (let i = 0; i < this.formData.items.length; i++) {
                    const it = this.formData.items[i];
                    if (!it || !it.selected) continue;

                    setIf(`item_product_name_${i}`, it.item_name || "");
                    setIf(`item_product_packing_${i}`, it.packing || "");
                    setIf(`item_product_id_${i}`, it.item_id || "");
                    // rate per qty field
                    setIf(`items product amount${i}`, it.single_unit_price || "");
                    // discount rate field
                    setIf(`item_product_price_${i}`, it.max_single_unit_price || "");
                    setIf(`item_product_quantity_${i}`, it.quantity || "");
                      // calculate amount
                    let amount = (Number(it.max_single_unit_price) || 0) * (Number(it.quantity) || 0);
                    setIf(`item_product_amount_${i}`, amount, true);
                    setIf(`cgst_tax_${i}`, it.cgst || "");
                    setIf(`sgst_tax_${i}`, it.sgst || "");
                    let totalTaxRate = (Number(it.cgst) || 0) + (Number(it.sgst) || 0);
                    let taxAmount = (Number(it.max_single_unit_price) || 0) * totalTaxRate / 100;
                    setIf(`item_product_tax_${i}`, taxAmount, true);
                    console.log("it.cgst", it.cgst, "it.sgst", it.sgst);   
                    console.log("totalTaxRate =>", totalTaxRate);
                    console.log("items product amount single_unit_price ", it.single_unit_price);
                    console.log("items product amount max_single_unit_price ", it.max_single_unit_price);
                    console.log("taxAmount =>", taxAmount);

                }

                this.updateTotalProd(lastFilledIdx ?? 0);
                this.updateAgg();
                this.updateGoodsValue();

                this.selectedItermIndex = (firstFilledIdx !== null) ? firstFilledIdx : 0;
                const focusEl = document.getElementById(`item_product_return_qty_${this.selectedItermIndex}`);
                if (focusEl) focusEl.focus();

                this.spinning = false;
            });
        },

        focusproductEelment()
        {
            document.getElementById("item_product_quantity_0").focus();
        },


        updateCustomer(selectedParty) {
            //console.log('selectedParty',selectedParty)
            this.formData.party_customer_id = selectedParty.id;
            this.formData.party_customer_name = selectedParty.name;
            this.formData.party_customer_mobile = selectedParty.mobile_number;
            ////console.log("hello child" + this.formData)
            document.getElementById("party_customer_id").value = this.formData.party_customer_id;
            document.getElementById("form_item_customer_name").value = this.formData.party_customer_name;
            document.getElementById("form_item_mobile_number").value = this.formData.party_customer_mobile;
        },
        updateProduct(selectedParty) {
                             // Apply user calculation: max_single_unit_price = single_unit_price * (discount_rate / 100)
                             const price = this.formData.items[this.selectedItermIndex].single_unit_price;
                             const discountRate = this.formData.items[this.selectedItermIndex].discount_rate || 0;
                             this.formData.items[this.selectedItermIndex].max_single_unit_price = price * (discountRate / 100);
                             console.log("Calculated max_single_unit_price", this.formData.items[this.selectedItermIndex].max_single_unit_price);
                             console.log("price", price, "discountRate", discountRate);
            //console.log('Cgst Tax',selectedParty);
            this.formData.items[this.selectedItermIndex].item_id= selectedParty.id
                 this.formData.items[this.selectedItermIndex].item_name= selectedParty.name
                 // User will provide their own logic for max_single_unit_price and single_unit_price

                 this.formData.items[this.selectedItermIndex].quantity= 0;
                 this.formData.items[this.selectedItermIndex].maxquantity= selectedParty.quantity;
                 this.formData.items[this.selectedItermIndex].packing= selectedParty.packing;
                 this.formData.items[this.selectedItermIndex].cgst= selectedParty.cgst;
                 this.formData.items[this.selectedItermIndex].sgst= selectedParty.sgst;
                 this.formData.items[this.selectedItermIndex].cess = selectedParty.cess;
                 this.formData.items[this.selectedItermIndex].hsnCode = selectedParty.hsnCode;
                 this.formData.items[this.selectedItermIndex].discount_type_id= selectedParty.discount_type_id;
                 ////console.log(this.formData.items[this.selectedItermIndex].packing);
                 document.getElementById("item_product_name_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_name;
                 document.getElementById("item_product_packing_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].packing;
                 document.getElementById("item_product_id_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_id;
                 // User will provide their own calculation for item_product_price_X
                 document.getElementById("item_product_quantity_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].quantity;
                 document.getElementById("item_product_amount_"+this.selectedItermIndex).value =0.0
                 document.getElementById("cgst_tax_"+this.selectedItermIndex).value =this.formData.items[this.selectedItermIndex].cgst;
                 document.getElementById("sgst_tax_"+this.selectedItermIndex).value =this.formData.items[this.selectedItermIndex].sgst;
                 document.getElementById("item_product_tax_"+ this.selectedItermIndex).innerHTML=selectedParty.lgst;
                 document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                  console.log(" selectedParty.cgst", selectedParty.cgst)
                  console.log("item_product_price_", this.formatNumber(this.formData.items[this.selectedItermIndex].single_unit_price))
        },

        showProductModal(event,index) {
            this.selectedItermIndex = index;
            if (this.salesReturnType == "single" && this.formData.items[index].item_id == null) {
                const btn = document.getElementById("saveF8");
                if (btn) btn.focus();
                event.preventDefault();
                return false;
            }
            console.log('=>', index, event.key, event.keyCode, this.formData.items[index].item_id);
            if (event.keyCode == 16 || event == "shift" || event.keyCode == 255) {
                event.preventDefault();
                return false;
            }
            if (event.keyCode == 46 || event.keyCode == 8) {
                //this.deleteConfirmationPopup= true;
            }
            console.log('=>', this.salesReturnType);
            if (this.formData.party_name != "" && index > 0 && event.key == 'Enter') {
                if (!this.formData.items[index].item_id) {
                    this.isProuctsModalVisible = true;
                } else if (this.formData.items[index].item_id > 0 && event.keyCode == 13) {
                    console.log("TTT");
                    const qtyEl = document.getElementById("item_product_return_qty_" + index);
                    if (qtyEl) qtyEl.focus();
                    event.preventDefault();
                }
            } else if (this.formData.party_name != "" && index > 0 && event.key == 'Tab') {
                ////console.log("Tab Key Press",this.formData.party_customer_mobile);
                this.selectedItermIndex = index;
                if (!this.formData.bill_number || this.formData.bill_number.trim() == "") {
                    const billEl = document.getElementById("form_item_bill_number");
                    if (billEl) billEl.focus();
                    event.preventDefault();
                    return false;
                } else if (!this.formData.party_customer_mobile || this.formData.party_customer_mobile.trim() == "") {
                    const mobEl = document.getElementById("form_item_mobile_number");
                    if (mobEl) mobEl.focus();
                    event.preventDefault();
                    return false;
                } else if (!this.formData.items[index].item_id) {
                    this.isProuctsModalVisible = true;
                } else if (this.formData.items[index].item_id > 0 && event.keyCode == 13) {
                    console.log("TTT");
                    const qtyEl2 = document.getElementById("item_product_return_qty_" + index);
                    if (qtyEl2) qtyEl2.focus();
                    event.preventDefault();
                } else if (this.formData.items[index].item_id > 0 && event.keyCode == 9) {
                    console.log("SSS");
                    const nameEl = document.getElementById("item_product_name_" + index);
                    if (nameEl) nameEl.focus();
                    event.preventDefault();
                } else if (this.formData.items[index].item_id > 0 && (event.keyCode != 9 && event.keyCode != 13)) {
                    console.log("BBB");
                    this.isProuctsModalVisible = true;
                    event.preventDefault();
                } else {
                    this.saveSalesReturnEntry();
                }
            } else if (this.formData.party_name != "" && index > 0 && (event.keyCode !== 9 && event.keyCode != 119 && event.key != "F8")) {
                ////console.log("POP Pay 2");
                this.selectedItermIndex = index;
                this.isProuctsModalVisible = true;
                const psearch = document.getElementById("form_item_party_name_search");
                if (psearch) psearch.focus();
                //event.preventDefault();
                return false;
            } else if (this.formData.party_name != "" && index > 0 && (event.keyCode !== 9 && event.keyCode != 119)) {
                if (!this.formData.party_customer_mobile || this.formData.party_customer_mobile.trim() == "") {
                    const mobEl2 = document.getElementById("form_item_mobile_number");
                    if (mobEl2) mobEl2.focus();
                    event.preventDefault();
                    return false;
                } else {
                    this.saveSalesReturnEntry();
                }
            } else if (this.formData.party_name != "" && index > 0 && event.keyCode == 119) {
                console.log("F8 focus");
                this.saveSalesReturnEntry();
            }
        },
        validateMobile(event,index)
        {
            ////console.log(event.key)
            if(this.formData.party_name!=""  &&  event.key == 'Enter')
                {
                    this.selectedItermIndex =  index;
                    if(this.formData.bill_number==undefined || this.formData.bill_number=="" || this.formData.bill_number.trim()==""){ ////console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_bill_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){ ////console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_mobile_number").focus();
                    }
                    else
                    {
                       ////console.log('=> mobile',this.formData.party_customer_mobile);
                    }
                    return false;

                }
        },


    onclose()
    {
    this.$router.push({ name: 'admin.dashboard.index' });
   },

   handleClose() {
            this.isModalVisible = false;
            this.isProuctsModalVisible = false;
            if(this.selectedItermIndex!=undefined){
                const prodName = document.getElementById("item_product_name_"+this.selectedItermIndex).value;

                if(prodName==undefined || prodName=="" || prodName.trim()=="")
                {

                    document.getElementById("item_product_name_"+this.selectedItermIndex).focus();

                }
                else{
                    console.log("Close 2w",this.selectedItermIndex,prodName);
                    const cindex =this.selectedItermIndex;
                    setTimeout(function(){
                        document.getElementById("item_product_quantity_"+cindex).focus();
                    },500)

                }
                this.updateTotalProd();
            }
            else
            {
                document.getElementById("item_product_name_0").focus();
            }
        },
        focusSearchInput() {
            this.$refs.searchInput.focus(); // Focus on the input field
        },
        handleSuccess(xid) {
            this.isProuctsModalVisible = false;
            this.isModalVisible = false;
            ////console.log('Success:', xid);
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
        handleProductModal(){ //console.log("Product closed");
        this.isProuctsModalVisible  =false;
        //this.focusProductList();
        },
        focusProductList()
        {
            let cf =0;

            for (let i = 0; i < this.formData.items.length; i++) {

                if(cf== 0 && (this.formData.items[i].item_id==undefined || this.formData.items[i].item_id==null || this.formData.items[i].item_id==""))
                {    cf=i;
                    //console.log("Find Post","item_product_name_"+i);

                break;
                }
            }

            //console.log("cf="+cf);
            if(cf==0)
            {
                setTimeout(function(){

                    document.getElementById("shortcut").focus();

                },500)

            }
            else
            {
                setTimeout(function(){
                    document.getElementById("item_product_name_"+cf).focus();
                },500)
            }
        },

        handlenumberModel(){
        this.isNumberVisible=false;
        //this.focusProductList();
        },
        focusinputvalue(event)
        {
            event.target.select()

        },


getTotalAmount(type) {
    let total = 0;

    this.formData.items.forEach(item => {

        if (!item || !item.item_id) return;

        if (type === 'total') {
            total += Number(item.return_qty || 0) * Number(item.single_unit_price || 0);
        }

        if (type === 'sgst') {
            total += Number(item.sgst_amount || 0);
        }

        if (type === 'cgst') {
            total += Number(item.cgst_amount || 0);
        }

        if (type === 'cess') {
            total += Number(item.cess_amount || 0);
        }

        if (type === 'discount') {
            total += Number(item.discount_value || 0);
        }

    });

    return total;
},

        getQuantity(index,event) {
            // Update additems table values instantly at the start
            let totalCgst = 0;
                        // Update additems table and summary fields instantly
                        // Remove duplicate declaration
                        let totalSgst = 0;
                        let totalIgst = 0;
                        let totalCess = 0;
                        let grandTotal = 0;
                        let totalGoodsValue = 0;
                        let totalTax = 0;
                        let totalDiscount = 0;
                        let totalItems = 0;
                        // totalCgst already declared above, remove duplicate
                        for (let i = 0; i < this.formData.items.length; i++) {
                            const item = this.formData.items[i];
                            if (!item || !item.item_id) continue;
                            totalItems++;
                            // Calculate discount value
                            let price = Number(item.single_unit_price) || 0;
                            let maxprice = Number(item.max_single_unit_price) || 0;
                            let quantity = Number(item.return_qty) || 0;
                            let returnqty = Number(item.return_qty) || 0;
                            let discountRate = Number(item.discount_rate) || 0;
                            let discountType = Number(item.discount_type_id) || 0;
                            // Map cgst and sgst from the item (API response)
                            let cgst = Number(item.cgst) || 0;
                            let sgst = Number(item.sgst) || 0;
                            let cess = Number(item.cess) || 0;
                            let discountValue = 0;
                            if (discountType === 2) {
                                discountValue = (discountRate / 100) * (price * quantity);
                            } else if (discountType === 3) {
                                discountValue = discountRate;
                            } else if (discountType === 4) {
                                discountValue = discountRate * quantity;
                            }
                            let taxableAmount = (maxprice * quantity);
                            if (taxableAmount < 0) taxableAmount = 0;
                            let sgstAmount = (sgst / 100) * taxableAmount;
                            let cgstAmount = (cgst / 100) * taxableAmount;
                            let cessAmount = (cess / 100) * taxableAmount;
                            let totalAmount = taxableAmount + sgstAmount + cgstAmount;
                            item.discount_value = discountValue;
                            item.sgst_amount = sgstAmount;
                            item.cgst_amount = cgstAmount;
                            item.cess_amount = cessAmount;
                            item.amount = totalAmount;
                            totalCgst += cgstAmount;
                            totalSgst += sgstAmount;
                            totalCess += cessAmount;
                            grandTotal += totalAmount;
                            totalGoodsValue += totalAmount;
                            totalTax += (taxableAmount / 100) * (cgst + sgst);
                            console.log("cgst", cgst, "sgst", sgst, "taxableAmount", taxableAmount,"totalTax =>", totalTax);
                            totalDiscount += discountValue;
                            // Vue reactivity will update the amount input automatically
                            console.log("totalAmount",totalAmount,"totalCgst", totalCgst, "totalSgst", totalSgst, "totalCess", totalCess);
                        }
                        totalIgst = totalCgst + totalSgst;
                        // Update additems table values
                        const igst0 = document.getElementById("igst_amount_0");
                            if (igst0) {
                                igst0.value = this.formatCurrency(totalSgst);
                            }
                        const igst1 = document.getElementById("igst_amount_1");
                            if (igst1) {
                                igst1.value = this.formatCurrency(totalCgst);
                            }
                        const igst2 = document.getElementById("igst_amount_2");
                            if (igst2) {
                                igst2.value = this.formatCurrency(totalIgst);
                            }
                        const igst3 = document.getElementById("igst_amount_3");
                            if (igst3) {
                                igst3.value = this.formatCurrency(totalCess);
                            }
                            // Add igst_amount_4 as sum of igst_amount_0+1+2+3
                            const igst4 = document.getElementById("igst_amount_4");
                            if (igst4) {
                                let sum = 0;
                                // If both SGST and CGST are present (non-zero), use only those and CESS
                                if (totalSgst > 0 && totalCgst > 0) {
                                    sum += totalSgst;
                                    sum += totalCgst;
                                    sum += totalCess;
                                } else {
                                    // Otherwise, use IGST and CESS
                                    sum += totalIgst;
                                    sum += totalCess;
                                }
                                igst4.value = this.formatCurrency(sum);
                            }
                        // Update summary fields instantly
                        const grandTotalSpan = document.getElementById("grand_total");
                        if (grandTotalSpan) grandTotalSpan.innerHTML = this.formatCurrency(grandTotal + totalCess);
                        const goodsValueInput = document.getElementById("total_goods_value");
                        if (goodsValueInput) goodsValueInput.value = this.formatCurrency(totalGoodsValue);
                        const discountTextSpan = document.getElementById("total_discount_text");
                        if (discountTextSpan) discountTextSpan.innerHTML = this.formatCurrency(totalTax);
                        const discountInfoAmount = document.getElementById("discount_info_amount");
                        if (discountInfoAmount) discountInfoAmount.innerHTML = this.formatCurrency(totalItems);
                        const cgstTotalText = document.getElementById("cgst_total_text");
                        if (cgstTotalText) cgstTotalText.innerHTML = this.formatCurrency(totalCgst);
                        const sgstTotalText = document.getElementById("sgst_total_text");
                        if (sgstTotalText) sgstTotalText.innerHTML = this.formatCurrency(totalSgst);
                        // Force Vue reactivity for totals
                        this.formData.items = [...this.formData.items];
                        // ...existing code...
            let maxPrice = Number(this.formData.items[index].max_single_unit_price) || 0;
            let quantity = Number(this.formData.items[index].return_qty) || 0;
            let cgst = Number(this.formData.items[index].cgst) || 0;
            let sgst = Number(this.formData.items[index].sgst) || 0;
            // User formula for totalamount
            let subtotal = maxPrice * quantity;
            let tax = (subtotal / 100) * (cgst + sgst);
            let totalAmount = subtotal + tax;
            this.formData.items[index].amount = totalAmount;
            console.log("getQuantity - index", index, "quantity", quantity, "maxPrice", maxPrice, "cgst", cgst, "sgst", sgst, "subtotal", subtotal, "tax", tax, "totalAmount", totalAmount);

            // Update DOM instantly for UI
            const amountInput = document.getElementById("item_product_amount_" + index + "_" + (this.formData.items[index].unique || index));
            if (amountInput) amountInput.value = this.formatCurrency(totalAmount);

            // Update tax display using requested formula
            let totalTaxRate = cgst + sgst;
            let totaltaxamt = (quantity * maxPrice);
            let taxAmount = (totaltaxamt / 100) * totalTaxRate;
            const taxStr = totalTaxRate + ",(" + taxAmount.toFixed(2) + ")";
            const taxEl = document.getElementById("item_product_tax_" + index);
            if (taxEl) taxEl.innerHTML = taxStr;

            // Update totals
            this.updateAgg();
            this.updateTotalProd(index);
            this.showGstDetails(index);
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

        validateReturnQty(index, event) {
        const returnQty = Number(event.target.value) || 0;
        const quantity = Number(this.formData.items[index].quantity) || 0;

        if (returnQty > quantity) {
            notification.warning({
                placement: "bottomRight",
                message: "Return Quantity should be less than or equal to Previous Quantity!",
            });
            // Immediately reset to quantity
            this.formData.items[index].return_qty = quantity;
            this.formData.items[index].return_qty = quantity;
            this.$nextTick(() => {
                document.getElementById(`item_product_return_qty_${index}`).focus();
            });
        }
    },

        checkMaxQuantity(index, event) {
        const quantity = Number(this.formData.items[index].quantity) || 0;
        const returnQty = Number(this.formData.items[index].return_qty) || 0;

        if (returnQty > quantity) {
            notification.warning({
                placement: "bottomRight",
                message: "Return Quantity should be less than or equal to Previous Quantity!",
            });
            // Reset return_qty to quantity if it exceeds
            this.formData.items[index].return_qty = quantity;
            document.getElementById(`item_product_return_qty_${index}`).value = quantity;
            this.$nextTick(() => {
                document.getElementById(`item_product_return_qty_${index}`).focus();
            });
            event.preventDefault();
            return false;
        }

        if ((event.key === "Tab" || event.key === "Enter") && returnQty <= 0) {
            notification.warning({
                placement: "bottomRight",
                message: "Return Quantity must be greater than 0!",
            });
            document.getElementById(`item_product_return_qty_${this.selectedItermIndex}`).focus();
            event.preventDefault();
            return false;
        } else if (event.key === "Tab" || event.key === "Enter") {
            this.selectedItermIndex = index; // Ensure correct index is set
            document.getElementById(`item_product_return_reason_code_${this.selectedItermIndex}`).focus();
            event.preventDefault();
            return false;
        }
    },
        checkSingleItemPrice(index,event)
        {

           if(Number(this.formData.items[index].single_unit_price)>Number(this.formData.items[index].max_single_unit_price))
            {}
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                //console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if(( event.key=="Enter") && (Number(event.target.value)>0))
            {
                console.log("222",document.getElementById("item_product_disc_type_"+this.selectedItermIndex));

               document.getElementById("item_product_disc_type_"+index).focus();
               event.preventDefault();
                return false;
            }
            else if(( event.key=="F8") && (Number(event.target.value)>0))
            {
               console.log(index,"F8",this.selectedItermIndex);
              this.saveSalesReturnEntry();
               event.preventDefault();
                return false;
            }
            else if(event.key==undefined)
            {
                document.getElementById("item_product_price_"+index).focus();
            }
        },

        checkDisc(index,event)
        {
            ////console.log('checkDisc',this.formData.items[index].discount_rate)
            if(( event.key=="Enter") )
            {

               document.getElementById("item_product_name_"+(index+1)).focus();
               event.preventDefault();
                return false;
            }
            else if(( event.key=="F8") )
            {

                this.saveSalesReturnEntry();
               event.preventDefault();
                return false;
            }
        },

        success(response)
        {
            this.formData.selectedInvoice = this.formData.bill_number;
            //console.log('rely=>>',response.message);
            this.loading= false;
            notification.success({
                        placement:  "bottomRight",
                        message: "Credit Note Created Successfully !    ",
                        description: ""
                    });
            this.isPrintModalVisible= true;
        },

        validateSales()
        {
            if(this.formData.party_id==undefined || this.formData.party_id=="" || this.formData.party_id==""){ ////console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_party_name").focus();
                        return false;
            }
            else if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){ ////console.log('+>',this.formData.bill_number)
                document.getElementById("form_item_mobile_number").focus();
                        return false;
            }
            else
            {
                let itemAvailable =false;
                this.formData.items.forEach((element)=>{
                    if(itemAvailable == false && element.item_id!=undefined && element.item_id!=""){
                        itemAvailable = true;
                    }
                })
                //console.log('itemAvailable',itemAvailable)
                if(itemAvailable==false){
                    notification.warning({
                        placement:  "bottomRight",
                        message: "Please add one item !",
                        //description: configObject.successMessage
                    });
                    document.getElementById("item_product_name_0").focus();
                    return false;
                }
                return true;
            }

        }, 
        saveSalesReturnEntry(event)
        {
            console.log('submit',event);
            if(this.validateSales()){
                this.spinning= true;
                
                // Build clean payload - filter out empty items and unnecessary fields
                // Calculate correct values for payload
                const total_items = this.formData.items.filter(item => item.item_id && item.return_qty).length;
                const total_quantity = this.formData.items.reduce((sum, item) => sum + (Number(item.return_qty) || 0), 0);
                const subtotal = this.formData.items.reduce((sum, item) => sum + (Number(item.amount) || 0), 0);
                const total_discount = this.formData.items.reduce((sum, item) => sum + (Number(item.discount_value) || 0), 0);
                const tax_amount = this.formData.items.reduce((sum, item) => sum + (Number(item.cgst_amount || 0) + Number(item.sgst_amount || 0) + Number(item.cess_amount || 0)), 0);
                const totalCess = this.formData.items.reduce((sum, item) => sum + (Number(item.cess_amount) || 0), 0);
                const total = subtotal + totalCess;

                const payload = {
                    order_date: this.formData.order_date,
                    party_id: this.formData.party_id,
                    party_customer_id: this.formData.party_customer_id,
                    party_customer_mobile: this.formData.party_customer_mobile,
                    bill_number: this.formData.bill_number,
                    invoice_number: this.formData.invoice_number,
                    order_status: this.formData.order_status,
                    total_items,
                    total_quantity,
                    subtotal,
                    discount: total_discount,
                    tax_amount,
                    total,
                    selectedInvoice: this.formData.selectedInvoice,
                    items: this.formData.items
                        .filter(item => item.item_id && item.return_qty) // Only items with product and return quantity
                        .map(item => ({
                            item_id: item.item_id,
                            return_qty: item.return_qty,
                            return_reason_code : item.return_reason_code,
                            quantity: item.quantity,
                            invoice_number: this.formData.invoice_number,
                            mrp: item.mrp,
                            single_unit_price: item.single_unit_price,
                            discount_type_id: item.discount_type_id,
                            discount_rate: item.discount_rate,
                            cgst: item.cgst,
                            sgst: item.sgst,
                            cess: item.cess,
                            igst: item.igst,
                            amount: item.amount
                        }))
                };
                
                console.log('Sending payload:', JSON.stringify(payload, null, 2));
                
                axiosAdmin
                    .post("sales/salesReturn", payload)
                    .then(response => {  
                        console.log('Success response:', response);
                        this.spinning= false;
                        this.success(response);
                    })
                    .catch(errorResponse => {
                        this.spinning= false;
                        console.error('Error response:', errorResponse.response?.data || errorResponse);
                        var err = errorResponse.data || errorResponse.response?.data;
                        const errorCode = errorResponse.status || errorResponse.response?.status;
                        var errorRules = {};

                        if (errorCode == 422) {
                            if (err.error && typeof err.error.details != "undefined") {
                                var keys = Object.keys(err.error.details);
                                for (var i = 0; i < keys.length; i++) {
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
                        } else if (errorResponse.response?.data?.error?.message) {
                            message.error(errorResponse.response.data.error.message);
                        } else {
                            message.error('Failed to save sales return. Please check the console for details.');
                        }
                    });
            }
            else
            {
                document.getElementById("shortcut").focus();
                return false;
            }
        },
        checkQuantity(index,event)
        {
            //console.log(index,'price focus',this.formData.items[index].quantity);
            const quantity = this.formData.items[this.selectedItermIndex].quantity;
            if(quantity==undefined || quantity=="" || quantity.trim()=="")
            {
                document.getElementById("item_product_quantity_"+index).focus();
                return false;
            }
        },
        updateAgg()
        {
            let count = 0;
            let quantity = 0;
            this.formData.items.forEach((elem) => {
                if(elem.quantity != undefined && elem.quantity != null && Number(elem.quantity) > 0)
                {
                    quantity = quantity + Number(elem.quantity);
                }
            })
            
            // Add null check before setting value
            const totalQtyElement = document.getElementById("total_quantity_details");
            if (totalQtyElement) {
                totalQtyElement.value = quantity.toFixed(2);
            }
        },
        updateTotalProd(index){
            let count =0;
            this.formData.items.forEach((elem)=>{
                if(elem.item_id!=undefined && elem.item_id!=null && elem.item_id>0)
                {
                    count =count+1;
                }
                //console.log('count=>',count);
            })
            document.getElementById("total_prod_count").value="Total : "+count+" Product";
            this.showGstDetails(index);
        },
        showGstDetails(index)
        {
            if(index!=undefined &&  index!=null )
            {
                //console.log('<>',this.formData.items[index]);
                const amount =   Number(this.formData.items[index].amount) ;
                if(amount>0){
                const cgst = Number(this.formData.items[index].cgst) ;
                const sgst = Number(this.formData.items[index].sgst) ;
                document.getElementById('cgst_total_text').innerHTML= ((cgst/100)*amount).toFixed(2);
                document.getElementById('sgst_total_text').innerHTML= ((sgst/100)*amount).toFixed(2);
                }
                else
                {
                    document.getElementById('cgst_total_text').innerHTML= document.getElementById('igst_amount_0').value;
                    document.getElementById('sgst_total_text').innerHTML= document.getElementById('igst_amount_1').value;
                }

            }
            else
            {
                document.getElementById('cgst_total_text').innerHTML= document.getElementById('igst_amount_0').value;
                document.getElementById('sgst_total_text').innerHTML= document.getElementById('igst_amount_1').value;
            }

        },
        updateGoodsValue()
        {
            let count =0;
            let quantity =0;
            this.formData.items.forEach((elem)=>{ //console.log('amount=>',Number(elem.amount))
                if(elem.amount!=undefined && elem.amount!=null && Number(elem.amount)>0)
                {
                    //count =count+1;
                    quantity = quantity+Number(elem.amount);
                }
            })
           // document.getElementById("total_prod_count").value="Total : "+count+" Product";
            document.getElementById("total_goods_value").value=this.formatCurrency(quantity.toFixed(2));
        },
        gotoNext(index,event)
        {
            console.log(index,event.keyCode,Number(this.formData.items[index].quantity))
            if(event.keyCode==13 && Number(this.formData.items[index].quantity)>0)
            {
                console.log("Enter key pressed gotoNext")
                document.getElementById('item_product_price_'+index).focus();
                event.preventDefault();
                return false;

            }
            if(event.keyCode==119 && Number(this.formData.items[index].quantity)>0)
            {
                this.saveSalesReturnEntry();
            }
        },

        gotoReason(index, event) {
        const returnQty = Number(this.formData.items[index].return_qty) || 0;
        if (event.key === "Enter" && returnQty > 0) {
            document.getElementById(`item_product_return_reason_code_${index}`).focus();
            event.preventDefault();
            return false;
        } 
        //else if (event.key === "Enter" && returnQty <= 0) {
           /// notification.warning({
               /// placement: "bottomRight",
                ///message: "Please Enter the Return Quantity!",
            ///});
            ///document.getElementById(`item_product_return_qty_${index}`).focus();
            //event.preventDefault();
            //return false;
        //}
    },



        // gotoRate(index, event) {

        //     console.log(event.keyCode,"return code ",this.formData.items[index].return_reason_code);
        //     if (event.keyCode === undefined && Number(this.formData.items[index].return_reason_code) > 0) {
        //         console.log("Enter key pressed gotoRate");
        //         if (this.formData.items[(index + 1)].item_id > 0) {
        //             document.getElementById('item_product_return_qty_' + (index + 1)).focus();
        //         }
        //         else
        //         {
        //             document.getElementById("saveF8").focus();
        //         }
        //          event.preventDefault();
        //          return false;
        //     }
        //     else if(event.keyCode === 13 && Number(this.formData.items[index].return_reason_code) > 0) {
        //         console.log("Enter key pressed gotoRate 2");
        //         document.getElementById('item_product_return_qty_' + index).focus();
        //          event.preventDefault();
        //         return false;
        //     }

        //    else if (event.keyCode === 119 && Number(this.formData.items[index].return_reason_code) > 0) {
        //         console.log("F8 key pressed  gotoRate 3");
        //         this.saveSalesReturnEntry();
        //         return false;
        //     }

        //   /*  const nextField = document.getElementById('item_product_name_' + (index + 1));
        //     if (nextField) {
        //         nextField.focus();
        //     }*/
        // },


        gotoRate(index, event) {
    console.log(event.keyCode, "return code ", this.formData.items[index].return_reason_code);
    if (event.keyCode === 13 && Number(this.formData.items[index].return_reason_code) > 0) {
        console.log("Enter key pressed gotoRate - Moving to Save");
         document.getElementById("saveF8").focus();
        event.preventDefault();
        return false;
    } else if (event.keyCode === 119 && Number(this.formData.items[index].return_reason_code) > 0) {
        console.log("F8 key pressed gotoRate - Saving directly");
        this.saveSalesReturnEntry();
        event.preventDefault();
        return false;
    }
    // Handle undefined keyCode (e.g., change event from dropdown)
    else if (event.keyCode === undefined && Number(this.formData.items[index].return_reason_code) > 0) {
        console.log("Return reason selected, checking next step");
        if (this.formData.items[index + 1]?.item_id > 0) {
            document.getElementById("item_product_return_qty_" + (index + 1)).focus();
        } else {
            document.getElementById("saveF8").focus();
        }
        event.preventDefault();
        return false;
    }
},


handleSaveKeydown(event) {
    if (event.keyCode === 13) { // Enter key
        console.log("Enter pressed on Save button");
        this.saveSalesReturnEntry(event);
        event.preventDefault();
    }
},

        onCheckAllChange(index)
        {
            console.log("checked",index,this.formData.items[index].selected);
            this.formData.items[index].selected != this.formData.items[index].selected;
        },
        resetFormData()
        {
            console.log("formdata reset");
            this.formData=({
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
                salesReturnType:null,
                selectedInvoice:localStorage.getItem("selectedInvoice"),
                items:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null},
                    {index:2,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:3,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:4,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:5,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:6,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:7,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:8,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:9,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                    {index:10,item_id:null,item_name:null,unit_id:null,quantity:'',mrp:null,single_unit_price:null,discount_type_id:0,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:"",packing:null,invoice:null,invoice_date:null,return_qty:null,return_reason_code:null,selected:false,cgst:null,sgst:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,},
                ],


                total_discount:0,
                invoice_value:0,
                index: 0,

            total_discount: 0,
            invoice_value: 0,
            //selectedPartyIds: [] ,
            index: 0,
        });
        document.getElementById('total_discount_text').innerHTML    =   "0.0";
        document.getElementById('cgst_total_text').innerHTML        =   "";
        document.getElementById('sgst_total_text').innerHTML        =    "";
        document.getElementById('igst_amount_0').value              =   0
        document.getElementById('igst_amount_1').value              =   0
        document.getElementById('grand_total').innerHTML              =   "0";
        }
    },



    computed
    : {
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

select {
	appearance: none;
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
  .ant-notification-notice{background-color: rgb(233, 236, 141) !important;}
  .ant-notification-notice-message > span{ color:#fff !important}
  .page-content-sub-header{padding: 0px !important;}
  .ant-card-body{padding-top:10px !important}
  .ant-form-item .ant-form-item-label >label{font-size:12px !important}
  .ant-form-vertical .ant-form-item-label{padding: 0px !important;}
  .ant-input{font-size:13px !important; line-height: 1.2 !important;}
  td > .ant-input{border: none !important;
  border-radius: 0px !important;
  }
  .responsive-table th, .responsive-table td {
    padding: 0px !important;
  }
  .invoicevalues{
    background-color: #92b4b5 !important;
  }
</style>
