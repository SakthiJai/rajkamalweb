<template>
    <AdminPageHeader>
        <template #header>
            <!--- @closed="handleClose"-->
            <ProductModal v-if="isProuctsModalVisible" :visible="isProuctsModalVisible" :formData="formData" :url="url"
                :successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess"  @closed="handleProductModal"
                 v-on:productclose-method="handleClose"  v-on:child-method="updateProduct" />
           <!-- payments modal-->
             <SalesReturnRecent v-if="isSalesReturnRecentVisible"  :visible="isSalesReturnRecentVisible" :formData="invoiceData" :url="url":successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess" @closed="handleClosePayments"  v-on:child-method="getProducts" :bill-value="formData.total" :bill-number="invoiceData"
               />
             <!-- end payments modal-->

        <!--- purchase print modal-->
        <PurchaseReturnPrintModel
        v-if="isPurchaseReturnModalVisible"
        :visible="isPurchaseReturnModalVisible"
        :formData="formData"
        :url="url"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handleClosePurchasePrint"
    />


        <!-- end purchase print modal-->

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
    <a-spin :spinning="spinning">
    <admin-page-table-content>



        <a-card class="page-content-container mt-5 mb-5">
            <a-form layout="vertical">
                <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
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

                                <!-- <a-input text v-model="formData.payment_id" id="payment_id" @blur="" />  -->
                        </a-form-item>
                    </a-col>
                    <!--- modal-->
                    <SalesModel v-if="isModalVisible" :visible="isModalVisible" :formData="formData" :url="url"
                        :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                        @addEditSuccess="handleSuccess" @closed="handleCloseCustomer" v-on:child-method="updateParent"  v-on:close-method="focusEelment" />

                    <InvoiceItemSelectModel v-if="isInvoiceItemSelectModel" :visible="isInvoiceItemSelectModel" :invoice-list="invoiceData.invoiceItems" :url="url"
                        :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                        @addEditSuccess="handleSuccess" @closed="handleCloseCustomer" v-on:select-method="updateItems"  v-on:close-method="focusEelment" />
                    <!-- end new modal-->

                    <!-- sales modal number -->
                    <SalesCustomerModel v-if="isCustomerVisible" :visible="isCustomerVisible" :formData="formData"
                        :url="url" :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                        @addEditSuccess="handleSuccess" @closed="handleSalesCustomer"
                        v-on:cutomer-method="updateCustomer" />
                    <!-- sales modal number -->

                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock._note')" name="bill_number"
                            :help="rules.bill_number ? rules.bill_number.message : null"
                            :validateStatus="rules.bill_number ? 'error' : null" class="required">

                            <input v-model="formData.bill_number" id="form_item_bill_number" readonly tabindex="-1" @keydown="validateMobile($event,index)"
                                @input="formData.bill_number = $event.target.value"  class="ant-input css-dev-only-do-not-override-wosfq4"
                                :placeholder="$t('stock.cr_note', [$t('stock.bill_number')])" />
                        </a-form-item>
                </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                      <!---->  <a-form-item :label="$t('stock.entry_date')" name="order_date"
                            :help="rules.order_date ? rules.order_date.message : null"
                            :validateStatus="rules.order_date ? 'error' : null">

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
                                    <tbody id="itemtablepurchasereturn">
                                        <tr v-for="(header, index) in formData.items" :key="index">

                                            <td style="width:20%">
                                                <input  autocomplete="off"
                                                v-model="formData.items[index].item_name"
                                                :ref="`input-${index}`"
                                                :id="`item_product_name_${index}`"
                                                @focus="updateTotalProd(index),updateFree()"
                                                name="party_name" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                @keydown="showProductModal($event,index)" style="color:black;font-weight:bolder;"
                                                >
                                                <a-input hidden :id="`item_product_id_${index}`" v-model="formData.items[index].item_id" />
                                                <a-input hidden  :id="`igst_item_${index}`"/>
                                                <input  hidden autocomplete="off" tabindex="-1" :id="`cgst_tax_${index}`" v-model="formData.items[index].cgst">
                                                <input  hidden autocomplete="off" tabindex="-1" :id="`sgst_tax_${index}`" v-model="formData.items[index].sgst">
                                            </td>

                                            <td style="width:15%">
                                                <input disabled autocomplete="off" tabindex="-1"
                                                :id="`item_product_packing_${index}`"
                                                v-model="formData.items[index].packing"
                                                 style="color:black;font-weight:bolder;"
                                                readonly class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >
                                            </td>

                                            <td style="width:10%">
                                                <input  autocomplete="off"
                                                :id="`item_product_quantity_${index}`"
                                                v-model="formData.items[index].quantity"  @keyup="gotoNext(index,$event)"
                                                @input="getQuantity(index,$event)" @focus="getQuantity(index,$event),updateFree(),focusinputvalue($event)"
                                                name="quantity[]" @blur="updateAgg(),checkMaxQuantity(index,$event)" style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"

                                                @keypress="onlyForCurrency"
                                                >
                                            </td>

                                            <td style="width:10%">
                                                <input  autocomplete="off"
                                                :id="`item_product_free_${index}`"
                                                v-model="formData.items[index].free"  @keyup="gotoNext(index,$event)"
                                                @input="getQuantity(index,$event)" @focus="getQuantity(index,$event),updateFree(),focusinputvalue($event)"
                                                name="free[]" @blur="updateFree(),checkMaxQuantity(index,$event)" style="color:black;font-weight:bolder;text-align-last:right;"class="ant-input css-dev-only-do-not-override-wosfq4"

                                                @keypress="onlyForCurrency"
                                                >
                                            </td>


                                            <td style="width:15%">
                                                <input  autocomplete="off"
                                                v-model="formData.items[index].single_unit_price" @focus="updateFree(),focusinputvalue($event)"
                                                :ref="`input-${index}`" @input="getQuantity(index,$event)"
                                                :id="`item_product_price_${index}`" @keydown="checkSingleItemPrice(index,$event)"

                                                name="single_unit_price"class="ant-input css-dev-only-do-not-override-wosfq4"
                                                 style="color:black;font-weight:bolder;text-align-last:right;"

                                                >

                                            </td>

                                            <td style="width:15%">
                                                <input readonly  autocomplete="off"
                                                :id="`item_product_disc_${index}`"
                                                @input="getDiscount(index,$event)"
                                                @focus="updateFree(),focusinputvalue($event)"
                                                @keydown="checkDisc(index,$event)"
                                                v-model="formData.items[index].discount_rate"
                                                class="ant-input css-dev-only-do-not-override-wosfq4"
                                                name="party_name" style="color:black;font-weight:bolder;text-align-last:right;"

                                                ></input>
                                                <a-input hidden :id="`item_discount_total_${index}`"/>
                                            </td>

                                            <td style="width:15%">
                                                <input
                                                disabled=true


                                                :id="`item_product_amount_${index}`"
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

                                            <td style="width:15%">
                                                <input
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%">
                                                <input  id="total_quantity_details"
                                                value="0.00"
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%">
                                                <input  id="total_free_details"
                                                value="0.00"
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:15%">
                                                <input
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>

                                            </td>

                                            <td style="width:15%">
                                                <input
                                                disabled=true value="Goods Value"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:15%">
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
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
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
                                                &nbsp;{{ header }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in additems" :key="index" :class="{ highlight: index === selectedIndex }">
                                            <td style="font-weight:bolder;font-size: 13px;width:40%">&nbsp;{{ item.name }}</td>
                                            <td style="text-align:right;font-weight:bolder;width:10%">{{ item.location }}</td>
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
                            <a-button type="button" id="saveF10" class="backgrounds" :loading="loading" @click="saveSalesReturnEntry($event)" block>
                                <span class="shortcut" id="shortcut">
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
                            <a-button ref="closeBtn" class="savecolse" @click="showconfirmReturn()"   block>
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
                <span  class="shortcut ng-star-inserted"><code>F10</code></span>
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
 import PurchaseReturnPrintModel from "./PurchaseReturnPrintModel.vue";
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
        PurchaseReturnPrintModel,
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

       let spinning=ref(false)
        let selectedItermIndex = ref('');
        let formData = ref("Unknown")
        let invoiceData = ref("Unknown")
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
                tax_amount:0,
                discount:0,
                total:0,
                due_amount:0,
                order_status:"processing",
                total_items:0,
                total_quantity:0,
                free:0,
                subtotal:0,
                payment_id:0,
                selectedInvoice:localStorage.getItem("selectedInvoice"),
                items:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:2,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:3,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:4,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:5,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:6,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:7,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:8,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:9,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                    {index:10,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null,selected:false,cgst:null,sgst:null},
                ],

                invoiceitems:[
                    {index:1,item_id:null,item_name:null,unit_id:null,quantity:'',free:'',mrp:null,single_unit_price:null,discount_rate:null,amount:null,maxquantity:0,max_single_unit_price:0,packing:null},
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
            selectedInvoice:localStorage.getItem("selectedInvoice"),
            additems: [
                { name: 'CGST Output', location: '', value: '' },
                { name: 'SGST Output', location: '', value: '' },
                { name: '', location: '', value: '' }],
            isNumberVisible: false,
            isSalesReturnRecentVisible:false,
            isModalPopup:false,
            isCustomerVisible: false,
            isModalVisible: false,
            isInvoiceItemSelectModel:false,
            isProuctsModalVisible: false,
            deleteConfirmationPopup:false,
            stockDateColor: '',
			isNumberVisible:false,
            isPrintModalVisible: false,
            isPurchaseReturnModalVisible:false,
            items: [
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
            ],
            invoiceitems:[
                { quantity: "0.00",
                    free:"0.00",
                  }

            ],

            isOpensave: false,

            dropsitems: [
        { text: "Save & SMS", icon: `<svg height="14" viewBox="0 0 1024 1024"><path fill="currentColor" d="M256 448h512v128h-512zM256 256h512v128h-512zM960 0h-896c-35.376 0-64 28.624-64 64v704c0 35.376 28.658 64 64 64h128v192l288-192h480c35.376 0 64-28.624 64-64v-704c0-35.376-28.658-64-64.002-64zM896 704h-416l-160 96v-96h-192v-576h768v576z"></path></svg>`, isSvg: true },
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
            headers: ['Product', 'Packing', 'Qty','Free', 'Rate', 'Disc %', '₹ Amount'],
            addtionaldetalisheader:['Additional Details', '%', '₹ Amount'],
        };
    },

    mounted() {

        window.addEventListener('keydown', this.handleGlobalKeydown);
        this.autoFocusInput();
        this.getInvoiceDetails();

    },
   beforeUnmount() {
        window.removeEventListener('keydown', this.handleGlobalKeydown);
    },

    methods: {

        focusQuantityInput(index = 0) {
            this.$nextTick(() => {
                const quantityInput = document.getElementById(`item_product_quantity_${index}`);
                if (!quantityInput) {
                    return;
                }

                quantityInput.focus();

                setTimeout(() => {
                    this.getQuantity(index, { target: quantityInput });
                    this.updateFree();
                    this.focusinputvalue({ target: quantityInput });
                }, 0);
            });
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
            unique:Math.random().toString(36).substring(2,7)
        });
        this.formData.items.forEach((items,listindex)=>{
           items.index=listindex+1;
        })
        console.log(this.formData.items[8].unique);
        var T = document.getElementById('itemtablepurchasereturn');
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

        handleGlobalKeydown(event) {
            // 🔥 Prevent opening multiple modals
            if (document.querySelector('.ant-modal-confirm')) {
                return;
            }

            if (event.key === "Escape") {
                event.preventDefault();
                this.showconfirmReturn();
            }
        },

      getInvoiceDetails(){

        //console.log("selectedInvoice inside method",this.selectedInvoice);
        if(this.selectedInvoice!="null")
        {
            this.formData.bill_number=this.selectedInvoice;
            this.spinning = true;
            axiosAdmin
            .get("sales/getpurchaseInvoiceDetails/"+this.selectedInvoice)
            .then(response => {  //console.log(response)
                // Toastr Notificaiton

                this.formData.party_id=response.data.invoiceData.party_id;

                this.formData.party_name=response.data.invoiceData.party_name,
                // this.formData.party_customer_id=response.data.customerData.id,
                // this.formData.customer_name=response.data.customerData.cus_name,
                // this.formData.party_customer_mobile=response.data.customerData.mobile_number,
                // this.formData.address = response.data.customerData.address;
                this.spinning= false;
                if(response.data.invoiceItems && response.data.invoiceItems.length>0)
                {
                    let finalIndex  =   0;
                    let grand_total =   0;
                    let total_disc  =   0;
                    let totalcgst   =   0;
                    let totalsgst   =   0;
                    let total_gst    =   0;
                    let total_cgst   = 0;
                    let total_sgst   = 0 ;
                    response.data.invoiceItems.forEach((data,index)=>{

                        this.formData.items[index].item_id                  = data.product_id;
                        this.formData.items[index].item_name                = data.product_name;
                        this.formData.items[index].unit_id                  = "";
                        this.formData.items[index].quantity                 = this.formatNumber(data.quantity);
                        this.formData.items[index].free                 = this.formatNumber(data.free);
                        this.formData.items[index].mrp                      = this.formatNumber(data.mrp);
                        this.formData.items[index].single_unit_price        = this.formatNumber(data.single_unit_price);
                        console.log("single_unit_price",this.formData.items[index].single_unit_price);
                        this.formData.items[index].discount_rate            = data.discount_rate;
                        this.formData.items[index].amount                   = this.formatNumber(data.subtotal);
                        this.formData.items[index].maxquantity              = this.formatNumber(data.stock);
                        this.formData.items[index].max_single_unit_price    = this.formatNumber(data.mrp);
                        this.formData.items[index].packing                  = data.pack;
                        this.formData.items[index].cgst                     =  (data.cgst >= 0 ? data.cgst : 0);
                        this.formData.items[index].sgst                     =  (data.sgst >= 0 ? data.sgst : 0);
                        total_cgst                                           = (Number(data.subtotal)/100) * (data.cgst >= 0 ? data.cgst : 0);
                        total_sgst                                           = (Number(data.subtotal)/100) * (data.sgst >= 0 ? data.sgst : 0);
                        total_gst                          = total_cgst + total_sgst;

                        grand_total                                         = grand_total+Number(data.subtotal) + total_gst;
                        total_disc                                          =  total_disc+Number(data.discount_rate);
                        totalcgst                                           = totalcgst+ (data.cgst >= 0 ? data.cgst : 0);
                        totalsgst                                           =  totalsgst+ (data.sgst >= 0 ? data.
                        sgst : 0);
                        console.log("cgst",totalcgst,"sgst",totalsgst)
                        document.getElementById('item_product_price_'+index).value=this.formatNumber(data.single_unit_price)
                        document.getElementById('item_product_amount_'+index).value=this.formatNumber(data.subtotal)

                        document.getElementById('item_product_disc_'+index).value=this.formatNumber(data.discount_rate)
                        document.getElementById('item_discount_total_'+index).value=data.discount_rate;

                        finalIndex= index

                    })

                    total_disc = total_disc>0?(total_disc/100)*grand_total:0;

                    const subtotalElem = document.getElementById('total_goods_value');
                    const igstAmount0Elem = document.getElementById('igst_amount_0');
                    const igstAmount1Elem = document.getElementById('igst_amount_1');
                    const grandTotalElem = document.getElementById('grand_total');
                    if (subtotalElem && igstAmount0Elem && igstAmount1Elem && grandTotalElem) {
                        // Parse values, removing commas and converting to numbers
                        const subtotal = parseFloat((subtotalElem.value || '0').replace(/,/g, ''));
                        const igst0 = parseFloat((igstAmount0Elem.value || '0').replace(/,/g, ''));
                        const igst1 = parseFloat((igstAmount1Elem.value || '0').replace(/,/g, ''));
                        const grandTotal = subtotal + igst0 + igst1;
                        grandTotalElem.innerHTML = this.formatCurrency(isNaN(grandTotal) ? 0 : grandTotal);
                    }

                    console.log("grand_total=>",this.formatCurrency(totalsgst>0?((totalsgst/2)/100)*grand_total:0));
                    this.selectedItermIndex=(finalIndex);

                    console.log(this.formData.items)
                    this.$nextTick(() => {
                        response.data.invoiceItems.forEach((item, itemIndex) => {
                            this.getQuantity(itemIndex, null);
                        });

                        this.focusQuantityInput(0);
                    });


                }

            })
            .catch(errorResponse => {
                this.spinning= false;
            })
        }

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

            this.isPrintModalVisible = false;
        },

        handleClosePurchasePrint(){
            this.isPurchaseReturnModalVisible = false;
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
        else if ((event.keyCode !== 13 && event.keyCode !== 9) && this.formData.party_id>0) {
         this.isModalVisible = true;
         return false;

        }
    },

    handleClosepopup(){
        this.deleteConfirmationPopup=false;
        },


    // handleKeyDownPurchase(event) {
    //   if (event.key === 'Escape' || event.keyCode === 27) {
    //      this.isModalVisible = false;
    //      this.autoFocusInput();
    //   }
    //   else if (event.key === 'F10') {
    //     this.saveSalesReturnEntry();
    //   }
    //   else if ((event.keyCode === 13 || event.keyCode === 9) && this.formData.party_id>0) {
    //      this.isModalVisible = false;
    //      document.getElementById('item_product_name_'+this.selectedItermIndex).focus();
    //     ////console.log("sele ",this.selectedItermIndex)
    //   }
    //   else if ((event.keyCode !== 13 && event.keyCode !== 9) && this.formData.party_id>0)
    //   {
    //     this.isModalVisible = false;
    //   }
    // },

    handleKeydownDatePicker(event) {

        if(event.keyCode==27)
      {
        this.showconfirmReturn();
      }
      else if ((event.key === 'Tab' || event.key === 'Enter') && event.target.value=="") {
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
        ////console.log("Payment called");
        this.isSalesReturnRecentVisible = true;
        document.getElementById('amount_0').focus();
        $event.preventDefault();
    },
    handleClosePayments(){
        this.isSalesReturnRecentVisible = false;
        // this.isPurchaseReturnModalVisible= true;
        this.isInvoiceItemSelectModel= true;

        document.getElementById('billing_print').focus();
    },

        inputData($event) {
            // Value will update reactively
            $event.target.value;
        },
        showNumberModal(event) {
            //console.log("Open Model",event.keyCode);
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
                document.getElementById("item_product_name_0").focus();
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

        handleCloseCustomer() { ////console.log("Close 1",this.formData.customer_name);
        this.invoiceData = null;
            this.isModalVisible = false;
            this.spinning= true;
                const billNumberUrl = `sales/crNumber/`+this.formData.party_id;
                axiosAdmin.get(billNumberUrl).then((response) => {
                //console.log(response)
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


       showconfirmReturn() {
        let that = this;

        const modal = Modal.confirm({
            title: "Confirmation",
            icon: createVNode(ExclamationCircleOutlined),
            content:
            "Transaction data will be lost. Are you sure you want to close?",
            okText: "OK",
            cancelText: "Cancel",
            autoFocusButton: "cancel",

            onOk() {
            window.removeEventListener("keydown", handleKeydown);

            localStorage.setItem("selectedInvoice", null);

            that.$router.push({
                name: `admin.stock.purchases.Returnindex`,
            });
            },
            onCancel() {
            window.removeEventListener("keydown", handleKeydown);

            that.$nextTick(() => {
                that.$refs.closeBtn?.focus();
            });
            },
        });

        const handleKeydown = (e) => {
            const buttons = document.querySelectorAll(
            ".ant-modal-confirm .ant-btn"
            );

            const cancelBtn = Array.from(buttons).find((btn) =>
            btn.classList.contains("ant-btn-default")
            );

            const okBtn = Array.from(buttons).find((btn) =>
            btn.classList.contains("ant-btn-primary")
            );

            if (e.key === "ArrowRight" && okBtn) {
            e.preventDefault();
            okBtn.focus();
            }
            if (e.key === "ArrowLeft" && cancelBtn) {
            e.preventDefault();
            cancelBtn.focus();
            }

           if (e.key === "Escape") {
            e.preventDefault();
            modal.destroy();
            window.removeEventListener("keydown", handleKeydown);

            that.$nextTick(() => {
                that.$refs.closeBtn?.focus();
            });
            }

            if (e.key === "Enter") {
            const active = document.activeElement;
            if (active) active.click();
            }
        };

        window.addEventListener("keydown", handleKeydown);
        },
        focusSearchInput() {
            this.$refs.searchInput.focus(); 
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
            this.formData.party_id = selectedParty.id.toString();
            this.formData.party_name = selectedParty.name;
            ////console.log("hello child" + this.formData.party_id)
            document.getElementById("party_id").value = this.formData.party_id;
            document.getElementById("form_item_name").value = this.formData.name;
            document.getElementById("form_item_party_name").value = this.formData.party_name;

            if(this.formData.bill_number==undefined || this.formData.bill_number=="")
            {

            }

        },
         openProduct(data)
        {
            console.log("Open Product method",data);
            this.invoiceData.invoiceItems = data.invoiceItems;
             console.log( this.invoiceData.invoiceItems);
                this.spinning = false;
                this.isInvoiceItemSelectModel = true
        },

        getProducts(selectedParty){
            console.log("selectedParty",selectedParty);
             this.spinning= true;
            axiosAdmin
            .get("sales/getInvoiceItems/"+selectedInvoice, )
            .then(response => {  console.log(response)
                // Toastr Notificaiton

                this.openProduct(response.data);

            })
            if(selectedParty=="manual")
            {
                this.isSalesReturnRecentVisible = false;
                document.getElementById("item_product_name_0").focus();
            }
            else if(selectedParty=="single")
            {
                console.log(this.invoiceData)
                // this.isInvoiceItemSelectModel =! this.isInvoiceItemSelectModell
                /**/
            }

        },


        updateItems(selectedKeys)
        {
            let list =[];
            this.spinning= true;
            this.isSalesReturnRecentVisible = false;
            selectedKeys.forEach((element)=>{   list.push(Number(element));});
            console.log(list);
            this.isInvoiceItemSelectModel =false;
            if(this.invoiceData.invoiceItems && this.invoiceData.invoiceItems.length>0)
                {
                    let finalIndex  =   0;
                    let grand_total =   0;
                    let total_disc  =   0;
                    let totalcgst   =   0;
                    let totalsgst   =   0;
                    let total_sgst = 0;
                    let total_cgst  = 0 ;
                    let total_gst   = 0;
                    let sub_total_amount = 0;
                    this.invoiceData.invoiceItems.forEach((data,index)=>{
                        console.log(data.product_id,list.indexOf(data.product_id));
                        if(list.indexOf(data.product_id)!=-1 && this.formData.items[index].selected==false){
                        this.formData.items[index].item_id                  = data.product_id;
                        this.formData.items[index].item_name                = data.product_name;
                        document.getElementById('item_product_name_'+index).value=data.product_name
                        this.formData.items[index].unit_id                  = "";
                        this.formData.items[index].quantity                 = this.formatNumber(data.quantity);
                        document.getElementById('item_product_quantity_'+index).value=data.quantity;
                        this.formData.items[index].free                 = "0.00";
                        document.getElementById('item_product_free_'+index).value=data.free;
                        this.formData.items[index].mrp                      = this.formatNumber(data.mrp);
                        this.formData.items[index].single_unit_price        = this.formatNumber(data.single_unit_price);
                        console.log("this.formatNumber(data.single_unit_price)",this.formatNumber(data.single_unit_price));
                        document.getElementById('item_product_price_'+index).value=this.formatNumber(data.single_unit_price)
                        this.formData.items[index].discount_rate            = data.discount_rate;
                        this.formData.items[index].amount                   = this.formatNumber(data.subtotal);
                        this.formData.items[index].maxquantity              = this.formatNumber(data.stock);
                        this.formData.items[index].max_single_unit_price    = this.formatNumber(data.mrp);
                        this.formData.items[index].packing                  = data.pack;
                        this.formData.items[index].cgst                     = (data.product?.cgst >= 0 ? data.product.cgst : 0);
                        this.formData.items[index].sgst                     = (data.product?.sgst >= 0 ? data.product.sgst : 0);
                        this.formData.items[index].selected                 = true;
                        total_disc                                          =  total_disc+Number(data.discount_rate);
                        totalcgst                                           =  totalcgst+ (data.product?.cgst >= 0 ? data.product.cgst : 0);
                        totalsgst                                           =  totalsgst+ (data.product?.sgst >= 0 ? data.product.sgst : 0);
                        total_cgst     =   (Number(data.subtotal) / 100 ) * totalcgst;
                        total_sgst     =    (Number(data.subtotal) / 100) * totalsgst ;
                        total_gst      =  total_cgst + total_sgst ;
                        console.log("total_gst", total_gst)
                        sub_total_amount      = Number(data.subtotal);
                        grand_total                                         = Number(data.subtotal) + total_gst ;
                        console.log("totalcgst",totalcgst,"sgst",totalsgst)

                        document.getElementById('item_product_amount_'+index).value=this.formatNumber(data.subtotal)
                        document.getElementById('item_product_packing_'+index).value=data.pack

                        document.getElementById('item_product_disc_'+index).value=this.formatNumber(data.discount_rate)
                        document.getElementById('item_discount_total_'+index).value=data.discount_rate;

                        finalIndex= index
                        }

                    })

                    total_disc = total_disc>0?(total_disc/100)*grand_total:0;
                    let temp = this;
                    setTimeout(function()
                    {
                        const totalGstAmount = (totalcgst>0?((totalcgst)/100)*sub_total_amount:0) + (totalsgst>0?((totalsgst)/100)*sub_total_amount:0)
                        console.log("totalgstamount" , totalGstAmount)
                        document.getElementById('total_goods_value').value          =    temp.formatCurrency(grand_total);
                        document.getElementById('grand_total').innerHTML            =   temp.formatCurrency((grand_total)+(totalGstAmount));
                        temp.formData.total                 =   grand_total;
                        temp.formData.tax_amount            =   totalGstAmount;
                        temp.formData.total_discount        =   total_disc;
                        temp.formData.total_items           =   finalIndex+1;

                        document.getElementById('total_discount_text').innerHTML    =   temp.formatCurrency(total_disc);
                        document.getElementById('cgst_total_text').innerHTML        =    temp.formatCurrency(totalcgst>0?((totalcgst/2)/100)*grand_total:0)
                        document.getElementById('sgst_total_text').innerHTML        =    temp.formatCurrency(totalsgst>0?((totalsgst/2)/100)*grand_total:0)

                        document.getElementById('igst_amount_0').value              =   temp.formatCurrency(totalcgst>0?((totalcgst)/100)*grand_total:0);
                        document.getElementById('igst_amount_1').value              =   temp.formatCurrency(totalsgst>0?((totalsgst)/100)*grand_total:0);
                        temp.spinning= false;

                    },2000);

                    console.log("<>",this.formatCurrency((grand_total-total_disc)+(totalsgst>0?((totalsgst/2)/100)*grand_total:0)));

                    this.selectedItermIndex=(finalIndex);

                    console.log(this.formData.items)
                    this.focusQuantityInput(0)


                }
        },

        focusproductEelment()
        {
            this.focusQuantityInput(0);
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
            //console.log('Cgst Tax',selectedParty);
            this.formData.items[this.selectedItermIndex].item_id= selectedParty.id
                 this.formData.items[this.selectedItermIndex].item_name= selectedParty.name
                 this.formData.items[this.selectedItermIndex].single_unit_price= selectedParty.single_unit_price;
                 this.formData.items[this.selectedItermIndex].max_single_unit_price= selectedParty.single_unit_price;
                 this.formData.items[this.selectedItermIndex].quantity= 0;
                 this.formData.items[this.selectedItermIndex].free= 0;

                 this.formData.items[this.selectedItermIndex].maxquantity= selectedParty.quantity;
                 this.formData.items[this.selectedItermIndex].packing= selectedParty.packing;
                 this.formData.items[this.selectedItermIndex].cgst= selectedParty.cgst;
                 this.formData.items[this.selectedItermIndex].sgst= selectedParty.sgst;
                 ////console.log(this.formData.items[this.selectedItermIndex].packing);
                 document.getElementById("item_product_name_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_name;
                 document.getElementById("item_product_packing_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].packing;
                 document.getElementById("item_product_id_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_id;
                 document.getElementById("item_product_price_"+this.selectedItermIndex).value = this.formatNumber(this.formData.items[this.selectedItermIndex].single_unit_price);
                 console.log("this.formatNumber(data.single_unit_price)",this.formatNumber(this.formData.items[this.selectedItermIndex].single_unit_price))
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].quantity;
                document.getElementById("item_product_free_"+this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].free;
                document.getElementById("item_product_amount_"+this.selectedItermIndex).value =0.0
                 document.getElementById("cgst_tax_"+this.selectedItermIndex).value =this.formData.items[this.selectedItermIndex].cgst;
                 document.getElementById("sgst_tax_"+this.selectedItermIndex).value =this.formData.items[this.selectedItermIndex].sgst;
                 document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                 document.getElementById("item_product_free_"+this.selectedItermIndex).focus();

        },

        showProductModal(event,index) {
            this.selectedItermIndex = index;
            console.log('=>', index,event.key,event.keyCode ,this.formData.items[index].item_id)

            if(event.keyCode==121)
                {
                    this.saveSalesReturnEntry();
                }
                else if(event.keyCode==27)
                {
                    this.showconfirmReturn();
                }
            else if(event.keyCode==46 || event.keyCode==8)
                {
                    this.deleteConfirmationPopup= true;
                }
               else if(this.formData.party_name!="" && index>0 &&  event.key == 'Enter')
                 {
                    if(this.formData.items[index].item_id==undefined || this.formData.items[index].item_id=="" || this.formData.items[index].item_id==null)
                    {
                        this.isProuctsModalVisible = true;
                    }
                    else if(this.formData.items[index].item_id>0 && event.keyCode==13)
                    {
                        console.log("TTT");
                        document.getElementById("item_product_quantity_"+index).focus();
                        event.preventDefault();
                        document.getElementById("item_product_free_"+index).focus();
                    }
                 }
                else if(this.formData.party_name!="" && index>0 &&  event.key == 'Tab')
                {
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
                        document.getElementById("item_product_name_"+index).focus();
                        event.preventDefault();
                    }
                    else if(this.formData.items[index].item_id>0 && (event.keyCode!=9 && event.keyCode!=13))
                    { console.log("BBB");
                        this.isProuctsModalVisible = true;
                        event.preventDefault();
                    }
                    else
                    {
                        this.saveSalesReturnEntry();
                    }


                }
                else if(this.formData.party_name!="" && index==0 && event.key!="F10")
                {

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
                else if(this.formData.party_name!="" && index>0 && (event.keyCode !== 9 && event.keyCode!=121 && event.key!="F10")  )
                { ////console.log("POP Pay 2");
                    this.selectedItermIndex =  index;
                    this.isProuctsModalVisible = true;

                    document.getElementById("form_item_party_name_search").focus();
                    //event.preventDefault();
                    return false;

                }
                else if(this.formData.party_name!="" && index>0 && (event.keyCode !== 9 && event.keyCode!=121) )
                {

                    if(this.formData.party_customer_mobile==undefined || this.formData.party_customer_mobile=="" || this.formData.party_customer_mobile.trim()==""){
                        document.getElementById("form_item_mobile_number").focus();
                        event.preventDefault();
                        return false;
                    }
                    else
                    {
                    this.saveSalesReturnEntry();
                    }
                }
                else if(this.formData.party_name!="" && index>0 &&  event.keyCode==121 )
                {
                    console.log("F10 focus");
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
        getTotalAmount(type)
        {
            console.log(this.formData.items)
            let total       =   0;
            let sgst        =   0;
            let cgst        =   0;
            let discount    =   0;
            this.formData.items.forEach((element)=>{

                if(element.item_id>0 && element.single_unit_price>0 && element.quantity>0)
                {
                    total = total+ Number(element.single_unit_price)*Number(element.quantity);
                }
                if(element.item_id>0 && element.sgst>0){    sgst=sgst+element.sgst; }
                if(element.item_id>0 && element.cgst>0){    cgst=cgst+element.cgst; }
                if(element.item_id>0 && element.discount_rate>=0){  discount=discount+Number(element.discount_rate);    }

            });
            if(type=="sgst")
            {
                return (sgst/100)*total;
            }
            else if(type=="cgst")
            {
                return (cgst/100)*total;
            }
            else if(type=="discount")
            {
                return (discount/100)*total;
            }
           else
           {
                return total;
           }

        },
        getQuantity(index,event) {
            // Guard against undefined item
            if (!this.formData.items[index]) {
                return;
            }

            // Get values
            let quantity = Number(document.getElementById("item_product_quantity_" + index).value) || 0;
            let freeQty = Number(document.getElementById("item_product_free_" + index)?.value) || 0;
            let price = Number(document.getElementById("item_product_price_" + index).value) || 0;
            let discount = Number(document.getElementById("item_product_disc_" + index).value) || 0;

            // Always use CGST/SGST from product object only, fallback to previous values if missing
            let cgst = 0;
            let sgst = 0;
            const item = this.formData.items[index];
            if (item.product && typeof item.product === 'object' && item.product.cgst !== undefined && item.product.sgst !== undefined) {
                cgst = Number(item.product.cgst) || 0;
                sgst = Number(item.product.sgst) || 0;
                console.log("CGST/SGST from product", index, cgst, sgst);
            } else {
                // Fallback: preserve previous CGST/SGST if product is missing
                cgst = Number(item.cgst) || 0;
                sgst = Number(item.sgst) || 0;
                console.warn("Product CGST/SGST missing, fallback to previous", index, cgst, sgst);
            }
            this.formData.items[index].cgst = cgst;
            this.formData.items[index].sgst = sgst;
            console.log("cgst", cgst , "sgst", sgst);

            // Calculation
            let effectiveQty = quantity - freeQty;
            if (effectiveQty < 0) effectiveQty = 0;
            const subtotal = effectiveQty * price;
            const discount_amount = (subtotal / 100) * discount;
            const final_amount = subtotal - discount_amount;

            // Update item
            this.formData.items[index].discount_rate = discount;
            this.formData.items[index].single_unit_price = price;
            this.formData.items[index].amount = isNaN(final_amount) ? 0 : final_amount;

            // Update UI
            const amountElem = document.getElementById("item_product_amount_" + index);
            if (amountElem) amountElem.value = this.formatCurrency(isNaN(final_amount) ? 0 : final_amount);

            // Totals
            const totalAmount = this.formData.items.reduce((sum, el) => sum + (Number(el.amount) || 0), 0);
            this.totalAmount = isNaN(totalAmount) ? 0 : totalAmount;

            const sgstElem = document.getElementById("sgst_total_text");
            if (sgstElem) {
                const sgstValue = this.formatCurrency((sgst / 100) * this.totalAmount);
                sgstElem.innerHTML = sgstValue;
                const igstAmount1Elem = document.getElementById('igst_amount_1');
                if (igstAmount1Elem) igstAmount1Elem.value = sgstValue;
            }
            const cgstElem = document.getElementById("cgst_total_text");
            if (cgstElem) {
                const cgstValue = this.formatCurrency((cgst / 100) * this.totalAmount);
                cgstElem.innerHTML = cgstValue;
                const igstAmount0Elem = document.getElementById('igst_amount_0');
                if (igstAmount0Elem) igstAmount0Elem.value = cgstValue;
            }
            const totalSgstAmount = (sgst / 100) * this.totalAmount;
            const totalCgstAmount = (cgst / 100) * this.totalAmount;
            let totalDiscount = this.formData.items.reduce((sum, el) => sum + ((el.amount && el.discount_rate) ? ((el.amount / 100) * el.discount_rate) : 0), 0);
            let invoiceValue = (this.totalAmount + totalSgstAmount + totalCgstAmount);
            if (isNaN(totalDiscount) || totalDiscount === undefined || totalDiscount === null) totalDiscount = 0;
            if (isNaN(invoiceValue) || invoiceValue === undefined || invoiceValue === null) invoiceValue = 0;

            const grandTotalElem = document.getElementById('grand_total');
            if (grandTotalElem) grandTotalElem.innerHTML = this.formatCurrency(invoiceValue);
            const totalDiscountElem = document.getElementById('total_discount_text');
            if (totalDiscountElem) totalDiscountElem.innerHTML = this.formatCurrency(totalDiscount);
            const totalGoodsElem = document.getElementById('total_goods_value');
            if (totalGoodsElem) totalGoodsElem.value = this.formatCurrency(totalAmount);

            this.formData.total = this.totalAmount;
            this.formData.tax_amount = (isNaN(totalSgstAmount) ? 0 : totalSgstAmount) + (isNaN(totalCgstAmount) ? 0 : totalCgstAmount);
            this.formData.total_discount = totalDiscount;


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

                /*notification.warning({
                        placement:  "bottomRight",
                        message: "Stock Going to be Negative!",

                    });*/

            }
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                ////console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if((event.key=="Tab" || event.key=="Enter"))
            {

                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                event.preventDefault()
                return false;
            }

            else if((event.key=="Tab" || event.key=="Enter"))
            {

                document.getElementById("item_product_free_"+this.selectedItermIndex).focus();
                event.preventDefault()
                return false;
            }


        },
        checkSingleItemPrice(index,event)
        {

           if(Number(this.formData.items[index].single_unit_price)>Number(this.formData.items[index].max_single_unit_price))
            {

                /*notification.warning({
                        placement:  "bottomRight",
                        message: "As Per M.R.P Allowed Upto : "+this.formData.items[index].max_single_unit_price,
                        //description: configObject.successMessage
                    });*/

            }
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                //console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if(( event.key=="Enter") && (Number(event.target.value)>0))
            {
               //console.log("222");
               document.getElementById("item_product_disc_"+this.selectedItermIndex).focus();
               event.preventDefault();
                return false;
            }
            else if(event.key==undefined)
            {
                document.getElementById("item_product_price_"+this.selectedItermIndex).focus();
            }


        },
        checkDisc(index,event)
        {
            ////console.log('checkDisc',this.formData.items[index].discount_rate)
            if(( event.key=="Enter") )
            {

               document.getElementById("item_product_name_"+(this.selectedItermIndex+1)).focus();
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
                        message: "Purchase  Return added successfully !    ",
                        description: response.message
                    });
            this.isPurchaseReturnModalVisible= true;

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

        // getPurchaseReturnPdf
        saveSalesReturnEntry(event)
        {
            console.log('submit',event);
            this.spinning= true;
            axiosAdmin
            .post("sales/salesPurchase", this.formData)

            .then(response => {  //console.log(response)
                // Toastr Notificaiton
                this.spinning= false;
                this.success(response);
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

        // else
        // {
        //     document.getElementById("shortcut").focus();
        //             return false;
        // }
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
            let count =0;
            let quantity =0;
            this.formData.items.forEach((elem)=>{
                if(elem.quantity!=undefined && elem.quantity!=null && Number(elem.quantity)>0)
                {//console.log("quantity",elem)
                    //count =count+1;
                    quantity = quantity+Number(elem.quantity);
                }
            })
           // document.getElementById("total_prod_count").value="Total : "+count+" Product";
            document.getElementById("total_quantity_details").value=quantity.toFixed(2);
        },
        updateFree(){
            let count =0;
            let free =0;
            this.formData.items.forEach((elem)=>{
                if(elem.free!=undefined && elem.free!=null && Number(elem.free)>0)
                {//console.log("quantity",elem)
                    //count =count+1;
                    free = free+Number(elem.free);
                }
            })
            document.getElementById("total_free_details").value=free.toFixed(2);
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
                console.log("Enter key pressed")
                document.getElementById('item_product_quantity_'+index).focus();
                event.preventDefault();
                return false;

            }
            if(event.keyCode==121 && Number(this.formData.items[index].quantity)>0)
            {
                this.saveSalesReturnEntry();
            }
        },
        // gotoNext(index,event)
        // {
        //     console.log(event.keyCode,Number(this.formData.items[index].free))
        //     if(event.keyCode==13 && Number(this.formData.items[index].free))
        //     {
        //         console.log("Enter key pressed")
        //         document.getElementById('item_product_price_'+index).focus();
        //         event.preventDefault();
        //         return false;

        //     }
        // },


        onCheckAllChange(index)
        {
            console.log("checked",index,this.formData.items[index].selected);
            this.formData.items[index].selected != this.formData.items[index].selected;
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
