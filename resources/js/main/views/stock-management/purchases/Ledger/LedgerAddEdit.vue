<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @close="handleClose"
        class="popups">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Modify Ledger</span>
                <button @click="onClose" type="button" aria-label="Close" class="ant-modal-close" id="closeicon">
                    <span class="ant-modal-close-x"><span role="img" aria-label="close"
                            class="anticon anticon-close ant-modal-close-icon">
                            <svg focusable="false" data-icon="close" width="1em" height="1em" fill="currentColor"
                                aria-hidden="true" fill-rule="evenodd" viewBox="64 64 896 896">
                                <path
                                    d="M799.86 166.31c.02 0 .04.02.08.06l57.69 57.7c.04.03.05.05.06.08a.12.12 0 010 .06c0 .03-.02.05-.06.09L569.93 512l287.7 287.7c.04.04.05.06.06.09a.12.12 0 010 .07c0 .02-.02.04-.06.08l-57.7 57.69c-.03.04-.05.05-.07.06a.12.12 0 01-.07 0c-.03 0-.05-.02-.09-.06L512 569.93l-287.7 287.7c-.04.04-.06.05-.09.06a.12.12 0 01-.07 0c-.02 0-.04-.02-.08-.06l-57.69-57.7c-.04-.03-.05-.05-.06-.07a.12.12 0 010-.07c0-.03.02-.05.06-.09L454.07 512l-287.7-287.7c-.04-.04-.05-.06-.06-.09a.12.12 0 010-.07c0-.02.02-.04.06-.08l57.7-57.69c.03-.04.05-.05.07-.06a.12.12 0 01.07 0c.03 0 .05.02.09.06L512 454.07l287.7-287.7c.04-.04.06-.05.09-.06a.12.12 0 01.07 0z">
                                </path>
                            </svg>
                        </span>
                    </span>
                </button>
            </div>
        </template>
        <!-- <form  @submit.prevent="onSubmitLedger" layout="vertical" ref="myForm" id="myForm1"> -->
        <form ref="myForm1" layout="vertical" @submit.prevent="onSubmitLedger">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="16" :lg="16" class="info">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">
                            General Info
                        </legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.party_name')" name="party_name"
                                            :help="rules.party_name ? rules.party_name.message : null"
                                            :validateStatus="rules.party_name ? 'error' : null" class="required">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input name="party_name" v-model:value="formDataLedger.party_name"
                                            :placeholder="$t('common.placeholder_default_text', [$t('stock.party_name')])"
                                            value="qfqw"></a-input>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.account_group')" name="account_group"
                                            :help="rules.account_group ? rules.account_group.message : null"
                                            :validateStatus="rules.account_group ? 'error' : null" class="required">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input v-model:value="formDataLedger.account_group"
                                            :style="{ backgroundColor: accountGroupColor }"
                                            @focus="changeColorOnFocus('accountGroup')"
                                            @blur="resetColorOnBlur('accountGroup')" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!-- accountsgroup-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.stations')" name="station"
                                            :help="rules.stations ? rules.stations.message : null"
                                            :validateStatus="rules.stations ? 'error' : null" class="required">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <!-- <a-input v-model:value="formDataLedger.station"
                                            :style="{ backgroundColor: stationsColor }"
                                            @focus="changeColorOnFocus('stations')"
                                            @blur="resetColorOnBlur('stations')" /> -->

                                            <span style="display: flex">
                                                <StationSearchInput
                                                    @valueChanged="
                                                        (productId) => (formData.product_id = productId)
                                                    "
                                                    @valueSuccess="getStockValue"
                                                    :productData="data"
                                                    v-model:value="formDataLedger.station_name"
                                                />
                                            </span>
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.mail_to')" name="mail_to">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input v-model:value="formDataLedger.mail_to" type="email" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!--- accounts group-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.address')" name="address">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <a-input v-model:value="formDataLedger.Address" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!-- countrys-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                     <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.stock_country')" name="stock_country"
                                            :help="rules.stock_country ? rules.stock_country.message : null"
                                            :validateStatus="rules.stock_country ? 'error' : null" class="required">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <!-- <a-input v-model:value="formDataLedger.stock_country" /> -->
                                        <span style="display: flex">
                                            <CountySearchInput
                                                @valueChanged="
                                                    (productId) => (formData.product_id = productId)
                                                "
                                                @valueSuccess="getStockValue"
                                                :productData="data"
                                                v-model:value="formDataLedger.name"
                                            />
                                        </span>
                                    </a-col> 
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.stock_state')" name="stock_state"
                                            :help="rules.stock_state ? rules.stock_state.message : null"
                                            :validateStatus="rules.stock_state ? 'error' : null" class="required">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <!-- <a-input v-model:value="formDataLedger.stock_state" /> -->
                                        <StateSearchInput
                                        @valueChanged="
                                            (productId) => (formData.state_id = productId)
                                        "
                                        @valueSuccess="getStockValue"
                                        :productData="data"
                                        v-model:value="formDataLedger.state_name"
                                    />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.stock_city')" name="stock_city">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input v-model:value="formDataLedger.stock_city" />
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.stock_pincode')" name="stock_pincode">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input v-model:value="formDataLedger.stock_pincode" type="number"
                                            maxlength="6" />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!-- end countrys-->
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item :label="$t('stock.parent_ledger')" name="parent_ledger">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="19" :lg="19">
                                        <!-- <a-input v-model:value="formDataLedger.parent_ledger"
                                            placeholder="Search here.." /> -->
                                            <ParentSearchInput
                                                @valueChanged="
                                                    (productId) => (formData.product_id = productId)
                                                "
                                                @valueSuccess="getStockValue"
                                                :productData="data"
                                                v-model:value="formDataLedger.party_name"
                                            />
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                </a-col>

                <a-col :xs="24" :sm="24" :md="8" :lg="8" class="info">
                    <fieldset>
                        <legend class="basicdetalis">
                            Balance
                        </legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="11" :lg="11">
                                        <a-form-item :label="$t('stock.balancing_method')" name="balancing_method"
                                            :help="rules.balancing_method ? rules.balancing_method.message : null"
                                            :validateStatus="rules.balancing_method ? 'error' : null">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                        <a-select v-model:value="formDataLedger.balancing_method" class="balance">
                                            <a-select-option key="1" value="1" aria-selected="true">
                                                On accounts
                                            </a-select-option>

                                        </a-select>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col :xs="24" :sm="24" :md="11" :lg="11">
                                                <a-form-item :label="$t('stock.opening_balance')"
                                                    name="stock.opening_balance"
                                                    :help="rules.opening_balance ? rules.opening_balance.message : null"
                                                    :validateStatus="rules.opening_balance ? 'error' : null">
                                                </a-form-item>
                                            </a-col>
                                            <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                                <a-input-group compact>
                                                    <a-button type="primary" class="indiannumber">
                                                        ₹
                                                    </a-button>
                                                    <a-input v-model:value="formDataLedger.opening_balance"
                                                        class="amount" type="number" style="width:85%" />
                                                </a-input-group>
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col :xs="24" :sm="24" :md="11" :lg="11">
                                                <a-form-item :label="$t('stock.credit_days')" name="credit_days"
                                                    :help="rules.credit_days ? rules.credit_days.message : null"
                                                    :validateStatus="rules.credit_days ? 'error' : null">
                                                </a-form-item>
                                            </a-col>
                                            <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                                <a-input v-model:value="formDataLedger.credit_days" type="number" />

                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                    <fieldset class="contactdetalis">
                        <legend class="basicdetalis">
                            Contact Numbers
                        </legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="11" :lg="11">
                                        <a-form-item :label="$t('stock.phone_number')" name="phone_number"
                                            :help="rules.phone_number ? rules.phone_number.message : null"
                                            :validateStatus="rules.phone_number ? 'error' : null">
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                        <a-input-group compact>
                                            <a-button type="primary" class="indiannumber">
                                                +91
                                            </a-button>
                                            <a-input v-model:value="formDataLedger.phone_number" style="width:74%"
                                                type="number" maxlength="10" @input="onInputPhoneNumber" />
                                        </a-input-group>
                                        <!-- <a-input v-model:value="formDataLedger.phone_number" class="amount" /> -->
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col :xs="24" :sm="24" :md="11" :lg="11">
                                                <a-form-item :label="$t('stock.mobile_number')" name="mobile_number"
                                                    :help="rules.mobile_number ? rules.mobile_number.message : null"
                                                    :validateStatus="rules.mobile_number ? 'error' : null"
                                                    class="required">
                                                </a-form-item>
                                            </a-col>
                                            <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                                <a-input-group compact>
                                                    <a-button type="primary" class="indiannumber">
                                                        +91
                                                    </a-button>
                                                    <a-input v-model:value="formDataLedger.mobile_number"
                                                        style="width:74%" maxlength="10" />
                                                </a-input-group>

                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col :xs="24" :sm="24" :md="11" :lg="11">
                                                <a-form-item :label="$t('stock.whatsapp_number')"
                                                    name="stock.whatsapp_number"
                                                    :help="rules.whatsapp_number ? rules.whatsapp_number.message : null"
                                                    :validateStatus="rules.whatsapp_number ? 'error' : null">
                                                </a-form-item>
                                            </a-col>
                                            <a-col :xs="24" :sm="24" :md="13" :lg="13">
                                                <span style="display: flex">
                                                    <a-input-group compact>
                                                        <a-button type="primary" class="indiannumber">
                                                            +91
                                                        </a-button>
                                                        <a-input v-model:value="formDataLedger.whatsapp_number" c
                                                            style="width:73%" type="number" maxlength="10" />
                                                    </a-input-group>
                                                    
                                                </span>

                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                </a-col>
            </a-row>
            <a-tabs v-model:activeKey="activeKey">
                <a-tab-pane key="taxdetails" tab="GST/Tax Details">
                    <template #tab class="actingtabs" id="activity">
                        <span>
                            GST/Tax Details
                        </span>
                    </template>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-form-item :label="$t('stock.ledger_type')" name="ledger_type"
                                        :help="rules.ledger_type ? rules.ledger_type.message : null"
                                        :validateStatus="rules.ledger_type ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-select v-model:value="formDataLedger.ledger_type" style="width:100%;">
                                        <a-select-option key="Registered" value="Registered" aria-selected="true">
                                            Registered
                                        </a-select-option>
                                        <a-select-option key="Composition" value="Composition" aria-selected="true">
                                            Composition
                                        </a-select-option>
                                        <a-select-option key="Unregistered" value="Unregistered" aria-selected="true">
                                            Unregistered
                                        </a-select-option>
                                    </a-select>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-form-item :label="$t('stock.pan_number')" name="pan_number"
                                        :help="rules.pan_number ? rules.pan_number.message : null"
                                        :validateStatus="rules.pan_number ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-input v-model:value="formDataLedger.pan_number" />
                                </a-col>
                            </a-row>

                            <a-form-item>
                                <span style="display: flex">
                                    <ProductSearchInput @valueChanged="(productId) => (formData.product_id = productId)
                                        " @valueSuccess="getStockValue" :productData="data" />

                                </span>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </a-tab-pane>
                <!--- second tabs-->
                <a-tab-pane key="contactinfo" tab="Contact Info">
                    <template #tab>
                        <span>
                            Contact Info
                        </span>
                    </template>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="4" :lg="4">
                                    <a-select v-model:value="formDataLedger.customer_title" style="width:100%;">
                                        <a-select-option key="Mr." value="Mr." aria-selected="true">
                                            Mr.
                                        </a-select-option>
                                        <a-select-option key="Miss.." value="Miss.." aria-selected="true">
                                            Miss.
                                        </a-select-option>
                                        <a-select-option key="Mrs." value="Mrs." aria-selected="true">
                                            Mrs.
                                        </a-select-option>
                                    </a-select>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-input v-model:value="formDataLedger.customer_first_name"
                                        placeholder="First Name" />
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.designation')" name="designation"
                                        :help="rules.designation ? rules.designation.message : null"
                                        :validateStatus="rules.designation ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-input v-model:value="formDataLedger.designation" placeholder="Manager" />
                                </a-col>
                            </a-row>

                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="4" :lg="4">

                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-input v-model:value="formDataLedger.customer_last_name"
                                        placeholder="Last Name" />
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.website')" name="website"
                                        :help="rules.website ? rules.website.message : null"
                                        :validateStatus="rules.website ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-input v-model:value="formDataLedger.website" placeholder="www.example.com" />
                                </a-col>
                            </a-row>

                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="4" :lg="4">
                                    <a-form-item :label="$t('stock.gender')" name="gender"
                                        :help="rules.gender ? rules.gender.message : null"
                                        :validateStatus="rules.gender ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <a-select v-model:value="formDataLedger.gender" style="width:100%;">
                                        <a-select-option key="Male" value="Male" aria-selected="true">
                                            Male
                                        </a-select-option>
                                        <a-select-option key="Female" value="Female" aria-selected="true">
                                            Female
                                        </a-select-option>
                                        <a-select-option key="Transgender" value="Transgender" aria-selected="true">
                                            Transgender
                                        </a-select-option>
                                    </a-select>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.email_id')" name="email_id"
                                        :help="rules.email_id ? rules.email_id.message : null"
                                        :validateStatus="rules.email_id ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                    <span style="display: flex">
                                        <a-input-group compact>

                                            <a-input v-model:value="formDataLedger.customer_email" class="amount" />
                                        </a-input-group>
                                       
                                    </span>
                                </a-col>
                            </a-row>


                        </a-col>
                    </a-row>
                </a-tab-pane>
                <!--- second tab end -->

                <!-- third row stared-->
                <a-tab-pane key="bankdetails" tab="Bank Details">
                    <template class="actingtabs">
                        <span>
                            Bank Details
                        </span>
                    </template>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                                    <a-form-item :label="$t('stock.banks')" name="bank_name"
                                        :help="rules.banks ? rules.banks.message : null"
                                        :validateStatus="rules.banks ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input v-model:value="formDataLedger.bank_name" />
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.account_number')" name="account_number"
                                        :help="rules.account_number ? rules.account_number.message : null"
                                        :validateStatus="rules.account_number ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input v-model:value="formDataLedger.account_number" />
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                                    <a-form-item :label="$t('stock.branch')" name="branch"
                                        :help="rules.branch ? rules.branch.message : null"
                                        :validateStatus="rules.branch ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input v-model:value="formDataLedger.branch" />
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.account_type')" name="account_type"
                                        :help="rules.account_type ? rules.account_type.message : null"
                                        :validateStatus="rules.account_type ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-select v-model:value="formDataLedger.account_type" style="width:100%;">
                                        <a-select-option key="Saving Account" value="Saving Account" aria-selected="true">
                                            Saving Account
                                        </a-select-option>
                                        <a-select-option key="Current Account" value="Current Account" aria-selected="true">
                                            Current Account
                                        </a-select-option>
                                    </a-select>
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                                    <a-form-item :label="$t('stock.ifsc_code')" name="ifsc_code"
                                        :help="rules.ifsc_code ? rules.ifsc_code.message : null"
                                        :validateStatus="rules.ifsc_code ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input v-model:value="formDataLedger.ifsc_code" />
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.holder_name')" name="holder_name"
                                        :help="rules.holder_name ? rules.holder_name.message : null"
                                        :validateStatus="rules.holder_name ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input v-model:value="formDataLedger.account_holder_name" />
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                </a-tab-pane>
                <!-- end third tabs -->
                <!-- thid tab start-->
                <a-tab-pane key="visibility" tab="Others">
                    <template #tab>
                        <span>
                            Others
                        </span>
                    </template>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.discounts')" name="discounts"
                                        :help="rules.discounts ? rules.discounts.message : null"
                                        :validateStatus="rules.discounts ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                                    <a-button :style="buttonStyle" @click="showModal">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <DiscountModel v-if="isModalVisible" :visible="isModalVisible"
                                    :formDataLedger="formDataLedger" :url="url" :addEditType="addEditType"
                                    :pageTitle="pageTitle" :successMessage="successMessage"
                                    @addEditSuccess="handleSuccess" @closed="handleClose" />
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.credit_limit')" name="credit_limit"
                                        :help="rules.credit_limit ? rules.credit_limit.message : null"
                                        :validateStatus="rules.credit_limit ? 'error' : null">
                                    </a-form-item>
                                </a-col>

                                <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                                    <a-button :style="buttonStyle" @click="showModales">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <LimitModel v-if="isModalVisibles" :visible="isModalVisibles"
                                    :formDataLedger="formDataLedger" :url="url" :addEditType="addEditType"
                                    :pageTitle="pageTitle" :successMessage="successMessage"
                                    @addEditSuccess="handleSuccess" @closed="handleCloses" />
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.others')" name="others"
                                        :help="rules.others ? rules.others.message : null"
                                        :validateStatus="rules.others ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="4" :lg="4">
                                    <a-button :style="buttonStyle" @click="showOtherModal" class="adddetalis">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <OtherModel v-if="isModalVisibleing" :visible="isModalVisibleing"
                                    :formDataLedger="formDataLedger" :url="url" :addEditType="addEditType"
                                    :pageTitle="pageTitle" :successMessage="successMessage"
                                    @addEditSuccess="handleSuccess" @closed="handleClosing" />
                            </a-row>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.billing_rate')" name="billing_rate"
                                        :help="rules.billing_rate ? rules.billing_rate.message : null"
                                        :validateStatus="rules.billing_rate ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                                    <a-button :style="buttonStyle" @click="showBillingModal">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <BillingModel v-if="isModalBillingVisible" :visible="isModalBillingVisible"
                                    :formDataLedger="formDataLedger" :url="url" :addEditType="addEditType"
                                    :pageTitle="pageTitle" :successMessage="successMessage"
                                    @addEditSuccess="handleSuccess" @closed="handlebillingClose" />


                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.copy_dealings')" name="copy_dealings"
                                        :help="rules.copy_dealings ? rules.copy_dealings.message : null"
                                        :validateStatus="rules.copy_dealings ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                                    <a-button :style="buttonStyle" @click="showCopyModal">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <CopyModel v-if="isModalCopy" :visible="isModalCopy" :formDataLedger="formDataLedger"
                                    :url="url" :addEditType="addEditType" :pageTitle="pageTitle"
                                    :successMessage="successMessage" @addEditSuccess="handleSuccess"
                                    @closed="handleCopy" />


                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.geo_location')" name="geo_location"
                                        :help="rules.geo_location ? rules.geo_location.message : null"
                                        :validateStatus="rules.geo_location ? 'error' : null">
                                    </a-form-item>
                                </a-col>

                                <a-col :xs="24" :sm="24" :md="4" :lg="4">
                                    <a-button :style="buttonStyle" @click="showLocationModal">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <LocationModel v-if="isLocationModal" :visible="isLocationModal"
                                    :formDataLedger="formDataLedger" :url="url" :addEditType="addEditType"
                                    :pageTitle="pageTitle" :successMessage="successMessage"
                                    @addEditSuccess="handleSuccess" @closed="handleLocationClose" />
                            </a-row>
                        </a-col>
                    </a-row>
                    <!--- end of second row add detalis-->

                    <!--- copy_dealings second row add detalis -->
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                    <a-form-item :label="$t('stock.birthday')" name="birthday"
                                        :help="rules.birthday ? rules.birthday.message : null"
                                        :validateStatus="rules.birthday ? 'error' : null">
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                                    <a-button :style="buttonStyle" @click="showBirthdayModal">
                                        <PlusOutlined />
                                        Add Detalis
                                    </a-button>
                                </a-col>
                                <BirthdayModel v-if="isModalBirthday" :visible="isModalBirthday"
                                    :formDataLedger="formDataLedger" :url="url" :addEditType="addEditType"
                                    :pageTitle="pageTitle" :successMessage="successMessage"
                                    @addEditSuccess="handleSuccess" @closed="handleBirthday" />
                            </a-row>
                        </a-col>
                    </a-row>
                    <!--- end of second row add detalis-->
                </a-tab-pane>
                <!-- end of fourth tabs-->
            </a-tabs>
            <!--- end tabs--->
            <!-- <template #footer> -->
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <button type="button" id="btn-Ledger" @click="switchTab" title="Ledger" class="btn default-btn">
                        <span class="box">
                            <span class="shortcut"><code>F5</code></span>
                            <span>Switch Tab</span>
                        </span>
                    </button>
                    <button @click="showGSTModal" id="btn-Ledger" title="Ledger"
                        class="btn default-btn ng-star-inserted gst"><span class="box">
                            <span class="ng-star-inserted">GST Verification</span></span></button>
                </a-col>

                <GSTModel v-if="isModalGST" :visible="isModalGST" :formDataLedger="formDataLedger" :url="url"
                    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                    @addEditSuccess="handleSuccess" @closed="handleGST" />

                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                </a-col>
                <a-col :xs="24" :sm="24" :md="9" :lg="9">
                    <button @click="onSubmit" type="submit" id="btn-Ledger" title="Ledger"
                        class="btn default-btn ng-star-inserted gst">
                        <span class="box">
                            <span class="shortcut ng-star-inserted"><code>F10</code></span>
                            <span class="ng-star-inserted">Save</span>
                        </span>
                        <span class="effect"></span>
                    </button>
                    <button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"
                        @click="resetForm">
                        <span class="box">
                            <span class="shortcut ng-star-inserted">
                                <code>F9</code>
                            </span>
                            <span class="ng-star-inserted">Clear</span>
                        </span>
                        <span class="effect"></span>
                    </button>
                    <button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"><span
                            class="box"><span class="shortcut ng-star-inserted"><code>Esc</code></span><span
                                class="ng-star-inserted">Close</span></span><span class="effect"></span></button>
                </a-col>
            </a-row>
            <!-- </template> -->
        </form>
        <template #footer class="floats" style="display:none !important;">
            <div class="floats" style="display:none;">
                <a-button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"
                    @click="EnterprisesModel"><span class="box"><span
                            class="shortcut ng-star-inserted"><code>F4</code></span><span
                            class="ng-star-inserted">Ledger</span></span><span class="effect"></span></a-button>
                <a-button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"><span
                        class="box"><span class="shortcut ng-star-inserted"><code>F8</code></span><span
                            class="ng-star-inserted">Outstanding</span></span><span class="effect"></span></a-button>
                <a-button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted"><span
                        class="box">
                        <span class="shortcut ng-star-inserted"><code>F7</code></span><span
                            class="ng-star-inserted">All</span></span><span class="effect"></span></a-button>
            </div>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, reactive, onMounted, watch, ref } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import ExpenseCategoryModal from '../ExpenseCategoryModal.vue';
import { SearchOutlined } from "@ant-design/icons-vue";
import DiscountModel from '../DiscountModel.vue';
import LimitModel from '../LimitModel.vue';
import OtherModel from '../OtherModel.vue';
import BillingModel from '../BillingModel.vue';
import CopyModel from '../CopyModel.vue';
import LocationModel from '../LocationModel.vue';
import BirthdayModel from '../BirthdayModel.vue';
import GSTModel from '../GSTModel.vue';
import fields from "./../Ledger/fields";
import CountySearchInput from "../../.../../../../../common/components/ledger/CountySearchInput.vue";
import StateSearchInput from "../../.../../../../../common/components/ledger/StateSearchInput.vue";
import ParentSearchInput from "../.../../../../../../common/components/ledger/ParentSearchInput.vue";
import StationSearchInput from "../.../../../../../../common/components/ledger/StationSearchInput.vue";
export default defineComponent({
    props: [
        "formData",
        "formDataLedger",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "productData",
    ],
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
        CountySearchInput,
        StateSearchInput,
        ParentSearchInput,
        StationSearchInput,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { adjustmentTypes } = fields();
        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        const formDataLedger = ref({});

        // Watch for formData prop changes to sync with formDataLedger
        watch(
            () => props.formData,
            (newData) => {
                formDataLedger.value = { ...newData };
            }
        );

        const ledgerSubmit = (formDataLedger) => {
            console.log("Store Led");
            if(formDataLedger.xid!=undefined){
            addEditRequestAdmin({
                url: `store-ledger/${formDataLedger.xid}`,
                data: formDataLedger,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        }
        };

        const valueChanged = (value, option) => {
            emit("valueChanged", value);
            emit("valueSuccess");
        };

        return {
            loading,
            rules,
            onClose,
            formDataLedger,
            ledgerSubmit,
           // getStockValue,
            valueChanged,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },

    data(props) {
        return {
            formDataLedger: {
                party_name: "",
                account_group: "",
                station: "",
                mail_to: "",
                address: "",
                stock_country: "",
                stock_state: "",
                stock_city: "",
                stock_pincode: "",
                parent_ledger: "",
                balancing_method: "1",
                opening_balance: "",
                credit_days: "",
                phone_number: "",
                mobile_number: "",
                whatsapp_number: "",
                ledger_type: "1",
                gender: "Male",
                account_type: "Saving Account",
                customer_title: "Mr."
            },
            inputColor: "",
            partyNameColor: '',
            accountGroupColor: '',
            stationsColor: '',
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
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Modify Ledger',
            successMessage: 'Operation successful!',
            activeKey: 'taxdetails',


            resetForm() {

                this.formDataLedger = {
                    party_name: "",
                    account_group: "",
                    station: "",
                    Address: "",
                    mail_to: "",
                    stock_country: "",
                    stock_state: "",
                    stock_city: "",
                    stock_pincode: "",
                    parent_ledger: "",
                    balancing_method: "",
                    opening_balance: "",
                    credit_days: "",
                    phone_number: "",
                    mobile_number: "",
                    whatsapp_number: "",
                    ledger_type: "1",
                    gender: "Male",
                    account_type: "Saving Account",
                    customer_title: "Mr.",
                    _method: "PUT"
                };
            },
        };

    },

    methods: {
        switchTab() {
            const tabs = ['taxdetails','contactinfo', 'bankdetails','visibility',  ];
            const currentIndex = tabs.indexOf(this.activeKey);
            this.activeKey = tabs[(currentIndex + 1) % tabs.length];
        },

        resetForm() {
            if (this.$refs.myForm1) {
                this.$refs.myForm1.resetFields();
            }
        },
        async onSubmitLedger() {
            try {
                this.ledgerSubmit(this.formDataLedger);
            } catch (error) {
                this.$message.error(this.$t("company.updateFailed"));
                console.error(error);
            }
        },
        autoFocusInput() {
            /*this.$nextTick(() => {
                this.$refs.partyInput.focus();  // Automatically focus the input
            });*/
        },
        showGSTModal() {
            this.isModalGST = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showBirthdayModal() {
            this.isModalBirthday = true;

            document.addEventListener('keydown', this.handleKeydown);
        },
        showLocationModal() {
            this.isLocationModal = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showCopyModal() {
            this.isModalCopy = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showBillingModal() {
            this.isModalBillingVisible = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showModal() {
            this.isModalVisible = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showModales() {
            this.isModalVisibles = true;
            document.addEventListener('keydown', this.handleKeydown);
        },
        showOtherModal() {
            this.isModalVisibleing = true; // Open the modal
            document.addEventListener('keydown', this.handleKeydown);
        },
        handleLocationClose() {
            this.isLocationModal = false;
        },
        handleCopy() {
            this.isModalCopy = false;
        },
        handleClose() {
            this.isModalVisible = false;
            document.removeEventListener('keydown', this.handleKeydown);
        },
        handleCloses() {
            this.isModalVisibles = false;
            document.removeEventListener('keydown', this.handleKeydown);
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
            document.removeEventListener('keydown', this.handleKeydown);
            console.log('Success:', xid);
        },
        handleKeydown(event) {
            if (event.key === 'Escape') {
                this.handleClose();
            } else if (event.key === 'F9') {
                event.preventDefault();
                this.resetForm();
            } else if (event.key === 'F5') {
                event.preventDefault();
                this.switchTab();
            } else if (event.key === 'F10') {
                event.preventDefault();
                this.onSubmitLedger();
            }
        },




        changeColorOnFocus(inputField) {
            if (inputField === 'partyName') {
                this.partyNameColor = '#ffd451';
            } else if (inputField === 'accountGroup') {
                this.accountGroupColor = '#ffd451';
            } else if (inputField === 'stations') {
                this.stationsColor = '#ffd451';
            }
        },
        resetColorOnBlur(inputField) {
            if (inputField === 'partyName') {
                this.partyNameColor = '';
            } else if (inputField === 'accountGroup') {
                this.accountGroupColor = '';
            } else if (inputField === 'stations') {
                this.stationsColor = '';
            }
        }
    },
    mounted() {
        // Add keydown event listener to capture F9 key
        window.addEventListener('keydown', this.handleKeydown);
    },
    beforeDestroy() {
        // Remove event listener when component is destroyed
        window.removeEventListener('keydown', this.handleKeydown);
    },
});
</script>

<style>
:where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
    margin-bottom: 6px !important;
}

.popups {
    width: 80% !important;
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

.responsive-table {
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
    font-weight:bolder;
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
    padding: .01rem .5rem;
    display: flex;
    align-items: center;
}

button.btn {
    padding: .375rem .3rem;
    border: none;
    border: solid rgba(0, 0, 0, .2);
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

.indiannumber {
    padding: 4px 7px !important;
    background: #f6f6f6;
    border-color: #c2c2c2;
    color: black;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs .ant-tabs-tab+.ant-tabs-tab {
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

button.btn {
    cursor: pointer;
}

.ant-form-item-explain-error {
    font-size: 13px;
}
</style>