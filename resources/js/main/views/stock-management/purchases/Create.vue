<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.${orderPageObject.menuKey}`)" @back="() => $router.go(-1)" class="p-0">
                <template #extra>
                    <a-button type="primary" :loading="loading" @click="onSubmit" block>
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        {{ $t("common.save") }}
                    </a-button>
                </template>
            </a-page-header>
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
                        <!-- <a-form-item :label="$t(`stock.party_name`)" name="party_name":help="rules.party_name ? rules.party_name.message : null
                            " :validateStatus="rules.party_name ? 'error' : null" class="required">
                            <a-input v-model:value="formData.party_name" :placeholder="$t('common.placeholder_default_text', [
                                $t('stock.party_name'),
                            ])
                                " />
                        </a-form-item> -->
                        <a-form-item :label="$t('stock.party_name')" name="party_name" ref="input"
                            :help="rules.party_name ? rules.party_name.message : null"
                            :validateStatus="rules.party_name ? 'error' : null" class="required">
                            <a-input v-model:value="formData.party_name"
                                :placeholder="$t('common.placeholder_default_text', [$t('stock.party_name')])"
                                @focus="showModal"  @blur="" />
                        </a-form-item>
                    </a-col>

                    <!--- modal-->
                    <SalesModel
                    v-if="isModalVisible"
                    :visible="isModalVisible"
                    :formData="formData"
                    :url="url"
                    :addEditType="addEditType"
                    :pageTitle="pageTitle"
                    :successMessage="successMessage"
                    @addEditSuccess="handleSuccess"
                    @closed="handleClose"
                  />

                    <!-- end new modal-->
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.bill_number`)" name="bill_number" :help="rules.bill_number ? rules.bill_number.message : null
                            " :validateStatus="rules.bill_number ? 'error' : null" class="required">
                            <a-input v-model:value="formData.bill_number" :placeholder="$t('common.placeholder_default_text', [
                                $t('stock.bill_number'),
                            ])
                                " />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item 
                          :label="$t('stock.stock_date')" 
                          name="stock_date" 
                          :help="rules.stock_date ? rules.stock_date.message : null" 
                          :validateStatus="rules.stock_date ? 'error' : null"
                        >
                          <a-date-picker 
                            v-model:value="formData.stock_date" 
                            :format="appSetting.date_format" 
                            valueFormat="YYYY-MM-DD" 
                            style="width: 100%" 
                          />
                        </a-form-item>
                      </a-col>
                </a-row>

                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.mobile_number`)" name="mobile_number" :help="rules.mobile_number ? rules.mobile_number.message : null
                            " :validateStatus="rules.mobile_number ? 'error' : null" class="required">
                            <a-input v-model:value="formData.mobile_number" :placeholder="$t('common.placeholder_default_text', [
                                $t('stock.mobile_number'),
                            ])
                                " />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.customer_name`)" name="customer_name" :help="rules.customer_name ? rules.customer_name.message : null
                            " :validateStatus="rules.customer_name ? 'error' : null" class="required">
                            <a-input v-model:value="formData.customer_name" :placeholder="$t('common.placeholder_default_text', [
                                $t('stock.customer_name'),
                            ])
                                " />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t(`stock.address`)" name="address" :help="rules.address ? rules.address.message : null
                            " :validateStatus="rules.address ? 'error' : null" class="required">
                            <a-input v-model:value="formData.address" :placeholder="$t('common.placeholder_default_text', [
                                $t('stock.address'),
                            ])
                                " />
                        </a-form-item>
                    </a-col>
                </a-row>


                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-table :row-key="(record) => record.xid" :dataSource="selectedProducts"
                            :columns="orderItemColumns" :pagination="false">
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.dataIndex === 'name'">
                                    {{ record.name }} <br />
                                    <small>
                                        <a-typography-text code v-if="record.product_type != 'service'">
                                            {{ $t("product.avl_qty") }}
                                            {{
                                                `${record.stock_quantity}${record.unit_short_name}`
                                            }}
                                        </a-typography-text>
                                    </small>
                                </template>
                                <template v-if="column.dataIndex === 'unit_quantity'">
                                    <a-input-number id="inputNumber" v-model:value="record.quantity"
                                        @change="quantityChanged(record)" :min="0" />
                                </template>
                                <template v-if="column.dataIndex === 'single_unit_price'">
                                    {{ formatAmountCurrency(record.single_unit_price) }}
                                </template>
                                <template v-if="column.dataIndex === 'total_discount'">
                                    {{ formatAmountCurrency(record.total_discount) }}
                                </template>
                                <template v-if="column.dataIndex === 'total_tax'">
                                    {{ formatAmountCurrency(record.total_tax) }}
                                </template>
                                <template v-if="column.dataIndex === 'subtotal'">
                                    {{ formatAmountCurrency(record.subtotal) }}
                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button type="primary" @click="editItem(record)" style="margin-left: 4px">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                    <a-button type="primary" @click="showDeleteConfirm(record)"
                                        style="margin-left: 4px">
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                    </a-button>
                                </template>
                            </template>
                            <template #summary>
                                <a-table-summary-row>
                                    <a-table-summary-cell :col-span="4"></a-table-summary-cell>
                                    <a-table-summary-cell>
                                        {{ $t("product.subtotal") }}
                                    </a-table-summary-cell>
                                    <a-table-summary-cell>
                                        {{ formatAmountCurrency(productsAmount.tax) }}
                                    </a-table-summary-cell>
                                    <a-table-summary-cell :col-span="2">
                                        {{
                                            formatAmountCurrency(productsAmount.subtotal)
                                        }}
                                    </a-table-summary-cell>
                                </a-table-summary-row>
                            </template>
                        </a-table>
                    </a-col>
                </a-row>

                <!-- total disc-->
                <a-row :gutter="16" class="mt-10 disc">
                    <a-col :xs="12" :sm="12" :md="13" :lg="13">
                        {{ $t("stock.totals_disc") }}
                    </a-col>
                    <a-col :xs="12" :sm="12" :md="3" :lg="3">
                        <span>:</span>
                    </a-col>
                    <a-col :xs="12" :sm="12" :md="3" :lg="3">
                        <span>{{ formatAmountCurrency(formData.tax_amount) }}</span>
                        <!-- <span>{{ formatCurrency(formData.tax_amount) }}</span> -->
                    </a-col>
                </a-row>
                <!-- end of total disc-->

                <!--- new design-->
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <fieldset>
                            <legend class="basicdetalis">
                                Discount Info
                            </legend>
                            <a-row :gutter="16" class="mt-10">
                                <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                    {{ $t("stock.total_item") }}

                                </a-col>
                                <a-col :xs="12" :sm="12" :md="3" :lg="3">

                                    <span>:</span>
                                </a-col>
                                <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                    <span>{{ formatAmountCurrency(formData.tax_amount) }}</span>
                                    <!-- <span>{{ formatCurrency(formData.tax_amount) }}</span> -->
                                </a-col>
                            </a-row>
                            <a-row :gutter="16" class="mt-10">
                                <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                    {{ $t("stock.scheme") }}

                                </a-col>
                                <a-col :xs="12" :sm="12" :md="3" :lg="3">

                                    <span>:</span>
                                </a-col>
                                <a-col :xs="12" :sm="12" :md="4" :lg="4">

                                    <span>{{ formatAmountCurrency(formData.tax_amount) }}</span>
                                </a-col>
                            </a-row>
                        </fieldset>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <fieldset>
                            <legend class="basicdetalis">
                                Tax Info
                            </legend>
                            <a-row :gutter="16" class="mt-10">
                                <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                    {{ $t("stock.igst") }}

                                </a-col>
                                <a-col :xs="12" :sm="12" :md="3" :lg="3">

                                    <span>:</span>
                                </a-col>
                                <a-col :xs="12" :sm="12" :md="4" :lg="4">
                                    <span>{{ (formData.tax_amount) }}</span>
                                </a-col>
                            </a-row>
                        </fieldset>
                    </a-col>
                    <!-- table design-->
                    <a-col :xs="24" :sm="24" :md="10" :lg="10" class="tables">
                        <a-table :row-key="(record) => record.xid" :dataSource="selectedProducts"
                            :columns="salesItemColumns" :pagination="false">
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.dataIndex === 'name'">
                                    {{ record.name }} <br />
                                    <small>
                                        <a-typography-text code v-if="record.product_type != 'service'">
                                            {{ $t("product.avl_qty") }}
                                            {{
                                                `${record.stock_quantity}${record.unit_short_name}`
                                            }}
                                        </a-typography-text>
                                    </small>
                                </template>
                                <template v-if="column.dataIndex === 'unit_quantity'">
                                    <a-input-number id="inputNumber" v-model:value="record.quantity"
                                        @change="quantityChanged(record)" :min="0" />
                                </template>
                                <template v-if="column.dataIndex === 'single_unit_price'">
                                    {{ formatAmountCurrency(record.single_unit_price) }}
                                </template>
                                <template v-if="column.dataIndex === 'total_discount'">
                                    {{ formatAmountCurrency(record.total_discount) }}
                                </template>
                                <template v-if="column.dataIndex === 'total_tax'">
                                    {{ formatAmountCurrency(record.total_tax) }}
                                </template>
                                <template v-if="column.dataIndex === 'subtotal'">
                                    {{ formatAmountCurrency(record.subtotal) }}
                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button type="primary" @click="editItem(record)" style="margin-left: 4px">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                    <a-button type="primary" @click="showDeleteConfirm(record)"
                                        style="margin-left: 4px">
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                    </a-button>
                                </template>
                            </template>

                        </a-table>
                    </a-col>
                    <!-- end of table design-->




                </a-row>

                <!--- end new design-->

                <a-row :gutter="16" class="mt-30">
                    <a-col :xs="24" :sm="24" :md="16" :lg="16">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-form-item :label="$t('warehouse.terms_condition')" name="terms_condition" :help="rules.terms_condition
                                    ? rules.terms_condition.message
                                    : null
                                    " :validateStatus="rules.terms_condition ? 'error' : null
                                        ">
                                    <a-textarea v-model:value="formData.terms_condition"
                                        :placeholder="$t('warehouse.terms_condition')"
                                        :auto-size="{ minRows: 2, maxRows: 3 }" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-form-item :label="$t('stock.notes')" name="notes"
                                    :help="rules.notes ? rules.notes.message : null"
                                    :validateStatus="rules.notes ? 'error' : null">
                                    <a-textarea v-model:value="formData.notes" :placeholder="$t('stock.notes')"
                                        :auto-size="{ minRows: 2, maxRows: 3 }" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-row :gutter="16" v-if="orderPageObject.type != 'quotations'">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-form-item :label="$t('stock.status')" name="order_status" :help="rules.order_status
                                    ? rules.order_status.message
                                    : null
                                    " :validateStatus="rules.order_status ? 'error' : null" class="required">
                                    <a-select v-model:value="formData.order_status" :placeholder="$t('common.select_default_text', [
                                        $t('stock.status'),
                                    ])
                                        " :allowClear="true">
                                        <a-select-option v-for="status in allOrderStatus" :key="status.key"
                                            :value="status.key">
                                            {{ status.value }}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-form-item :label="$t('stock.order_tax')" name="tax_id"
                                    :help="rules.tax_id ? rules.tax_id.message : null"
                                    :validateStatus="rules.tax_id ? 'error' : null">
                                    <span style="display: flex">
                                        <a-select v-model:value="formData.tax_id" :placeholder="$t('common.select_default_text', [
                                            $t('stock.order_tax'),
                                        ])
                                            " :allowClear="true" @change="taxChanged" optionFilterProp="title"
                                            show-search>
                                            <a-select-option v-for="tax in taxes" :key="tax.xid" :value="tax.xid"
                                                :title="tax.name" :tax="tax">
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
                                <a-form-item :label="$t('stock.discount')" name="discount"
                                    :help="rules.discount ? rules.discount.message : null"
                                    :validateStatus="rules.discount ? 'error' : null">
                                    <a-input-number v-model:value="formData.discount" :placeholder="$t('common.placeholder_default_text', [
                                        $t('stock.discount'),
                                    ])
                                        " @change="recalculateFinalTotal" min="0" style="width: 100%">
                                        <template #addonBefore>
                                            {{ appSetting.currency.symbol }}
                                        </template>
                                    </a-input-number>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-form-item :label="$t('stock.shipping')" name="shipping"
                                    :help="rules.shipping ? rules.shipping.message : null"
                                    :validateStatus="rules.shipping ? 'error' : null">
                                    <a-input-number v-model:value="formData.shipping" :placeholder="$t('common.placeholder_default_text', [
                                        $t('stock.shipping'),
                                    ])
                                        " @change="recalculateFinalTotal" min="0" style="width: 100%">
                                        <template #addonBefore>
                                            {{ appSetting.currency.symbol }}
                                        </template>
                                    </a-input-number>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16" v-if="
                            orderPageObject.type == 'sales' ||
                            orderPageObject.type == 'sales-returns' ||
                            orderPageObject.type == 'purchases' ||
                            orderPageObject.type == 'purchase-returns'
                        ">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-form-item :label="$t('stock.paid_payment')" name="paid_payment" :help="rules.paid_payment
                                    ? rules.paid_payment.message
                                    : null
                                    " :validateStatus="rules.paid_payment ? 'error' : null">
                                    <a-input-number v-model:value="payment.amount" :placeholder="$t('common.placeholder_default_text', [
                                        $t('stock.paid_payment'),
                                    ])
                                        " min="0" style="width: 100%">
                                        <template #addonBefore>
                                            {{ appSetting.currency.symbol }}
                                        </template>
                                        <template #addonAfter>
                                            <a-select v-model:value="payment.payment_mode_id" style="width: 120px">
                                                <a-select-option v-for="paymentMode in paymentModes"
                                                    :key="paymentMode.xid" :value="paymentMode.xid">
                                                    {{ paymentMode.name }}
                                                </a-select-option>
                                            </a-select>
                                        </template>
                                    </a-input-number>
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16" class="mt-10">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.order_tax") }}

                            </a-col>

                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ formatAmountCurrency(formData.tax_amount) }}
                            </a-col>
                        </a-row>
                        <a-row :gutter="16" class="mt-10">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.discount") }}
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ formatAmountCurrency(formData.discount) }}
                            </a-col>
                        </a-row>
                        <a-row :gutter="16" class="mt-10">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.shipping") }}
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ formatAmountCurrency(formData.shipping) }}
                            </a-col>
                        </a-row>
                        <a-row :gutter="16" class="mt-10">
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ $t("stock.grand_total") }}
                            </a-col>
                            <a-col :xs="12" :sm="12" :md="12" :lg="12">
                                {{ formatAmountCurrency(formData.subtotal) }}
                            </a-col>
                        </a-row>
                        <a-row :gutter="16" class="mt-20 mb-20">
                            <a-button type="primary" :loading="loading" @click="onSubmit" block>
                                <template #icon>
                                    <SaveOutlined />
                                </template>
                                {{ $t("common.save") }}
                            </a-button>
                        </a-row>
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
import stockManagement from "./stockManagement";
import common from "../../../../common/composable/common";
import fields from "./fields";
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
    },
    setup() {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
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
        const { orderItemColumns } = fields();
        const { salesItemColumns } = fields();
        const {
            state,
            orderType,
            orderPageObject,
            selectedProducts,
            formData,
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

        onMounted(() => {
            const taxesPromise = axiosAdmin.get(taxUrl);
            const unitsPromise = axiosAdmin.get(unitUrl);
            const warehousesPromise = axiosAdmin.get(warehouseUrl);
            const paymentModesPromise = axiosAdmin.get(paymentModeUrl);

            Promise.all([
                taxesPromise,
                unitsPromise,
                warehousesPromise,
                paymentModesPromise,
            ]).then(
                ([
                    taxResponse,
                    unitResponse,
                    warehousesResponse,
                    paymentModesResponse,
                ]) => {
                    taxes.value = taxResponse.data;
                    allUnits.value = unitResponse.data;
                    warehouses.value = warehousesResponse.data;
                    paymentModes.value = paymentModesResponse.data;
                    let paymentType = find(paymentModes.value, {
                        name: "Cash",
                    });
                    payment.value.payment_mode_id = paymentType.xid;
                }
            );

            if (orderType.value == "purchases") {
                allOrderStatus.value = purchaseOrderStatus;
            } else if (orderType.value == "sales") {
                allOrderStatus.value = salesOrderStatus;
            } else if (orderType.value == "sales-returns") {
                allOrderStatus.value = salesReturnStatus;
            } else if (orderType.value == "purchase-returns") {
                allOrderStatus.value = purchaseReturnStatus;
            } else if (orderType.value == "quotations") {
                allOrderStatus.value = [];
            } else if (orderType.value == "stock-transfers") {
                allOrderStatus.value = salesOrderStatus;
            }
        });

        const onSubmit = () => {
            allPayments.value = [];
            allPayments.value.push(payment.value);

            const newFormDataObject = {
                ...formData.value,
                order_type: orderPageObject.value.type,
                total: formData.value.subtotal,
                total_items: selectedProducts.value.length,
                product_items: selectedProducts.value,
                pay_object: formFields.value,
                all_payments: allPayments.value,
            };

            addEditRequestAdmin({
                url: orderType.value,
                data: newFormDataObject,
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
            isModalVisible: false,
            formData: {
                stock_date: this.getCurrentDate(),
            },
            
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Select Party',
            successMessage: 'Operation successful!'

            };
       
        },
        methods: {

            getCurrentDate() {
      // returns the current date in the format YYYY-MM-DD
      return new Date().toISOString().split('T')[0];
    },
        showModal() {
        this.isModalVisible = true;
        this.$refs.dummykeyboard.focus();
        },
      
        handleClose() {
        this.isModalVisible = false;
        },
        focusSearchInput() {
        this.$refs.searchInput.focus(); // Focus on the input field
    },
        handleSuccess(xid) {
        // Handle success logic
        this.isModalVisible = false;
        console.log('Success:', xid);
        }
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
</style>