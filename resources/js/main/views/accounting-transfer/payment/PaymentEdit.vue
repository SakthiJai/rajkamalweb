

<template>
    <AdminPageHeader>
        <template #header>
            <!--- @closed="handleClose"-->
            <ProductModal v-if="isProuctsModalVisible" :visible="isProuctsModalVisible" :formData="formData" :url="url"
                :successMessage="successMessage" :addEditType="addEditType" @addEditSuccess="handleSuccess"  @closed="handleProductModal"
                 v-on:productclose-method="handleClose"  v-on:child-method="updateProduct" />
        <!-- end bill return modal-->

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
         <h1 style="font-size: 16px;">Payment Voucher</h1>

        </template>
    </AdminPageHeader>
    <div id="salespaymentcreste">
    <a-spin :spinning="spinning">
    <admin-page-table-content>
        <a-card class="page-content-container mt-5 mb-5">
            <a-form layout="vertical">
                <a-row :gutter="16">

               

                    <ReciptModal v-if="isReceiptModalVisible" :visible="isReceiptModalVisible" :formData="formData" :url="url"
                    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                    @addEditSuccess="handleSuccess" @closed="handleCloseCustomer" v-on:child-method="updateParents"  v-on:close-method="focusEelment" />

                  
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row style="margin-bottom:12px;margin-top:8px">
                           <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-form-item
                          :label="$t('Payment Mode')"
                          name="receiptpayment_mode"
                          :help="rules.receiptpayment_mode ? rules.receiptpayment_mode.message : null"
                          :validateStatus="rules.receiptpayment_mode ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                <PaymentItemSearch   ref="DateInput"
                                @valueChanged="
                                  (productId) => (formData.receiptpayment_mode = productId)
                                "
                                @valueSuccess="getStockValue"
                                :productData="data"
                               @keyup="handlekeyup"
                               v-model:value="formData.receiptpayment_mode"
                                autocomplete="off"
                                 @input="validateField('receiptpayment_mode')"
                              />
                            </a-col>

                            <a-input  hidden type="date" v-model:value="formData.order_date" autocomplete="off"
                            @keyup="handlekeyup"/>
                        </a-row>


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

                                            <!-- S.NO -->
                                        <tr v-for="(prod, index) in formData.items" :key="prod.unique" :id="prod.unique">
                                            <!-- <td style="width:1%">
                                                <input autocomplete="off" tabindex="-1"
                                                :id="`item_product_packing_${index}`"

                                                 style="color:black;font-weight:bolder;"
                                                readonly class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >
                                            </td> -->
                                            <td style="width:1%">
                                                <span v-if="hasData(index)"
                                                    :style="{ 'color': focusedIndex === index ? '#ffd451' : 'black', 'font-weight': 'bolder', 'display': 'block', 'text-align': 'center' }">
                                                    {{ getSNo(index) }}
                                                </span>
                                            </td>
                                             <!-- Account -->
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

                                        <!-- Station -->
                                        <td style="width:15%">
                                                    <input autocomplete="off"
                                                        :id="`item_product_quantity_${index}`"
                                                        v-model="formData.items[index].quantity"
                                                        @keydown="handleQuantityKeyDown(index, $event)"
                                                        @input="getQuantity(index, $event)"
                                                        @focus="getQuantity(index, $event), focusinputvalue($event)"
                                                        @blur="updateAgg()"
                                                        name="quantity[]"
                                                        style="color:black;font-weight:bolder;text-align-last:right;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                        @keypress="onlyAplhaChar"
                                                    >
                                                </td>
                                                <!-- Date -->
                                                <td style="width:12%" class="datepicker">
                                                    <input type="date"
                                                        size="small"
                                                        placeholder="Date"
                                                        format="DD-MM-YYYY"
                                                        :id="`datepicker_${index}`"
                                                       
                                                        v-model="formData.items[index].receipt_date"
                                                        @keydown="(event) => { handleDateKeyDown(index, event); checkdatetoAmount(index, event); }"
                                                        @blur="updateFree(), checkMaxQuantity(index, $event)"
                                                        name="free[]"
                                                        style="color:black;font-weight:bolder;text-align-last:right;display: block !important;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                    />

                                                </td>
                                                <!-- Inst. Type -->
                                                <td style="width:10%">
                                                    <select class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                                                        v-model="formData.items[index].inst_type"
                                                        :id="`item_product_disc_${index}`"
                                                        @keydown="handleInstTypeKeyDown(index, $event)"
                                                        placeholder="Select Payment Type"
                                                        :allowClear="false"
                                                        optionFilterProp="title"
                                                        show-search
                                                        @change="gotoRate(index, $event)"
                                                    >
                                                        <option value="cash" title="Cash">Cash</option>
                                                        <option value="dd" title="Demand Draft">D.D</option>
                                                        <option value="neft" title="NEFT Transfer">NEFT</option>
                                                        <option value="rtgs" title="RTGS Transfer">RTGS</option>
                                                        <option value="imps" title="IMPS Transfer">IMPS</option>
                                                        <option value="cheque" title="Cheque Payment">Cheque</option>
                                                        <option value="na" title="Not Applicable">N/A</option>
                                                    </select>
                                                </td>
                                                <!-- Inst. No -->
                                                <td style="width:10%">
                                                    <input autocomplete="off" type="number"
                                                        v-model="formData.items[index].inst_no"
                                                        @keydown="handleInstNoKeyDown(index, $event)"
                                                        @keypress="onlyForCurrency"
                                                        :id="`item_product_price_${index}`"
                                                        name="single_unit_price"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                        style="color:black;font-weight:bolder;text-align-last:right;display:block !important;"
                                                    >
                                                </td>
                                                <!-- Amount -->
                                                <td style="width:13%">
                                                    <input type="number"
                                                        v-model="formData.items[index].amount"
                                                        @keydown="handleAmountKeyDown(index, $event)"
                                                        @focus="updateFree(index)"
                                                        @keypress="onlyForCurrency"
                                                        :id="`item_product_amount_${index}`"
                                                        name="table_total_amount[]"
                                                        style="color:black;font-weight:bolder;text-align-last:right;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                    >
                                                </td>
                                                <!-- Short Amount -->
                                                <td style="width:13%">
                                                    <input type="number"
                                                        v-model="formData.items[index].short_amount"
                                                        @keydown="handleShortAmountKeyDown(index, $event)"
                                                        @focus="updateTotalProd(index)"
                                                        @keypress="onlyForCurrency"
                                                        :id="`item_short_amount_${index}`"
                                                        name="table_total_amount[]"
                                                        style="color:black;font-weight:bolder;text-align-last:right;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                    >
                                                </td>
                                        </tr>


                                        <!--- new row-->
                                        <tr v-for="(header, index) in formData.invoiceitems" :key="index" class="invoicevalues">

                                            <td style="width:10%">
                                                <input  id="total_quantity_detaildss"
                                                disabled=false
                                                name="table_total_amounttdtt[]" style="color:#92b4b5; text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%">
                                                <input id="total_prod_count"
                                                disabled=true
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>




                                            <td style="width:10%">
                                                <input  id="total_quantity_detaildss"
                                                disabled=false
                                                name="table_total_amounttdtt[]" style="color:#92b4b5; text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%">
                                                <input  id="total_quantity_detaildss"
                                                disabled=false
                                                name="table_total_amounttdtt[]" style="color:#92b4b5; text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                            <td style="width:10%">
                                                <input  id="total_quantity_detaildss"
                                                disabled=false
                                                name="table_total_amounttdtt[]" style="color:#92b4b5; text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%">
                                                <input  id="total_quantity_detaildss"
                                                disabled=false
                                                name="table_total_amounttdtt[]" style="color:#92b4b5; text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%">
                                                <input id="total_amount" disabled=true value="0.00" name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>

                                            <td style="width:10%">
                                                <input id="total_short_amount" disabled=true value="0.00" name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
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
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    </a-col>
                </a-row>
                <a-row style="margin-top:10px">
                    <b>Balance&nbsp; ₹  :&nbsp;<span id="balance_amt" >0.00</span></b>
                </a-row>
               <!-- text arae -->
                 <a-row style="margin-top:10px">
                    <h1 style="margin-top: 20px;">Narration</h1>
                </a-row>
                <a-row >
                    <a-textarea
                    id="narrationFields"
                    v-model:value="formData.narration"
                    rows="4"
                    autocomplete="off"
                    @keydown="focusOnProductName(index, $event)"
                  />
                </a-row>
                <a-row :gutter="16" class="mt-20 mb-20">
                    <a-col :xs="24" :sm="24" :md="9" :lg="9"></a-col>
                    <a-col :xs="24" :sm="24" :md="7" :lg="7"></a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-button type="button" id="saveF8" class="backgrounds" :loading="loading" @click="saveSalesReturnEntry($event)" block>
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
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="savecolse" @click="showconfirmBill()"   block>
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
import stockManagement from "../../stock-management/purchases/stockManagement";

import common from "../../../../common/composable/common";
import fields from "../../../views/accounting-transfer/receipt/fields";
import ProductModal from '../../stock-management/purchases/Product/ProductModal.vue';
import TaxAddButton from "../../settings/taxes/AddButton.vue";
import WarehouseAddButton from "../../settings/warehouses/AddButton.vue";
import ProductAddButton from "../../product-manager/products/AddButton.vue";
import DatePicker from "../../../../common/components/common/calendar/DatePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import UserSearch from "../../stock-management/purchases/UserSearch.vue";
import FormItemHeading from "../../../../common/components/common/typography/FormItemHeading.vue";
import { some, forEach, find } from "lodash-es";
import PaymentModeAddButton from "../../settings/payment-modes/AddButton.vue";
import SalesModel from "../../stock-management/purchases/SalesModel.vue";
import SalesNumberModel from "../../stock-management/purchases/SalesNumberModel.vue";
import SalesCustomerModel from "../../stock-management/purchases/SalesCustomerModel.vue";
import PaymentsModal from "../../stock-management/purchases/PaymentsModal.vue";
import { message, notification } from "ant-design-vue";
import ReciptModal from "../../stock-management/purchases/ReciptModal.vue";
import { Modal } from 'ant-design-vue';
import { createVNode } from 'vue';
import PaymentItemSearch from "../../../../common/components/accounttrans/PaymentItemSearch.vue";


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
        DatePicker,
        AdminPageHeader,
        UserSearch,
        MinusSquareOutlined,
        FormItemHeading,
        PaymentModeAddButton,
        ProductModal,
        SalesNumberModel,
        PaymentsModal,
        SalesCustomerModel,
        ReciptModal,
        PaymentItemSearch,

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
        let recentEventId = ref("Unknown")
        let discountTypes = ref([])
        const { orderItemColumns } = fields();
        const { salesItemColumns } = fields();
          formData=({
                order_date: new Date().toISOString().split('T')[0],
                invoice_date: new Date().toISOString().split('T')[0],

                party_id:0,
                product_id: '',
                party_product_name: '',
                party_name:'',
                receiptpayment_mode:'HDFC Bank',
                narration: '',
                payment_id:0,
                selectedInvoice:localStorage.getItem("selectedInvoice"),
                items:[
                    {index:1,item_id:null,item_name:null,quantity:'',short_amount:'', amount:'',inst_type:null,inst_no:null,receipt_date:null},
                    {index:2,item_id:null,item_name:null,quantity:'',short_amount:'',amount:'',inst_type:null,inst_no:null,receipt_date:null},
                    {index:3,item_id:null,item_name:null,quantity:'',short_amount:'',amount:'',inst_type:null,inst_no:null,receipt_date:null},
                    {index:4,item_id:null,item_name:null,quantity:'',short_amount:'',amount:'',inst_type:null,inst_no:null,receipt_date:null},
                    {index:5,item_id:null,item_name:null,quantity:'',short_amount:'',amount:'',inst_type:null,inst_no:null,receipt_date:null},
                    {index:6,item_id:null,item_name:null,quantity:'',short_amount:'',amount:'',inst_type:null,inst_no:null,receipt_date:null},
                    {index:7,item_id:null,item_name:null,quantity:'',short_amount:'',amount:'',inst_type:null,inst_no:null,receipt_date:null},
                     ],
                  });


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
                { name: 'CGST Output', location: '', value: '' },
                { name: 'SGST Output', location: '', value: '' },
                { name: '', location: '', value: '' }],
            isNumberVisible: false,
            isPaymentsModalVisible:false,
            isModalPopup:false,
            isCustomerVisible: false,
            isModalVisible: false,
            isReceiptModalVisible:false,
            isProuctsModalVisible: false,
            deleteConfirmationPopup:false,
            stockDateColor: '',
             focusedIndex: -1,
			isNumberVisible:false,
            //isPurchaseBillModalVisible: false,
            showDeletePopup: false,
            selectedItemIndex: null,
            items: [
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
                { amount: 0 },
            ],
            invoiceitems:[
                { quantity: "" }

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
            headers: ['S.No', 'Account', 'Station','Date','Inst. Type','Inst. No', 'Amount','Short Amount'],
            addtionaldetalisheader:['Additional Details', '%', '₹ Amount'],

        };
    },

    mounted() {

        console.log("mounted", this.$route.params.id);
    this.getPaymentData(this.$route.params.id);
        this.setDefaultDate();
        document.addEventListener("keydown", this.handleKeyDown);
        [].forEach.call(document.querySelectorAll('.datepicker'), function (el, index) {
    if (index === 0) {
        el.style.visibility = 'visible'; 
        let today = new Date().toISOString().split('T')[0];
        el.value = today;
    } else {
        el.style.visibility = 'hidden'; 
    }
});

        document.addEventListener("keydown", this.handleKeyDown);
        document.getElementById('salespaymentcreste').addEventListener('keydown', this.handleKeyDowning);
        this.autoFocusInput();

        document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("total_quantity_detaildss").value = "";
});

    },
    beforeDestroy() {
       // document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {

        async getPaymentData(id) {
      try {
        if (id > 0) {
          const response = await axiosAdmin.get(`receiptpaymentdetails/${id}`);
          if (response.data && response.data.receiptPayments && response.data.receiptPayments.length > 0) {
            const paymentData = response.data.receiptPayments[0];

            this.formData.id = paymentData.id;
            this.formData.narration = paymentData.narration;
            this.formData.receiptpayment_mode = paymentData.receiptpayment_mode;
            console.log(paymentData.receiptpayment_mode);
            console.log("Payment Data:", paymentData);
          } else {
            console.error("No payment data found.");
          }
        }
      } catch (error) {
        console.error("Error fetching payment data:", error);
      }
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
        narration:"",
        party_product_name: "",
        party_name: "",
        total_items: 0,
        total_quantity: 0,
        subtotal: 0,
        payment_id: 0,
        selectedInvoice: localStorage.getItem("selectedInvoice"),
        items: Array.from({ length: 10 }, (_, index) => ({
            index: index + 1,
            item_id: null,
            item_name: null,

            amount: null,
            maxquantity: 0,
            max_single_unit_price: 0,
            packing: null,
            cgst: null,
            sgst: null,
            unique: Math.random().toString(36).substring(2, 7),
        })),
        invoiceitems: [
            { index: 1, item_id: null, item_name: null, unit_id: null, quantity: "", amount: "", mrp: null, amount: null, maxquantity: 0, max_single_unit_price: 0, packing: null }
        ],
        additems: [
            { index: 1, item_id: null, item_name: null, unit_id: null },
            { index: 2, item_id: null, item_name: null, unit_id: null },
            { index: 3, item_id: null, item_name: null, unit_id: null },
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



        confirmDeletion(event, index) {
      if (event.key === "Backspace" || event.key === "Delete") {
        event.preventDefault();

        if (confirm("Do you want to delete this row?")) {
          this.formData.items.splice(index, 1);
        }
      }
    },

      getInvoiceDetails(){
        //console.log("selectedInvoice inside method",this.selectedInvoice);
        if(this.selectedInvoice!="null")
        {
            this.formData.bill_number=this.selectedInvoice;
            this.spinning = true;
            axiosAdmin
            .get("sales/getbillInvoiceDetails/"+this.selectedInvoice)
            .then(response => {  //console.log(response)
                // Toastr Notificaiton
                this.formData.party_id=response.data.invoiceData.party_id;

                this.formData.party_name=response.data.invoiceData.party_name,
                this.formData.party_customer_id=response.data.customerData.id,
                this.formData.customer_name=response.data.customerData.cus_name,
                this.formData.party_customer_mobile=response.data.customerData.mobile_number,
                this.formData.address = response.data.customerData.address;
                this.formData.order_date = response.data.invoiceData.order_date.split('T')[0];
                this.formData.invoice_date = response.data.invoiceData.invoice_date.split('T')[0];

                console.log(response.data.invoiceData.order_date);
                console.log(response.data.invoiceData.invoice_date);

                this.spinning= false;
                if(response.data.invoiceItems && response.data.invoiceItems.length>0)
                {
                    let finalIndex  =   0;
                    let grand_total =   0;
                    let total_disc  =   0;
                    let totalcgst   =   0;
                    let totalsgst   =   0;
                    response.data.invoiceItems.forEach((data,index)=>{

                        this.formData.items[index].item_id                  = data.party_id;
                        this.formData.items[index].item_name                = data.product_name;
                        this.formData.items[index].unit_id                  = "";
                        this.formData.items[index].quantity                 = this.formatNumber(data.quantity);
                        this.formData.items[index].mrp                      = this.formatNumber(data.mrp);
                        this.formData.items[index].single_unit_price        = this.formatNumber(data.single_unit_price);
                        this.formData.items[index].discount_rate            = data.discount_rate;
                        this.formData.items[index].amount                   = this.formatNumber(data.subtotal);
                        this.formData.items[index].maxquantity              = this.formatNumber(data.stock);
                        this.formData.items[index].max_single_unit_price    = this.formatNumber(data.mrp);
                        this.formData.items[index].packing                  = data.pack;
                        this.formData.items[index].cgst                     = (data.cgst>=0?data.cgst:0);
                        this.formData.items[index].sgst                     = (data.sgst>=0?data.sgst:0);
                        this.formData.items[index].discount_type_id         = (data.discount_type_id);
                        grand_total                                         = grand_total+Number(data.subtotal);
                        total_disc                                          =  total_disc+Number(data.discount_rate);
                        totalcgst                                           =  totalcgst+ (data.cgst>=0?data.cgst:0);
                        totalsgst                                           =  totalsgst+ (data.sgst>=0?data.sgst:0);

                        document.getElementById('item_product_amount_'+index).value=this.formatNumber(data.subtotal)

                        document.getElementById('item_product_disc_'+index).value=this.formatNumber(data.discount_rate)
                        document.getElementById('item_discount_total_'+index).value=data.discount_rate;
                        const taxvalueStr = (cgst_tax_percentage + sgst_tax_percentage) + ",(" + (((cgst_tax_percentage + sgst_tax_percentage) / 100) * singleItemTotal).toFixed(2) + ")"; console.log("taxvalueStr",taxvalueStr)

document.getElementById("item_product_tax_" + index).innerHTML = taxvalueStr;


                        finalIndex= index

                    })

                    total_disc = this.getTotalAmount('discount');
                   // totalcgst = this.getTotalAmount("cgst");
                    //totalsgst  = this.getTotalAmount("sgst");
                    document.getElementById('total_goods_value').value          =    this.formatCurrency(grand_total);
                    document.getElementById('grand_total').innerHTML            =   this.formatCurrency((grand_total-total_disc)+(totalsgst>0?((totalsgst/2)/100)*grand_total:0));
                    document.getElementById('total_discount_text').innerHTML    =   this.formatCurrency(total_disc);
                    document.getElementById('cgst_total_text').innerHTML        =    this.formatCurrency(totalcgst>0?((totalcgst/2)/100)*grand_total:0)
                    document.getElementById('sgst_total_text').innerHTML        =    this.formatCurrency(totalsgst>0?((totalsgst/2)/100)*grand_total:0)

                    document.getElementById('igst_amount_0').value              =   this.formatCurrency(totalcgst>0?((totalcgst/2)/100)*grand_total:0);
                    document.getElementById('igst_amount_1').value              =   this.formatCurrency(totalsgst>0?((totalsgst/2)/100)*grand_total:0);


                    //console.log("grand_total=>",this.formatCurrency(totalsgst>0?((totalsgst/2)/100)*grand_total:0));
                    this.selectedItermIndex=(finalIndex+1);


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

    // showPrintModal() {
    //         //console.log("Print called");
    //         this.isPurchaseBillModalVisible = true;
    //     },

    //     handleClosePrint() {
    //         this.isPurchaseBillModalVisible = false;
    //     },

    handleEnterKey(event) {
      // Check if Enter or Space key is pressed
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent default scrolling behavior for the Space key
        this.toggleDropdowns();
      }
    },
    handleKeyUp(event) {
      if (event.key === 'Enter') {
        const targetElement = document.getElementById('item_product_name_0');
        if (targetElement) {
          targetElement.focus();
        }
      }
    },
setDefaultDate() {
      const today = new Date().toISOString().split('T')[0];
      if (this.formData.items.length > 0) {
        this.formData.items[0].receipt_date = today;
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

        onlyAplhaChar(e) {
        // Get the pressed key as a string
        const char = String.fromCharCode(e.which);

        // Create a regex that allows alphabets (both cases), digits, dots, commas, hyphens, and spaces.
        const allowedCharacters = /^[a-zA-Z0-9.,\-\s]*$/;

        // If the character does not match the allowed set, prevent its input.
        if (!allowedCharacters.test(char)) {
            e.preventDefault();
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


                handleQuantityKeyDown(index, event) {
                const value = this.formData.items[index].quantity;
                console.log(`Quantity: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                const isShiftTab = event.shiftKey && event.key === "Tab";

                if (isTabOrEnter && !isShiftTab) { // Forward movement
                    event.preventDefault();
                    if (!value || value.toString().trim() === "") {
                        console.log("Empty quantity, staying in field");
                        this.$nextTick(() => {
                            document.getElementById(`item_product_quantity_${index}`).focus();
                        });
                        return false;
                    }
                    console.log("Valid quantity, moving to date");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_free_${index}`).focus();
                    });
                    return false;
                }
                if (isShiftTab) { // Backward movement
                    event.preventDefault();
                    console.log("Shift+Tab pressed, moving back to name");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_name_${index}`).focus();
                    });
                    return false;
                }
                // if (event.key === "F8" || event.keyCode === 119) {
                //     event.preventDefault();
                //     this.saveSalesReturnEntry();
                // }
                if (event.key === "Escape" || event.keyCode === 27) {
                    event.preventDefault();
                    this.showconfirmBill();
                }
            },

            handleDateKeyDown(index, event) {
                const value = this.formData.items[index].receipt_date;
                console.log(`Date: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                const isShiftTab = event.shiftKey && event.key === "Tab";

                if (isTabOrEnter && !isShiftTab) {
                    event.preventDefault();
                    if (!value || value.toString().trim() === "") {
                        console.log("Empty date, staying in field");
                        this.$nextTick(() => {
                            document.getElementById(`item_product_free_${index}`).focus();
                        });
                        return false;
                    }
                    console.log("Valid date, moving to inst_type");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_disc_${index}`).focus();
                    });
                    return false;
                }
                if (isShiftTab) {
                    event.preventDefault();
                    console.log("Shift+Tab pressed, moving back to quantity");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_quantity_${index}`).focus();
                    });
                    return false;
                }
                // if (event.key === "F8" || event.keyCode === 119) {
                //     event.preventDefault();
                //     this.saveSalesReturnEntry();
                // }
                if (event.key === "Escape" || event.keyCode === 27) {
                    event.preventDefault();
                    this.showconfirmBill();
                }
            },

            handleInstTypeKeyDown(index, event) {
                const value = this.formData.items[index].inst_type;
                console.log(`Inst Type: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                const isShiftTab = event.shiftKey && event.key === "Tab";

                if (isTabOrEnter && !isShiftTab) {
                    event.preventDefault();
                    if (!value || value.toString().trim() === "") {
                        console.log("Empty inst_type, staying in field");
                        this.$nextTick(() => {
                            document.getElementById(`item_product_disc_${index}`).focus();
                        });
                        return false;
                    }
                    console.log("Valid inst_type, moving to inst_no");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_price_${index}`).focus();
                    });
                    return false;
                }
                if (isShiftTab) {
                    event.preventDefault();
                    console.log("Shift+Tab pressed, moving back to date");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_free_${index}`).focus();
                    });
                    return false;
                }
                // if (event.key === "F8" || event.keyCode === 119) {
                //     event.preventDefault();
                //     this.saveSalesReturnEntry();
                // }
                if (event.key === "Escape" || event.keyCode === 27) {
                    event.preventDefault();
                    this.showconfirmBill();
                }
            },

            handleInstNoKeyDown(index, event) {
                const value = this.formData.items[index].inst_no;
                console.log(`Inst No: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                const isShiftTab = event.shiftKey && event.key === "Tab";

                if (isTabOrEnter && !isShiftTab) {
                    event.preventDefault();
                    if (!value || value.toString().trim() === "" || Number(value) <= 0) {
                        console.log("Invalid inst_no, staying in field");
                        this.$nextTick(() => {
                            document.getElementById(`item_product_price_${index}`).focus();
                        });
                        return false;
                    }
                    console.log("Valid inst_no, moving to amount");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_amount_${index}`).focus();
                    });
                    return false;
                }
                if (isShiftTab) {
                    event.preventDefault();
                    console.log("Shift+Tab pressed, moving back to inst_type");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_disc_${index}`).focus();
                    });
                    return false;
                }
                // if (event.key === "F8" || event.keyCode === 119) {
                //     event.preventDefault();
                //     this.saveSalesReturnEntry();
                // }
                if (event.key === "Escape" || event.keyCode === 27) {
                    event.preventDefault();
                    this.showconfirmBill();
                }
            },

            handleAmountKeyDown(index, event) {
                const value = this.formData.items[index].amount;
                console.log(`Amount: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                const isShiftTab = event.shiftKey && event.key === "Tab";

                if (isTabOrEnter && !isShiftTab) {
                    event.preventDefault();
                    if (!value || value.toString().trim() === "" || Number(value) <= 0) {
                        console.log("Invalid amount, staying in field");
                        this.$nextTick(() => {
                            document.getElementById(`item_product_amount_${index}`).focus();
                        });
                        return false;
                    }
                    console.log("Valid amount, moving to short_amount");
                    this.$nextTick(() => {
                        document.getElementById(`item_short_amount_${index}`).focus();
                    });
                    return false;
                }
                if (isShiftTab) {
                    event.preventDefault();
                    console.log("Shift+Tab pressed, moving back to inst_no");
                    this.$nextTick(() => {
                        document.getElementById(`item_product_price_${index}`).focus();
                    });
                    return false;
                }
                // if (event.key === "F8" || event.keyCode === 119) {
                //     event.preventDefault();
                //     this.saveSalesReturnEntry();
                // }
                if (event.key === "Escape" || event.keyCode === 27) {
                    event.preventDefault();
                    this.showconfirmBill();
                }
            },

            handleShortAmountKeyDown(index, event) {
    const value = this.formData.items[index].short_amount;
    console.log(`Short Amount: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);

    const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
    const isShiftTab = event.shiftKey && event.key === "Tab";

    if (isTabOrEnter && !isShiftTab) {
        event.preventDefault();
        if (!value || value.toString().trim() === "" || Number(value) < 0) {
            console.log("Invalid short_amount, staying in field");
            this.$nextTick(() => {
                document.getElementById(`item_short_amount_${index}`).focus();
            });
            return false;
        }
        console.log("Valid short_amount, moving to next row");
        this.selectedItermIndex = index + 1;
        this.$nextTick(() => {
            const nextField = document.getElementById(`item_product_name_${this.selectedItermIndex}`);
            if (nextField) {
                nextField.focus();
            } else {
                document.getElementById("saveF8").focus(); 
            }
        });
        return false;
    }

    if (isShiftTab) {
        event.preventDefault();
        console.log("Shift+Tab pressed, moving back to amount");
        this.$nextTick(() => {
            document.getElementById(`item_product_amount_${index}`).focus();
        });
        return false;
    }

    if (event.key === "Escape" || event.keyCode === 27) {
        event.preventDefault();
        this.showconfirmBill();
    }

    // Handle F8 key press
    if (event.key === "F8") {
        event.preventDefault();
        console.log("F8 key pressed, saving sales return entry...");
        this.saveSalesReturnEntry();
    }
},


            hasData(index) {
        const item = this.formData.items[index];
        return item && (item.item_name || item.quantity || item.receipt_date || item.inst_type || item.inst_no || item.amount || item.short_amount);
    },

    getSNo(index) {
        const filledRows = this.formData.items.filter(this.hasData);
        const filledIndex = filledRows.findIndex(row => row === this.formData.items[index]);
        return filledIndex >= 0 ? filledIndex + 1 : '';
    },

    updateSNoVisibility() {
        this.$forceUpdate(); // Forces Vue to re-render S.No visibility
    },

    onFocus(index) {
        this.focusedIndex = index; // Set the focused index
        console.log(`Focused on item_product_name_${index}, S.No visible as ${this.getSNo(index)}`);
        this.updateTotalProd(index); // Keep your existing focus logic
        this.updateSNoVisibility();
    },

    onBlur() {
        this.focusedIndex = -1; // Reset to hide S.No on blur if no data
        console.log("Blur event, S.No hidden unless data exists");
        this.updateSNoVisibility();
    },

                gotoNext(index, event) {
                        console.log(event.keyCode, Number(this.formData.items[index].quantity));
                        if (event.keyCode == 13) {
                            console.log("Enter key pressed, moving to Free field");
                            document.getElementById(`item_product_free_${index}`).focus();
                            event.preventDefault();
                            return false;
                        }
                        // else if (event.key === "F8" || event.keyCode === 119) {
                        //     this.saveSalesReturnEntry();
                        // }

                        else if (event.keyCode == 27) {
                            this.showconfirmBill();
                        }
                    },



            gotoNextFree(index, event) {
                // if(event.keyCode === 13){
                //     document.getElementById(`item_product_price_${index}`).focus();
                // }

                console.log(event.keyCode, Number(this.formData.items[index].date));

                if (event.keyCode == 13) {
                    console.log("Enter key pressed, moving to Price field");
                    document.getElementById(`item_product_price_${index}`).focus();
                    event.preventDefault();
                    return false;
                }
                else if (event.key === "F8" || event.keyCode === 119) {
                    event.preventDefault();
                    //this.saveSalesReturnEntry();
                } else if (event.keyCode == 27) {
                    this.showconfirmBill();
                }
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
                document.getElementById("item_product_free_"+this.selectedItermIndex).focus();
                event.preventDefault()
                return false;
            }
        },


        checkstationtoDate(index,event){
           if(event.keyCode === 13){
        document.getElementById(`item_product_free_${index}`).focus();
    }
        },

        checkinstNotoAmount(index,event){
           if(event.keyCode === 13){
        document.getElementById(`item_product_amount_${index}`).focus();
    }
        },

        checkdatetoInstType(index,event){
           if(event.keyCode === 13){
        document.getElementById(`item_product_disc_${index}`).focus();
    }
        },

        checkInstTypeInstNo(index,event){
           if(event.keyCode === 13){
        document.getElementById(`item_product_price_${index}`).focus();
    }
        },

        checkEntermoveshortamount(index,event){
           if(event.keyCode === 13){
        document.getElementById(`item_short_amount_${index}`).focus();
    }
        },

        checkshortamounttoproductname1(index,event){
           if(event.keyCode === 13){
        document.getElementById(`item_product_name_${index+1}`).focus();
    }
        },

        checkSingleItemPrice(index,event)
        {
           if(Number(this.formData.items[index].single_unit_price)>Number(this.formData.items[index].max_single_unit_price))
            { }
            if((event.key=="Tab" || event.key=="Enter") && (Number(event.target.value)<=0))
            {
                //console.log("Quantity is Zero");
                document.getElementById("item_product_quantity_"+this.selectedItermIndex).focus();
                return false;
            }
            else if(( event.key=="Enter") && (Number(event.target.value)>0))
            {
               console.log("222",document.getElementById("item_product_disc_type_"+this.selectedItermIndex));
               //document.getElementById("item_product_disc_"+this.selectedItermIndex).focus();
               document.getElementById("item_product_disc_type_"+this.selectedItermIndex).focus();

               event.preventDefault();
                return false;
            }
            else if(event.key==undefined)
            {
                document.getElementById("item_product_free_"+this.selectedItermIndex).focus();
            }
            // else if (event.key === "F8" || event.keyCode === 119)
            // {
            //     event.preventDefault();
            //     this.saveSalesReturnEntry();
            // }
            else if(event.keyCode===27)
            {
                this.showconfirmBill();
            }
        },


        autoFocusInput() {
      this.$nextTick(() => {
        if (this.$refs.DateInput) {
          this.$refs.DateInput.$el.focus(); // Ensuring correct element selection
        } else {
          console.log("DateInput ref is not available yet");
        }
      });
    },


             handleSuccess(xid) {
            this.isProuctsModalVisible = false;
            this.isModalVisible = false;
            this.focusElement('item_product_name_0');
            ////console.log('Success:', xid);
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
        // this.formData.party_id==undefined || this.formData.party_id=="" || this.formData.party_id<0
        if( event.key == " " || event.key == 'Enter')
        {
            this.isReceiptModalVisible = true;
            return false;
        }
        else if ((event.keyCode === 13 || event.keyCode === 9) && this.formData.party_id>0) {
         this.isReceiptModalVisible = false;
         document.getElementById('item_product_name_'+this.selectedItermIndex).focus();
        }
        else if ((event.keyCode === 27) && this.formData.party_id>0) {
         this.showconfirmBill();
        }
        else if ((event.keyCode !== 13 && event.keyCode !== 9 && event.keyCode !== 113) && this.formData.party_id>0) {
         this.isReceiptModalVisible = true;
         return false;
        }
    },

    handleKeyDown(event) {
      if (event.key === 'Escape' || event.keyCode === 27) {

        this.showconfirmBill();
         this.autoFocusInput();
      }
    //   else if (event.key === "F8" || event.keyCode === 119) {
    //      event.preventDefault();

    //     this.saveSalesReturnEntry();
    //   }
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
         this.showconfirmBill();
         this.focusEelmentss();

      }
    },

    handleKeydownDatePicker(event) {
            console.log('event.keyCode ', event.keyCode);
            this.recentEventId = event;

            if (event.keyCode === 27) {  // Escape key
              this.showconfirmBill();
            } else if ((event.key === 'Tab' || event.key === 'Enter') && event.target.value === "")
            {  // Enter/Tab keyy
                this.formData.order_date = new Date().toISOString().split('T')[0];
                document.getElementById("form_item_order_date").value = this.formData.order_date;
                this.focusElement('party_name');
                event.preventDefault();
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
            }
            else if (field === 'bill_number') {
                document.getElementById('form_item_bill_number').focus();
            }
            else if (field === 'invoice_date') {
                document.getElementById('invoice_date').focus();
            }
            else if (field === 'item_product_name_0') {
                document.getElementById('item_product_name_0').focus();
            }
        },


    showPaymentModal(){
        ////console.log("Payment called");
        this.isPaymentsModalVisible = true;
        document.getElementById('amount_0').focus();
        $event.preventDefault();
    },

        inputData($event) {
            // Value will update reactively
            $event.target.value;
        },
        showNumberModal(event) {
            console.log("Open Model",event.keyCode);
            if((event.keyCode==13 || event.keyCode==32 || event.keyCode==9 ))
            {
                this.isNumberVisible = true;
            }
            // else if (event.key === "F8" || event.keyCode === 119)
            //     {
            //     this.saveSalesReturnEntry();
            // }
            else if(event.keyCode==27) // press F8
            {
                this.showconfirmBill();
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
                document.getElementById("form_item_address").focus()
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

        getCurrentDates() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },
        showModal() {
            this.isReceiptModalVisible = true;
            ////console.log(33);
            //this.$refs.dummykeyboard.focus();
        },

        handleCloseCustomer() {
this.isReceiptModalVisible = false;
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


        updateParent(selectedParty) {
            this.formData.party_id = selectedParty.id.toString();
            this.formData.party_name = selectedParty.name;
            ////console.log("hello child" + this.formData.party_id)
            document.getElementById("party_id").value = this.formData.party_id;
            document.getElementById("form_item_name").value = this.formData.name;
            document.getElementById("form_item_party_name").value = this.formData.party_name;
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

        // handleCloseBillPrints(){
        //     this.isPurchaseBillModalVisible = false;
        // },
        updateCustomer(selectedParty) {
            //console.log('selectedParty',selectedParty)
            this.formData.party_customer_id = selectedParty.id;
            this.formData.party_customer_name = selectedParty.name;
            this.formData.party_customer_mobile = selectedParty.mobile_number;
            this.formData.address               =   selectedParty.address
            ////console.log("hello child" + this.formData)
            document.getElementById("party_customer_id").value = this.formData.party_customer_id;
            document.getElementById("form_item_customer_name").value = this.formData.party_customer_name;
            document.getElementById("form_item_mobile_number").value = this.formData.party_customer_mobile;
            document.getElementById("form_item_address").value = this.formData.address;
        },

        updateParents(selectedParty) {
    console.log('Selected Party ID:', selectedParty.id);
    console.log('Selected Item Index:', this.selectedItermIndex);

    if (!this.formData.items[this.selectedItermIndex]) {
        this.formData.items[this.selectedItermIndex] = {};
    }

    this.formData.items[this.selectedItermIndex].item_id = selectedParty.id;
    this.formData.items[this.selectedItermIndex].item_name = selectedParty.party_name;
    document.getElementById("item_product_quantity_" + this.selectedItermIndex).value = selectedParty.station;
    document.getElementById("item_product_name_" + this.selectedItermIndex).value = this.formData.items[this.selectedItermIndex].item_name;
    document.getElementById("datepicker_"+this.selectedItermIndex).style.visibility= "visible";
    const openingBalance = parseFloat(selectedParty.opening_balance) || 0; // Fallback to 0 if invalid
    document.getElementById("balance_amt").innerHTML = openingBalance.toFixed(2);
    console.log(openingBalance);

   // document.getElementById("datepicker_" + this.selectedItermIndex).focus();



  setTimeout(() => {
    const selectedItermIndex = this.selectedItermIndex; // Ensure it exists
    const element = document.getElementById("datepicker_" + selectedItermIndex);
    if (element) {
        element.focus();
        element.style.backgroundColor = "#FFD451";
        element.click();
        element.addEventListener("change", () => {
            element.style.backgroundColor = "";
        });
        element.addEventListener("blur", () => {
            element.style.backgroundColor = "";
        });
    } else {
        console.log("Element not found!");
    }
  }, 100);





},



        focusproductFreeEelment()
        {
            document.getElementById("item_product_free_0").focus();
        },

        showProductModal(event, index) {
    if (event.key === 'Enter' || event.keyCode === 13 || event.key === ' ' || event.keyCode === 32) {
        this.selectedItermIndex = index;
        this.isReceiptModalVisible = true;
    } else if ((event.keyCode === 9 || event.keyCode === 13) && event.target.id === `item_product_name_${index}`) {
        event.preventDefault(); // Prevent default tabbing behavior

        const narrationField = document.getElementById("narrationFields");
        const nextField = document.getElementById(`item_product_name_${index + 1}`);

        if (event.target.id === `item_product_name_${index}`) {
            // First press moves focus to narrationFields
            if (narrationField) {
                narrationField.focus();
            }
        } else if (event.target.id === "narrationFields") {
            // Second press moves focus to the next product name field
            if (nextField) {
                nextField.focus();
            }
        }
    } else if (event.key === 'F8' || event.keyCode === 119) {
        
        this.saveSalesReturnEntry();
    }
},




focusOnProductName(index, event) {
        if (event.keyCode === 13 || event.key ===  'Tab') {
            event.preventDefault();
            const nextField = document.getElementById(`item_product_name_1`);
            if (nextField) {
                nextField.focus();
            }
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
        focusinputvalue(event)
        {
            event.target.select()

        },
       
        getQuantity(index,event) {
            let  quantity              = document.getElementById("item_product_quantity_"+index).value;
             let  amount              = document.getElementById("item_product_free_"+index).value;
             let  taxvalue              = Number(document.getElementById("item_product_tax_"+index).innerHTML) ;

            let  price                 = document.getElementById("item_product_price_"+index).value;
            let  discount              = document.getElementById("item_product_disc_"+index).value;
            const cgst_tax_percentage  = Number(this.formData.items[index].cgst);
            const sgst_tax_percentage  = Number( this.formData.items[index].sgst);

            if(quantity==undefined || quantity=="" || quantity<0){quantity=0}
            if(price==undefined || price=="" || price<0){price=0}
            if(amount==undefined || amount=="" || amount<0){amount=0}
            if(taxvalue==undefined || taxvalue=="" || taxvalue<0){taxvalue=0}

            if(discount==undefined || discount=="" || discount<0){discount=0}
            const singleItemTotal = Number(quantity*price);
            document.getElementById("item_product_amount_"+index).value =this.formatCurrency(singleItemTotal);
            const taxvalueStr = (cgst_tax_percentage + sgst_tax_percentage) + ",(" + (((cgst_tax_percentage + sgst_tax_percentage) / 100) * singleItemTotal).toFixed(2)+")";
            if (singleItemTotal >0) {
                document.getElementById("item_product_tax_" + index).innerHTML = taxvalueStr;
            }

            this.formData.items[index].discount_rate    =   discount;
            this.formData.items[index].single_unit_price    =   price;

            const totalAmount = this.getTotalAmount('total');
            this.totalAmount  =  totalAmount;

            document.getElementById("sgst_total_text").innerHTML = this.formatCurrency(Number((sgst_tax_percentage/100))*Number(this.totalAmount));
            document.getElementById("cgst_total_text").innerHTML = this.formatCurrency(Number((cgst_tax_percentage/100))*Number(this.totalAmount));

            const totalSgstAmount   =   this.getTotalAmount('sgst');
            const totalCgstAmount   =   this.getTotalAmount('cgst');
            const totalDiscount     =   this.getTotalAmount('discount');

            document.getElementById("igst_amount_0").value       =  this.formatCurrency(totalSgstAmount);
            document.getElementById("igst_amount_1").value       =  this.formatCurrency(totalCgstAmount);
            this.formData.tax_amount                             =  totalSgstAmount+ totalCgstAmount;
            document.getElementById("total_goods_value").value   =  this.formatCurrency(totalAmount);
            this.formData.subtotal                               =  totalAmount;
            document.getElementById("grand_total").innerHTML     =  this.formatCurrency((totalAmount+totalSgstAmount+totalCgstAmount)-Number(totalDiscount));
            this.formData.total                                   = (totalAmount+totalSgstAmount+totalCgstAmount)-Number(totalDiscount);
            document.getElementById('total_discount_text').innerHTML = this.formatCurrency(totalDiscount)
            this.formData.discount                                   =   totalDiscount;

            this.updateTotalProd(index);
            this.updateSNoVisibility();


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


        validateField(field) {
      const value = this.formData[field];
      const valueed = this.rules[field]?.message || field;

      if (!value) {
        this.rules[field] = {
          message: "This Field is required.",
        };
      } else {
        this.rules[field] = null;
      }
    },
        checkDisc(index,event)
        {
           // console.log('checkDisc',this.formData.items[index].discount_rate)
            if(( event.key=="Enter") )
            {

               document.getElementById("item_product_name_"+(this.selectedItermIndex+1)).focus();
               event.preventDefault();
                return false;
            }
            // else if (event.key === "F8" || event.keyCode === 119)
            //                 {
            //     this.saveSalesReturnEntry();
            // }
            else if(event.keyCode==27)
            {
                this.showconfirmBill();
            }
        },
        success(response)
        {
            this.formData.selectedInvoice = this.formData.bill_number;
            //console.log('rely=>>',response.message);
                this.loading= false;
                notification.success({
                            placement:  "bottomRight",
                            message: "Receipt Purchase Bill saved successfully ! ",
                            description: response.message
                        });
                 this.$router.push({
                name: 'admin.payment.index'
               });


        },
        successDraft(response)
        {
            this.formData.selectedInvoice = this.formData.bill_number;
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

                if(itemAvailable==false){
                    notification.warning({
                        placement:  "bottomRight",
                        message: "Please add one item !",

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
            this.spinning= true;
            axiosAdmin
            .post("receiptpaymentdetails", this.formData)
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

        updateFree()
        {
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


updateTotalProd(index) {
    let count = 0;
    let totalAmount = 0;
    let totalShortAmount = 0;

    this.formData.items.forEach((elem) => {
        if (elem.item_id != undefined && elem.item_id != null && elem.item_id > 0) {
            count += 1;
            totalAmount += parseFloat(elem.amount) || 0; // Sum of amounts
            totalShortAmount += parseFloat(elem.short_amount) || 0; // Sum of short amounts
        }
    });

    document.getElementById("total_prod_count").value = "Total : " + count + " Product";
    document.getElementById("total_amount").value = this.formatCurrency(totalAmount.toFixed(2)); // Total Amount
    document.getElementById("total_short_amount").value = this.formatCurrency(totalShortAmount.toFixed(2)); // Total Short Amount

    //this.showGstDetails(index);
    this.updateSNoVisibility();
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
             document.getElementById("total_goods_value").value=this.formatCurrency(quantity.toFixed(2));
        },



        showconfirmBill() {
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
                        name: `admin.payment.index`,
                    });
                },
                onCancel() {
                    that.$refs.DateInput && that.$refs.DateInput.focus();
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
            });
        },



        deleteConfirmation()
        {
            Modal.confirm({
                    title: 'Confirmation',
                    icon: createVNode(ExclamationCircleOutlined),
                    content: createVNode('div', { style: 'color:red;' }, 'Are you sure you want to delete the selected Record?'),
                   // autoFocusButton:'ok',
                    onOk() {
                    console.log('OK');
                    },
                    onCancel() {
                    console.log('Cancel');
                    },

                });
        },


        checkdatetoAmount(index, event) {
    if (event.keyCode === 13) { // Enter key
        event.preventDefault();
        const nextField = document.getElementById(`item_product_disc_${index}`);
        if (nextField) {
            nextField.focus();
        }
    } else if (event.keyCode === 119) { // F8 key
        event.preventDefault();
        this.saveSalesReturnEntry();
    }
},

        updateDiscount(that)
        {

            this.formData.items[this.selectedItermIndex].discount_type_id = document.getElementById('item_product_disc_type_'+this.selectedItermIndex).value;
            console.log(this.formData.items[this.selectedItermIndex].discount_type_id);
            if(  document.getElementById('item_product_disc_type_'+this.selectedItermIndex).value==1){
                this.formData.items[this.selectedItermIndex].discount_rate=0;
                document.getElementById('item_product_name_'+(this.selectedItermIndex+1)).focus()
                document.getElementById('item_product_disc_'+this.selectedItermIndex).value=0
            }
            else
            {

                document.getElementById('item_product_disc_'+this.selectedItermIndex).focus()
            }



        }


        // dublicateentery//


        // end dublicate//
    },
    computed: {
        grandTotal() {
            return this.formData.items.reduce((total, item) => total + Number(item.amount), 0);
        },
        totalAmount() {
        return this.items.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
    },
    totalShortAmount() {
        return this.items.reduce((sum, item) => sum + (parseFloat(item.short_amount) || 0), 0);
    }
    },
    watch: {
    "formData.god_owns"(newValue) {
      this.validateField("receiptpayment_mode");
    },
   
  },
};

//input field


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
