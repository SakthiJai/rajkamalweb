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
            <!-- payments modal-->
            <PaymentsModal
                v-if="isPaymentsModalVisible"
                :visible="isPaymentsModalVisible"
                :formData="formData"
                :url="url"
                :successMessage="successMessage"
                :addEditType="addEditType"
                @addEditSuccess="handleSuccess"
                @closed="handleClosePayments"
                v-on:child-method="updatePayment"
                :bill-value="formData.total"
                :bill-number="formData.bill_number"
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
                            : $t(`Customer`)
                    }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    <router-link
                        :to="{
                            name: `admin.stock.customer.create`,
                        }"
                    >
                        {{ $t(`Customer Edit`) }}
                    </router-link>
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>
    <a-spin :spinning="spinning">
        <admin-page-table-content>
            <a-card class="page-content-container mt-5 mb-5">
                <form ref="myForm1" layout="vertical" @submit.prevent="">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="13" :lg="13" class="info">
                            <fieldset
                                class="fieldheightsss"
                                style="margin-top: 14px"
                            >
                                <legend class="basicdetalis">
                                    Customer Details
                                </legend>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="
                                                $t('customers.mobile_number')
                                            "
                                            name="mobile_number"
                                            :help="
                                                rules.mobile_number
                                                    ? rules.mobile_number
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.mobile_number
                                                    ? 'error'
                                                    : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <a-input-group compact>
                                            <a-input
                                                v-model:value="
                                                    formDataLedger.mobile_number
                                                "
                                            />
                                        </a-input-group>
                                    </a-col>
                                </a-row>

                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="$t('common.name')"
                                                    name="cus_name"
                                                    :help="
                                                        rules.cus_name
                                                            ? rules.cus_name
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.cus_name
                                                            ? 'error'
                                                            : null
                                                    "
                                                    class="required"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="19"
                                                :lg="19"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.cus_name
                                                    "
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <!-- countrys-->
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="$t('stock.gender')"
                                                    name="custome_gender"
                                                    :help="
                                                        rules.custome_gender
                                                            ? rules
                                                                  .custome_gender
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.custome_gender
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-select
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                    v-model:value="
                                                        formDataLedger.gender
                                                    "
                                                    style="width: 73%"
                                                >
                                                    <a-select-option
                                                        key="Male"
                                                        value="Male"
                                                        aria-selected="true"
                                                    >
                                                        Male
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="Female"
                                                        value="Female"
                                                        aria-selected="true"
                                                    >
                                                        Female
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="Female"
                                                        value="Female"
                                                        aria-selected="true"
                                                    >
                                                        Transgender
                                                    </a-select-option>
                                                </a-select>
                                            </a-col>

                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="$t('stock.age')"
                                                    name="customer_age"
                                                    :help="
                                                        rules.customer_age
                                                            ? rules.customer_age
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.customer_age
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.age
                                                    "
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.parent_ledger'
                                                        )
                                                    "
                                                    name="parent_ledger"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="19"
                                                :lg="19"
                                            >
                                                <ParentSearchInput
                                                    @valueChanged="
                                                        (productId) =>
                                                            (formDataLedger.parent_ledger =
                                                                productId)
                                                    "
                                                    @valueSuccess="
                                                        getStockValue
                                                    "
                                                    :productData="data"
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                    v-model:value="
                                                        formDataLedger.ledger_id
                                                    "
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                            </fieldset>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="11" :lg="11" class="info">
                            <fieldset
                                class="fieldheightsss"
                                style="margin-top: 14px"
                            >
                                <legend class="basicdetalis">
                                    Contact Details
                                </legend>

                                <!-- accountsgroup-->
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="$t('stock.address')"
                                                    name="address"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="19"
                                                :lg="19"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.address
                                                    "
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="$t('common.email')"
                                                    name="mail_to"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="19"
                                                :lg="19"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.email
                                                    "
                                                    type="email"
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <!--- accounts group-->

                                <!--  pin number -->

                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.pin_number')
                                                    "
                                                    name="custome_gender"
                                                    :help="
                                                        rules.custome_gender
                                                            ? rules
                                                                  .custome_gender
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.custome_gender
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.phone_number
                                                    "
                                                    type="text"
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                />
                                            </a-col>

                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="4"
                                                :lg="4"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.cus_phone_number'
                                                        )
                                                    "
                                                    name="cus_phone_number"
                                                    :help="
                                                        rules.customer_age
                                                            ? rules.customer_age
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.customer_age
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="8"
                                                :lg="8"
                                            >
                                                <a-input-group compact>
                                                    <a-button
                                                        type="primary"
                                                        class="pannumber"
                                                        >+91</a-button
                                                    >
                                                    <a-input
                                                        v-on:keyup.enter="
                                                            moveToNextField(
                                                                $event.target
                                                            )
                                                        "
                                                        v-model:value="
                                                            formDataLedger.pin_number
                                                        "
                                                        id="dob"
                                                        style="width: 70%"
                                                        @keypress="
                                                            onlyForNumber
                                                        "
                                                    />
                                                </a-input-group>
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <!-- countrys-->
                                <a-row :gutter="16" style="margin-top: 12px">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.custom_wattsapp'
                                                        )
                                                    "
                                                    name="stock_country"
                                                    :help="
                                                        rules.stock_country
                                                            ? rules
                                                                  .stock_country
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.stock_country
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="19"
                                                :lg="19"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.whatsapp_numbner
                                                    "
                                                    @keypress="onlyForNumber"
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>

                                <!-- end countrys-->
                            </fieldset>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="24" class="">
                            <fieldset class="fieldheightsss">
                                <legend class="basicdetalis">
                                    Other Details
                                </legend>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                        <a-form-item
                                            :label="$t('stock.dob')"
                                            name="custome_mobile"
                                            :help="
                                                rules.custome_mobile
                                                    ? rules.custome_mobile
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.custome_mobile
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="6" :lg="6">
                                        <!-- <DatePicker @dateTimeChanged="(changedDateTime) =>
                                        (formData.freeze_upto = changedDateTime)
                                        " v-model:value="formDataLedger.dob" /> -->

                                        <input
                                            type="date"
                                            v-model="formDataLedger.dob"
                                            class="ant-input css-dev-only-do-not-override-wosfq4"
                                            id="order_date"
                                        />
                                    </a-col>
                                    <a-col :xs="12" :sm="24" :md="3" :lg="3">
                                        <a-form-item
                                            :label="$t('stock.customer_type')"
                                            name="customer_type"
                                            :help="
                                                rules.custome_gender
                                                    ? rules.custome_gender
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.custome_gender
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="12" :sm="24" :md="7" :lg="7">
                                        <a-select
                                            v-on:keyup.enter="
                                                moveToNextField($event.target)
                                            "
                                            v-model:value="
                                                formDataLedger.customer_type
                                            "
                                            style="width: 73%"
                                        >
                                            <a-select-option
                                                key="Regular"
                                                value="Regular"
                                                aria-selected="true"
                                            >
                                                Regular
                                            </a-select-option>
                                            <a-select-option
                                                key="BPL Holder"
                                                value="BPL Holder"
                                                aria-selected="true"
                                            >
                                                BPL Holder
                                            </a-select-option>
                                            <a-select-option
                                                key="Pensioner"
                                                value="Pensioner"
                                                aria-selected="true"
                                            >
                                                Pensioner
                                            </a-select-option>
                                            <a-select-option
                                                key="One Time"
                                                value="One Time"
                                                aria-selected="true"
                                            >
                                                One Time
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                </a-row>

                                <!-- countrys-->
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="3"
                                                :lg="3"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.billing_discount'
                                                        )
                                                    "
                                                    name="billing_discount"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="6"
                                                :lg="6"
                                            >
                                                <a-input-group compact>
                                                    <a-button
                                                        type="primary"
                                                        class="pannumber"
                                                        >&#8377;</a-button
                                                    >
                                                    <a-input
                                                        v-on:keyup.enter="
                                                            moveToNextField(
                                                                $event.target
                                                            )
                                                        "
                                                        v-model:value="
                                                            formDataLedger.billing_discount
                                                        "
                                                        style="width: 88%"
                                                        @keypress="
                                                            onlyForNumber
                                                        "
                                                    />
                                                </a-input-group>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="3"
                                                :lg="3"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.government_id'
                                                        )
                                                    "
                                                    name="Government_Id"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                    v-model:value="
                                                        formDataLedger.government_id
                                                    "
                                                    style="width: 74%"
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                            </fieldset>
                        </a-col>
                    </a-row>
                    <!--- end tabs--->

                    <a-row :gutter="16" style="margin-top: 12px">
                        <a-col :xs="24" :sm="24" :md="15" :lg="15"></a-col>

                        <a-col :xs="24" :sm="24" :md="9" :lg="9">
                            <button
                                @click="onSubmitLedger"
                                type="submit"
                                id="btn-Ledger"
                                title="Ledger"
                                class="btn default-btn ng-star-inserted gst"
                            >
                                <span class="box">
                                    <span class="shortcut ng-star-inserted"
                                        ><code>F8</code></span
                                    >
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
                                @click="closeCreateCustomer"
                                class="btn default-btn ng-star-inserted"
                            >
                                <span class="box"
                                    ><span class="shortcut ng-star-inserted"
                                        ><code>Esc</code></span
                                    ><span class="ng-star-inserted"
                                        >Close</span
                                    ></span
                                ><span class="effect"></span>
                            </button>
                        </a-col>
                    </a-row>
                    <!-- </template> -->
                </form>
            </a-card>
        </admin-page-table-content>
    </a-spin>
</template>

<script>
import {
    onMounted,
    ref,
    toRefs,
    computed,
    getCurrentInstance,
    inject,
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
import { useRouter, useRoute } from "vue-router";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import stockManagement from "../stockManagement";
import common from "../../../../../common/composable/common";
import fields from "./fields";
import DatePicker from "../../../../../common/components/common/calendar/DatePicker.vue";
import AdminPageHeader from "../../../../../common/layouts/AdminPageHeader.vue";

import ParentSearchInput from "../../../../../common/components/ledger/ParentSearchInput.vue";

import { some, forEach, find } from "lodash-es";

import { message, notification } from "ant-design-vue";

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
        SaveOutlined,
        LoadingOutlined,
        DatePicker,
        AdminPageHeader,
        ParentSearchInput,
        MinusSquareOutlined,
    },
    setup(props, { emit }) {
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
        const { orderItemColumns } = fields();
        const { salesItemColumns } = fields();
        const handleDelete = () => {};
        const ledgerSubmit = (formDataLedger, id) => {
            addEditRequestAdmin({
                url: `update-ledger/customer/${id}`,
                data: formDataLedger,
                success: (res) => {
                    notification.success({
                        placement: "bottomRight",
                        message: "Customer Mobile saved successfully",
                    });
                },
            });
        };

        //const handleDelete = () => {};

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
        const router = useRoute();
        console.log(router.params.id);
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
            ledgerSubmit,
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
            // showDeleteConfirmation,
            //deleteItem,
        };
    },
    data() {
        return {
            formDataLedger: {
                cus_name: "",
                email: "",
                address: "",
                mobile_number: "",
                whatsapp_number: "",
                ledger_id: "",
                gender: "Male",
                age: 0,
                billing_discount: "",
                government_id: "",
                customer_type: "One Time",
                dob: "",
            },

            //recordId: null,
            spinning: false,
            selectedInvoice: localStorage.getItem("selectedInvoice"),

            isNumberVisible: false,
            isPaymentsModalVisible: false,
            isModalPopup: false,
            isCustomerVisible: false,
            isModalVisible: false,
            isProuctsModalVisible: false,
            deleteConfirmationPopup: false,
            stockDateColor: "",
            isNumberVisible: false,
            isPrintModalVisible: false,
            showDeletePopup: false,
            selectedItemIndex: null,

            isOpensave: false,

            totalAmount: 0,
            totalIgstAmount: 0,
            totalDiscountAmount: 0,
            url: "your-url-here",
            addEditType: "add",
            pageTitle: "Select Party",
            successMessage: "Operation successful!",
        };
    },

    mounted() {
        console.log("mounter", this.router.params.id);
        this.getCustomerData(this.router.params.id);
        //document.addEventListener('keyup', this.handleKeyDown);
    },
    beforeDestroy() {
        // document.removeEventListener('keydown', this.handleKeyDown);
    },

    methods: {
        getCustomerData(id) {
            try {
                axiosAdmin.get(`sales-number/${id}`).then((response) => {
                    this.formDataLedger = response;
                    console.log("<>", response);
                }); //
            } catch (error) {
                console.error("Error fetching sales number:", error);
            }
        },
    },

    async onSubmitLedger() {
        try {
            this.ledgerSubmit(this.formDataLedger);
        } catch (error) {
            this.$message.error(this.$t("company.updateFailed"));
            console.error(error);
        }
    },
    computed: {
        grandTotal() {
            return this.formData.items.reduce(
                (total, item) => total + Number(item.amount),
                0
            );
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

.basicdetalis {
    font-size: 15px !important;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
    margin-bottom: 6px !important;
}

.pannumber {
    padding: 4px 7px !important;
    background: #f6f6f6;
    border-color: #c2c2c2;
    color: black;
    height: 26px;
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

.gst {
    background-color: #1f6d70 !important;
    color: white !important;
}
</style>
