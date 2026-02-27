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
                <!-- <a-breadcrumb-item>
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
                </a-breadcrumb-item> -->
                <a-breadcrumb-item>
                    <router-link
                        :to="{
                            name: `admin.stock.taxcategory.create`,
                        }"
                    >
                        {{ $t(`Tax Edit`) }}
                    </router-link>
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>
    <a-spin :spinning="spinning">
        <admin-page-table-content>
            <a-card class="page-content-container mt-5 mb-5">
                <!-- Tax table -->
                <form ref="myForm1" layout="vertical" @submit.prevent="">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="16" :lg="16" class="info">
                            <fieldset class="fieldheightsss">
                                <legend class="basicdetalis">
                             Create Sale Master
                                </legend>
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
                                                        $t('stock.party_name')
                                                    "
                                                    name="party_name"
                                                    :help="
                                                        rules.party_name
                                                            ? rules.party_name
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.party_name
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
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    name="party_name"
                                                    ref="partyinput"
                                                    v-show="
                                                        formDataLedger.gst_available ==
                                                        '1'
                                                    "
                                                    v-model:value="
                                                        formDataLedger.party_name
                                                    "
                                                    @focus="handleFocus"
                                                    v-on:keyup.enter="
                                                        validateLedger();
                                                        moveToNextField(
                                                            $event.target
                                                        );
                                                    "
                                                    autocomplete="off"
                                                    :placeholder="
                                                        $t(
                                                            'common.placeholder_default_text',
                                                            [
                                                                $t(
                                                                    'stock.party_name'
                                                                ),
                                                            ]
                                                        )
                                                    "
                                                >
                                                </a-input>
                                            </a-col>

                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.party_type')
                                                    "
                                                    name="party_type"
                                                    :help="
                                                        rules.party_type
                                                            ? rules.party_type
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.party_type
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
                                                        formDataLedger.party_type
                                                    "
                                                    style="width: 100%"
                                                >
                                                    <a-select-option
                                                        key="Registered"
                                                        value="Registered"
                                                        aria-selected="true"
                                                    >
                                                        Customer
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="Composition"
                                                        value="Composition"
                                                        aria-selected="true"
                                                    >
                                                        Supplier
                                                    </a-select-option>
                                                </a-select>
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
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
                                                    :label="
                                                        $t(
                                                            'stock.mobile_number'
                                                        )
                                                    "
                                                    name="mobile_number"
                                                    :help="
                                                        rules.mobile_number
                                                            ? rules
                                                                  .mobile_number
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
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    name="mobile_number"
                                                    v-model:value="
                                                        formDataLedger.mobile_number
                                                    "
                                                    v-on:keyup.enter="
                                                        validateLedger();
                                                        moveToNextField(
                                                            $event.target
                                                        );
                                                    "
                                                    autocomplete="off"
                                                ></a-input>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="$t('stock.mail_to')"
                                                    name="mail_to"
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
                                                        formDataLedger.mail_to
                                                    "
                                                    type="email"
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                    complete="off"
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <!--- accounts group-->
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
                                                    :label="
                                                        $t('stock.stock_city')
                                                    "
                                                    name="stock_city"
                                                    :help="
                                                        rules.stock_city
                                                            ? rules.stock_city
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.stock_city
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
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.stock_city
                                                    "
                                                    autocomplete="off"
                                                    v-on:keyup.enter="
                                                        validateLedger();
                                                        moveToNextField(
                                                            $event.target
                                                        );
                                                    "
                                                />
                                            </a-col>

                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.stock_pincode'
                                                        )
                                                    "
                                                    name="stock_pincode"
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
                                                        formDataLedger.stock_pincode
                                                    "
                                                    type="number"
                                                    autocomplete="off"
                                                    maxlength="6"
                                                    @input="onInputPincode"
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
                                                    :label="
                                                        $t('stock.stock_state')
                                                    "
                                                    name="stock_state"
                                                    :help="
                                                        rules.stock_state
                                                            ? rules.stock_state
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.stock_state
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
                                                <StateSearchInput
                                                    @valueChanged="
                                                        (productId) =>
                                                            (formDataLedger.stock_state =
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
                                                />
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.stock_country'
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
                                                :md="7"
                                                :lg="7"
                                            >
                                                <span style="display: flex">
                                                    <CountySearchInput
                                                        @valueChanged="
                                                            (productId) =>
                                                                (formDataLedger.stock_country =
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
                                                        autocomplete="off"
                                                    />
                                                </span>
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>

                                <!-- end countrys-->
                                <!-- website inputs-->
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
                                                    :label="$t('stock.website')"
                                                    name="website"
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
                                                        formDataLedger.website
                                                    "
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
                                                :md="5"
                                                :lg="5"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.account_group'
                                                        )
                                                    "
                                                    name="account_group"
                                                    :help="
                                                        rules.account_group
                                                            ? rules
                                                                  .account_group
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.account_group
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
                                                :md="7"
                                                :lg="7"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formDataLedger.account_group
                                                    "
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                    :style="{
                                                        backgroundColor:
                                                            accountGroupColor,
                                                    }"
                                                    @focus="
                                                        changeColorOnFocus(
                                                            'accountGroup'
                                                        )
                                                    "
                                                    @blur="
                                                        resetColorOnBlur(
                                                            'accountGroup'
                                                        )
                                                    "
                                                    autocomplete="off"
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <!-- end website-->
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
                                                />
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                </a-row>
                                <!-- <input type="checkbox" class="checkboxing" id="jack" value="Jack"
                                  v-model="showShippingAddress" />Shipping Address -->

                                <!-- Shipping Address Section -->
                                <!-- <a-row v-if="showShippingAddress" :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">

                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item :label="$t('stock.party_name')" name="party_name"
                      :help="rules.party_name ? rules.party_name.message : null"
                      :validateStatus="rules.party_name ? 'error' : null" class="required">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <a-input name="party_name" ref="partyInput"
                      v-model:value="formDataLedger.party_name"
                      v-on:keyup.enter="moveToNextField($event.target)"
                      :placeholder="$t('common.placeholder_default_text', [$t('stock.party_name')])" />
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item :label="$t('stock.party_type')" name="party_type"
                      :help="rules.party_type ? rules.party_type.message : null"
                      :validateStatus="rules.party_type ? 'error' : null">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <a-select v-on:keyup.enter="moveToNextField($event.target)"
                      v-model:value="formDataLedger.party_type" style="width:100%;">
                      <a-select-option key="Registered" value="Registered" aria-selected="true">
                        Customer
                      </a-select-option>
                      <a-select-option key="Composition" value="Composition" aria-selected="true">
                        Supplier
                      </a-select-option>
                    </a-select>
                  </a-col>
                </a-row>
              </a-col>
            </a-row>


            <a-row v-if="showShippingAddress" :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item :label="$t('stock.mobile_number')" name="mobile_number"
                      :help="rules.mobile_number ? rules.mobile_number.message : null"
                      :validateStatus="rules.mobile_number ? 'error' : null" class="required">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <a-input name="mobile_number" v-model:value="formDataLedger.mobile_number"
                      v-on:keyup.enter="moveToNextField($event.target)" />
                  </a-col>
                </a-row>
              </a-col>
            </a-row> -->
                            </fieldset>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="8" :lg="8" class="info">
                            <fieldset class="balanced">
                                <legend class="basicdetalis">Balance</legend>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="11"
                                                :lg="11"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.balancing_method'
                                                        )
                                                    "
                                                    name="balancing_method"
                                                    :help="
                                                        rules.balancing_method
                                                            ? rules
                                                                  .balancing_method
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.balancing_method
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="13"
                                                :lg="13"
                                            >
                                                <a-select
                                                    v-model:value="
                                                        formDataLedger.balancing_method
                                                    "
                                                    class="balance"
                                                    v-on:keyup.enter="
                                                        moveToNextField(
                                                            $event.target
                                                        )
                                                    "
                                                >
                                                    <a-select-option
                                                        key="1"
                                                        value="1"
                                                        aria-selected="true"
                                                    >
                                                        To Collect
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="1"
                                                        value="1"
                                                        aria-selected="true"
                                                    >
                                                        To Pay
                                                    </a-select-option>
                                                </a-select>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="24"
                                                :lg="24"
                                            >
                                                <a-row :gutter="16">
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="11"
                                                        :lg="11"
                                                    >
                                                        <a-form-item
                                                            :label="
                                                                $t(
                                                                    'stock.opening_balance'
                                                                )
                                                            "
                                                            name="stock.opening_balance"
                                                            :help="
                                                                rules.opening_balance
                                                                    ? rules
                                                                          .opening_balance
                                                                          .message
                                                                    : null
                                                            "
                                                            :validateStatus="
                                                                rules.opening_balance
                                                                    ? 'error'
                                                                    : null
                                                            "
                                                        >
                                                        </a-form-item>
                                                    </a-col>
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="13"
                                                        :lg="13"
                                                    >
                                                        <a-input-group compact>
                                                            <a-span
                                                                type="primary"
                                                                class="indiannumbers"
                                                            >
                                                                ₹
                                                            </a-span>
                                                            <a-input
                                                                v-on:keyup.enter="
                                                                    moveToNextField(
                                                                        $event.target
                                                                    )
                                                                "
                                                                v-model:value="
                                                                    formDataLedger.opening_balance
                                                                "
                                                                class="amount"
                                                                style="
                                                                    width: 79%;
                                                                "
                                                                @input="
                                                                    onInputOpeningBalance
                                                                "
                                                            />
                                                        </a-input-group>
                                                    </a-col>
                                                </a-row>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="24"
                                                :lg="24"
                                            >
                                                <a-row :gutter="16">
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="11"
                                                        :lg="11"
                                                    >
                                                        <a-form-item
                                                            :label="
                                                                $t(
                                                                    'stock.credit_days'
                                                                )
                                                            "
                                                            name="credit_days"
                                                            :help="
                                                                rules.credit_days
                                                                    ? rules
                                                                          .credit_days
                                                                          .message
                                                                    : null
                                                            "
                                                            :validateStatus="
                                                                rules.credit_days
                                                                    ? 'error'
                                                                    : null
                                                            "
                                                        >
                                                        </a-form-item>
                                                    </a-col>
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="13"
                                                        :lg="13"
                                                    >
                                                        <a-input
                                                            v-on:keyup.enter="
                                                                moveToNextField(
                                                                    $event.target
                                                                )
                                                            "
                                                            v-model:value="
                                                                formDataLedger.credit_days
                                                            "
                                                            type="number"
                                                            @input="
                                                                onInputCreditDays
                                                            "
                                                        />
                                                    </a-col>
                                                </a-row>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="24"
                                                :lg="24"
                                            >
                                                <a-row :gutter="16">
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="11"
                                                        :lg="11"
                                                    >
                                                        <a-form-item
                                                            :label="
                                                                $t(
                                                                    'stock.credit_limit'
                                                                )
                                                            "
                                                            name="credit_limit"
                                                            :help="
                                                                rules.credit_limit
                                                                    ? rules
                                                                          .credit_limit
                                                                          .message
                                                                    : null
                                                            "
                                                            :validateStatus="
                                                                rules.credit_limit
                                                                    ? 'error'
                                                                    : null
                                                            "
                                                        >
                                                        </a-form-item>
                                                    </a-col>
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="13"
                                                        :lg="13"
                                                    >
                                                        <a-input
                                                            v-on:keyup.enter="
                                                                moveToNextField(
                                                                    $event.target
                                                                )
                                                            "
                                                            v-model:value="
                                                                formDataLedger.credit_limit
                                                            "
                                                            type="number"
                                                            @input="
                                                                onInputCreditDays
                                                            "
                                                        />
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
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="11"
                                                :lg="11"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.phone_number')
                                                    "
                                                    name="phone_number"
                                                    :help="
                                                        rules.phone_number
                                                            ? rules.phone_number
                                                                  .message
                                                            : null
                                                    "
                                                    :validateStatus="
                                                        rules.phone_number
                                                            ? 'error'
                                                            : null
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="13"
                                                :lg="13"
                                            >
                                                <a-input-group compact>
                                                    <a-span
                                                        type="primary"
                                                        class="indiannumbers"
                                                        >+91</a-span
                                                    >
                                                    <a-input
                                                        v-on:keyup.enter="
                                                            moveToNextField(
                                                                $event.target
                                                            )
                                                        "
                                                        v-model:value="
                                                            formDataLedger.phone_number
                                                        "
                                                        id="phonenumber"
                                                        style="width: 74%"
                                                        @input="
                                                            onInputPhoneNumber
                                                        "
                                                        :value="
                                                            formDataLedger.phone_number
                                                        "
                                                    />
                                                </a-input-group>
                                            </a-col>
                                        </a-row>

                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="24"
                                                :lg="24"
                                            >
                                                <a-row :gutter="16">
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="11"
                                                        :lg="11"
                                                    >
                                                        <a-form-item
                                                            :label="
                                                                $t(
                                                                    'stock.mobile_number'
                                                                )
                                                            "
                                                            name="mobile_number"
                                                            :help="
                                                                rules.mobile_number
                                                                    ? rules
                                                                          .mobile_number
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
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="13"
                                                        :lg="13"
                                                    >
                                                        <a-input-group compact>
                                                            <a-span
                                                                type="primary"
                                                                class="indiannumbers"
                                                            >
                                                                +91
                                                            </a-span>
                                                            <a-input
                                                                v-on:keyup.enter="
                                                                    moveToNextField(
                                                                        $event.target
                                                                    )
                                                                "
                                                                v-model:value="
                                                                    formDataLedger.mobile_number
                                                                "
                                                                style="
                                                                    width: 74%;
                                                                "
                                                                @input="
                                                                    onInputMobileNumber
                                                                "
                                                            />
                                                        </a-input-group>
                                                    </a-col>
                                                </a-row>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="24"
                                                :lg="24"
                                            >
                                                <a-row :gutter="16">
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="11"
                                                        :lg="11"
                                                    >
                                                        <a-form-item
                                                            :label="
                                                                $t(
                                                                    'stock.whatsapp_number'
                                                                )
                                                            "
                                                            name="stock.whatsapp_number"
                                                            :help="
                                                                rules.whatsapp_number
                                                                    ? rules
                                                                          .whatsapp_number
                                                                          .message
                                                                    : null
                                                            "
                                                            :validateStatus="
                                                                rules.whatsapp_number
                                                                    ? 'error'
                                                                    : null
                                                            "
                                                        >
                                                        </a-form-item>
                                                    </a-col>
                                                    <a-col
                                                        :xs="24"
                                                        :sm="24"
                                                        :md="13"
                                                        :lg="13"
                                                    >
                                                        <span
                                                            style="
                                                                display: flex;
                                                            "
                                                        >
                                                            <a-input-group
                                                                compact
                                                            >
                                                                <a-span
                                                                    type="primary"
                                                                    class="indiannumbers"
                                                                >
                                                                    +91
                                                                </a-span>
                                                                <a-input
                                                                    v-model:value="
                                                                        formDataLedger.whatsapp_number
                                                                    "
                                                                    c
                                                                    style="
                                                                        width: 74%;
                                                                    "
                                                                    @input="
                                                                        onInputWattsappNumber
                                                                    "
                                                                />
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
                    <a-tabs
                        v-model:activeKey="activeKey"
                        v-if="formDataLedger.gst_available == '1'"
                    >
                        <a-tab-pane key="taxdetails" tab="GST/Tax Details">
                            <template #tab class="actingtabs" id="activity">
                                <span> GST/Tax Details </span>
                            </template>
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
                                                :label="$t('stock.ledger_type')"
                                                name="ledger_type"
                                                :help="
                                                    rules.ledger_type
                                                        ? rules.ledger_type
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.ledger_type
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
                                                    formDataLedger.ledger_type
                                                "
                                                style="width: 100%"
                                            >
                                                <a-select-option
                                                    key="Registered"
                                                    value="Registered"
                                                    aria-selected="true"
                                                >
                                                    Registered
                                                </a-select-option>
                                                <a-select-option
                                                    key="Composition"
                                                    value="Composition"
                                                    aria-selected="true"
                                                >
                                                    Composition
                                                </a-select-option>
                                                <a-select-option
                                                    key="Unregistered"
                                                    value="Unregistered"
                                                    aria-selected="true"
                                                >
                                                    Unregistered
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
                                                :label="$t('stock.pan_number')"
                                                name="pan_number"
                                                :help="
                                                    rules.pan_number
                                                        ? rules.pan_number
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.pan_number
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
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.pan_number
                                                "
                                                @input="onInputPanNumber"
                                            />
                                        </a-col>
                                    </a-row>
                                    <a-row :gutter="16">
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-form-item
                                                :label="$t('stock.gst_number')"
                                                name="gst_number"
                                                :help="
                                                    rules.gst_number
                                                        ? rules.gst_number
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.gst_number
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
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.gst_number
                                                "
                                            />
                                        </a-col>
                                    </a-row>

                                    <a-form-item>
                                        <span style="display: flex">
                                            <ProductSearchInput
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                @valueChanged="
                                                    (productId) =>
                                                        (formData.product_id =
                                                            productId)
                                                "
                                                @valueSuccess="getStockValue"
                                                :productData="data"
                                            />
                                        </span>
                                    </a-form-item>
                                </a-col>
                            </a-row>
                        </a-tab-pane>
                        <!--- second tabs-->
                        <a-tab-pane key="contactinfo" tab="Contact Info">
                            <template #tab>
                                <span> Contact Info </span>
                            </template>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                    <a-row :gutter="16">
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                        >
                                            <a-select
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.customer_title
                                                "
                                                style="width: 100%"
                                            >
                                                <a-select-option
                                                    key="Mr."
                                                    value="Mr."
                                                    aria-selected="true"
                                                >
                                                    Mr.
                                                </a-select-option>
                                                <a-select-option
                                                    key="Miss.."
                                                    value="Miss.."
                                                    aria-selected="true"
                                                >
                                                    Miss.
                                                </a-select-option>
                                                <a-select-option
                                                    key="Mrs."
                                                    value="Mrs."
                                                    aria-selected="true"
                                                >
                                                    Mrs.
                                                </a-select-option>
                                            </a-select>
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
                                                    formDataLedger.customer_first_name
                                                "
                                                placeholder="First Name"
                                            />
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="$t('stock.designation')"
                                                name="designation"
                                                :help="
                                                    rules.designation
                                                        ? rules.designation
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.designation
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
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.designation
                                                "
                                                placeholder="Manager"
                                            />
                                        </a-col>
                                    </a-row>

                                    <a-row :gutter="16">
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                        >
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
                                                    formDataLedger.customer_last_name
                                                "
                                                placeholder="Last Name"
                                            />
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="$t('stock.website')"
                                                name="website"
                                                :help="
                                                    rules.website
                                                        ? rules.website.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.website
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
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.website
                                                "
                                                placeholder="www.example.com"
                                            />
                                        </a-col>
                                    </a-row>

                                    <a-row :gutter="16">
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                        >
                                            <a-form-item
                                                :label="$t('stock.gender')"
                                                name="gender"
                                                :help="
                                                    rules.gender
                                                        ? rules.gender.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.gender
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
                                                style="width: 100%"
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
                                                    key="Transgender"
                                                    value="Transgender"
                                                    aria-selected="true"
                                                >
                                                    Transgender
                                                </a-select-option>
                                            </a-select>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="$t('stock.email_id')"
                                                name="customer_email"
                                                :help="
                                                    rules.customer_email
                                                        ? rules.customer_email
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.customer_email
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
                                            <span style="display: flex">
                                                <a-input-group compact>
                                                    <a-input
                                                        v-on:keyup.enter="
                                                            moveToNextField(
                                                                $event.target
                                                            )
                                                        "
                                                        v-model:value="
                                                            formDataLedger.customer_email
                                                        "
                                                    />
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
                                <span> Bank Details </span>
                            </template>
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                    <a-row :gutter="16">
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="2"
                                            :lg="2"
                                        >
                                            <a-form-item
                                                :label="$t('stock.banks')"
                                                name="bank_name"
                                                :help="
                                                    rules.bank_name
                                                        ? rules.bank_name
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.bank_name
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-input
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.bank_name
                                                "
                                            />
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="
                                                    $t('stock.account_number')
                                                "
                                                name="account_number"
                                                :help="
                                                    rules.account_number
                                                        ? rules.account_number
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.account_number
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-input
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.account_number
                                                "
                                                @input="onInputACCOUNTNUMBER"
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
                                            :md="2"
                                            :lg="2"
                                        >
                                            <a-form-item
                                                :label="$t('stock.branch')"
                                                name="branch"
                                                :help="
                                                    rules.branch
                                                        ? rules.branch.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.branch
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-input
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.branch
                                                "
                                            />
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="
                                                    $t('stock.account_type')
                                                "
                                                name="account_type"
                                                :help="
                                                    rules.account_type
                                                        ? rules.account_type
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.account_type
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-select
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.account_type
                                                "
                                                style="width: 100%"
                                            >
                                                <a-select-option
                                                    key="Saving Account"
                                                    value="Saving Account"
                                                    aria-selected="true"
                                                >
                                                    Saving Account
                                                </a-select-option>
                                                <a-select-option
                                                    key="Current Account"
                                                    value="Current Account"
                                                    aria-selected="true"
                                                >
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
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="2"
                                            :lg="2"
                                        >
                                            <a-form-item
                                                :label="$t('stock.ifsc_code')"
                                                name="ifsc_code"
                                                :help="
                                                    rules.ifsc_code
                                                        ? rules.ifsc_code
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.ifsc_code
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-input
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.ifsc_code
                                                "
                                                @input="onInputIFSCCODE"
                                            />
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="$t('stock.holder_name')"
                                                name="account_holder_name"
                                                :help="
                                                    rules.account_holder_name
                                                        ? rules
                                                              .account_holder_name
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.account_holder_name
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="5"
                                            :lg="5"
                                        >
                                            <a-input
                                                v-on:keyup.enter="
                                                    moveToNextField(
                                                        $event.target
                                                    )
                                                "
                                                v-model:value="
                                                    formDataLedger.account_holder_name
                                                "
                                            />
                                        </a-col>
                                    </a-row>
                                </a-col>
                            </a-row>
                        </a-tab-pane>
                        <!-- end third tabs -->
                        <!-- thid tab start-->
                        <a-tab-pane key="visibility" tab="Others">
                            <template #tab>
                                <span> Others </span>
                            </template>

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
                                                :label="$t('stock.discounts')"
                                                name="discounts"
                                                :help="
                                                    rules.discounts
                                                        ? rules.discounts
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.discounts
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                            class="borderright"
                                        >
                                            <a-button
                                                :style="buttonStyle"
                                                @click="showModal"
                                            >
                                                <PlusOutlined />
                                                Add Detalis
                                            </a-button>
                                        </a-col>
                                        <DiscountModel
                                            v-if="isModalVisible"
                                            :visible="isModalVisible"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handleClose"
                                        />

                                        <LimitModel
                                            v-if="isModalVisibles"
                                            :visible="isModalVisibles"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handleCloses"
                                        />
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="$t('stock.others')"
                                                name="others"
                                                :help="
                                                    rules.others
                                                        ? rules.others.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.others
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                        >
                                            <a-button
                                                :style="buttonStyle"
                                                @click="showOtherModal"
                                                class="adddetalis"
                                            >
                                                <PlusOutlined />
                                                Add Detalis
                                            </a-button>
                                        </a-col>
                                        <OtherModel
                                            v-if="isModalVisibleing"
                                            :visible="isModalVisibleing"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handleClosing"
                                        />
                                    </a-row>
                                </a-col>
                            </a-row>
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
                                                    $t('stock.billing_rate')
                                                "
                                                name="billing_rate"
                                                :help="
                                                    rules.billing_rate
                                                        ? rules.billing_rate
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.billing_rate
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                            class="borderright"
                                        >
                                            <a-button
                                                :style="buttonStyle"
                                                @click="showBillingModal"
                                            >
                                                <PlusOutlined />
                                                Add Detalis
                                            </a-button>
                                        </a-col>
                                        <BillingModel
                                            v-if="isModalBillingVisible"
                                            :visible="isModalBillingVisible"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handlebillingClose"
                                        />

                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="
                                                    $t('stock.copy_dealings')
                                                "
                                                name="copy_dealings"
                                                :help="
                                                    rules.copy_dealings
                                                        ? rules.copy_dealings
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.copy_dealings
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                            class="borderright"
                                        >
                                            <a-button
                                                :style="buttonStyle"
                                                @click="showCopyModal"
                                            >
                                                <PlusOutlined />
                                                Add Detalis
                                            </a-button>
                                        </a-col>
                                        <CopyModel
                                            v-if="isModalCopy"
                                            :visible="isModalCopy"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handleCopy"
                                        />

                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="
                                                    $t('stock.geo_location')
                                                "
                                                name="geo_location"
                                                :help="
                                                    rules.geo_location
                                                        ? rules.geo_location
                                                              .message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.geo_location
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>

                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                        >
                                            <a-button
                                                :style="buttonStyle"
                                                @click="showLocationModal"
                                            >
                                                <PlusOutlined />
                                                Add Detalis
                                            </a-button>
                                        </a-col>
                                        <LocationModel
                                            v-if="isLocationModal"
                                            :visible="isLocationModal"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handleLocationClose"
                                        />
                                    </a-row>
                                </a-col>
                            </a-row>
                            <!--- end of second row add detalis-->

                            <!--- copy_dealings second row add detalis -->
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                    <a-row :gutter="16">
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                            class="borderright"
                                        >
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="3"
                                            :lg="3"
                                        >
                                            <a-form-item
                                                :label="$t('stock.birthday')"
                                                name="birthday"
                                                :help="
                                                    rules.birthday
                                                        ? rules.birthday.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.birthday
                                                        ? 'error'
                                                        : null
                                                "
                                            >
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            :xs="24"
                                            :sm="24"
                                            :md="4"
                                            :lg="4"
                                            class="borderright"
                                        >
                                            <a-button
                                                :style="buttonStyle"
                                                @click="showBirthdayModal"
                                            >
                                                <PlusOutlined />
                                                Add Detalis
                                            </a-button>
                                        </a-col>
                                        <BirthdayModel
                                            v-if="isModalBirthday"
                                            :visible="isModalBirthday"
                                            :formDataLedger="formDataLedger"
                                            :url="url"
                                            :addEditType="addEditType"
                                            :pageTitle="pageTitle"
                                            :successMessage="successMessage"
                                            @addEditSuccess="handleSuccess"
                                            @closed="handleBirthday"
                                        />
                                    </a-row>
                                </a-col>
                            </a-row>
                            <!--- end of second row add detalis-->
                        </a-tab-pane>
                        <!-- end of fourth tabs-->

                        <!-- fifth tab-->
                        <a-tab-pane key="productdetails" tab="Product Detalis">
                            <template #tab class="actingtabs" id="activity">
                                <span> Product Details </span>
                            </template>
                            <a-row
                                :gutter="16"
                                v-for="(
                                    field, index
                                ) in formDataLedger.inputFields"
                                :key="index"
                            >
                                <!-- Keyword Input -->
                                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                                    <a-form-item
                                        :label="$t('stock.keyword')"
                                        name="keyword"
                                        :help="
                                            rules.keyword
                                                ? rules.keyword.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.keyword ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input
                                        v-model:value="
                                            formDataLedger.inputFields[index]
                                                .keyword
                                        "
                                        placeholder="Enter Keyword"
                                    />
                                </a-col>

                                <!-- Value Input -->
                                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                                    <a-form-item
                                        :label="$t('stock.value')"
                                        name="value"
                                        :help="
                                            rules.value
                                                ? rules.value.message
                                                : null
                                        "
                                        :validateStatus="
                                            rules.value ? 'error' : null
                                        "
                                    >
                                    </a-form-item>
                                </a-col>
                                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                    <a-input
                                        v-model:value="
                                            formDataLedger.inputFields[index]
                                                .value
                                        "
                                        placeholder="Enter Value"
                                    />
                                </a-col>

                                <!-- Add/Remove Buttons -->
                                <a-col :xs="24" :sm="24" :md="4" :lg="4">
                                    <svg
                                        @click="addInputField"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        width="24"
                                        height="24"
                                        class="ml-2 cursor-pointer"
                                    >
                                        <path fill="none" d="M0 0h24v24H0z" />
                                        <path
                                            fill="green"
                                            d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                                        />
                                    </svg>
                                    <svg
                                        v-if="
                                            formDataLedger.inputFields.length >
                                            1
                                        "
                                        @click="removeInputField(index)"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        width="24"
                                        height="24"
                                        class="ml-2 cursor-pointer"
                                    >
                                        <path fill="none" d="M0 0h24v24H0z" />
                                        <path
                                            fill="#EC4899"
                                            d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-9.414l2.828-2.829 1.415 1.415L13.414 12l2.829 2.828-1.415 1.415L12 13.414l-2.828 2.829-1.415-1.415L10.586 12 7.757 9.172l1.415-1.415L12 10.586z"
                                        />
                                    </svg>
                                </a-col>
                            </a-row>
                        </a-tab-pane>
                        <!--- end fifth tab-->
                    </a-tabs>
                    <!--- end tabs--->
                    <!-- <template #footer> -->

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="7" :lg="7">
                            <button
                                v-if="formDataLedger.gst_available == '1'"
                                type="button"
                                id="btn-Ledger"
                                @click="switchTab"
                                title="Ledger"
                                class="btn default-btn"
                            >
                                <span class="box">
                                    <span class="shortcut"
                                        ><code>F4</code></span
                                    >
                                    <span>Switch Tab</span>
                                </span>
                            </button>
                            <!-- <button v-if="formDataLedger.gst_available == '1'"
                  @click="showGSTModal"
                  id="btn-Ledger"
                  title="Ledger"
                  class="btn default-btn ng-star-inserted gst"
                >
                  <span class="box">
                    <span class="ng-star-inserted">GST Verification</span></span
                  >
                </button> -->
                        </a-col>

                        <!-- <GSTModel
                v-if="isModalGST"
                :visible="isModalGST"
                :formDataLedger="formDataLedger"
                :url="url"
                :addEditType="addEditType"
                :pageTitle="pageTitle"
                :successMessage="successMessage"
                @addEditSuccess="handleSuccess"
                @closed="handleGST"
              /> -->

                        <a-col :xs="24" :sm="24" :md="10" :lg="10"> </a-col>
                        <a-col :xs="24" :sm="24" :md="7" :lg="7">
                            <button
                                @click="saveLedgerEntry"
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
                                @click="onClose"
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
                <!-- end party table -->
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
import apiAdmin from "../../../../common/composable/apiAdmin";
import stockManagement from "../../../../main/views/stock-management/purchases/stockManagement";
import common from "../../../../common/composable/common";
//import fields from "./fields";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import { some, forEach, find } from "lodash-es";
import { message, notification } from "ant-design-vue";
import CountySearchInput from "../../.../../../../common/components/ledger/CountySearchInput.vue";
import StateSearchInput from "../../.../../../../common/components/ledger/StateSearchInput.vue";
import ParentSearchInput from "../../.../../../../common/components/ledger/ParentSearchInput.vue";

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
        AdminPageHeader,
        MinusSquareOutlined,
        CountySearchInput,
        StateSearchInput,
        ParentSearchInput,
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
                balancing_method: "To Collect",
                opening_balance: "",
                credit_days: "",
                phone_number: "",
                mobile_number: "",
                whatsapp_number: "",
                ledger_type: "",
                gender: "Male",
                account_type: "Current Account",
                customer_title: "Mr.",
                party_type: "Customer",
                gst_available: "0",
                with_gst_number: "",
                with_pan_number: "",
                inputFields: [{ keyword: "", value: "" }],
            },

            //recordId: null,

            activeKey: "taxdetails",
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
            pageTitle: "Select Tax",
            successMessage: "Operation successful!",
            inputFields: [{ keyword: "", value: "" }],
        };
    },

    mounted() {
        console.log("mounter", this.router.params.id);
        this.getCustomerData(this.router.params.id);
        window.addEventListener("keydown", this.handleKeydown);
        this.handleFocus();
    },
    beforeDestroy() {
        // document.removeEventListener('keydown', this.handleKeyDown);
        window.removeEventListener("keydown", this.handleKeydown);
    },

    watch: {
        "formDataLedger.gst_available"(newVal) {
            this.handleFocus();
        },
    },

    methods: {
        getCustomerData(id) {
            try {
                axiosAdmin.get(`fetchTax/${id}`).then((response) => {
                    console.log("<>", response);

                    this.formDataLedger = response.ledger;
                });
            } catch (error) {
                console.error("Error fetching Tax :", error);
            }
        },

        handleFocus() {
            this.$nextTick(() => {
                if (this.formDataLedger.gst_available == "0") {
                    const gstInput = this.$refs.gstinput;
                    if (gstInput) {
                        gstInput.focus();
                        console.log("GST Input focused");
                    } else {
                        console.error(
                            "GST input not found after mount/update."
                        );
                    }
                } else if (this.formDataLedger.gst_available == "1") {
                    const partyInput = this.$refs.partyinput;
                    if (partyInput) {
                        partyInput.focus();
                        console.log("Party Input focused");
                    } else {
                        console.error(
                            "Party input not found after mount/update."
                        );
                    }
                } else {
                    console.error("Invalid gst_available value");
                }
            });
        },

        switchTab() {
            console.log(this.activeKey);
            const tabs = [
                "taxdetails",
                "contactinfo",
                "bankdetails",
                "visibility",
                "productdetails",
            ];
            const currentIndex = tabs.indexOf(this.activeKey);
            this.activeKey = tabs[(currentIndex + 1) % tabs.length];
        },

        handleKeydown(event) {
            if (event.key === "Escape") {
                this.$router.push({ name: "admin.taxcategory.index" });
            } else if (event.key === "F9") {
                event.preventDefault();
                this.resetForm();
            } else if (event.key === "F4") {
                event.preventDefault();
                this.switchTab();
            } else if (event.key === "F8") {
                event.preventDefault();
                this.saveLedgerEntry();
            }
        },
        addInputField() {
            this.formDataLedger.inputFields.push({ keyword: "", value: "" });
        },
        removeInputField(index) {
            this.formDataLedger.inputFields.splice(index, 1);
        },
        success(response) {
            console.log(5454554635365);
            notification.success({
                placement: "bottomRight",
                message: "Customer Saved Successfully!",
            });
            this.formDataLedger.inputFields.forEach((data) => {
                data["id"] = response.data.id;
            });
            axiosAdmin
                .post("ledger/ledgeritem", {
                    data: this.formDataLedger.inputFields,
                })
                .then((response) => {
                    console.log("Ledger items saved successfully:", response);
                })
                .catch((error) => {
                    console.error("Error saving ledger items:", error);
                });
        },
        saveLedgerEntry() {
            if (this.validateLedger()) {
                // Only proceed if validation is successful
                const { inputFields, ...restOfFormDataLedger } =
                    this.formDataLedger;
                axiosAdmin
                    .post("store-ledger", restOfFormDataLedger)
                    .then((response) => {
                        console.log(response);
                        this.success(response);
                    })
                    .catch((errorResponse) => {
                        var err = errorResponse.response?.data || {};
                        const errorCode = errorResponse.response?.status || 500;
                        var errorRules = {};

                        if (errorCode === 422) {
                            if (
                                err.error &&
                                typeof err.error.details !== "undefined"
                            ) {
                                var keys = Object.keys(err.error.details);
                                for (var i = 0; i < keys.length; i++) {
                                    var key = keys[i].replace(".", "\\.");
                                    errorRules[key] = {
                                        required: true,
                                        message: err.error.details[keys[i]][0],
                                    };
                                }
                            }
                            this.rules = errorRules;
                            message.error(
                                "Please fix the errors and try again."
                            );
                        } else if (err && err.message) {
                            message.error(err.message);
                        }
                    });
            }
        },

        validateLedger() {
            let isValid = true;

            if (
                !this.formDataLedger.party_name ||
                this.formDataLedger.party_name.trim() === ""
            ) {
                this.rules.party_name = {
                    message: "Party name is required.",
                };
                isValid = false;
            } else {
                this.rules.party_name = null;
            }

            if (
                !this.formDataLedger.mobile_number ||
                this.formDataLedger.mobile_number.trim() === ""
            ) {
                this.rules.mobile_number = {
                    message: "Mobile Number is required.",
                };
                isValid = false;
            } else {
                this.rules.mobile_number = null;
            }

            if (
                !this.formDataLedger.stock_city ||
                this.formDataLedger.stock_city.trim() === ""
            ) {
                this.rules.stock_city = {
                    message: "City is required.",
                };
                isValid = false;
            } else {
                this.rules.stock_city = null;
            }

            if (
                !this.formDataLedger.account_group ||
                this.formDataLedger.account_group.trim() === ""
            ) {
                this.rules.account_group = {
                    message: "Account Group is required.",
                };
                isValid = false;
            } else {
                this.rules.account_group = null;
            }
            return isValid;
        },
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

.indiannumbers {
    padding: 4px 7px !important;
    background: #eaeaea;
    border-color: #c2c2c2;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs
    .ant-tabs-tab
    + .ant-tabs-tab {
    margin: 0 0 0 0px !important;
    border-left: 1px solid #e4e4e4 !important;
}

.ant-tabs-nav-wrap {
    background-color: #e4e4e4 !important;
    margin-top: 12px !important;
}
</style>
