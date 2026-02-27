

<template>
    <AdminPageHeader>
        <template #header>

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
         <h1 style="font-size: 16px;">Receipt Voucher</h1>

        </template>
    </AdminPageHeader>
    <div id="salesreceiptcreate">
    <a-spin :spinning="spinning">
    <admin-page-table-content>
        <a-card class="page-content-container mt-5 mb-5">
            <a-form layout="vertical">
                <a-row :gutter="16">

                    <!--- modal-->
                    <ReciptModal v-if="isReceiptModalVisible" :visible="isReceiptModalVisible" :formData="formData" :url="url"
                    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                    @addEditSuccess="handleSuccess" @closed="handleCloseCustomer" v-on:child-method="updateParent"  v-on:close-method="focusEelment" />

                    <!-- sales modal number -->

                    <!-- sales modal number -->
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row style="margin-bottom:12px;margin-top:8px">
                           <a-col>
                            <a-form-item
                             :label="$t('Recipt Mode')"
                             name="party_name"
                            style="margin-right:10px; margin-top:5px "
                            class="required"
                            >
                        </a-form-item>

                            </a-col>
                            <a-col :xs="24" :sm="24" :md="5" :lg="5">

                              <select

                              v-model="formData.receipt_mode"
                              name="expense_category_id"
                              id="expense_category_id"
                              :allowClear="true"
                              optionFilterProp="label"
                              class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                              ref="expenseCategorySelect"
                              @change="handleChange"
                              @keydown="focusNext($event, 'expense_category_id')"
                              v-on:close-method="focusElement"
                            >
                            <option value="null" disabled style="color:black;">-- Select--</option>
                              <option
                                v-for="expenseCategory in expenseCategories"
                                :key="expenseCategory.id"
                                :value="expenseCategory.id"
                                :label="expenseCategory.bank_name"
                              >
                                {{ expenseCategory.bank_name }}
                              </option>
                            </select>

                            <!-- <ReceiptSearchInput
                                ref="DateInput"
                                id="expense_category_id"
                                @valueChanged="(productId) => (formData.receipt_mode = productId)"
                                @valueSuccess="getStockValue"
                                :productData="expenseCategories"
                                @keyup="handleKeyUp"
                                v-model:value="formData.receipt_mode"
                                autocomplete="off"
                                @input="validateField('expense_category_id')"
                              /> -->


                            </a-col>
                            <a-input  hidden type="date" v-model:value="formData.order_date" autocomplete="off"
                            @keyup="handlekeyup"/>
                        </a-row>
                      </a-col>

                </a-row>
                <!-- sales modal number -->
                <SalesNumberModel v-if="isNumberVisible" :visible="isNumberVisible" :formData="formData" :url="url"
                    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage" @closed="handlenumberModelll"
                    @addEditSuccess="handleSuccess"
                    v-on:salenumberclose-method="focusEelmentss" />



                    <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <div id="app" class="table-container">
                                <table id="cashTable" class="responsive-table" >
                                    <thead>
                                        <tr>
                                            <th v-for="(header, index) in casheaders" :key="index"
                                            v-bind:style=" index>1 ? 'text-align:right' : 'text-align:left' "
                                            class="tableheading">&nbsp;{{ header }}&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemtable">
                                         <tr v-for="(prod, index) in formData.items" :key="prod.unique" :id="prod.unique">
                                            <!-- <td style="width:1%">
                                                <input autocomplete="off" tabindex="-1"
                                                 v-if="formData.items[index].item_name && formData.items[index].item_name.trim() !== ''"
                                                 :value="index + 1"
                                                :id="`item_product_packing_${index}`"

                                                 style="color:black;font-weight:bolder;"
                                                readonly class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >
                                            </td> -->
                                            <td style="width:15%">
                                                <input autocomplete="off"
                                                v-model="formData.items[index].item_name"
                                                :ref="`input-${index}`"
                                                :id="`item_cash_product_name_${index}`"
                                                 @focus="updateTotalProd(index, $event)" 
                                                @click="handleClick(index, $event)" 
                                                name="party_name" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                @keydown="showProductModal($event,index)"
                                                @keydown.enter.prevent="moveToNextField($event, index, 'item_product_quantity_')"
                                                 @keyup="updateSerialNumber(index)"
                                                 @keydown.enter="updateSerialNumber(index)"
                                                style="color:black;font-weight:bolder;"

                                                >
                                                <a-input hidden :id="`item_product_id_${index}`" v-model="formData.items[index].item_id" />
                                                <a-input hidden  :id="`igst_item_${index}`"/>
                                                <input  hidden autocomplete="off" tabindex="-1" :id="`cgst_tax_${index}`" v-model="formData.items[index].cgst">
                                                <input  hidden autocomplete="off" tabindex="-1" :id="`sgst_tax_${index}`" v-model="formData.items[index].sgst">
                                            </td>


                                        <!-- Statoion -->
                                            <td style="width:10%">
                                                <input autocomplete="off"
                                                name="station"
                                                :id="`item_cash_product_station_${index}`"
                                                v-model="formData.items[index].station"
                                                style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                @keypress="onlyAplhaChar"
                                                @keydown="(event) => { handleStationKeyDown(index, event); checkstationtoDate(index, event); }"
                                                >
                                            </td>


                                                <!-- Datee -->
                                                <td style="width:10%" class="datepicker">
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

                                                <!-- Amount -->
                                            <td style="width:10%">
                                                <input autocomplete="off"
                                                type="number"
                                                v-model="formData.items[index].amount"
                                              
                                                :id="`item_product_amount_${index}`"
                                                @keydown="(event) => { checkAmounttoShortamount(index, event); handleAmountKeyDownCash(index, event); }"
                                                 
                                                name="single_unit_price"class="ant-input css-dev-only-do-not-override-wosfq4"
                                                style="color:black;font-weight:bolder;text-align-last:right;display:block !important;"
                                                >
                                            </td>

                                                 <!-- Short amount -->
                                            <td style="width:10%" >
                                                <input
                                                autocomplete="off"
                                                type="number"
                                                v-model="formData.items[index].short_amount"
                                                @keydown="(event) => { checkshortamounttoproductname1(index, event); handleShortAmountKeyDown(index, event); }"
                                                @focus="updateTotalProd(index)"
                                                 :id="`item_short_amount_${index}`"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                            <td style="width:10%;display:none;" >
                                                <input
                                                :id="`item_product_amount_${index}`"
                                                @keydown.enter.prevent="moveToNextField($event, index, 'narrationField')"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                            <td style="width:10%;display:none;" >
                                                <input
                                                :id="`narrationField${index}`"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                        </tr>

                                        <!--- new row-->
                                        <tr v-for="(header, index) in formData.invoiceitems" :key="index" class="invoicevalues">

                                            <td style="width:10%">
                                                <input id="total_prod_count" disabled=true name="table_total_amount[]"
                                                style="color:black;font-weight:bolder;text-align-last:left;"
                                                class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>
                                              <td style="width:10%"></td>
                                            <td style="width:10%"></td>


                                            <td style="width:10%">
                                                <input id="total_amount"  v-model:="formData.subtotal" disabled=true  name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>
                                            <td style="width:10%">
                                                <input id="total_short_amount" v-model:="formData.short_subtotal"  disabled=true  name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>
                                        </tr>
                                        <!-- end of row-->
                                    </tbody>
                                </table>

                                <table id="otherTable" class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th v-for="(header, index) in headers" :key="index"
                                                    v-bind:style="index > 1 ? 'text-align:right' : 'text-align:left'"
                                                    class="tableheading">{{ header }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemtable">
                                            <tr v-for="(prod, index) in formData.items" :key="prod.unique" :id="prod.unique">
                                                <!-- S.No -->
                                                <!-- <td style="width:1%">
                                                    <input autocomplete="off" tabindex="-1"
                                                        v-if="formData.items[index].item_name && formData.items[index].item_name.trim() !== ''"
                                                        :value="index + 1"
                                                        :id="`item_product_packing_${index}`"
                                                        style="color:black;font-weight:bolder;"
                                                        readonly class="ant-input css-dev-only-do-not-override-wosfq4">
                                                </td> -->

                                                    <!-- Account -->
                                                    <td style="width:20%">
                                                        <input autocomplete="off"
                                                            v-model="formData.items[index].item_name"
                                                            :ref="`input-${index}`"
                                                            :id="`item_product_name_${index}`"
                                                            @focus="updateTotalProd(index)"
                                                            name="party_name"
                                                            class="ant-input css-dev-only-do-not-override-wosfq4"
                                                            @click="handleClick(index, $event)" 
                                                            @keydown="showProductModal($event,index)"
                                                            @keydown.enter.prevent="moveToNextField($event, index, 'item_product_quantity_')"
                                                            @keyup="updateSerialNumber(index)"
                                                            style="color:black;font-weight:bolder;">
                                                        <a-input hidden :id="`item_product_id_${index}`" v-model="formData.items[index].item_id" />
                                                        <a-input hidden :id="`igst_item_${index}`"/>
                                                        <input hidden autocomplete="off" tabindex="-1" :id="`cgst_tax_${index}`" v-model="formData.items[index].cgst">
                                                        <input hidden autocomplete="off" tabindex="-1" :id="`sgst_tax_${index}`" v-model="formData.items[index].sgst">
                                                    </td>

                                                    <!-- Station -->
                                                    <td style="width:15%">
                                                    <input autocomplete="off"
                                                        :id="`item_product_quantity_${index}`"
                                                        v-model="formData.items[index].station"
                                                        @keydown="(event) => { handleOtherStationKeyDown(index, event); checkotherstationtoDate(index, event); }"
                                                        @input="getQuantity(index, $event)"
                                                        @focus="getQuantity(index, $event), focusinputvalue($event)"
                                                        @blur="updateAgg()"
                                                        name="station"
                                                        style="color:black;font-weight:bolder;text-align-last:right;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                        @keypress="onlyAplhaChar"
                                                    >
                                                </td>

                                        <!-- Date -->
                                        <td style="width:10%" class="datepickers">
                                            <input type="date"
                                                size="small"
                                                placeholder="Date"
                                                format="DD-MM-YYYY"
                                                :id="`cash_datepicker_${index}`"
                                                v-model="formData.items[index].receipt_date"
                                                  @keydown="(event) => { handleOtherDateKeyDown(index, event); checkdatetoAmounts(index, event); }"
                                                @blur="updateFreehead(), checkMaxQuantity(index, $event)"
                                                name="free[]"
                                                style="color:black;font-weight:bolder;text-align-last:right;display: block !important;"
                                                class="ant-input css-dev-only-do-not-override-wosfq4"/>
                                        </td>

                                    <!-- Inst. Type -->
                                    <td class="insttype" style="width:10%">
                                        <select
                                            class="ant-input css-dev-only-do-not-override-wosfq4 mobilenumbering"
                                            v-model="formData.items[index].inst_type"
                                            :id="`item_product_disc_${index}`"
                                            @keydown="handleInstTypeKeyDown(index, $event)"
                                            placeholder="Select Payment Type"
                                            :allowClear="false"
                                            optionFilterProp="title"
                                            show-search
                                            :disabled="isCashSelected">
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
                                                          @input="typeControll(index)"
                                                    >
                                                </td>


                                                <!-- Amount -->
                                                <td style="width:10%">
                                                <input autocomplete="off"
                                                type="number"
                                                v-model="formData.items[index].amount"
                                              
                                               @keypress="onlyForCurrency"
                                                :id="`item_product_amounts_${index}`"
                                                @keyup="(event) => { checkAmounttoShortamounts(index, event); handleAmountKeyDownCash(index, event); }"
                                                 
                                                name="single_unit_price"class="ant-input css-dev-only-do-not-override-wosfq4"
                                                style="color:black;font-weight:bolder;text-align-last:right;display:block !important;"
                                                >
                                            </td>

                                                 <!-- Short amount -->
                                            <td style="width:10%" >
                                                <input
                                                autocomplete="off"
                                                type="number"
                                                v-model="formData.items[index].short_amount"
                                                @keydown="(event) => { checkshortamounttoproductname1(index, event); handleShortAmountsKeyDown(index, event); }"
                                                @focus="updateTotalProd(index)"
                                                @keypress="onlyForCurrency"
                                                 :id="`item_short_amounts_${index}`"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                            <td style="width:10%;display:none;" >
                                                <input
                                                :id="`item_product_amount_${index}`"
                                                @keydown.enter.prevent="moveToNextField($event, index, 'narrationField')"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>
                                            <td style="width:10%;display:none;" >
                                                <input
                                                :id="`narrationField${index}`"
                                                name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                         </tr>

                                 <!-- Rest of the table remains the same -->

                                 <tr v-for="(header, index) in formData.invoiceitems" :key="index" class="invoicevalues">
                                            <td style="width:10%"></td>
                                            <td style="width:10%">
                                                <input id="total_prod_count" disabled=true name="table_total_amount[]"
                                                style="color:black;font-weight:bolder;text-align-last:left;"
                                                class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>
                                              <td style="width:10%"></td>
                                            <td style="width:10%"></td>
                                            <td style="width:10%"></td>

                                            <td style="width:10%">
                                              <input id="total_amount1"  v-model:="formData.subtotal" disabled=true  name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                          </td>
                                          <td style="width:10%">
                                              <input id="total_short_amount1" v-model:="formData.short_subtotal"  disabled=true  name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                          </td>
<!--
                                          <td style="width:10%">
                                                <input id="total_amount" disabled=true  v-model:="formData.subtotal" name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>

                                            <td style="width:10%">
                                                <input id="total_short_amount" v-model:="formData.short_subtotal" disabled=true value="0.00" name="table_total_amount[]" style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td> -->

                                        </tr>
    </tbody>
                                </table>
                            </div>
                    </a-col>
                </a-row>
 


                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">

            <div id="app" class="table-container">

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
                    <!-- <b>Balance&nbsp; ₹  :&nbsp;<span id="balance_amt" >0.00</span></b> -->

                </a-row>

                 <!-- New table -->
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">

                        <div id="app" class="table-container">
                                <table v-if="recentBill.data.length > 0" class="responsive-table">
                                    <thead>
                                        <tr>
                                            <th v-for="(headers2, index) in headers2" :key="index"
                                            v-bind:style=" index>1 ? 'text-align:right' : 'text-align:left' "
                                            class="tableheading">&nbsp;{{ headers2 }}&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemtable">


                                            <!-- S.NO -->
                                        <tr v-for="(prod, index) in recentBill.data" :key="prod.unique1" :id="prod.unique1">
                                 
                                             <!-- Date -->
                                            <td style="width:20%">
                                                <input autocomplete="off" readonly id="invoice_date"
                                                 v-model="prod.invoiceDate"   class="ant-input css-dev-only-do-not-override-wosfq4"
                                                >
                                               
                                            </td>

                                        <!-- Number -->
                                        <td style="width:15%">
                                                    <input autocomplete="off"
                                                        id="invoice_number"
                                                        style="color:black;font-weight:bolder;text-align-last:right;"
                                                        v-model="prod.invoice_number"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                    >
                                                </td>
                                                <!-- invoice_amount -->
                                                <td style="width:12%" >
                                                    <input  readonly
                                                     v-model="prod.total"
                                                        id="invoice_amount"
                                                        style="color:black;font-weight:bolder;text-align-last:right;display: block !important;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                    />

                                                </td>
                                                <!-- Balance -->
                                     
                                               
                                                <td style="width:10%">
                                                    <input autocomplete="off" type="number"
                                                       id="invoice_balance"  v-model="prod.due_amount"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                        style="color:black;font-weight:bolder;text-align-last:right;display:block !important;"
                                                        
                                                    >
                                                </td>
                                               
                                                <!-- Text -->
                                                <td style="width:13%">
                                                    <input type="number" v-model="prod.paid_amount"
                                                     autocomplete="off"
                                                        id="invoice_text"
                                                        style="color:black;font-weight:bolder;text-align-last:right;"
                                                        class="ant-input css-dev-only-do-not-override-wosfq4"
                                                    >
                                                </td>
                                        </tr>


                                        <!--- new row-->
                                        <tr  :key="index" class="invoicevalues">

                                          

                                            <td style="width:10%">
                                                <input 
                                                value="Total"
                                                disabled=true
                                                 style="color:black;font-weight:bolder;text-align-last:left;" class="ant-input css-dev-only-do-not-override-wosfq4"
                                                ></input>
                                            </td>

                                           <td style="width:10%"></td>
                                            <td style="width:10%"></td>
                                           
                                            <td style="width:10%">
                                                <input id="invoice_total_amount" disabled=true  
                                                :value="totalAmount1" 
                                                style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>

                                            <td style="width:10%">
                                                <input id="invoice_balance_amount"  disabled=true 
                                                :value="paidTotal"  
                                                style="color:black;font-weight:bolder;text-align-last:right;" class="ant-input css-dev-only-do-not-override-wosfq4"></input>
                                            </td>
                                        </tr>
                                        <!-- end of row-->
                                    </tbody>
                                </table>
                            </div>
                    </a-col>
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
                  @keydown="handleKeyDownes($event, index)"
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

                    <!-- <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-button  class="draft"   block @click="saveSalesEntryDraft()">
                                Save As Draft
                              </a-button>
                        </a-col> -->
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
import {
  onMounted,
  ref,
  toRefs,
  computed,
  getCurrentInstance,
  inject,
  watch,
} from "vue";
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
import fields from "./fields";
import ProductModal from "../../stock-management/purchases/Product/ProductModal.vue";
import TaxAddButton from "../../settings/taxes/AddButton.vue";
import WarehouseAddButton from "../../settings/warehouses/AddButton.vue";
import ProductAddButton from "../../product-manager/products/AddButton.vue";

import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import DatePicker from "../../../../common/components/common/calendar/DatePicker.vue";
import ReceiptSearchInput from "../../../../common/components/accounttrans/ReceiptSearchInput.vue";
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
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";  
import { reactive } from 'vue';

export default {
  props: ["username"],
  inject: ["globalVar"],
  components: {
    EyeOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    ReceiptSearchInput,
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
    // PrintModel,
    //DeleteConfirmationPopupModel,
    // BillReturnPrintModel,
  },
  setup(props, { emit }) {

const recentBill = reactive({
        data: []
        }); 

    const { loading, handlePaymentSubmit, rules } = apiAdmin();
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

    let spinning = ref(false);
    let selectedItermIndex = ref("");
    let formData = ref("Unknown");
    let recentEventId = ref("Unknown");
    let discountTypes = ref([]);
    const { orderItemColumns } = fields();
    const { salesItemColumns } = fields();
    formData = {
      order_date: new Date().toISOString().split("T")[0],
      invoice_date: new Date().toISOString().split("T")[0],
      party_id: 0,
      product_id: "",
      party_product_name: "",
      party_name: "",
      receipt_mode: "",
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
      payment_id: 0,
      selectedInvoice: localStorage.getItem("selectedInvoice"),
      items: [
        {
          index: 1,
          item_id: null,
          item_name: null,
          station: "",
          receipt_date: null,
           inst_type:"",
          inst_no:"",
          amount: "",
          short_amount: "",
         unique1:Math.random().toString(36).substring(2,7),
         recentBill:[]
        },
        {

          index: 2,
          item_id: null,
          item_name: null,
          station: "",
          receipt_date: null,
           inst_type:"",
          inst_no:"",
          amount: "",
          short_amount: "",
          recentBill:[]
        },
       
      ],

      invoiceitems: [
        {
          index: 1,
          item_id: null,
          item_name: null,
          unit_id: null,
          quantity: "",
          free: "",
          mrp: null,
          discount_value: 0,
          discount_rate: null,
          amount: null,
          maxquantity: 0,
          max_single_unit_price: 0,
          packing: null,
        },
      ],

      additems: [
        { index: 1, item_id: null, item_name: null, unit_id: null },
        { index: 2, item_id: null, item_name: null, unit_id: null },
        { index: 3, item_id: null, item_name: null, unit_id: null },
      ],
      total_discount: 0,
      invoice_value: 0,
      index: 0,
      additems: [
        { index: 1, item_id: null, item_name: null, unit_id: null },
        { index: 2, item_id: null, item_name: null, unit_id: null },
        { index: 3, item_id: null, item_name: null, unit_id: null },
      ],
      total_discount: 0,
      invoice_value: 0,
      //selectedPartyIds: [] ,
      index: 0,
    };

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
      recentEventId,
      // showDeleteConfirmation,
      //deleteItem,
    };

  },
  data() {

    return {
       
      recentBill: {data:[]},

      expenseCategorySelect: [],
      expenseCategories: [],
      spinning: false,
      selectedInvoice: localStorage.getItem("selectedInvoice"),
      isNumberVisible: false,
      isPaymentsModalVisible: false,
      isModalPopup: false,
      isCustomerVisible: false,
      isModalVisible: false,
      isReceiptModalVisible: false,
      isProuctsModalVisible: false,
      deleteConfirmationPopup: false,
      stockDateColor: "",
      isNumberVisible: false,
      //isPurchaseBillModalVisible: false,
      showDeletePopup: false,
      selectedItemIndex: null,
      showNarration: false,
      isOpensave: false,
      lastFocusedIndex: 0,

      dropsitems: [],
      totalAmount: 0,
      totalShortAmount: 0,
      totalIgstAmount: 0,
      totalDiscountAmount: 0,
      url: "your-url-here",
      addEditType: "add",

      successMessage: "Operation successful!",
      headers: [ "Account", "Station", "Date",'Inst. Type','Inst. No', "Amount", "Short Amount"],
       headers2: [ 'Date', 'invoice Number','Invoice Amount','Balance Amount','Paying Amount'],

      casheaders: [ "Account", "Station", "Date", "Amount", "Short Amount"],

    };
  },


mounted() {

  this.invoice();

  this.$nextTick(() => {
    this.$refs.expenseCategorySelect?.focus();
  });

  this.getInvoiceDetails();
  this.fetchReceiptmodes();
  this.setDefaultDate();
  [].forEach.call(
    document.querySelectorAll(".datepicker"),
    function (el, index) {
      if (index === 0) {
        el.style.visibility = "visible";
        let today = new Date().toISOString().split("T")[0];
        el.value = today;
      } else {
        el.style.visibility = "hidden";
      }
    }
  );
  [].forEach.call(
    document.querySelectorAll(".datepickers"),
    function (el, index) {
      if (index === 0) {
        el.style.visibility = "visible";
        let today = new Date().toISOString().split("T")[0];
        el.value = today;
      } else {
        el.style.visibility = "hidden";
      }
    }
  );
  document
    .getElementById("cashTable").style.display="none";

  document
    .getElementById("salesreceiptcreate")
    .addEventListener("keydown", this.handleKeyDowning);

  // ✅ ADD F8 / End key listener WITHOUT CHANGING ANY EXISTING CODE
  this.boundKeyUp = (e) => {
    if (e.keyCode === 119 || e.keyCode === 35) { // F8 or End
      e.preventDefault();
      this.saveSalesReturnEntry();
    }
  };
  document
    .getElementById("salesreceiptcreate")
    .addEventListener("keyup", this.boundKeyUp);

  this.autoFocusInput();
},

beforeDestroy() {
  // Remove F8/End listener to avoid memory leaks
  document
    .getElementById("salesreceiptcreate")
    .removeEventListener("keyup", this.boundKeyUp);
},


  methods: {

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

          amount: null,
          maxquantity: 0,
          max_single_unit_price: 0,
          packing: null,
          cgst: null,
          sgst: null,
          unique: Math.random().toString(36).substring(2, 7),
        })),
        invoiceitems: [
          {
            index: 1,
            item_id: null,
            item_name: null,
            unit_id: null,
            quantity: "",
            free: "",
            mrp: null,
            amount: null,
            maxquantity: 0,
            max_single_unit_price: 0,
            packing: null,
          },
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

      // Reset the value of the total_prod_count input field
      const totalProdCountInput = document.getElementById("total_prod_count");
      if (totalProdCountInput) {
        totalProdCountInput.value = (0).toFixed(2);
      }

      const totalQualityInput = document.getElementById(
        "total_quantity_details"
      );
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
        input.value = (0).toFixed(2); // Reset the value of each IGST input
      });
    },

    getDefaultDate() {
      return new Date(); // Returns the current date
    },
    hideEmptyDate(index) {
      if (!this.formData.items[index].date) {
        this.formData.items[index].date = "";
      }
    },
     updateSerialNumber(index,event) {


    this.formData.items = [...this.formData.items]; // Force Vue to detect changes
  },

    moveToNextField(event, index, nextPrefix) {
      const nextId = `${nextPrefix}${index}`;
      this.$nextTick(() => {
        const nextEl = document.getElementById(nextId);
        if (nextEl) {
          nextEl.focus();
        } else {
          console.error(`Element not found with id: ${nextId}`);
        }
      });
    },

    focusNext(event) {
    this.formData.receipt_mode = event.target.value;
    console.log("fertyujkjhgfdfgh", event.target.value);
    if (event.key === 'Enter') {
        if (event.target.value === "6") {
            const targetElement = document.getElementById("item_cash_product_name_0");
            if (targetElement) {
                targetElement.focus();
            }
        }
        else if (this.formData.receipt_mode && this.formData.receipt_mode.trim() !== '') {
            const targetElement = document.getElementById("item_product_name_0");
            if (targetElement) {
                targetElement.focus();
            }
        }
    }
},



    handleReceiptModeChange(event) {
      const selectedCategory = this.expenseCategories.find(
        (cat) => cat.id === this.formData.receipt_mode
      );
      // Show narration if "Cash Account" is selected
      this.showNarration = selectedCategory.bank_name === "Cash Account";
    },
    handleItemNameKeyDown(index, event) {
      const value = this.formData.items[index].item_name;
      console.log(
        `Item Name: Index ${index}, Value: ${value}, Key: ${event.key}`
      );

      if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();
        if (!value || value.trim() === "") {
          this.$nextTick(() => {
            document.getElementById(`item_product_name_${index}`).focus();
          });
          return;
        }
        this.moveToNextField(event, index, "item_product_quantity_");
      }
      if (event.shiftKey && event.key === "Tab") {
        // Handle shift+tab if needed for previous field
      }

    },


    handleChange(event)
      {
        this.formData.receipt_mode = event.target.value;
       // console.log("Selected receipt mode:",this.formData.receipt_mode);
        if(event.target.value==6)
      {
        document
      .getElementById("cashTable").style.display="inline-table";
      document
      .getElementById("otherTable").style.display="none";
      }
      else
      {
        document
      .getElementById("cashTable").style.display="none";
      document
      .getElementById("otherTable").style.display="inline-table";
      }
      },

    //   headerKeyevents
    // Add these new methods to your methods object

   


    checkotherstationtoDate(index, event) {
      if (event.key === 'Enter') {
        console.log("checking the condition")
        document.getElementById(`cash_datepicker_${index}`).focus();
      }
    },


    handleOtherStationKeyDown(index, event) {
    const value = this.formData.items[index].station;
    if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();
        this.$nextTick(() => {
            const dateField = document.getElementById(`cash_datepicker_${index}`);
            if (dateField) {
                dateField.focus();
            }
        });
    }
},

handleOtherDateKeyDown(index, event) {
    const value = this.formData.items[index].receipt_date;
    if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();
        if (!value) {
            this.formData.items[index].receipt_date = new Date().toISOString().split("T")[0];
        }
        this.$nextTick(() => {
            const instTypeField = document.getElementById(`item_product_disc_${index}`);
            if (instTypeField) {
                instTypeField.focus();
            }
        });
    }
},

handleInstTypeKeyDown(index, event) {
    const value = this.formData.items[index].inst_type;
    if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();
        if (!value || value.trim() === "") {
            this.formData.items[index].inst_type = "na"; // Default value if empty
        }
        this.$nextTick(() => {
            const instNoField = document.getElementById(`item_product_price_${index}`);
            if (instNoField) {
                instNoField.focus();
            }
        });
    }
},
handleInstNoKeyDown(index, event) {


const value = this.formData.items[index].inst_no;
console.log(`Inst No: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
const isShiftTab = event.shiftKey && event.key === "Tab";

if (isTabOrEnter && !isShiftTab) {
    event.preventDefault();
    if (!value || value.toString().trim() === "" || Number(value) <= 0 || value.toString().length <6) {
        console.log("Invalid inst_no, staying in field");
        this.$nextTick(() => {
            document.getElementById(`item_product_price_${index}`).focus();
        });
        return false;
    }
    console.log("Valid inst_no, moving to amount");
    this.$nextTick(() => {
        document.getElementById(`item_product_amounts_${index}`).focus();
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
if (event.key === "Escape" || event.keyCode === 27) {
    event.preventDefault();
    this.showconfirmBill();
}
},

handleKeyDownes(event) {
    console.log(`Narration: Key: ${event.key}, Shift: ${event.shiftKey}`);

    if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();
        this.$nextTick(() => {
            const saveButton = document.getElementById('saveF8');
            if (saveButton) {
                console.log("Focusing save button");
                saveButton.focus();
            } else {
                console.error("Save button not found");
            }
        });
    }
},

formatCurrency(value) {
  return value.toLocaleString("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
},


                            // CashKeyEvents
                            handleStationKeyDown(index, event) {
                            const value = this.formData.items[index].station;
                            console.log(
                                `Station: Index ${index}, Value: ${value}, Key: ${event.key}`
                            );


                            if (event.shiftKey && event.key === "Tab") {
                                event.preventDefault();
                                document.getElementById(`item_product_name_${this.lastFocusedIndex}`).focus();
                            }
                            },

                            handleInstTypeKeyDown(index, event) {
                                        const value = this.formData.items[index].inst_type;
                                        console.log(`Inst Type: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                                        const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                                        const isShiftTab = event.shiftKey && event.key === "Tab";


                                            if (event.shiftKey && event.key === "Tab") {
                                event.preventDefault();
                                document.getElementById(`cash_datepicker_${index}`).focus();
                                return;
                            }

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
                                        if (event.key === "Escape" || event.keyCode === 27) {
                                            event.preventDefault();
                                            this.showconfirmBill();
                                        }
                                    },

                            typeControll(index,event) {
                            let value = this.formData.items[index].inst_no.toString();

                            if(value.length === 6 ) {
                                    document.getElementById(`item_product_amount_${index}`).focus();
                            }
                        },

                            handleDateKeyDown(index, event) {
                            const value = this.formData.items[index].receipt_date;
                            console.log(`Date: Index ${index}, Value: ${value}, Key: ${event.key}`);

                            if (event.shiftKey && event.key === "Tab") {
                                event.preventDefault();
                                // Move back to the previous field (quantity)
                                document.getElementById(`item_product_quantity_${index}`).focus();
                                return;
                            }

                            if (event.key === "Tab" || event.key === "Enter") {
                                event.preventDefault();
                                if (!value) {
                                this.$nextTick(() => {
                                    document.getElementById(`datepicker_${index}`).focus();
                                });
                                return;
                                }
                                this.moveToNextField(event, index, "item_product_disc_");
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
                            console.log("Valid inst_no, moving to amounttt");
                            this.$nextTick(() => {
                                document.getElementById(`item_product_amounts_${index}`).focus();
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
                        if (event.key === "Escape" || event.keyCode === 27) {
                            event.preventDefault();
                            this.showconfirmBill();
                        }
                        },


                        handleAmountKeyDownCash(index, event) {
                        const value = this.formData.items[index].amount;
                        console.log(`Amount: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                        const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                        const isShiftTab = event.shiftKey && event.key === "Tab";

                        let   tempValue = Number(event.target.value);
                        if (tempValue > 0) {
                            this.recentBill.data.forEach((bill) => {
                                console.log("due",bill.due_amount,tempValue)
                                if(bill.due_amount>0 && tempValue>0)
                                {
                                  if (tempValue <= bill.due_amount) { 
                                    bill.paid_amount = tempValue; tempValue = 0; 
                                    } 
                                  else if (tempValue > bill.due_amount) { 
                                    bill.paid_amount = bill.due_amount; 
                                    tempValue = tempValue- bill.due_amount;  
                                      } 
                                                            
                                      }
                                
                            })
                        }  

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
                        document.getElementById(`item_product_amounts_${index}`).focus();
                    });
                    return false;
                }
                console.log("Valid amount, moving to short_amount");
                this.$nextTick(() => {
                    document.getElementById(`item_short_amounts_${index}`).focus();
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
            if (event.key === "Escape" || event.keyCode === 27) {
                event.preventDefault();
                this.showconfirmBill();
            }
            },


    handleShortAmountKeyDown(index, event) {
      const value = this.formData.items[index].short_amount;
      console.log(
        `Short Amount: Index ${index}, Value: ${value}, Key: ${event.key}`
      );

      if (event.shiftKey && event.key === "Tab") {
        event.preventDefault();
        document.getElementById(`item_product_amount_${index}`).focus();
        return;
      }

      if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();

        this.$nextTick(() => {
          const nextField = document.getElementById(`narrationFields`);
          if (nextField) {
            nextField.focus();
          } else {
            document.getElementById("saveF8")?.focus();
          }
        });
      }

    },

    handleShortAmountsKeyDown(index, event) {
      const value = this.formData.items[index].short_amount;
      console.log(
        `Short Amount: Index ${index}, Value: ${value}, Key: ${event.key}`
      );

      if (event.shiftKey && event.key === "Tab") {
        event.preventDefault();
        document.getElementById(`item_product_amounts_${index}`).focus();
        return;
      }

      if (event.key === "Tab" || event.key === "Enter") {
        event.preventDefault();

        this.$nextTick(() => {
          const nextField = document.getElementById(`narrationFields`);
          if (nextField) {
            nextField.focus();
          } else {
            document.getElementById("saveF8")?.focus();
          }
        });
      }
    },
    checkstationtoDate(index, event) {
      if (event.key === 'Enter') {
        console.log("checking the condition")
        document.getElementById(`datepicker_${index}`).focus();
      }
    },

    checkdatetoAmount(index, event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        const nextField = document.getElementById(
          `item_product_amount_${index}`
        );
        if (nextField) {
          nextField.focus();
        }
      } else if (event.keyCode === 119) {
        event.preventDefault();
        this.saveSalesReturnEntry();
      }
    },

    checkdatetoAmounts(index, event) {
                const value = this.formData.items[index].receipt_date;
                console.log(`Date: Index ${index}, Value: ${value}, Key: ${event.key}, Shift: ${event.shiftKey}`);
                const isTabOrEnter = event.key === "Tab" || event.key === "Enter";
                const isShiftTab = event.shiftKey && event.key === "Tab";

                if (isTabOrEnter && !isShiftTab) {
                    event.preventDefault();
                    if (!value || value.toString().trim() === "") {
                        console.log("Empty date, staying in field");
                        this.$nextTick(() => {
                            document.getElementById(`item_product_quantity_${index}`).focus();
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

                if (event.key === "Escape" || event.keyCode === 27) {
                    event.preventDefault();
                    this.showconfirmBill();
                }
            },

    checkAmounttoShortamount(index, event) {
      if (event.keyCode === 13) {
        document.getElementById(`item_short_amount_${index}`).focus();
      } else if (event.keyCode === 119) {
        this.saveSalesReturnEntry();
      }
    },
    checkAmounttoShortamounts(index, event) {
      if (event.keyCode === 13) {
        document.getElementById(`item_short_amounts_${index}`).focus();
      } else if (event.keyCode === 119) {
        this.saveSalesReturnEntry();
      }
    },
    checkshortamounttoproductname1(index, event) {
      if (event.keyCode === 13) {
        document.getElementById(`item_product_name_${index + 1}`).focus();
      } else if (event.keyCode === 119) {
        this.saveSalesReturnEntry();
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

    invoice(){
        console.log("akash", this.formData.party_id)
        
      const billNumberUrl = `sales/crNumber/`+this.formData.party_id;

axiosAdmin.get(billNumberUrl)
  .then((response) => {
    console.log("heeeeeeeeeeeeeee",response.data.recentBill[0].invoiceDate);
    console.log(this.recentBill)
    // this.recentBill.data = response.data.recentBill;
    this.recentBill.data = response.data.recentBill.filter(bill => bill.due_amount > 0);
    
     console.log("Updated invoiceDate:", this.recentBill.data);
  })
  .catch((error) => {
    console.error("API Error:", error); // Handle errors
  });

    },

    formatNumber(num) {
      return parseFloat(num).toFixed(2);
    },

    toggleDropdowns() {
      this.isOpensave = !this.isOpensave;
    },

    handleEnterKey(event) {

      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        this.toggleDropdowns();
      }
    },
    handlekeyup() {

      console.log("checking",this.formData.receipt_mode)
      if (this.formData.receipt_mode!== '') {
        const targetElement = document.getElementById("item_product_name_0");
        if (targetElement) {
          targetElement.focus();
        }
      }
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

            this.updateTotalProd(index);

        },

    setDefaultDate() {
      const today = new Date().toISOString().split("T")[0];
      this.formData.items.forEach((item) => {
        if (!item.receipt_date) {
          item.receipt_date = today;
        }
      });
    },

    onlyForCurrency($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (
        (keyCode < 48 || keyCode > 57) &&
        (keyCode !== 46 || this.price.indexOf(".") != -1)
      ) {
        $event.preventDefault();
      }
      if (
        this.price != null &&
        this.price.indexOf(".") > -1 &&
        this.price.split(".")[1].length > 1
      ) {
        $event.preventDefault();
      }
    },

    onlyAplhaChar(e) {
      const char = String.fromCharCode(e.which);
      const allowedCharacters = /^[a-zA-Z0-9.,\-\s]*$/;

      if (!allowedCharacters.test(char)) {
        e.preventDefault();
      }
    },



    handleFocus() {
      this.$nextTick(() => {
        const firstInput = document.getElementById("form_item_party_name");
        if (firstInput) {
          firstInput.focus();
          var that = this;
          setTimeout(() => {
            if (that.selectedInvoice == "null") {
              this.showModal();
            }
          }, 600);
        }
      });
    },

    gotoNext(index, event) {
      console.log(event.keyCode, Number(this.formData.items[index].quantity));
      if (
        event.keyCode == 13 &&
        Number(this.formData.items[index].quantity) > 0
      ) {
        console.log("Enter key pressed, moving to Free field");
        document.getElementById(`item_product_free_${index}`).focus();
        event.preventDefault();
        return false;
      } else if (event.key === "F8" || event.keyCode === 119) {
      } else if (event.keyCode == 27) {
        this.showconfirmBill();
      }
    },

    gotoNextFree(index, event) {
      console.log(event.keyCode, Number(this.formData.items[index].free));
      if (event.keyCode == 13) {
        console.log("Enter key pressed, moving to Price field");
        document.getElementById(`item_product_free_${index}`).focus();
        event.preventDefault();
        return false;
      } else if (event.key === "F8" || event.keyCode === 119) {
      } else if (event.keyCode == 27) {
        this.showconfirmBill();
      }
    },
    autoFocusInput() {
      this.$nextTick(() => {
        if (this.$refs.DateInput) {
          this.$refs.DateInput.$el.focus();
        } else {
          console.log("DateInput ref is not available yet");
        }
      });
    },

    handleSuccess(xid) {
      this.isProuctsModalVisible = false;
      this.isModalVisible = false;
      this.focusElement("item_product_name_0");
      ////console.log('Success:', xid);
    },




focusElement() {
    this.$nextTick(() => {
        const element = document.getElementById("form_item_bill_number");
        if (element) {
            element.focus();
        } else {
            console.warn("Element with ID 'form_item_bill_number' not found in the DOM.");
        }
    });
},

    handleClosepopup() {
      this.deleteConfirmationPopup = false;
    },

    handleBlur() {
      ////console.log('Focus removed from input');
      this.showMessage = true;
    },

    handleKeyDown(event) {
      if (event.key === "Escape" || event.keyCode === 27) {
        this.showconfirmBill();
        this.autoFocusInput();
      }

      else if (event.keyCode === 9 && this.formData.party_id > 0) {
        event.preventDefault();
        document
          .querySelector("textarea[v-model\\:value='formData.narration']")
          .focus();
      } else if (
        (event.keyCode === 13 || event.keyCode === 9) &&
        this.formData.party_id > 0
      ) {
        this.isModalVisible = false;
        document
          .getElementById("item_product_name_" + this.selectedItermIndex)
          .focus();
      } else if (
        event.keyCode !== 13 &&
        event.keyCode !== 9 &&
        this.formData.party_id > 0
      ) {
        this.isModalVisible = true;
      }
    },

    handleKeyDowning(event) {
      console.log("event", event.target.id);
      this.recentEventId = event;
      if (event.key === "Escape" || event.keyCode === 27) {
        this.showconfirmBill();
        this.focusEelmentss();
        event.preventDefault();
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


    showPaymentModal() {
      ////console.log("Payment called");
      this.isPaymentsModalVisible = true;
      document.getElementById("amount_0").focus();
      $event.preventDefault();
    },

    inputData($event) {
      // Value will update reactively
      $event.target.value;
    },

    getCurrentDate() {
      // returns the current date in the format YYYY-MM-DD
      return new Date().toISOString().split("T")[0];
    },

    showModal() {
      this.isReceiptModalVisible = true;
      ////console.log(33);
      //this.$refs.dummykeyboard.focus();
    },

    handleCloseCustomer() {
      this.isReceiptModalVisible = false;
      if(this.isReceiptModalVisible === false){
            
             this.invoice();
           }
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

    // focusEelment() {
    //   ////console.log('Success:');
    //   document.getElementById("form_item_bill_number").focus();
    // },

    focusEelmentss() {
      document.querySelector(".mobilenumbering").focus();
    },

    focusproductEelment() {
      document.getElementById("item_product_quantity_0").focus();
    },

                updateParent(selectedParty) {
                console.log("Selected Party ID:", selectedParty.id);
                console.log("Selected Item Index:", this.selectedItermIndex);

              this.formData.party_id = selectedParty.id.toString();
                // if(selectedParty){
                // this.formData.items[this.selectedItermIndex].item_name = selectedParty.name
                // }


                if (!this.formData.items[this.selectedItermIndex]) {
                    this.formData.items[this.selectedItermIndex] = {};
                }
               console.log("selectedParty.party_name",selectedParty.party_name)
               console.log("selectedParty.name",selectedParty.name)
                

               // Update item details
               this.formData.items[this.selectedItermIndex].item_id = selectedParty.id;
                 this.formData.items[this.selectedItermIndex].item_name = selectedParty.party_name;
                this.formData.items[this.selectedItermIndex].station = selectedParty.station;
       
                // Update input fields for both tables
                if(selectedParty.party_name !== undefined){
            
           
                document.getElementById(`item_product_name_${this.selectedItermIndex}`).value =
                    this.formData.items[this.selectedItermIndex].item_name;
                document.getElementById(`item_cash_product_name_${this.selectedItermIndex}`).value =
                    this.formData.items[this.selectedItermIndex].item_name;
                    console.log("selectedParty.station",selectedParty.station)
                document.getElementById(`item_product_quantity_${this.selectedItermIndex}`).value =
                    selectedParty.station;
                document.getElementById(`item_cash_product_station_${this.selectedItermIndex}`).value =
                    selectedParty.station;
                }
                else{
                  document.getElementById(`item_product_name_${this.selectedItermIndex}`).value = selectedParty.name
                  console.log("selectedParty.station",selectedParty.station)
                  
                  document.getElementById(`item_product_quantity_${this.selectedItermIndex}`).value =selectedParty.station
                  
                   //for case account
                   document.getElementById(`item_cash_product_name_${this.selectedItermIndex}`).value =selectedParty.name
                   document.getElementById(`item_cash_product_station_${this.selectedItermIndex}`).value =selectedParty.station
                }

                const openingBalance = parseFloat(selectedParty.opening_balance) || 0;
                // document.getElementById("balance_amt").innerHTML = openingBalance.toFixed(2);

                // Make datepickers visible
                document.getElementById(`datepicker_${this.selectedItermIndex}`).style.visibility = "visible";
                document.getElementById(`cash_datepicker_${this.selectedItermIndex}`).style.visibility = "visible";


                
                // Focus logic based on receipt_mode
                this.$nextTick(() => {
                    if (this.formData.receipt_mode === "6") {
                        // Cash table sequence: Station → Date → Amount → Short Amount → Narration → Save
                        const stationField = document.getElementById(`item_cash_product_station_${this.selectedItermIndex}`);
                        if (stationField) {
                            stationField.focus();
                            stationField.style.backgroundColor = "#FFD451";
                            stationField.addEventListener("blur", () => {
                                stationField.style.backgroundColor = "";
                            }, { once: true });
                        }
                    } else {
                        // Other table sequence: Station → Date → Inst Type → Inst No → Amount → Short Amount → Narration → Save
                        const stationField = document.getElementById(`item_product_quantity_${this.selectedItermIndex}`);
                        if (stationField) {
                            stationField.focus();
                            stationField.style.backgroundColor = "#FFD451";
                            stationField.addEventListener("blur", () => {
                                stationField.style.backgroundColor = "";
                            }, { once: true });
                        }
                    }
                });
            },

           handleFocus1(index) {
  console.log("Element focused, waiting 1 second...");

  // Delay opening the modal by 1 second (1000 ms)
  setTimeout(() => {
    console.log("Modal opened after focus delay!");
    this.selectedItermIndex = index;
    this.isReceiptModalVisible = true;
  }, 1000);
},


    focusproductFreeEelment() {
      document.getElementById("item_product_free_0").focus();
    },
    showProductModal(event, index) {
      
      if (event.shiftKey && event.key === "Tab" && documentName) {
        event.preventDefault();
        console.log("gfghjkjhgvfcdfghjk")
        document.getElementById(`item_short_amount_${index -1}`).focus();
      }

      if (event.shiftKey && event.key === "Tab") {
        event.preventDefault();
        console.log("gfghjkjhgvfcdfghjk")
        document.getElementById(`item_short_amount_${index -1}`).focus();
      }

      if (event.shiftKey && event.key === "Tab") {
        event.preventDefault();
        console.log("gfghjkjhgvfcdfghjk")
        document.getElementById(`item_short_amount_${index -1}`).focus();
      }
      const handleSno = index;
      console.log("checking the account value : ",`item_product_name_${index}`)
       
      if (
        event.key === "Enter" ||
        event.keyCode === 13 ||
        event.key === " " ||
        event.keyCode === 32
      ) {
        console.log("akash")
        this.selectedItermIndex = index;
        this.isReceiptModalVisible = true;
      } else if (
        (event.keyCode === 9 || event.keyCode === 13) &&
        event.target.id === `item_product_name_${index}`
      ) {
        event.preventDefault(); // Prevent default tabbing behavior

        const narrationField = document.getElementById(
          "item_product_quantity_"
        );
        const nextField = document.getElementById(
          `item_product_name_${index + 1}`
        );

        if (event.target.id === `item_product_name_${index}`) {
          // First press moves focus to narrationFields
          if (narrationField) {
            narrationField.focus();
          }
        } else if (event.target.id === "item_product_quantity_") {
          if (nextField) {
            nextField.focus();
          }
        }
      } else if (event.key === "F8" || event.keyCode === 119) {
        this.saveSalesReturnEntry();
      }
    },

    onclose() {
      this.$router.push({ name: "admin.dashboard.index" });
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
      if (inputField === "order_date") {
        this.stockDateColor = "#ffd451";
      }
    },
    resetColorOnBlur(inputField) {
      if (inputField === "order_date") {
        this.stockDateColor = "";
      }
    },
    handleProductModal() {
      //console.log("Product closed");
      this.isProuctsModalVisible = false;
      //this.focusProductList();
    },

    focusinputvalue(event) {
      event.target.select();
    },

    formatCurrency(value) {
      return value.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },
    formatOfAmount(value) {
      var res = new Intl.NumberFormat("en-IN").format(value);
      return res;
    },
    revertFormattedNumber(formattedValue) {
      return parseFloat(formattedValue.replace(/,/g, ""));
    },
    checkMaxQuantity(index, event) {
      //console.log(this.formData.items[index].maxquantity,event.target.value,event.key)
      if (
        Number(this.formData.items[index].quantity) >
        Number(this.formData.items[index].maxquantity)
      ) {
        // notification.warning({
        //         placement:  "bottomRight",
        //         message: "Stock Going to be Negative!",
        //     });
      }
      if (
        (event.key == "Tab" || event.key == "Enter") &&
        Number(event.target.value) <= 0
      ) {
        ////console.log("Quantity is Zero");
        document
          .getElementById("item_product_quantity_" + this.selectedItermIndex)
          .focus();
        return false;
      } else if (event.key == "Tab" || event.key == "Enter") {
        document
          .getElementById("item_product_free_" + this.selectedItermIndex)
          .focus();
        event.preventDefault();
        return false;
      }
    },
    checkSingleItemPrice(index, event) {
      if (
        Number(this.formData.items[index].single_unit_price) >
        Number(this.formData.items[index].max_single_unit_price)
      ) {
      }
      if (
        (event.key == "Tab" || event.key == "Enter") &&
        Number(event.target.value) <= 0
      ) {
        //console.log("Quantity is Zero");
        document
          .getElementById("item_product_quantity_" + this.selectedItermIndex)
          .focus();
        return false;
      } else if (event.key == "Enter" && Number(event.target.value) > 0) {
        console.log(
          "222",
          document.getElementById(
            "item_product_disc_type_" + this.selectedItermIndex
          )
        );
        //document.getElementById("item_product_disc_"+this.selectedItermIndex).focus();
        document
          .getElementById("item_product_disc_type_" + this.selectedItermIndex)
          .focus();

        event.preventDefault();
        return false;
      } else if (event.key == undefined) {
        document
          .getElementById("item_product_free_" + this.selectedItermIndex)
          .focus();
      }
      // else if (event.key === "F8" || event.keyCode === 119)
      //                 {
      //     this.saveSalesReturnEntry();
      // }
      else if (event.keyCode === 27) {
        this.showconfirmBill();
        event.preventDefault();
      }
    },
    checkDisc(index, event) {
      // console.log('checkDisc',this.formData.items[index].discount_rate)
      if (event.key == "Enter") {
        document
          .getElementById("item_product_name_" + (this.selectedItermIndex + 1))
          .focus();
        event.preventDefault();
        return false;
      }
      else if (event.keyCode == 27) {
        this.showconfirmBill();
        event.preventDefault();
      }
    },


    getInvoiceDetails() {
    if (this.selectedInvoice !== "null") {
        this.formData.voucher_number = this.selectedInvoice;
        this.spinning = true;
        axiosAdmin
            .get("receipt/getInvoicereceipt/" + this.selectedInvoice)
            .then(response => {
                this.spinning = false;
                const invoiceData = response.data.invoiceData;
                const partyDetails = response.data.partyDetails;
                console.log("API Response Data:", invoiceData);
                if (invoiceData && invoiceData.length > 0) {
                    this.formData.narration = invoiceData[0].narration;
                    this.formData.receipt_mode = invoiceData[0].receipt_mode;
                    console.log(invoiceData[0].receiptpayment_mode);
                    this.formData.order_date = invoiceData[0].order_date;
                    const formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
        });

    this.formData.subtotal = formatter.format(parseFloat(invoiceData[0].subtotal));
       const formatters = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
     });

this.formData.short_subtotal = formatters.format(parseFloat(invoiceData[0].short_subtotal));
                    this.formData.items = invoiceData.map((data, index) => {
                        return {
                            inst_no: data.inst_no,
                            inst_type: data.inst_type,
                            amount: data.amount,
                            item_id :data.party_id,
                            short_amount: data.short_amount,
                            receipt_date: data.receipt_date,
                            item_name: partyDetails[0].party_name,
                        };
                    });

                    console.log("Mapped Items:", this.formData.items);
                } else {
                    console.warn("No invoice data available.");
                    this.formData.items = [];
                }
            })
            .catch(errorResponse => {
                this.spinning = false;
                console.error("Error fetching invoice details: ", errorResponse);
            });
    }
},
    success(response) {
      this.formData.selectedInvoice = this.formData.bill_number;
      console.log("rely=>>", response.message);
      this.loading = false;
      notification.success({
        placement: "bottomRight",
        message: "Receipt Bill saved successfully ! ",
        description: response.message,
      });
      this.resetFormData();
      this.$router.push({
        name: "admin.receipt.index",
      });
    },
    successDraft(response) {
      this.formData.selectedInvoice = this.formData.bill_number;
      //console.log('rely=>>',response.message);
      this.loading = false;
      notification.success({
        placement: "bottomRight",
        message: "Draft Order saved successfully !    ",
        description: response.message,
      });
      //this.isPaymentsModalVisible= true;
    },

     handleKeyDownes(event) {

         console.log("Last Focused Index:", this.lastFocusedIndex);
  console.log("Key pressed:", event.key);
  if (event.shiftKey && event.key === "Tab") {
        event.preventDefault();
        // Move back to the previous field (quantity)
        document.getElementById(`item_short_amount_${ this.lastFocusedIndex -1}`).focus();
        return;
      }

  // Check for Enter or Tab key press
  if (event.key === 'Enter' || event.key === 'Tab') {
    event.preventDefault(); // Prevent default behavior
    console.log("checking fghjk : ",this.formData.narration)
    if (this.formData.narration !== undefined && this.formData.narration !== "") {
      const saveButton = document.getElementById('saveF8');
      if (saveButton) {
        saveButton.focus();
      }
    }

    else{
      //   const nextField = document.getElementById(`item_product_name_${ this.lastFocusedIndex}`);
      //  console.log("checking the value",nextField)
      // if (nextField) {
      //   nextField.focus();
      // }
    }
  }
},
    validateSales() {
      if (
        this.formData.party_id == undefined ||
        this.formData.party_id == "" ||
        this.formData.party_id == ""
      ) {
        ////console.log('+>',this.formData.bill_number)
        document.getElementById("form_item_party_name").focus();

        return false;
      } else {
        let itemAvailable = false;
        this.formData.items.forEach((element) => {
          if (
            itemAvailable == false &&
            element.item_id != undefined &&
            element.item_id != "" &&
            element.quantity > 0 &&
            element.single_unit_price > 0
          ) {
            itemAvailable = true;
          }
        });

        if (itemAvailable == false) {
          notification.warning({
            placement: "bottomRight",
            message: "Please add one item !",
          });
          document.getElementById("item_product_name_0").focus();
          return false;
        }
        return true;
      }
    },

    fetchReceiptmodes() {
            try {
                axiosAdmin
                    .get(`receiptbank?fields=id,xid,bank_name`)
                    .then((response) => {
                        this.expenseCategories = response.data; // Assuming the API returns the expense categories
                    })
                    .catch((error) => {
                        console.error("Error fetching expense details:", error);
                    });
            } catch (error) {
                console.error("Error fetching expense categories:", error);
            }
        },


    saveSalesReturnEntry(event) {
      
let totalPaidAmount = this.recentBill.data.reduce((sum, prod) => sum + (Number(prod.paid_amount) || 0), 0);
console.log("Total paid amount======>",totalPaidAmount)
 let enteredAmount = this.formData.items[0].amount;
console.log("Total tempValue======>",enteredAmount)

if (this.recentBill.data && this.recentBill.data.length>0 && totalPaidAmount !== enteredAmount && totalPaidAmount !== 0) {
    message.error("Entered amount does not match the total paid amount.");
    document.getElementById("item_product_amounts_0").focus();
    return; 
  }

      console.log("submit", event);
      this.spinning = true;
      this.formData.invoiceitems= this.recentBill.data;
      axiosAdmin
        .post("receiptdetalis", this.formData)
        .then((response) => {
          //console.log(response)
          // Toastr Notificaiton
          this.spinning = false;
          this.success(response);
        })
        .catch((errorResponse) => {
          this.spinning = false;
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

          //loading.value = false;
        });
    },


    updateFreehead() {
      let count = 0;
      let free = 0;
      this.formData.items.forEach((elem) => {
        if (
          elem.free != undefined &&
          elem.free != null &&
          Number(elem.free) > 0
        ) {
          //console.log("quantity",elem)
          //count =count+1;
          free = free + Number(elem.free);
        }
      });

      document.getElementById("total_free_details").value = free.toFixed(2);
    },

    updateFree() {
      let count = 0;
      let free = 0;
      this.formData.items.forEach((elem) => {
        if (
          elem.free != undefined &&
          elem.free != null &&
          Number(elem.free) > 0
        ) {
          //console.log("quantity",elem)
          //count =count+1;
          free = free + Number(elem.free);
        }
      });

      document.getElementById("total_free_details").value = free.toFixed(2);
    },

    updateAgg() {
      let count = 0;
      let quantity = 0;
      this.formData.items.forEach((elem) => {
        if (
          elem.quantity != undefined &&
          elem.quantity != null &&
          Number(elem.quantity) > 0
        ) {
          //console.log("quantity",elem)
          //count =count+1;
          quantity = quantity + Number(elem.quantity);
        }
      });
       document.getElementById("total_prod_count").value="Total : "+count+" Product";
      document.getElementById("total_quantity_details").value =
        quantity.toFixed(2);
    },


    updateOtherTableTotals(index) {
  console.log("Updating totals for otherTable, Index:", index);
  let count = 0;
  let totalAmount = 0;
  let totalShortAmount = 0;

  this.formData.items.forEach((elem) => {
    if (elem.item_name && elem.item_name.trim() !== "") { // Count rows with a valid item_name
      count += 1;
      totalAmount += parseFloat(elem.amount) || 0;
      totalShortAmount += parseFloat(elem.short_amount) || 0;
    }
  });

  const totalProdCount = document.getElementById("total_prod_count");
  const totalAmountField = document.getElementById("total_amount");
  const totalShortAmountField = document.getElementById("total_short_amount");

  if (totalProdCount) {
    totalProdCount.value = `Total: ${count} Product${count !== 1 ? 's' : ''}`;
  }
  if (totalAmountField) {
    totalAmountField.value = this.formatCurrency(totalAmount.toFixed(2));
  }
  if (totalShortAmountField) {
    totalShortAmountField.value = this.formatCurrency(totalShortAmount.toFixed(2));
  }
},

handleClick(index, event){

   this.selectedItermIndex = index;
      this.isReceiptModalVisible = true;
  
},

    updateTotalProd(index,event) {



  console.log("Setting lastFocusedIndex:", index);
  this.lastFocusedIndex = index;
  let count = 0;
  let totalAmount = 0;
  let totalShortAmount = 0;

  this.formData.items.forEach((elem) => {
    if (elem.item_id != undefined && elem.item_id != null && elem.item_id > 0) {
      count += 1;
      totalAmount += parseFloat(elem.amount) || 0;
      totalShortAmount += parseFloat(elem.short_amount) || 0;
    }
  });

  document.getElementById("total_prod_count").value = "Total : " + count + " Product";
  document.getElementById("total_amount").value = this.formatCurrency(totalAmount.toFixed(2));
   document.getElementById("total_amount1").value = this.formatCurrency(totalAmount.toFixed(2));
  document.getElementById("total_short_amount").value = this.formatCurrency(totalShortAmount.toFixed(2));
    document.getElementById("total_short_amount1").value = this.formatCurrency(totalShortAmount.toFixed(2));
},


    onFocus(index) {
        this.focusedIndex = index;
        console.log(`Focused on item_product_name_${index}, S.No visible as ${this.getSNo(index)}`);
        this.updateTotalProd(index);
     },




    foucsproducting() {
      document.getElementById("item_product_name_0").focus();
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
            name: `admin.receipt.index`,
          });
        },
      onCancel() {
    that.$emit("closed");
   },

        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
      });

      this.$nextTick(() => {
        const handleKeydown = (e) => {
          if (modal && (e.key === "ArrowLeft" || e.key === "ArrowRight")) {
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

    deleteConfirmation() {
      Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
          "div",
          { style: "color:red;" },
          "Are you sure you want to delete the selected Record?"
        ),
        // autoFocusButton:'ok',
        onOk() {
          console.log("OK");
        },
        onCancel() {
          console.log("Cancel");
        },
      });
    },
    updateDiscount(that) {
      this.formData.items[this.selectedItermIndex].discount_type_id =
        document.getElementById(
          "item_product_disc_type_" + this.selectedItermIndex
        ).value;
      console.log(
        this.formData.items[this.selectedItermIndex].discount_type_id
      );
      if (
        document.getElementById(
          "item_product_disc_type_" + this.selectedItermIndex
        ).value == 1
      ) {
        this.formData.items[this.selectedItermIndex].discount_rate = 0;
        document
          .getElementById("item_product_name_" + (this.selectedItermIndex + 1))
          .focus();
        document.getElementById(
          "item_product_disc_" + this.selectedItermIndex
        ).value = 0;
      } else {
        document
          .getElementById("item_product_disc_" + this.selectedItermIndex)
          .focus();
      }
    },

    // dublicateentery//

    // end dublicate//

  },
  computed: {

     totalAmount1() {
        return this.recentBill.data.reduce((sum, prod) => sum + (Number(prod.due_amount) || 0), 0).toFixed(2);
    },
    paidTotal() {
        return this.recentBill.data.reduce((sum, prod) => sum + (Number(prod.paid_amount) || 0), 0).toFixed(2);
    },
    
    grandTotal() {
      return this.formData.items.reduce(
        (total, item) => total + Number(item.amount),
        0
      );
    },



    totalAmount() {
      return this.items.reduce(
        (sum, item) => sum + (parseFloat(item.amount) || 0),
        0
      );
    },
    totalShortAmount() {
      return this.items.reduce(
        (sum, item) => sum + (parseFloat(item.short_amount) || 0),
        0
      );
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
#item_product_name_1 {
  pointer-events: none;
  cursor: pointer;
}
#item_product_quantity_1{
  pointer-events: none;
  cursor: pointer;
}
#cash_datepicker_1{
  pointer-events: none;
  cursor: pointer;
}
#item_product_disc_1{
  pointer-events: none;
  cursor: pointer;
}
#item_product_price_1{
  pointer-events: none;
  cursor: pointer;
}
#item_product_amounts_1{
  pointer-events: none;
  cursor: pointer;
}
#item_short_amounts_1{
  pointer-events: none;
  cursor: pointer;
}
#item_cash_product_name_1{
  pointer-events: none;
  cursor: pointer;
}
#item_cash_product_station_1{
  pointer-events: none;
  cursor: pointer;
}
#item_product_amount_1{
  pointer-events: none;
  cursor: pointer;
}
#item_short_amount_1{
  pointer-events: none;
  cursor: pointer;
}








</style>



