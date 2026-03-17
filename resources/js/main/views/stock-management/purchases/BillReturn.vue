<template>
    <AdminPageHeader>
        <template #header>
            <!--- @closed="handleClose"-->
            <ProductModal v-if="isProuctsModalVisible" :visible="isProuctsModalVisible" :formData="formData" :url="url"
                :successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess"  @closed="handleProductModal"
                 v-on:productclose-method="handleClose"  v-on:child-method="updateProduct" />
           <!-- payments modal-->
             <PaymentsModal v-if="isPaymentsModalVisible"  :visible="isPaymentsModalVisible" :formData="formData" :url="url":successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess" @closed="handleClosePayments" @closedbyclose ="handleClosePaymentsByClose"  v-on:child-method="updatePayment" :bill-value="formData.total" :bill-number="formData.bill_number"  :bill-items="formData"
               />


             <!-- end payments modal-->

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
            :bills-items="formData"
        />

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
                        name: `admin.stock.purchases.Billindex`,
                    }">
                        {{ $t(`Bill`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`Create`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>
    <div id="salescreatevue">
    <a-spin :spinning="spinning">
    <admin-page-table-content>

<!--      @focus="handlePartyNameFocus"
      @keydown="handlePartyNameKeydown" -->

        <a-card class="page-content-container mt-5 mb-5">
            <a-form layout="vertical">
               <a-row :gutter="16">
					<a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('stock.party_name')" name="party_name" ref="input"
                            :help="rules.party_name ? rules.party_name.message : null"
                            :validateStatus="rules.party_name ? 'error' : null" class="required">
                            <a-input v-model:value="formData.party_name" autocomplete="off"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.party_name')])"

                                @focus="handleFocus($event)" @blur="handleBlur" @keydown="customerhandleKeydown($event)"/>
                                <a-input hidden v-model="formData.party_id" id="party_id" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur="" />
                                <a-input hidden v-model="formData.tax_amount" id="tax_amount" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur=""/>
                                <a-input hidden v-model="formData.discount" id="discount" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur=""/>
                                <a-input hidden v-model="formData.subtotal" id="subtotal" :placeholder="$t('common.placeholder_default_text', [$t('stock.party_')])"@blur=""/>
                                <a-input hidden v-model="formData.total" id="total"/>
                                <a-input hidden v-model="formData.due_amount" id="due_amount"/>
                                <a-input hidden v-model="formData.order_status" id="order_status"/>
                                <a-input hidden v-model="formData.total_items" id="total_items"/>
                                <a-input hidden v-model="formData.total_quantity" id="total_quantity"/>
                                <a-input hidden v-model="formData.total_free" id="total_free"/>
                                 <a-input hidden v-model="formData.party_state" id="party_state"/>

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
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('stock.entry_date')" name="entry_date"
                            :help="rules.order_date ? rules.order_date.message : null"
                            :validateStatus="rules.order_date ? 'error' : null"><input type="date"
                            v-model="formData.order_date" class="ant-input css-dev-only-do-not-override-wosfq4"
                            id="order_date" ref="DateInput" required @keydown="handleKeydownDatePicker"
                            @focus="changeColorOnFocus('order_date')" @blur="resetColorOnBlur('order_date')"
                            @wheel.prevent="adjustDate($event)"
                            />
                        </a-form-item>

                      </a-col>
                </a-row>    
                <!-- sales modal number -->
                <SalesNumberModel v-if="isNumberVisible" :visible="isNumberVisible" :form-party="formData.party_id" :url="url"
                    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage" @closed="handlenumberModel"
                    @addEditSuccess="handleSuccess"  v-on:mobile-method="handleSalesNumber" v-on:cutomer-method="updateCustomer"  v-on:salenumberclose-method="focusEelmentss" />
                <!-- sales modal number -->

                <!-- Shipping Address modal-->
                <ShippingAdressModel v-if="isAddressVisible" :visible="isAddressVisible" :form-party="formData.party_id" :url="url"
                :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage" @closed="handleaddressModel"
                @addEditSuccess="handleSuccess"  v-on:address-method="handleAddress" v-on:shipping-method="updateShipping"  />

                 <!-- end Shipping Address modal-->
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('stock.invoice_no')"
                            name="bill_number"
                            class="required"
                        >
                            <input
                            v-model="formData.bill_number"
                            id="form_item_bill_number"
                            tabindex="-1"
                            @keyup="addressEvent($event)"
                            @input="formData.bill_number = $event.target.value"
                            class="ant-input css-dev-only-do-not-override-wosfq4"
                            autocomplete="off"
                            />
                        </a-form-item>
                 </a-col>
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
                   <a-form-item :label="$t('stock.invoice_date')" name="Invoice date"
                        :help="rules.invoice_date ? rules.invoice_date.message : null"
                        :validateStatus="rules.invoice_date ? 'error' : null">
                            <input type="date" v-model="formData.invoice_date" class="ant-input css-dev-only-do-not-override-wosfq4"
                            id="invoice_date" ref="DateInputt" required @keydown="handleKeydownDatePickers"
                            @wheel.prevent="adjustDate($event)"
                            />
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
                                        <tr  v-for="(prod, index) in formData.items" :key="prod.unique" :id="prod.unique">
                                            <td style="width:20%">
                                                <input autocomplete="off"

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
                                             <td style="width:8%">
                                                
                                                <input autocomplete="off" tabindex="-1"
                                                :id="`item_product_packing_${index}`"
                                                v-model="formData.items[index].packing"
                                                 style="color:black;font-weight:bolder;"
                                                readonly class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >

                                            </td>
                                            <td style="width:6%">
                                                <input autocomplete="off" 
                                                v-model="formData.items[index].single_unit_price" @focus="focusinputvalue($event)"
                                                :ref="`input-${index}`" @input="getQuantity(index,$event)"
                                                :id="`item_product_price_${index}`"
                                                @keyup="checkSingleItemPrice(index,$event)"

                                                name="single_unit_price" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                 style="color:black;font-weight:bolder;text-align-last:right;"

                                                >
                                            </td>

                                            <td style="width:5%">
                                                <input autocomplete="off"

                                                :id="`item_product_quantity_${index}`"
                                                v-model="formData.items[index].quantity"  @keydown="gotoNext(index,$event)"
                                                @input="getQuantity(index,$event)" @focus="focusinputvalue($event)"
                                                name="quantity[]" @blur="updateAgg(),checkMaxQuantity(index,$event)"
                                                style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"

                                                @keypress="onlyForCurrency"
                                                >
                                            </td>
                                            <td style="width:4%">
                                                <input autocomplete="off"

                                                :id="`item_product_freeQty_${index}`"
                                                v-model="formData.items[index].freeQty"  @keydown="gotoNext(index,$event)"
                                                @input="getQuantity(index,$event)" @focus="focusinputvalue($event)"
                                                name="freeQty[]" @blur="updateAgg(),checkMaxQuantity(index,$event)"
                                                style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"

                                                @keypress="onlyForCurrency"
                                                >
                                            </td>
                                            <td style="width:5%">
                                                <input autocomplete="off"
                                                  readonly  
                                                :id="`item_product_remQty_${index}`"
                                                v-model="formData.items[index].remQty"  
                                                name="remQty[]" @blur="updateAgg(),checkMaxQuantity(index,$event)"
                                                style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4" >
                                            </td>
                                            <td style="width:8%">
                                                <input autocomplete="off"
                                                  readonly  
                                                :id="`item_product_withoutDisc_${index}`"
                                                v-model="formData.items[index].withoutDisc"  
                                                name="withoutDisc[]"
                                                style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4">
                                            </td>

                                           
                                            
                                            <td style="width:8%">

                                                <select class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering" v-model="formData.items[index].discount_type_id" :id="`item_product_disc_type_${index}`"placeholder="Discount" :allowClear="false" optionFilterProp="title" show-search @change="updateDiscount(index,$event)">
                                                    <option v-for="tax in discountTypes" :key="tax.id" :value="tax.id" :title="tax.discount_type">
                                                        {{ tax.discount_type }}
                                                    </option>
                                                </select>

                                            </td>

                                            <td style="width:5%">
                                                <input autocomplete="off"
                                                :id="`item_product_disc_${index}`"
                                                @input="getQuantity(index,$event)"
                                                @keyup="checkDisc(index,$event)"
                                                @focus="focusinputvalue($event)"
                                                v-model="formData.items[index].discount_rate"
                                                class="ant-input css-dev-only-do-not-override-wosfq4"

                                                name="party_name" style="color:black;font-weight:bolder;text-align-last:right;"
                                                ></input>
                                                <a-input hidden :id="`item_discount_total_${index}`"/>
                                            </td>
                                            <td style="width:8%">
                                                <input autocomplete="off" readonly
                                                :id="`item_product_disc_value_${index}`"
                                                  class="ant-input css-dev-only-do-not-override-wosfq4"
                                                 style="color:black;font-weight:bolder;text-align-last:right;"
                                                ></input>
                                                <a-input hidden :id="`item_discount_total_${index}`"/>
                                            </td>
                                            <td style="text-align:right;width:8%">
                                               <b style="font-size:11px;" :id="`item_product_withDisc_${index}`"></b>&nbsp;&nbsp;
                                            </td>
                                            <td style="text-align:right;width:7%">
                                               <b style="font-size:11px;" :id="`item_product_tax_${index}`"></b>&nbsp;&nbsp;
                                               
                                            </td>

                                            <td style="width:12%">
                                                <input
                                                disabled=true :id="`item_product_amount_${index}`"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                        </tr>


                                        <!--- new row-->
                                        <tr v-for="(header, index) in formData.invoiceitems" :key="index" class="invoicevalues">
                                            <td style="width:20%">
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

                                            <td style="width:6%">
                                                <input  
                                                value="0.00"
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>


                                            <td style="width:5%">
                                                <input id="total_quantity_details"
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>

                                            </td>
                                            <td style="width:4%">
                                                <input id="total_free_quantity_details"

                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>

                                            </td>
                                            <td style="width:5%;text-align:right">
                                               <input id="total_remQty_quantity_details"

                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>

                                            </td>

                                            <td style="width:8%;text-align:right">
                                               
                                               <input 

                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                             <td style="width:8%;text-align:right">
                                               
                                                
                                            </td>
                                             <td style="width:5%;text-align:right">
                                               
                                                
                                            </td>
                                             <td style="width:8%;text-align:right">
                                               <input id="total_disc_amount"

                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                                
                                            </td>
                                             <td style="width:8%;text-align:right">
                                               <input id="total_with_disc_details"

                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                               
                                            </td>
                                             <td style="width:7%;text-align:right">
                                               
                                                <b><span id="total_discount_text1">0.00</span></b>&nbsp;
                                            </td>

                                            <td style="width:12%">
                                                <input id="total_goods_value"
                                                disabled=true value="0.00"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                        </tr>
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
                                        <tr style="width:100%" v-for="(item, index) in additems" :key="index" :id="item.name" :class="{ highlight: index === selectedIndex }">
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
                    <a-col :xs="24" :sm="24" :md="6" :lg="6"></a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-button type="button" id="saveF8" class="backgrounds" :loading="loading" @click="saveSalesEntry($event)" block>
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

                    <a-col :xs="24" :sm="24" :md="3" :lg="3"  v-if="selectedInvoice === 'null'">
                            <a-button  class="draft"  block @click="saveSalesEntryDraft()">
                                Save As Draft
                              </a-button>
                        </a-col>



                           <!-- E-Invoice Button (Visible only in Edit Mode) -->

                        <a-col :xs="24" :sm="24" :md="3" :lg="3" v-if="selectedInvoice !== 'null' && selectedInvoice !== null">
                            <a-button class="einvoice" block @click="openEInvoiceModal()">
                                E-Invoice
                            </a-button>
                            </a-col>

                            <!-- E-Way Bill Button (Visible only in Edit Mode) -->
                            <a-col :xs="24" :sm="24" :md="3" :lg="3" v-if="selectedInvoice !== 'null' && selectedInvoice !== null">
                            <a-button class="einvoice" block @click="openEwayBillModal()">
                                E-Way Bill
                            </a-button>
                            </a-col>

                        <!-- end  -->

                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="savecolse" @click="showconfirm()"   block>
                                <span class="shortcut">
                                    <code>Esc</code>
                                  </span>
                                <span class="savebutton">{{ $t("common.close") }}  <svg height="10px" width="10px" viewBox="0 0 611.96 611.96"><path d="M378.71,306,596.9,87.79a51.43,51.43,0,0,0-72.73-72.73L306,233.25,87.79,15.06A51.43,51.43,0,0,0,15.06,87.79L233.25,306,15.06,524.17a51.43,51.43,0,1,0,72.73,72.73L306,378.71,524.17,596.89a51.43,51.43,0,1,0,72.73-72.73Z"></path></svg>
                                </span>
                              </a-button>
                    </a-col>
                </a-row>
            </a-form>
        </a-card>
    </admin-page-table-content>
    </a-spin>
    </div>
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

                    <EInvoice
                v-if="isEInvoiceModalVisible"
                :visible="isEInvoiceModalVisible"
                :formData="formData"
                :url="url"
                :addEditType="addEditType"
                :pageTitle="pageTitle"
                :successMessage="successMessage"
                @addEditSuccess="handleSuccess"
                @closed="handleCloseEinvoice"
                :account-number="formData.account_number"
                :pays-items=billsItems
                />

                <EwayBillModel
                v-if="isEwayBillModelVisible"
                :visible="isEwayBillModelVisible"
                :formData="formData"
                :url="url"
                :addEditType="addEditType"
                :pageTitle="pageTitle"
                :successMessage="successMessage"
                @addEditSuccess="handleSuccess"
                @closed="handleCloseEWay"
                :account-number="formData.account_number"
                :pays-items=EwaybillsItems
                />

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
import EInvoice from "../../../../main/views/stock-management/purchases/EInvoice.vue"
import EwayBillModel from "../../../../main/views/stock-management/purchases/EwayBillModel.vue"
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import UserSearch from "./UserSearch.vue";
import FormItemHeading from "../../../../common/components/common/typography/FormItemHeading.vue";
import { some, forEach, find } from "lodash-es";

import PaymentModeAddButton from "../payments/AddButton.vue";
import SalesModel from "./SalesModel.vue";
import SalesNumberModel from "./SalesNumberModel.vue";
import SalesCustomerModel from "./SalesCustomerModel.vue";
import PaymentsModal from "./PaymentsModal.vue";
import { message, notification } from "ant-design-vue";
import ShippingAdressModel from "../../../../main/views/stock-management/purchases/ShippingAdressModel.vue"
import PrintModel from "./PrintModel.vue";
 import { Modal } from 'ant-design-vue';
import { createVNode} from 'vue';

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
        EwayBillModel,
        EInvoice,
        SalesNumberModel,
        PaymentsModal,
        SalesCustomerModel,
        PrintModel,
         ShippingAdressModel,
        //DeleteConfirmationPopupModel,
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
        let recentEventId = ref("Unknown")
        let discountTypes = ref([])
        const { orderItemColumns } = fields();
        const { salesItemColumns } = fields();
          formData=({
                order_date: new Date().toISOString().split('T')[0],
                party_id:0,
                product_id: '',
                party_product_name: '',
              party_name: '',
                party_state:"",
                party_customer_id:0,
                party_shippingaddress_id:0,
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
                selectedInvoice:localStorage.getItem("selectedInvoice"),
                items:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:2,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:3,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:4,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:5,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:6,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:7,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:8,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:9,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                    {index:10,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,cgst:null,sgst:null,cess:null,unique:Math.random().toString(36).substring(2,7),hsnCode:null,qtyUnit:null,isDelete:0},
                ],

                invoiceitems:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',freeQty:"",remQty:"",withoutDisc:"",withDisc:"",mrp:null,single_unit_price:null,discount_type_id:null,discount_value:0,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
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
console.log("formData",formData.selectedInvoice)

            const handleDelete = () => {};

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
            discountTypes,
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
            company,
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
            handleDelete,
            router,
            recentEventId
           // showDeleteConfirmation,
            //deleteItem,

        };
    },
    data() {
        return {

            spinning:false,
            selectedInvoice:localStorage.getItem("selectedInvoice"),
            additems: [
                {id:"CGST" ,name: 'CGST', location: '', value: '' },
                { id: "SGST", name: 'SGST', location: '', value: '' },
                { id: "IGST", name: 'IGST', location: '', value: '' },
                { id:"CESS" ,name: 'CESS', location: '', value: '' },

                { id:"Total" ,name: 'Total', location: '', value: '' }],
            isNumberVisible: false,
            isAddressVisible:false,
            isPaymentsModalVisible:false,
            isModalPopup:false,
            isCustomerVisible: false,
            isModalVisible: false,
            isProuctsModalVisible: false,
            deleteConfirmationPopup:false,
            stockDateColor: '',
         addressField: '',
			isNumberVisible:false,
            isPrintModalVisible: false,
            showDeletePopup: false,
            selectedItemIndex: null,
            deleteIndex: null,
            isEwaybillModalVisible:false,
            isEwaybillModalVisible:false,
            items: [
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
            ],
            invoiceitems:[
                { quantity: "0.00" }

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
            headers: ['Product', 'Packing',  'Rate','Qty','Free','Billing Qty','Value Before Discount','Disc Type', 'Disc Value','Disc Amount','Value After Discount','Tax %','₹ Taxable Amount'],
            addtionaldetalisheader:['Additional Details', '₹ Amount'],

        };
    },

    mounted() {

        document.getElementById('salescreatevue').addEventListener('keydown', this.handleKeyDowning);
        this.autoFocusInput();
        const billNumberUrl = `sales/billNumber`;
                axiosAdmin.get(billNumberUrl).then((response) => {
                    //console.log(response)
                    if (this.selectedInvoice == null || this.selectedInvoice == "" || this.selectedInvoice == undefined || this.selectedInvoice == "null") {
                       // this.formData.bill_number = response.data.ref;
                    }
                this.discountTypes = response.data.discountItems;
                //document.getElementById("form_item_bill_number").value = this.formData.bill_number;
                //this.loading= false;
                 });
        this.getInvoiceDetails();

    },
    beforeDestroy() {
       // document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
deleteItem()
{
    console.log(this.selectedItermIndex);
    if (this.deleteIndex!=null || this.deleteIndex!=undefined) {
        // Remove the selected item
        console.log(this.deleteIndex);

        let temp = this.formData;
        temp.items.forEach((item, index) => {

            if(item.index == (this.deleteIndex+1)){ console.log(item)
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
            unique:Math.random().toString(36).substring(2,7)
        });
        this.formData.items.forEach((items,listindex)=>{
           items.index=listindex+1;
        })
        console.log(this.formData.items[8].unique);
        var T = document.getElementById('itemtable');
        let rows = T.rows;          
        let lastRow = rows[rows.length - 2];
        var R = lastRow;
        var C = R.cloneNode(true);
        T.insertBefore(C,lastRow);

       // console.log(this.formData.items)

        this.deleteConfirmationPopup = false; // Hide the delete confirmation popup
        var that = this;
        setTimeout(function () { that.getQuantity(0, null); }, 1000)
        setTimeout(function(){ that.updateAgg();},1000)     


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




      getInvoiceDetails(){
        console.log("selectedInvoice inside method",this.formData.selectedInvoice);
        if(this.selectedInvoice!="null")
        {
            this.formData.bill_number=this.selectedInvoice;
            this.spinning = true;
            axiosAdmin
            .get("sales/getInvoiceDetails/"+this.selectedInvoice)
            .then(response => {  console.log(response)
                // Toastr Notificaiton
                this.formData.party_id=response.data.partyDetails.id;
                console.log(this.formData);
                this.formData.party_name                =   response.data.partyDetails.party_name,
                this.formData.party_customer_id         =   response.data.customerData.id,
                this.formData.customer_name             =   response.data.customerData.cus_name,
                this.formData.party_shippingaddress_id  =   response.data.shipppingaddressData.id,
            
                this.formData.shipping_address          =   response.data.shipppingaddressData.shipping_address,
                this.formData.party_customer_mobile     =   response.data.customerData.mobile_number,
                this.formData.order_date                =   response.data.invoiceData.order_date.split('T')[0];

                this.spinning = false;
               
                if(response.data.invoiceItems && response.data.invoiceItems.length>0)
                {
                    let finalIndex  =   0;
                    let grand_total =   0;
                    let total_disc  =   0;
                    let totalcgst   =   0;
                    let totalsgst   =   0;
                    response.data.invoiceItems.forEach((data,index)=>{
                        var quantity = 0; var price = 0;

                        this.formData.items[index].item_id                  = data.product_id;
                        this.formData.items[index].item_name                = data.product_name;
                        this.formData.items[index].unit_id                  = "";
                        this.formData.items[index].quantity                 = this.formatNumber(data.quantity);
                        quantity = this.formatNumber(data.quantity);
                        this.formData.items[index].mrp                      = this.formatNumber(data.mrp);
                        this.formData.items[index].single_unit_price        = this.formatNumber(data.single_unit_price);
                        price = this.formatNumber(data.single_unit_price);
                        this.formData.items[index].discount_rate            = data.discount_rate;
                        this.formData.items[index].amount                   = this.formatNumber(data.subtotal);
                        this.formData.items[index].maxquantity              = this.formatNumber(data.stock);
                        this.formData.items[index].freeQty                  = this.formatNumber(data.freeQty);
                        this.formData.items[index].remQty                   = this.formatNumber(data.quantity - data.freeQty);
                        this.formData.items[index].max_single_unit_price    = this.formatNumber(data.mrp);
                        this.formData.items[index].packing                  = data.pack;
                        this.formData.items[index].cgst                     = (data.product.cgst>=0?data.product.cgst:0);
                        this.formData.items[index].sgst                     = (data.product.sgst>=0?data.product.sgst:0);
                        this.formData.items[index].cess                     = (data.product.cess>=0?data.product.cess:0);
                        this.formData.items[index].discount_type_id         = (data.discount_type_id);
                        this.formData.items[index].hsnCode                  = (data.hsnCode);
                        this.formData.items[index].qtyUnit                  = (data.qtyUnit);

                       
                        total_disc                                          =  total_disc+Number(data.discount_rate);
                        totalcgst                                           =  totalcgst+ (data.product.cgst>=0?data.product.cgst:0);
                        totalsgst                                           =  totalsgst+ (data.product.sgst>=0?data.product.sgst:0);                     
                        
                        document.getElementById('item_product_disc_'+index).value=this.formatNumber(data.discount_rate)
                        document.getElementById('item_discount_total_'+index).value=data.discount_rate;

                        finalIndex = index;

                        const cgst_tax_percentage = Number(this.formData.items[index].cgst);
                        const sgst_tax_percentage = Number(this.formData.items[index].sgst);
                        if (quantity == undefined || quantity == "" || quantity < 0) { quantity = 0 }
                        if (price == undefined || price == "" || price < 0) { price = 0 }
                        // if(discount==undefined || discount=="" || discount<0){discount=0}
                        const singleItemTotal = Number(quantity * price);
                        document.getElementById("item_product_amount_" + index).value = this.formatCurrency(singleItemTotal);                           
                        
                        document.getElementById("item_product_withoutDisc_" + index).value = (this.formData.items[index].remQty * data.single_unit_price);
                        this.formData.items[index].withoutDisc = this.formatCurrency((this.formData.items[index].remQty * data.single_unit_price));

                        const totalDiscountByItem = this.getTotalAmount('discount', index);
                        const afterDisc = Number(this.formData.items[index].withoutDisc.replace(/,/g,'')) - Number(totalDiscountByItem); 
                        //item_product_withDisc_0
                        document.getElementById("item_product_withDisc_" + index).innerHTML = afterDisc;
                       
                       
                        const totalTaxValue = (((cgst_tax_percentage + sgst_tax_percentage) / 100) * afterDisc); 
                        this.formData.items[index].discount_value = totalTaxValue;
                        console.log("totalTaxValue=>",this.formData.items[index].discount_value)  
                         const taxvalueStr = (cgst_tax_percentage + sgst_tax_percentage) + ",(" + totalTaxValue.toFixed(2) + ")"; console.log("taxvalueStr",taxvalueStr)
                        document.getElementById("item_product_tax_" + index).innerHTML = taxvalueStr;
                        document.getElementById('item_product_amount_' + index).value = this.formatNumber(afterDisc + totalTaxValue)  
                        grand_total = grand_total + Number(afterDisc+totalTaxValue);        
                        
                    })
                   
                    total_disc = this.getTotalAmount('discount',null);
                    totalcgst = this.getTotalAmount("cgst",null);
                    totalsgst = this.getTotalAmount("sgst",null);
                    const cessAmount = this.getTotalAmount('cess', null);
                   console.log("cess =>",this.formatCurrency(cessAmount)) 
                    document.getElementById('total_goods_value').value          =    this.formatCurrency(grand_total);
                    console.log("edit grand total 1",totalcgst,totalsgst,total_disc);
                 
                    document.getElementById('igst_amount_0').value =   this.formatCurrency(totalcgst);
                    document.getElementById('igst_amount_1').value = this.formatCurrency(totalsgst);
                    document.getElementById("igst_amount_2").value = this.formatCurrency(totalcgst+totalsgst);
                    document.getElementById('igst_amount_3').value = this.formatCurrency(cessAmount);
                    console.log("edit grand total 2",  grand_total,totalcgst,totalsgst,cessAmount,total_disc);
                   
                    document.getElementById('grand_total').innerHTML = this.formatCurrency((grand_total + (cessAmount)));
                    this.formData.total =   (grand_total + (cessAmount));
                    console.log("this.formData.total", this.formData.total)
                    //console.log("grand_total=>",this.formatCurrency(totalsgst>0?((totalsgst/2)/100)*grand_total:0));
                    this.selectedItermIndex = (finalIndex);
                    document.getElementById('cgst_total_text').innerHTML        =    this.formatCurrency(totalcgst>0?totalcgst:0)
                    document.getElementById('sgst_total_text').innerHTML = this.formatCurrency(totalsgst > 0 ? totalsgst : 0);
                   
                     if (this.formData.party_state == this.company.state) {
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

                }
                console.log(this.formData.items)
                this.updateAgg();
                
            })
            .catch(errorResponse => {
                this.spinning= false;
            })
        }

      },

        formatNumber (num)
        {
       return parseFloat(num).toFixed(2)
      },

      toggleDropdowns()
       {
      this.isOpensave = !this.isOpensave;
    },

        openEInvoiceModal()
         {
        this.isEInvoiceModalVisible = true;
        console.log("E-Invoice modal triggered");


        },
        // Open E-Way Bill Modal

        openEwayBillModal()
        {
        this.isEwayBillModelVisible = true;
        console.log("E-Way modal triggered");


        },



        handleCloseEinvoice()
        {
        this.isEInvoiceModalVisible=false;
        },

        handleCloseEWay()
        {
        this.isEwayBillModelVisible=false;
        },

    showPrintModal() {
            //console.log("Print called");
            this.isPrintModalVisible = true;
        },

        handleClosePrint() {

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
                if(this.$refs.DateInput)
                this.$refs.DateInput.focus();
            });
        },

        handleClosepopup(){
        this.deleteConfirmationPopup=false;
        },

    handleBlur() {
      ////console.log('Focus removed from input');
      this.showMessage = true;
    },
    customerhandleKeydown(event)
    {
        console.log('calling every whae',event.keyCode)
        if(this.formData.party_id==undefined || this.formData.party_id=="" || this.formData.party_id<0)
        {
            this.isModalVisible = true;
            return false;
        }
        else if ((event.keyCode === 13 || event.keyCode === 9) && this.formData.party_id>0) {
         this.isModalVisible = false;
         document.getElementById('item_product_name_'+this.selectedItermIndex).focus();

        }
        else if ((event.keyCode === 27) && this.formData.party_id>0) {
         this.showconfirm(event);

        }
        else if ((  event.keyCode !== 119 && event.keyCode !== 255 && event.keyCode !== 13 && event.keyCode !== 9 && event.keyCode !== 119) && this.formData.party_id>0) {
         this.isModalVisible = true;
         return false;

        }
        else if ( event.keyCode == 119 && this.formData.party_id>0) {
            this.saveSalesEntry();

        }
    },
    handleKeyDown(event) {
      if (event.key === 'Escape' || event.keyCode === 27) {
         //this.isModalVisible = false;

         this.autoFocusInput();
      }
      else if (event.key === 'F8') {
        this.saveSalesEntry();
      }
      else if ((event.keyCode === 13 || event.keyCode === 9) && this.formData.party_id>0) {
         this.isModalVisible = false;
         document.getElementById('item_product_name_'+this.selectedItermIndex).focus();
        ////console.log("sele ",this.selectedItermIndex)
      }
      else if ((event.keyCode !== 13 && event.keyCode !== 9) && this.formData.party_id>0)
      {
        this.isModalVisible = true;
      }
    },

    handleKeyDowning(event){ console.log('event',event.target.id)
        this.recentEventId=event;
        if (event.key === 'Escape' || event.keyCode === 27) {
         //this.isModalVisible = false;
         this.focusEelmentss();
         //this.autoFocusInput();
      }
    },

    handleKeydownDatePicker(event) {
            console.log('event.keyCode ', event.keyCode);
            this.recentEventId = event;

            if (event.keyCode === 27) {  // Escape key
              this.showconfirm(event);
            } else if ((event.key === 'Tab' || event.key === 'Enter') && event.target.value === "") {  // Enter/Tab key
                this.formData.order_date = new Date().toISOString().split('T')[0];
                document.getElementById("form_item_order_date").value = this.formData.order_date;
                this.focusElement('party_name');
                event.preventDefault();
            }
            else if (event.keyCode === 119) {
                this.saveSalesEntry();
            }

            else if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
    const currentDate = new Date(this.formData.order_date || new Date());
    const increment = event.key === 'ArrowUp' ? 1 : -1; // Determine increment or decrement
    currentDate.setDate(currentDate.getDate() + increment);
    this.formData.order_date = currentDate.toISOString().split('T')[0];
    document.getElementById("form_item_order_date").value = this.formData.order_date;
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

        document.getElementById("form_item_order_date").value = this.formData.order_date;
        event.preventDefault();
    }


            else {
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
    const dateInput = document.getElementById("form_item_order_date");
    if (dateInput) {
        dateInput.value = this.formData.order_date;
    }
},

        focusElement(field) {
            if (field === 'order_date') {
                document.getElementById('order_date').focus();
            } else if (field === 'party_name') {
                document.getElementById('form_item_party_name').focus();
            } else if (field === 'item_product_name_0') {
                document.getElementById('item_product_name_0').focus();
            }
        },


    showPaymentModal(){
        ////console.log("Payment called");
        this.isPaymentsModalVisible = true;
        document.getElementById('amount_0').focus();
        $event.preventDefault();
        },
        handleClosePaymentsByClose(input){
        this.isPaymentsModalVisible = false;
        if(input==27)
        {  this.focusElement('order_date'); }

        setTimeout(function(){document.getElementById('form_item_party_name').focus();},500);

    },
    handleClosePayments(input){
        this.isPaymentsModalVisible = false;
        if(input==27)
        {  this.focusElement('order_date'); }
        else{
             this.isPrintModalVisible= true;
        }
        setTimeout(function(){document.getElementById('btn-Ledger').focus();},500);

    },

        inputData($event) {
            // Value will update reactively
            $event.target.value;
        },
        showNumberModal(event) {
            console.log("Open Model", event.keyCode);
            if(event.shiftKey && event.keyCode == 9)
                {
                    console.log("AAC");
                    return true;
                }
            if((event.keyCode==13 || event.keyCode==32 || event.keyCode==9 ))
            {
                this.isNumberVisible = true;
            }
            else if(event.keyCode==119) // press F8
            {
                this.saveSalesEntry();
            }
            else if(event.keyCode==27) // press F8
            {
                this.showconfirm(event);
            }
            event.preventDefault();
                        return false;


        },

        showAddressModal(event) {
    console.log("Open Model", event.keyCode);

    if (event.shiftKey && event.keyCode == 9) {
        console.log("shipping Address caed");
        return true;
    }

    // Show modal on Space or Tab
    if (event.keyCode == 32 || event.keyCode == 9) {
        this.isAddressVisible = true;
    }
    // Handle Enter key
    else if (event.keyCode == 13) {
        if (!this.isAddressVisible && !this.hasValidInput(event.target)) {
            // If modal not visible and field empty, show modal
            this.isAddressVisible = true;
        } else if (this.hasValidInput(event.target)) {
            // If field has value (after selection), proceed to save
            this.isAddressVisible = false;
            this.saveSalesEntry();
        }
    }
    // F8 key for save
    else if (event.keyCode == 119) {
        if (this.hasValidInput(event.target)) {
            this.isAddressVisible = false;
            this.saveSalesEntry();
        }
    }
    // Esc key
    else if (event.keyCode == 27) {
        this.showconfirm(event);
    }

    event.preventDefault();
    return false;
},

hasValidInput(target) {
    return target.value && target.value.trim() !== '';
},
      

        handleSalesNumber() {
            ////console.log("close Model",this.selectedItermIndex);
            this.isNumberVisible = false;
            if(this.selectedItermIndex==undefined){
                document.getElementById("item_product_name_0").focus();
            }

            else{
                document.getElementById("form_item_address").focus()
                let cf =0;
                for (let i = 0; i < this.formData.items.length; i++) {
                    if(cf== 0 && (this.formData.items[i].item_id!=undefined && this.formData.items[i].item_id>0 &&  this.formData.items[i].quantity>0))
                    {    cf=i;


                    break;
                    }
                }
                if(cf>0)
                {
                    document.getElementById("item_product_name_"+(this.selectedItermIndex)).focus();
                }
                else{
                    document.getElementById("shortcut").focus();
                }

            }
            return false;

        },
        handleAddress() {
            this.isAddressVisible = false;
            if(this.selectedItermIndex==undefined){
                document.getElementById("item_product_name_0").focus();
            }

            else{
                document.getElementById("form_item_address").focus()
                let cf =0;
                //this.focusProductList();
                for (let i = 0; i < this.formData.items.length; i++) {
                    if(cf== 0 && (this.formData.items[i].item_id!=undefined && this.formData.items[i].item_id>0 &&  this.formData.items[i].quantity>0))
                    {    cf=i;


                    break;
                    }
                }
                if(cf>0)
                {
                    document.getElementById("item_product_name_"+(this.selectedItermIndex)).focus();
                }
                else{
                    document.getElementById("shortcut").focus();
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

        handleCloseCustomer() {

            this.isModalVisible = false;
           // this.isProuctsModalVisible = false;
           this.focusElement('order_date');
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
            this.focusElement('item_product_name_0');
            ////console.log('Success:', xid);
        },
        updateParent(selectedParty) {
            console.log('selectedParty=>', selectedParty)
           // document.getElementById("balance_amt").innerHTML = 0.00;
           // document.getElementById("Due_amt").innerHTML = 0.00;
            document.getElementById("party_state").value = selectedParty.state;
            


            this.formData.party_id = selectedParty.id.toString();
            this.formData.party_name = selectedParty.name;
            this.formData.party_state = selectedParty.state;
            console.log(this.formData.party_state ,"==", this.company.state)
            if (this.formData.party_state == this.company.state) {
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
            document.getElementById("party_id").value = this.formData.party_id;
            document.getElementById("party_state").value = selectedParty.state;
            document.getElementById("form_item_name").value = this.formData.name;
            document.getElementById("form_item_party_name").value = this.formData.party_name;

            if(this.formData.party_id!=undefined && this.formData.party_id!="")
            {
                this.loading= true;
                const billNumberUrl = `sales/billNumber`+"/"+this.formData.party_id;
                axiosAdmin.get(billNumberUrl).then((response) => {
                //console.log(response)
                this.formData.bill_number = response.data.ref;
                    this.discountTypes = response.data.discountItems;
                   // document.getElementById("balance_amt").innerHTML = (response.data.balance).toFixed(2);
                   //  document.getElementById("Due_amt").innerHTML = (response.data.balance).toFixed(2);

                  //  document.getElementById("form_item_bill_number").value = this.formData.bill_number;
                this.loading= false;
                 });
            }
            this.isModalVisible=false;
            this.focusEelment()

        },

        updatePayment(selectedParty){
            this.formData.payment_id = selectedParty.id;
            ////console.log("hello child" + this.formData.payment_id)
            document.getElementById("payment_id").value = this.formData.payment_id;
        },
        focusEelment()
        {
            ////console.log('Success:');
            document.getElementById("form_item_bill_number").focus();
        },

        focusEelmentss(){
            document.querySelector(".mobilenumbering").focus();

        },

        focusproductEelment()
        {
            ////console.log('Success pr:');
            //document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
            document.getElementById("item_product_quantity_0").focus();
        },


        updateCustomer(selectedParty) {
            console.log('selectedParty',selectedParty)
            this.formData.party_customer_id = selectedParty.id;
            this.formData.party_customer_name = selectedParty.name;
            this.formData.party_customer_mobile = selectedParty.mobile_number;
            ///this.formData.address               =   selectedParty.address
            ////console.log("hello child" + this.formData)
            document.getElementById("party_customer_id").value = this.formData.party_customer_id;
            document.getElementById("form_item_customer_name").value = this.formData.party_customer_name;
            document.getElementById("form_item_mobile_number").value = this.formData.party_customer_mobile;
            //document.getElementById("form_item_address").value = this.formData.address;
        },

        //shippingaddress//


        updateShipping(selectedParty) {
             this.formData.shipping_address               =   selectedParty.shipping_address;
            this.formData.party_shippingaddress_id = selectedParty.id;

            document.getElementById("form_item_address").value = this.formData.shipping_address;
            document.getElementById("party_shippingaddress_id").value = this.formData.party_shippingaddress_id;
        },
        //endof shipping address//
        updateProduct(selectedParty) {

    const isDuplicate = this.formData.items.some((item, index) =>
        item.item_id === selectedParty.id && index !== this.selectedItermIndex
    );
    if (isDuplicate) {
        this.$notification.error({
            message: 'Duplicate Entry',
            description: 'This product has already been added to the list!'
        });
        return;
    }

    // Proceed with normal update if not duplicate ,hsnCode:null,qtyUnit:null,isDelete:0
    console.log('Cgst Tax', selectedParty);
    this.formData.items[this.selectedItermIndex].item_name = selectedParty.name;
    this.formData.items[this.selectedItermIndex].item_id = selectedParty.id;
    this.formData.items[this.selectedItermIndex].single_unit_price = 0;
    this.formData.items[this.selectedItermIndex].max_single_unit_price = 0;
    this.formData.items[this.selectedItermIndex].quantity = 0;
    this.formData.items[this.selectedItermIndex].maxquantity = selectedParty.quantity;
    this.formData.items[this.selectedItermIndex].packing = selectedParty.packing;
    this.formData.items[this.selectedItermIndex].cgst = selectedParty.cgst;
    this.formData.items[this.selectedItermIndex].sgst = selectedParty.sgst;
    this.formData.items[this.selectedItermIndex].cess = selectedParty.cess;


    this.formData.items[this.selectedItermIndex].discount_type_id = 3;
    this.formData.items[this.selectedItermIndex].hsnCode = selectedParty.hsnCode;
    this.formData.items[this.selectedItermIndex].qtyUnit = selectedParty.qtyUnit;

    // Update DOM elements
    document.getElementById("item_product_name_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_name;
    document.getElementById("item_product_packing_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].packing;
    document.getElementById("item_product_id_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_id;
    document.getElementById("item_product_price_" + this.selectedItermIndex).value = this.formatNumber(this.formData.items[this.selectedItermIndex].single_unit_price);
    document.getElementById("item_product_quantity_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].quantity;
    document.getElementById("item_product_amount_" + this.selectedItermIndex).value = 0.0;
    document.getElementById("cgst_tax_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].cgst;
    document.getElementById("sgst_tax_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].sgst;
    document.getElementById("item_product_tax_" + this.selectedItermIndex).innerHTML = selectedParty.lgst;
    document.getElementById("item_product_price_" + this.selectedItermIndex).focus();
    //this.$emit("closedbyclose");
},

        showProductModal(event,index) {
           this.recentEventId = event;
            this.selectedItermIndex = index;
            console.log('=>', index, event.key, event.keyCode, this.formData.items[index].item_id)

                if(event.keyCode==16)
                {   console.log("AAA");
                    return true;
                }
                else if(event.keyCode==255)
                {console.log("AAB");
                    return true;
                }
               else if(event.shiftKey && event.keyCode == 9)
                {
                    console.log("AAC");
                    return true;
                }
                else if(event.keyCode==119)
                {
                    console.log("AAD");
                    event.preventDefault();
                    this.saveSalesEntry();
                }
                else if(event.keyCode==27)
                {
                    console.log("AAE");
                    this.showconfirm(event)

                    return false;
                }
                else if(this.formData.items[index].item_id>0 && (event.keyCode==46 || event.keyCode==8))
                {console.log("AAF");
                    this.deleteConfirmation(index);
                    //this.deleteConfirmationPopup= true;
                }
                else if(this.formData.party_name!="" && index>0 &&  event.key == 'Enter')
                 {console.log("AAG");
                    if(this.formData.items[index].item_id==undefined || this.formData.items[index].item_id=="" || this.formData.items[index].item_id==null)
                    {
                        this.isProuctsModalVisible = true;
                    }
                    else if(this.formData.items[index].item_id>0 && event.keyCode==13)
                    {
                        console.log("TTT");
                        document.getElementById("item_product_quantity_"+index).focus();
                        event.preventDefault();
                    }
                 }
                else if(this.formData.party_name!="" && index>0 &&  event.key == 'Tab')
                {console.log("AAH");
                    ////console.log("Tab Key Press",this.formData.party_customer_mobile);
                    this.selectedItermIndex =  index;
                    if(this.formData.bill_number==undefined || this.formData.bill_number=="" || this.formData.bill_number.trim()==""){ ////console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_bill_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){ ////console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_mobile_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else if(this.formData.items[index].item_id==undefined || this.formData.items[index].item_id=="" || this.formData.items[index].item_id==null)
                    {
                        this.isProuctsModalVisible = true;
                    }

                    else if(this.formData.items[index].item_id>0 && event.keyCode==13)
                    {
                        console.log("TTT");
                        document.getElementById("item_product_quantity_"+index).focus();
                        event.preventDefault();
                    }
                    else if(this.formData.items[index].item_id>0 && event.keyCode==9)
                    {
                        console.log("SSS");
                        document.getElementById("item_product_quantity_"+index).focus();
                        event.preventDefault();
                    }
                    else if(this.formData.items[index].item_id>0 && (event.keyCode!=9 && event.keyCode!=13))
                    { console.log("BBB");
                        this.isProuctsModalVisible = true;
                        event.preventDefault();
                    }
                    else
                    {

                        this.saveSalesEntry();
                    }


                }
                else if(this.formData.party_name!="" && index==0 && event.key!="F8")
                {
                    console.log("AAI");
                    this.selectedItermIndex =  index;
                    if(this.formData.items[index].item_id==undefined || this.formData.items[index].item_id=="" || this.formData.items[index].item_id==null)
                    {
                        this.isProuctsModalVisible = true;
                    }

                    else if(this.formData.items[index].item_id>0 && event.keyCode==13)
                    {
                        document.getElementById("item_product_quantity_"+index).focus();
                        event.preventDefault();
                    }
                    else if(this.formData.items[index].item_id>0 && event.keyCode==9)
                    {
                        document.getElementById("item_product_name_"+index).focus();
                        event.preventDefault();
                    }
                    else if(this.formData.items[index].item_id>0 && (event.keyCode!=9 && event.keyCode!=13))
                    {
                        this.isProuctsModalVisible = true;
                        event.preventDefault();
                    }



                }
                else if(this.formData.party_name!="" && index>0 && (event.keyCode !== 9 && event.keyCode!=119 && event.key!="F8")  )
                { ////console.log("POP Pay 2");
                console.log("AAG");
                    this.selectedItermIndex =  index;
                    this.isProuctsModalVisible = true;

                    document.getElementById("form_item_party_name_search").focus();
                    //event.preventDefault();
                    return false;

                }
                else if(this.formData.party_name!="" && index>0 && (event.keyCode !== 9 && event.keyCode!=119) )
                {
                        console.log("AAHww");
                    if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){
                        document.getElementById("form_item_mobile_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else
                    {
                    this.saveSalesEntry();
                    }
                }
                else if(this.formData.party_name!="" && index>0 &&  event.keyCode==119 )
                {
                    console.log("F8 focus");
                    this.saveSalesEntry();

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
        this.focusEelmentss();
        //this.focusProductList();
        },
        handleaddressModel(){
        this.isAddressVisible=false;
        },
        focusinputvalue(event)
        {
            event.target.select()

        },
        getTotalAmount(type,index)
        {

            let total       =   0;
            let sgst        =   0;
            let cgst        =   0;

            let cessAmount = 0;
            let discount = 0;
            let totalProducts = 0;
            let sgstAmount = 0;
            let cgstAmount = 0;
            let singleItemTotal = 0;
            let freeQuantity = 0;
            for (var i = 0; i < this.formData.items.length; i++)
            {
                let element = index != null ? this.formData.items[index] : this.formData.items[i];
                //this.formData.items.forEach((element) => {
                  
                let tempQuantity = element.quantity;
                if(element.item_id>0 && element.single_unit_price>0 && element.quantity>0 )
                {
                    if (element.freeQty > 0) {
                        tempQuantity = tempQuantity - parseInt(element.freeQty);
                    }  
                    singleItemTotal = Number(element.single_unit_price) * Number(tempQuantity);
                    if (element.amount > 0) {
                        
                        total = total + Number(element.amount);
                    }
                    totalProducts = totalProducts + 1;
                }
                
               
                if (element.item_id > 0 && element.discount_rate >= 0) {
                    if (element.discount_type_id == 2) {
                        const baseTotal = Number(element.single_unit_price) * Number(tempQuantity);
                        discount += Number((element.discount_rate / 100) * baseTotal);
                        element.discount_value= Number((element.discount_rate / 100) * baseTotal)
                    }
                    if (element.discount_type_id == 3) {
                        discount += Number(element.discount_rate);
                        element.discount_value= Number(element.discount_rate)
                    }
                    if (element.discount_type_id == 4) {
                        const baseTotal = Number(element.discount_rate) * Number(tempQuantity);
                        element.discount_value= baseTotal
                        discount += (baseTotal);
                    }
                    

                }
                 if (element.item_id > 0 && element.sgst > 0) { sgstAmount += ((element.sgst/100) * (singleItemTotal-element.discount_value));   sgst=sgst+element.sgst;  }
                if (element.item_id > 0 && element.cgst > 0) { cgstAmount += ((element.cgst / 100) * (singleItemTotal - element.discount_value)); cgst = cgst + element.cgst; }
               if(element.item_id>0 && element.cess>0){    cessAmount += ((element.cess/100) * (singleItemTotal - element.discount_value)); }  
                if (index != null) { break; }
                // });
            }     

            if (type == "sgst") { return sgstAmount; }
            else if (type == "cgst") { return cgstAmount; }
            else if (type == "cess") { return cessAmount; }
            else if(type=="discount"){
                return discount;
            }
           else
           {
                return total;
           }

        },
        getQuantity(index, event) {
            console.log("getQuantity =>",index,event)
            let  taxvalue              = Number(document.getElementById("item_product_tax_"+index).innerHTML) ;
            let quantity            = document.getElementById("item_product_quantity_" + index).value;
            let  freeQuantity       = document.getElementById("item_product_freeQty_"+index).value;
            let  price                 = document.getElementById("item_product_price_"+index).value;
            let  discount              = document.getElementById("item_product_disc_"+index).value;
            const cgst_tax_percentage  = Number(this.formData.items[index].cgst);
            const sgst_tax_percentage  = Number( this.formData.items[index].sgst);

            if (quantity == undefined || quantity == "" || quantity <= 0) { 
                quantity = 0;
                console.log("item_product_tax_1")
                document.getElementById("item_product_tax_" + index).innerHTML = "";
                document.getElementById("item_product_amount_" + index).value = this.formatCurrency(0);
                document.getElementById("item_product_disc_" + index).value = "";


            }
            if(freeQuantity==undefined || freeQuantity=="" || freeQuantity<0){freeQuantity=0}            
            if(price==undefined || price=="" || price<0){price=0}
            if (discount == undefined || discount == "" || discount < 0) { discount = 0 }
            console.log(freeQuantity + '>=' + quantity)
            
            if (parseInt(freeQuantity) >= parseInt(quantity)) {
                this.formData.items[index].freeQty = 0;
                this.formData.items[index].remQty = 0;
                document.getElementById("item_product_freeQty_" + index).value = 0;
                document.getElementById("item_product_remQty_" + index).value = (quantity);
                freeQuantity = 0;
            }   
            if(freeQuantity>0 && quantity>0)
            {
                document.getElementById("item_product_remQty_"+index).value=(quantity - freeQuantity)
                quantity = quantity - freeQuantity;
                  this.formData.items[index].freeQty = freeQuantity;
                this.formData.items[index].remQty = quantity;
                
            } 
            else if(freeQuantity==null || freeQuantity==undefined || freeQuantity==0)
            {
                  document.getElementById("item_product_remQty_"+index).value=(quantity)
                quantity = quantity ;
            }           
            const singleItemTotal = Number(quantity*price);
            
            document.getElementById("item_product_withoutDisc_" + index).value = this.formatCurrency(singleItemTotal); 
            document.getElementById("item_product_withDisc_" + index).innerHTML = this.formatCurrency(singleItemTotal);  
            this.formData.items[index].withoutDisc = this.formatCurrency(singleItemTotal);
            this.formData.items[index].withDisc = this.formatCurrency(singleItemTotal);       
            const taxvalueStr1 = (cgst_tax_percentage + sgst_tax_percentage) + ",(" + (((cgst_tax_percentage + sgst_tax_percentage) / 100) * singleItemTotal).toFixed(2)+")";
            if (singleItemTotal > 0 && quantity > 0) {
             console.log("item_product_tax_2")   
                document.getElementById("item_product_tax_" + index).innerHTML = taxvalueStr1;
            }
            this.formData.items[index].discount_rate    =   discount;
            this.formData.items[index].single_unit_price    =   price;

           
             if (this.formData.party_state == this.company.state) {
                document.getElementById("sgst_total_text").innerHTML = this.formatCurrency(Number((sgst_tax_percentage / 100)) * Number(this.totalAmount));
            }
            if (this.formData.party_state == this.company.state) {
                document.getElementById("cgst_total_text").innerHTML = this.formatCurrency(Number((cgst_tax_percentage / 100)) * Number(this.totalAmount));
            }         

            const totalSgstAmount   =   this.getTotalAmount('sgst',null);
            const totalCgstAmount   =   this.getTotalAmount('cgst',null);
            const totalDiscount = this.getTotalAmount('discount', null);;
            const totalDiscountByItem = this.getTotalAmount('discount', index)

           // const totalAmount = this.getTotalAmount('total',null);
           // this.totalAmount = totalAmount;
            const cessAmount = this.getTotalAmount('cess',null);
          /*  if (totalDiscount > 0)
            {*/
               // this.totalAmount = this.totalAmount - totalDiscount;
                const withoutDisc = document.getElementById("item_product_withoutDisc_" + index).value;
                console.log("withoutDisc->",withoutDisc,withoutDisc.replace(/,/g,''),totalDiscount);
                let withDisc = parseFloat(withoutDisc.replace(/,/g,'')) - totalDiscountByItem;
                document.getElementById("item_product_withDisc_" + index).innerHTML = withDisc.toFixed(2); 
               
                const totalDiscValue = (((cgst_tax_percentage + sgst_tax_percentage) / 100) * (singleItemTotal - totalDiscountByItem)).toFixed(2);
               // console.log("totalAmount->",this.totalAmount , Number(totalDiscValue));
              //  this.totalAmount = this.totalAmount + Number(totalDiscValue)
                const taxvalueStr = (cgst_tax_percentage + sgst_tax_percentage) + ",(" + totalDiscValue +")";
                if (singleItemTotal >0 && quantity>0) {
                   
                    document.getElementById("item_product_tax_" + index).innerHTML = taxvalueStr;
                    this.formData.items[index].discount_value =totalDiscValue;
                }
                document.getElementById("item_product_disc_value_" + index).value = Number(totalDiscountByItem.toFixed(2));
                console.log("totalDiscValue->", totalDiscValue, withDisc);
                if (quantity > 0) {
                    this.formData.items[index].amount = (withDisc + Number(totalDiscValue));
                    document.getElementById("item_product_amount_" + index).value = this.formatCurrency((withDisc + Number(totalDiscValue)));
                }
          //  }
             this.totalAmount = this.getTotalAmount('total', null);
             console.log("totalAmount ->",this.totalAmount);
            document.getElementById("igst_amount_1").value       =  this.formatCurrency(totalSgstAmount>0?totalSgstAmount:0);
            document.getElementById("igst_amount_0").value       =  this.formatCurrency(totalCgstAmount>0?totalCgstAmount:0);
            document.getElementById("igst_amount_2").value = this.formatCurrency((totalSgstAmount + totalCgstAmount) > 0 ? (totalSgstAmount + totalCgstAmount) : 0);

            document.getElementById("igst_amount_3").value = this.formatCurrency(cessAmount);
             document.getElementById("igst_amount_4").value = this.formatCurrency( totalSgstAmount + totalCgstAmount+cessAmount);

            this.formData.tax_amount                             =  totalSgstAmount+ totalCgstAmount;
            document.getElementById("total_goods_value").value   =  this.formatCurrency(this.totalAmount);
            this.formData.subtotal                               =  this.totalAmount>0?this.totalAmount:0;
            document.getElementById("grand_total").innerHTML     = this.totalAmount>0? this.formatCurrency((this.totalAmount+cessAmount)):0;
            this.formData.total                                   = (this.totalAmount+cessAmount);
            console.log("totalSgstAmount",totalSgstAmount , "totalCgstAmount",totalCgstAmount);
            document.getElementById('total_discount_text1').innerHTML = this.formatCurrency((totalSgstAmount + totalCgstAmount) > 0 ? (totalSgstAmount + totalCgstAmount) : 0)
            this.formData.discount                                   =   totalDiscount;
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
        checkMaxQuantity(index,event)
        {
            //console.log(this.formData.items[index].maxquantity,event.target.value,event.key)
            if(Number(this.formData.items[index].quantity)>Number(this.formData.items[index].maxquantity))
            {

                // notification.warning({
                //         placement:  "bottomRight",
                //         message: "Stock Going to be Negative!",

                //     });

            }
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                ////console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if((event.key=="Tab" || event.key=="Enter"))
            {

                document.getElementById("item_product_price_"+this.selectedItermIndex).focus();
                event.preventDefault()
                return false;
            }


        },
        checkSingleItemPrice(index,event)
        {
            console.log("checkSingleItemPrice",event.key)
           if(Number(this.formData.items[index].single_unit_price)>Number(this.formData.items[index].max_single_unit_price))
            { }
            if ((event.key == "Tab" || event.key == "Enter") && (Number(event.target.value) >0)) {
                console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_" + index).focus();
                return false;
            }
            else if ((event.key == "Enter") && (Number(event.target.value) > 0)) {
                console.log("222", document.getElementById("item_product_disc_type_" + index));
                document.getElementById("item_product_quantity_" + index).focus();
                event.preventDefault();
                return false;
            }
            else if (event.key == undefined) {
                document.getElementById("item_product_price_" + index).focus();
            }
            else if (event.keyCode === 119) {
                this.saveSalesEntry();
            }
            else if (event.keyCode === 27) {
                this.showconfirm(event);
            }
            else if (event.key == "Delete") {
                event.preventDefault();  
                this.deleteConfirmation(index);
                
            }  
            this.showGstDetails(index);

        },


        checkDisc(index, event) {
            if (event.shiftKey && (event.key === "Tab" || event.key === "Enter") && (Number(event.target.value) <= 0)) { return;}
            if ((event.key === "Tab" || event.key === "Enter") && (Number(event.target.value) <= 0)) {
                console.log("Quantity is Zero, focus will move to item_product_disc_");
                document.getElementById("item_product_name_" + (index + 1)).focus();
                event.preventDefault();
                return false;
            }
            else if (event.key === "Enter")
            {
                console.log("Valid Discount Entered. Moving to next field...");
                this.selectedItermIndex = index + 1;
                document.getElementById("item_product_name_" + this.selectedItermIndex).focus(); event.preventDefault();
                return false;
            }
            else if (event.keyCode === 119) {
                this.saveSalesEntry();
            }
            else if (event.keyCode === 27) {
                this.showconfirm(event);
            }
            else if (event.key === "Delete")
            {
                 event.preventDefault(); 
                this.deleteConfirmation(index);
            }
            this.getQuantity(index,event);
            this.updateAgg();
        },


        success(response)
        {
            this.formData.selectedInvoice = this.formData.bill_number;
            localStorage.setItem("selectedInvoice", this.formData.selectedInvoice);
            this.loading= false;
            notification.success({
                        placement:  "bottomRight",
                        message: "Purchase bill saved successfully !    ",
                        description: response.message
                    });
            this.resetFormData();
            this.isPaymentsModalVisible = true;
        },
        successDraft(response)
        {
            this.formData.selectedInvoice = this.formData.bill_number;
            localStorage.setItem("selectedInvoice", this.formData.selectedInvoice);
            //console.log('rely=>>',response.message);
            this.loading= false;
            notification.success({
                        placement:  "bottomRight",
                        message: "Draft Order saved successfully !    ",
                        description: response.message
                    });
            //this.isPaymentsModalVisible= true;

        },
        validateSales()
        {
            if(this.formData.party_id==undefined || this.formData.party_id=="" || this.formData.party_id==""){ ////console.log('+>',this.formData.bill_number)
                        document.getElementById("form_item_party_name").focus();

                        return false;
            }
            
            else
            {
                let itemAvailable =false;
                this.formData.items.forEach((element)=>{
                    if(itemAvailable == false && element.item_id!=undefined && element.item_id!="" && element.quantity>0 && element.single_unit_price>0){
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
        saveSalesEntry(event)
        {
            console.log('submit', event);
            if (!this.validateSales()) {
                document.getElementById("shortcut").focus();
                return false;
            }
            // Extra validation: check for duplicate invoice number
            if (!this.formData.bill_number || this.formData.bill_number === "") {
                notification.error({
                    placement: "bottomRight",
                    message: "Invoice number is required!",
                });
                document.getElementById("form_item_bill_number").focus();
                return false;
            }
            this.spinning = true;
            axiosAdmin
                .post("purchase/purchaseBillDetail", this.formData)
                .then(response => {
                    console.log(response);
                    this.spinning = false;
                    if (response.message && response.message.includes("Duplicate entry")) {
                        notification.error({
                            placement: "bottomRight",
                            message: "Duplicate Invoice Number!",
                            description: response.message
                        });
                        document.getElementById("form_item_bill_number").focus();
                        return false;
                    }
                    this.success(response);
                    this.isModalVisible = true;
                })
                .catch(errorResponse => {
                    this.spinning = false;
                    var err = errorResponse.data;
                    const errorCode = errorResponse.status;
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
                        err = {
                            error: {
                                ...err
                            }
                        }
                    }
                });
        },
        saveSalesEntryDraft(event)
        {
            console.log('submit',event);
            if(this.validateSales()){
            this.spinning= true;
            axiosAdmin
            .post("sales/store", this.formData)
            .then(response => {  //console.log(response)
                // Toastr Notificaiton
                this.spinning= false;
                this.successDraft(response);
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
                    message.error("error messagein 2707",err.message);
                    err = {
                        error: {
                            ...err
                        }
                    }
                }



                //loading.value = false;
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
            console.log("updateAgg");
            let count =0;
            let quantity = 0;
            let freequantity = 0; 
            let remQty = 0;  
            let total_without_disc_details = 0;
            let total_with_disc_details = 0;
            let discount_value = 0;
            let total_disc = 0;
            this.formData.items.forEach((elem,index)=>{
                if(elem.quantity!=undefined && elem.quantity!=null && Number(elem.quantity)>0)
                {
                    //count =count+1;
                    quantity = quantity+Number(elem.quantity);
                }
                if(elem.freeQty!=undefined && elem.freeQty!=null && Number(elem.freeQty)>0)
                {
                    //count =count+1;
                    freequantity = freequantity+Number(elem.freeQty);
                }
                if(elem.remQty!=undefined && elem.remQty!=null && Number(elem.remQty)>0)
                {
                    //count =count+1;
                    remQty = remQty+Number(elem.remQty);
                }
                if(elem.withoutDisc!=undefined && elem.withoutDisc!=null && Number(elem.withoutDisc)>0)
                {
                    //count =count+1;
                    total_without_disc_details = total_without_disc_details+Number(elem.withoutDisc);
                }
                
                if(elem.discount_value!=undefined && elem.discount_value!=null && Number(elem.discount_value)>0)
                {
                    
                    discount_value = discount_value+Number(elem.discount_value);
                }
                total_with_disc_details = total_with_disc_details+Number(document.getElementById("item_product_withDisc_"+index).innerHTML);
                let discValue = Number(document.getElementById("item_product_disc_value_"+index).value);
                total_disc = total_disc + (discValue >= 0 ? discValue : 0);
            })
            console.log("total_disc=>",total_disc);
           // document.getElementById("total_prod_count").value="Total : "+count+" Product";
            document.getElementById("total_quantity_details").value = quantity.toFixed(0);
            document.getElementById("total_free_quantity_details").value = freequantity.toFixed(0);
            document.getElementById("total_remQty_quantity_details").value = (quantity-freequantity).toFixed(0);
            document.getElementById("total_with_disc_details").value = total_with_disc_details.toFixed(2);
            // document.getElementById("total_discount_text1").innerHTML = discount_value.toFixed(2);
            document.getElementById("total_disc_amount").value = total_disc.toFixed(2);
            
            
            
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
                const amount = Number(this.formData.items[index].amount);
                const discount =   Number(this.formData.items[index].discount_value) ;
                if(amount>0){
                const cgst = Number(this.formData.items[index].cgst) ;
                const sgst = Number(this.formData.items[index].sgst) ;
                document.getElementById('cgst_total_text').innerHTML= ((cgst/100)*(amount-discount)).toFixed(2);
                document.getElementById('sgst_total_text').innerHTML= ((sgst/100)*(amount-discount)).toFixed(2);
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
            console.log(event.target.id)
            if (event.which == 9 ||  event.key == "Tab") { event.preventDefault(); }
            
            if (event.target.id == 'item_product_quantity_' + index && (event.keyCode == 13 || event.keyCode == 9) && Number(this.formData.items[index].quantity) > 0) {
                console.log("Enter key pressed")
                document.getElementById('item_product_freeQty_' + index).focus();
                event.preventDefault();
                return false;

            }
            else if (event.target.id == 'item_product_freeQty_' + index && (event.keyCode == 13 || event.keyCode == 9)) {
                console.log("Enter key pressed")
                document.getElementById('item_product_disc_type_' + index).focus();
                event.preventDefault();
                return false;

            }
            else if (event.keyCode == 119) {
                this.saveSalesEntry()
            }
            else if (event.keyCode == 27) {
                this.showconfirm(event);
            }
            else if (event.key == "Delete") {
                event.preventDefault(); 
                this.deleteConfirmation(index);

            }  
        },
        addressEvent(event)
        {
            console.log("Address field key code =", event.keyCode)

            if((event.keyCode==119 || event.keyCode==13)&& (this.formData.address!=undefined  && this.formData.address!=null && this.formData.address!="" ))
            {
                event.preventDefault();
                this.saveSalesEntry();
            }
            else if((event.keyCode==119 || event.keyCode==13)&& this.formData.address==undefined )
            {

                if(this.formData.party_id==0 || this.formData.party_id=="")
                {

                    document.getElementById('invoice_date').focus();
                }
                
            }
            else if(event.keyCode==27)
            {
                this.showconfirm(event);
            }
        },

    showconfirm() {
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
                        name: `admin.stock.sales.index`,
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
        
        deleteConfirmation(index)
        {
            this.deleteIndex = index;
            var that = this;
            Modal.confirm({
                    title: 'Confirmation',
                    icon: createVNode(ExclamationCircleOutlined),
                    content: createVNode('div', { style: 'color:red;' }, 'Are you sure you want to delete the selected Record?'),
                   // autoFocusButton:'ok',
                    onOk() {
                    console.log('OK');
                        that.deleteItem();
                    },
                    onCancel() {
                    console.log('Cancel');
                    },

                });
        },
        updateDiscount(index,event)
        {
            document.getElementById('item_product_disc_' + index).readOnly = false;
            this.formData.items[index].discount_type_id = document.getElementById('item_product_disc_type_'+index).value;
            console.log(index,this.formData.items[index].discount_type_id);
            if(  document.getElementById('item_product_disc_type_'+index).value==1){
                this.formData.items[index].discount_rate=0;
                document.getElementById('item_product_disc_'+index).readOnly=true
            }
            document.getElementById('item_product_disc_'+index).focus();

            this.getQuantity(index,event);
        },
        

resetFormData() {
    let cgstTotal = 0;
    let sgstTotal = 0;
    let grandTotals = 0;
    let Totaldisc = 0;

    // Now reset the form data
    this.formData = {
        order_date: new Date().toISOString().split("T")[0],
        invoice_date: new Date().toISOString().split("T")[0],
        party_id: 0,
        product_id: "",
        party_product_name: "",
        party_name: "",
        party_customer_id: 0,
        party_customer_name: null,
        party_customer_mobile: null,
        bill_number: "",
        invoice_number: "",
        tax_amount: 0,
        discount: 0,
        total: 0,
        due_amount: 0,
        order_status: "processing",
        total_items: 0,
        total_quantity: 0,
        subtotal: 0,
        payment_id: 0,
        selectedInvoice: localStorage.getItem("selectedInvoice"),
        items: Array.from({ length: 10 }, (_, index) => ({
            index: index + 1,
            item_id: null,
            item_name: null,
            unit_id: null,
            quantity: "",
            free: "",
            mrp: null,
            single_unit_price: null,
            discount_type_id: null,
            discount_value: 0,
            discount_rate: null,
            amount: null,
            maxquantity: 0,
            max_single_unit_price: 0,
            packing: null,
            cgst: null,
            sgst: null,
            unique: Math.random().toString(36).substring(2, 7),
        })),
        invoiceitems: [
            { index: 1, item_id: null, item_name: null, unit_id: null, quantity: "", free: "", mrp: null, single_unit_price: null, discount_type_id: null, discount_value: 0, discount_rate: null, amount: null, maxquantity: 0, max_single_unit_price: 0, packing: null }
        ],
        additems: [
            { index: 1, item_id: null, item_name: null, unit_id: null,  },
            { index: 2, item_id: null, item_name: null, unit_id: null,},
            { index: 3, item_id: null, item_name: null, unit_id: null,}
                ],
        total_discount: 0,
        invoice_value: 0,
        index: 0,
    };

    // Update cgst_total_text element
    document.getElementById("cgst_total_text").textContent = cgstTotal.toFixed(2);
    document.getElementById("sgst_total_text").textContent = sgstTotal.toFixed(2);
    document.getElementById("grand_total").textContent = grandTotals.toFixed(2);
    document.getElementById("total_discount_text").textContent = Totaldisc.toFixed(2);

    // Reset the value of the total_prod_count input field
    const totalProdCountInput = document.getElementById("total_prod_count");
    if (totalProdCountInput) {
        totalProdCountInput.value = (0).toFixed(2);
    }

    const totalQualityInput = document.getElementById("total_quantity_details");
    if (totalQualityInput) {
        totalQualityInput.value = (0).toFixed(2);
    }

    const totalInput = document.getElementById("total_goods_value");
    if (totalInput) {
        totalInput.value = (0).toFixed(2);
    }

    // Reset dynamically generated IGST amounts
    const igstInputs = document.querySelectorAll('[id^="igst_amount_"]');
    igstInputs.forEach((input) => {
        input.value = (0).toFixed(2);  // Reset the value of each IGST input
    });
},
    computed: {
        grandTotal() {
            return this.formData.items.reduce((total, item) => total + Number(item.amount), 0);
        }
    }
}
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



.einvoice{

background-color:white;

font-size:13px;

border: solid 1px #00000033;

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
  .ant-notification-notice{background-color: rgb(233, 236, 141) !important;}
  .ant-notification-notice-message > span{ color:#fff !important}
  .page-content-sub-header{padding: 0px !important;}
  .ant-card-body{padding-top:10px !important}
  .ant-form-item .ant-form-item-label >label{font-size:12px !important}
  .ant-form-vertical .ant-form-item-label{padding: 0px !important;}
  .ant-input{font-size:13px !important; line-height: 1.2 !important;}
  td > .ant-input{border: none !important;
  border-radius: 0px !important; font-size:11px !important;
  }
  .responsive-table th, .responsive-table td {
    padding: 0px !important;
    font-size:12px;
  }
  .invoicevalues{
    background-color: #92b4b5 !important;
  }

select {
	appearance: none;
}
.breadcrumb-header .page-content-sub-header{ padding:2Px !important}
.ant-layout-header{ line-height:0px !important; }
.page-content-container .ant-card-body, .iThjOW .email-page-content-container .ant-card-body {
    padding: 0px;
    margin: 0px 16px 0;
    padding-top: 3px;
}
</style>

