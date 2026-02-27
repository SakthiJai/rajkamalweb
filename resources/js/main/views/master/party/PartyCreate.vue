<template>
  <a-spin :spinning="spinning">
    <admin-page-table-content>
      <a-card class="page-content-container mt-5 mb-5">
        <!-- party table -->

        <!-- end party table -->

        <form ref="myForm1" id="addledgerform" layout="vertical" @tt.prevent="">
          <a-row :gutter="16" class="withoutgst" @keydown="handleKeydown" tabindex="0">
            <a-col :xs="24" :sm="24" :md="12" :lg="24">
              <a-form-item>
                <a-radio-group
                  id="gstradio"
                  v-model:value="formDataLedger.gst_available"
                  ref="radioGroup"
                >
                  <a-radio
                    checked
                    ref="withGST"
                    :style="[
                      radioStyle,
                      formDataLedger.gst_available === 1 ? activeStyle : {},
                    ]"
                    :value="0"
                  >
                    {{ $t("Create Party Using GST Number") }}
                  </a-radio>

                  <a-radio
                    @click="withoutGST()"
                    ref="withoutGST"
                    :style="[
                      radioStyle,
                      formDataLedger.gst_available === 0 ? activeStyle : {},
                    ]"
                    :value="1"
                  >
                    {{ $t("Create Party don’t have a GST Number") }}
                  </a-radio>
                </a-radio-group>
              </a-form-item>
            </a-col>
          </a-row>
          <a-row :gutter="16" v-if="formDataLedger.gst_available == '0'">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('GST Number')"
                name="with_gst_number"
                :help="rules.with_gst_number ? rules.with_gst_number.message : null"
                :validateStatus="rules.with_gst_number ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="5" :lg="5">
              <a-input
                v-model:value="formDataLedger.with_gst_number"
                autocomplete="off"
                placeholder="22ABCDE1234F1Z5"
                ref="gstinput"
                id="gstnumber"
                @input="onInputwithGstNumber"
              />
            </a-col>
            <a-col :xs="24" :sm="24" :md="2" :lg="2">
              <a-form-item
                :label="$t('PAN Number')"
                name="with_pan_number"
                :help="rules.with_pan_number ? rules.with_pan_number.message : null"
                :validateStatus="rules.with_pan_number ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="5" :lg="5">
              <a-input
                name="with_pan_number"
                v-model:value="formDataLedger.with_pan_number"
                autocomplete="off"
                placeholder="ABCDE1234F"
                ref="paninput"
                id="pannumber"
                @input="onInputwithPanNumber"
              ></a-input>
            </a-col>
          </a-row>

          <!--  end with gst form-->

          <a-row
            :gutter="16"
            v-if="formDataLedger.gst_available == '1'"
            class="mainfroms"
          >
            <a-col :xs="24" :sm="24" :md="15" :lg="15" class="info">
              <fieldset class="" style="margin-top: 12px">
                <legend class="basicdetalis">General Info</legend>
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="5" :lg="9">
                        <a-form-item
                          :label="$t('Ledger / Party / Company Name')"
                          name="party_name"
                          :help="rules.party_name ? rules.party_name.message : null"
                          :validateStatus="rules.party_name ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="9" :lg="15">
                        <a-input
                          id="party_name_ledger"
                          name="party_name"
                          ref="partyinput"
                          v-show="formDataLedger.gst_available == '1'"
                          v-model:value="formDataLedger.party_name"
                          @input="clearValidationError('party_name')"
                          @blur="checkproductDuplicate($event)"
                          tabindex="-1"
                          @keydown.enter="focusNext"
                          @keydown.tab="focusNext"
                          autocomplete="off"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('stock.party_name'),
                            ])
                          "
                        >
                        </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <!-- My Table -->
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="5" :lg="9">
                        <a-form-item :label="$t('Trade name')" name="trade_name">
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="9" :lg="15">
                        <a-input
                          id="trade_name"
                          name="trade_name"
                          ref="tradeinput"
                          v-show="formDataLedger.gst_available == '1'"
                          autocomplete="off"
                          v-model:value="formDataLedger.trade_name"
                          @keydown.enter="focusNext"
                        >
                        </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
                <!-- My Table end -->

                <!-- accountsgroup-->
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="3" :lg="5">
                        <a-form-item
                          :label="$t('stock.party_type')"
                          name="party_type"
                          :help="rules.party_type ? rules.party_type.message : null"
                          :validateStatus="rules.party_type ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <a-select
                          id="party_type"
                          v-model:value="formDataLedger.party_type"
                          style="width: 100%; pointer-events: none"
                          @keydown.enter="focusNext"
                          :readonly="true"
                        >
                          <a-select-option key="1" value="1"> Customer </a-select-option>
                          <a-select-option key="2" value="2"> Supplier </a-select-option>
                        </a-select>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.mail_to')"
                          name="mail_to"
                          :help="rules.mail_to ? rules.mail_to.message : null"
                          :validateStatus="rules.mail_to ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="19" :lg="7">
                        <a-input
                          v-model:value="formDataLedger.mail_to"
                          type="text"
                          id="mail_to"
                          @keydown.enter="focusNext"
                          autocomplete="off"
                        />
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
                        <a-input
                          id="address"
                          v-model:value="formDataLedger.address"
                          @keydown.enter="focusNext"
                          autocomplete="off"
                        />
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.stock_city')"
                          name="stock_city"
                          :help="rules.stock_city ? rules.stock_city.message : null"
                          :validateStatus="rules.stock_city ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <a-input
                          id="stock_city"
                          v-model:value="formDataLedger.stock_city"
                          @input="clearValidationError('stock_city')"
                          autocomplete="off"
                          @keydown.enter="focusNext"
                          @keydown.tab="focusNext"
                        />
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.stock_pincode')"
                          name="stock_pincode"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <a-input
                          id="stock_pincode"
                          v-model:value="formDataLedger.stock_pincode"
                          type="number"
                          autocomplete="off"
                          maxlength="6"
                          @input="onInputPincode"
                          @keydown.enter="focusNext"
                        />
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
                <!-- countrys-->
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.stock_state')"
                          name="stock_state"
                          :help="rules.stock_state ? rules.stock_state.message : null"
                          :validateStatus="rules.stock_state ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <!--    @valueChanged="
                           (productId) => (formDataLedger.stock_state = productId)
                         " -->

                        <StateSearchInput
                          @valueChanged="handleStateChange"
                          @valueSuccess="getStockValue"
                          :productData="data"
                          @input="clearValidationError('stock_state')"
                          @keydown.enter="focusNext"
                          @keydown.tab.prevent
                          v-model:value="formDataLedger.stock_state"
                          id="state_search"
                        />
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.stock_country')"
                          name="stock_country"
                          :help="rules.stock_country ? rules.stock_country.message : null"
                          :validateStatus="rules.stock_country ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <span style="display: flex">
                          <CountySearchInput
                            @valueChanged="
                              (productId) => (formDataLedger.stock_country = productId)
                            "
                            id="country_search"
                            @valueSuccess="getStockValue"
                            :productData="data"
                            @keydown.enter="focusNext"
                            v-model:value="formDataLedger.stock_country"
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
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.account_group')"
                          name="account_group"
                          :help="rules.account_group ? rules.account_group.message : null"
                          :validateStatus="rules.account_group ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <a-input
                          id="account_group"
                          readonly="true"
                          v-model:value="formDataLedger.account_group"
                          @input="clearValidationError('account_group')"
                          @keydown.enter="focusNext"
                          :style="{
                            backgroundColor: accountGroupColor,
                          }"
                          @focus="changeColorOnFocus('accountGroup')"
                          @blur="resetColorOnBlur('accountGroup')"
                          autocomplete="off"
                        />
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('Category')"
                          :help="rules.category ? rules.category.message : null"
                          :validateStatus="rules.category ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="7" :lg="7">
                        <PartiescategorySearchInput
                          @valueChanged="
                            (productId) => (formDataLedger.category = productId)
                          "
                          @valueSuccess="getStockValue"
                          :productData="data"
                          @keydown.enter="focusNext"
                          v-model:value="formDataLedger.category"
                          autocomplete="off"
                          id="parties_search"
                        />
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <!-- end website-->
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        <a-form-item
                          :label="$t('stock.parent_ledger')"
                          name="parent_ledger"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="19" :lg="19">
                        <ParentSearchInput
                          id="parent_ledger"
                          @valueChanged="
                            (productId) => (formDataLedger.parent_ledger = productId)
                          "
                          @valueSuccess="getStockValue"
                          :productData="data"
                          @keydown.enter="focusNext"
                          v-model:value="formDataLedger.parent_ledger"
                        />
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
              </fieldset>
            </a-col>

            <a-col :xs="24" :sm="24" :md="9" :lg="9" class="info">
              <fieldset class="balanced" style="margin-top: 12px">
                <legend class="basicdetalis">Balance</legend>
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="9" :lg="9">
                        <a-form-item
                          :label="$t('stock.balancing_method')"
                          name="balancing_method"
                          :help="
                            rules.balancing_method ? rules.balancing_method.message : null
                          "
                          :validateStatus="rules.balancing_method ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="15" :lg="15">
                        <a-select
                          id="balancing_method"
                          v-model:value="formDataLedger.balancing_method"
                          class="balance"
                          @change="updateCreditType"
                          @keydown.enter="focusNext"
                        >
                          <a-select-option key="1" value="1">
                            To Collect
                          </a-select-option>
                          <a-select-option key="2" value="2"> To Pay </a-select-option>
                        </a-select>
                      </a-col>
                    </a-row>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="9" :lg="9">
                            <a-form-item
                              :label="$t('stock.opening_balance')"
                              name="stock.opening_balance"
                              :help="
                                rules.opening_balance
                                  ? rules.opening_balance.message
                                  : null
                              "
                              :validateStatus="rules.opening_balance ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col
                            :xs="24"
                            :sm="24"
                            :md="10"
                            :lg="10"
                            style="display: flex"
                          >
                            <a-input-group compact>
                              <a-span type="primary" class="indiannumbers"> ₹ </a-span>
                              <a-input
                                id="opening_balance"
                                v-model:value="formDataLedger.opening_balance"
                                @keydown.enter="focusNext"
                                class="amount"
                                @input="onInputOpeningBalance"
                                style="width: 82%"
                                autocomplete="off"
                              />
                            </a-input-group>
                          </a-col>
                          <a-col :xs="2" :sm="2" :md="2" :lg="2">
                            <a-select
                              v-model:value="formDataLedger.credit_type"
                              class="balances"
                              id="credit_type"
                              style="pointer-events: none"
                              @keydown.enter="focusNext"
                              v-on:keyup.enter="moveToNextField($event.target)"
                            >
                              <a-input
                                id="credit_type"
                                v-model:value="formDataLedger.credit_type"
                                class="balances"
                                style="width: 100%"
                                @keydown.enter="focusNext"
                                autocomplete="off"
                              />
                            </a-select>
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="9" :lg="9">
                            <a-form-item
                              :label="$t('stock.credit_days')"
                              name="credit_days"
                              :help="rules.credit_days ? rules.credit_days.message : null"
                              :validateStatus="rules.credit_days ? 'error' : null"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="15" :lg="15">
                            <a-input
                              id="credit_days"
                              @keydown.enter="focusNext"
                              v-model:value="formDataLedger.credit_days"
                              type="number"
                              @input="onInputCreditDays"
                              autocomplete="off"
                            />
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
              </fieldset>
              <fieldset class="contactdetalis">
                <legend class="basicdetalis">Contact Numbers</legend>
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="11" :lg="11">
                        <a-form-item
                          :label="$t('stock.phone_number')"
                          name="phone_number"
                          :help="rules.phone_number ? rules.phone_number.message : null"
                          :validateStatus="rules.phone_number ? 'error' : null"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="13" :lg="13">
                        <a-input-group compact>
                          <a-span type="primary" class="indiannumbers">+91</a-span>
                          <a-input
                            @keydown.enter="focusNext"
                            v-model:value="formDataLedger.phone_number"
                            id="phonenumber"
                            style="width: 74%"
                            @input="onInputPhoneNumber"
                            :value="formDataLedger.phone_number"
                            autocomplete="off"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="11" :lg="11">
                        <a-form-item
                          :label="$t('stock.mobile_number')"
                          name="mobile_number"
                          :help="rules.mobile_number ? rules.mobile_number.message : null"
                          :validateStatus="rules.mobile_number ? 'error' : null"
                          class="required"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="13" :lg="13">
                        <a-input-group compact>
                          <a-span type="primary" class="indiannumbers"> +91 </a-span>
                          <a-input
                            type="Number"
                            v-model:value="formDataLedger.mobile_number"
                            style="width: 74%"
                            @keydown.enter="focusNext"
                            @keydown.tab="focusNext"
                            @input="onInputMobileNumber"
                            autocomplete="off"
                          />
                        </a-input-group>
                      </a-col>
                    </a-row>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="11" :lg="11">
                            <a-form-item
                              :label="$t('stock.whatsapp_number')"
                              name="whatsapp_number"
                            >
                            </a-form-item>
                          </a-col>
                          <a-col :xs="24" :sm="24" :md="13" :lg="13">
                            <a-input-group compact>
                              <a-span type="primary" class="indiannumbers"> +91 </a-span>
                              <a-input
                                type="Number"
                                id="whatsapp_numbering"
                                v-model:value="formDataLedger.whatsapp_number"
                                style="width: 74%"
                                @keydown.enter="focusNext"
                                @input="onInputWhatsappNumber"
                                autocomplete="off"
                              />
                            </a-input-group>
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
                    <!-- Ledger Type Dropdown -->
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-form-item
                        :label="$t('stock.ledger_type')"
                        name="ledger_type"
                        :help="rules.ledger_type ? rules.ledger_type.message : null"
                        :validateStatus="rules.ledger_type ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                      <a-select
                        ref="taxInput"
                        id="ledger_type"
                        @focus="handlecontactFocus"
                        @keydown.enter="focusNext"
                        v-model:value="formDataLedger.ledger_type"
                        style="width: 100%"
                      >
                        <a-select-option
                          key="Un Registered"
                          value="Un Registered"
                          aria-selected="true"
                        >
                          GST Not Registered
                        </a-select-option>
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
                      </a-select>
                    </a-col>
                  </a-row>

                  <!-- PAN and GST Fields -->
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-form-item
                        :label="$t('PAN Number')"
                        name="pan_number"
                        :help="
                          rules.pan_number && formDataLedger.ledger_type === 'Registered'
                            ? rules.pan_number.message
                            : null
                        "
                        :validateStatus="
                          rules.pan_number && formDataLedger.ledger_type === 'Registered'
                            ? 'error'
                            : null
                        "
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                      <a-input
                        id="pan_number"
                        name="pan_number"
                        ref="paninput"
                        @keydown.enter="focusNext"
                        v-model:value="formDataLedger.pan_number"
                        autocomplete="off"
                        @input="PanValdiationWithoutgst('pan_number')"
                      />
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-form-item
                        :label="$t('GST Number')"
                        name="gst_number"
                        :help="
                          rules.gst_number && formDataLedger.ledger_type === 'Registered'
                            ? rules.gst_number.message
                            : null
                        "
                        :validateStatus="
                          rules.gst_number && formDataLedger.ledger_type === 'Registered'
                            ? 'error'
                            : null
                        "
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                      <a-input
                        id="gst_number"
                        @keydown.enter="handleEnterKey"
                        v-model:value="formDataLedger.gst_number"
                        autocomplete="off"
                        @input="GstValdiationWithoutgst('gst_number')"
                      />
                    </a-col>
                  </a-row>
                </a-col>
              </a-row>
            </a-tab-pane>

            <a-tab-pane key="contactinfo" tab="Contact Info">
              <template #tab>
                <span> Contact Info </span>
              </template>
              <a-row :gutter="16" v-for="(field, index) in formDataLedger.contactFields">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                      <a-select
                        @keydown.enter="focusNexts"
                        :id="'contact_title_' + index"
                        ref="contacttittle"
                        v-model:value="formDataLedger.contactFields[index].contact_tittle"
                        style="width: 50%"
                      >
                        <a-select-option disabled></a-select-option>
                        <a-select-option key="1" value="1"> Mr. </a-select-option>
                        <a-select-option key="2" value="2"> Miss. </a-select-option>
                        <a-select-option key="3" value="3"> Mrs. </a-select-option>
                      </a-select>
                    </a-col>

                    <!-- First Name -->
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item label="First Name"> </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-input
                        @keydown.enter="focusNexts"
                        ref="FirstName"
                        :id="'contact_name_' + index"
                        @focus="handlecontactFocus"
                        v-model:value="formDataLedger.contactFields[index].contact_name"
                        placeholder="First Name"
                        @blur="validateContactFields(index)"
                        :class="{
                          'is-invalid': !validateContactFields(index),
                        }"
                        autocomplete="off"
                      />
                    </a-col>

                    <!-- Last Name -->
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item label="Last Name"> </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-input
                        @keydown.enter="focusNexts"
                        :id="'contact_last_name' + index"
                        v-model:value="
                          formDataLedger.contactFields[index].contact_last_name
                        "
                        placeholder="Last Name"
                        autocomplete="off"
                      />
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                      <svg
                        @click="addContactInputField"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="24"
                        height="24"
                        :class="{
                          'cursor-ml-2 cursor-pointer':
                            formDataLedger.contactFields.length < 3,
                          'cursor-not-allowed opacity-50':
                            formDataLedger.contactFields.length >= 3,
                        }"
                      >
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path
                          fill="green"
                          d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                        />
                      </svg>

                      <svg
                        v-if="formDataLedger.contactFields.length > 1"
                        @click="removeContactInputField(index)"
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

                    <a-col :xs="24" :sm="24" :md="4" :lg="4"> </a-col>
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.designation')"
                        name="designation"
                        :help="rules.designation ? rules.designation.message : null"
                        :validateStatus="rules.designation ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-input
                        @keydown.enter="focusNexts"
                        v-model:value="
                          formDataLedger.contactFields[index].contact_designation
                        "
                        autocomplete="off"
                        placeholder="Manager"
                        :id="'contact_designation' + index"
                      />
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('Contact Number')"
                        name="customer_email"
                        :help="rules.customer_email ? rules.customer_email.message : null"
                        :validateStatus="rules.customer_email ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <span style="display: flex">
                        <a-input
                          type="Number"
                           :id="'contact_number_' + index"
                          @keydown.enter="focusNext"
                          v-model:value="
                            formDataLedger.contactFields[index].contact_number
                          "
                          autocomplete="off"
                        />
                      </span>
                      <span
                        v-if="formDataLedger.contactFields[index].contactNumberError"
                        style="color: red"
                      >
                        {{ formDataLedger.contactFields[index].contactNumberError }}
                      </span>
                    </a-col>
                  </a-row>

                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="4" :lg="4" />
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('Whatapp Number')"
                        name="gender"
                        :help="rules.gender ? rules.gender.message : null"
                        :validateStatus="rules.gender ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-input
                        type="Number"
                        @keydown.enter="focusNexts"
                        :id="'contact_whatsapp_number' + index"
                        v-model:value="
                          formDataLedger.contactFields[index].contact_whatsapp_number
                        "
                        autocomplete="off"
                      />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.email_id')"
                        name="customer_email"
                        :help="rules.customer_email ? rules.customer_email.message : null"
                        :validateStatus="rules.customer_email ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <span style="display: flex">
                        <a-input
                          @keydown.enter="handleEnterKey"
                          :id="'contact_email_id' + index"
                          v-model:value="
                            formDataLedger.contactFields[index].contact_email_id
                          "
                          autocomplete="off"
                          placeholder="Email"
                          @blur="validateContactFields(index)"
                          :required="formDataLedger.contactFields[index].isEmailRequired"
                          :class="{
                            'is-invalid':
                              formDataLedger.contactFields[index].isEmailRequired &&
                              !formDataLedger.contactFields[index].contact_email_id,
                          }"
                        />
                      </span>
                      <span
                        v-if="formDataLedger.contactFields[index].emailError"
                        style="color: red"
                      >
                        {{ formDataLedger.contactFields[index].emailError }}
                      </span>
                    </a-col>
                  </a-row>
                </a-col>
              </a-row>
            </a-tab-pane>
            <!--- second tab end -->

            <!-- Bank Deails-->
            <a-tab-pane key="bankdetails" tab="Bank Details">
              <template class="actingtabs">
                <span>Bank Details</span>
              </template>
              <a-row :gutter="16" v-for="(field, index) in formDataLedger.bankDetails">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                          <a-form-item
                            :label="$t('stock.holder_name')"
                            name="account_holder_name"
                            :help="
                              rules.account_holder_name
                                ? rules.account_holder_name.message
                                : null
                            "
                            :validateStatus="rules.account_holder_name ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            ref="bankInput"
                             :id="'bank_account_holder_name_' + index"
                            @focus="handlecontactFocus"
                            @keydown.enter="focusbankNext"
                            v-model:value="
                              formDataLedger.bankDetails[index].bank_account_holder_name
                            "
                            autocomplete="off"
                          />
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="2" :lg="2">
                          <a-form-item
                            :label="$t('stock.account_number')"
                            name="account_number"
                            :help="
                              rules.account_number ? rules.account_number.message : null
                            "
                            :validateStatus="rules.account_number ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            type="Number"
                            @keydown.enter="focusbankNext"
                            :id="'bank_account_number_' + index"
                            v-model:value="
                              formDataLedger.bankDetails[index].bank_account_number
                            "
                            @input="onInputACCOUNTNUMBER"
                            autocomplete="off"
                          />
                        </a-col>
                        <a-col :xs="19" :sm="19" :md="2" :lg="2">
                          <svg
                            @click="addBankDetail"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24"
                            height="24"
                            :class="{
                              'cursor-ml-2 cursor-pointer':
                                formDataLedger.bankDetails.length < 3,
                              'cursor-not-allowed opacity-50':
                                formDataLedger.bankDetails.length >= 3,
                            }"
                          >
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path
                              fill="green"
                              d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                            />
                          </svg>

                          <svg
                            v-if="formDataLedger.bankDetails.length > 1"
                            @click="removeBankDetail(index)"
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
                    </a-col>
                  </a-row>

                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.ifsc_code')"
                        name="ifsc_code"
                        :help="rules.ifsc_code ? rules.ifsc_code.message : null"
                        :validateStatus="rules.ifsc_code ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-input
                        @keydown.enter="focusbankNext"
                       :id="'bank_ifsc_code_' + index"
                        v-model:value="formDataLedger.bankDetails[index].bank_ifsc_code"
                        @input="onInputIFSCCODE"
                        autocomplete="off"
                      />
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="2" :lg="2">
                      <a-form-item
                        :label="$t('stock.account_type')"
                        name="account_type"
                        :help="rules.account_type ? rules.account_type.message : null"
                        :validateStatus="rules.account_type ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                      <a-select
                        @keydown.enter="focusbankNext"
                        :id="'bank_account_type_' + index"
                        v-model:value="
                          formDataLedger.bankDetails[index].bank_account_type
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

                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                          <a-form-item
                            :label="$t('stock.banks')"
                            name="bank_name"
                            :help="rules.bank_name ? rules.bank_name.message : null"
                            :validateStatus="rules.bank_name ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            @keydown.enter="focusbankNext"
                            :id="'bank_bank_name_' + index"
                            autocomplete="off"
                            v-model:value="
                              formDataLedger.bankDetails[index].bank_bank_name
                            "
                          />
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="2" :lg="2">
                          <a-form-item
                            :label="$t('stock.branch')"
                            name="branch"
                            :help="rules.branch ? rules.branch.message : null"
                            :validateStatus="rules.branch ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            @keydown.enter="handleEnterKey"
                           :id="'bank_branch_' + index"
                            v-model:value="formDataLedger.bankDetails[index].bank_branch"
                            autocomplete="off"
                          />
                        </a-col>
                      </a-row>
                    </a-col>
                  </a-row>
                </a-col>
              </a-row>
            </a-tab-pane>
            <!-- end third tabs -->

            <!-- my table start -->

            <a-tab-pane key="shippingdetails" tab="Shipping Details">
              <template class="actingtabs">
                <span> Shipping Details </span>
              </template>
              <a-row
                :gutter="16"
                v-for="(field, index) in formDataLedger.shippingDetails"
              >
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                          <a-form-item
                            :label="$t('Shipping Addresss')"
                            name="ship_address"
                            :help="rules.ship_address ? rules.ship_address.message : null"
                            :validateStatus="rules.ship_address ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            ref="shipInput"
                            :id="'shipping_address_' + index"
                            @focus="handlecontactFocus"
                            @keydown.enter="focusshippingNext"
                            v-model:value="
                              formDataLedger.shippingDetails[index].shipping_address"
                            autocomplete="off"
                          />
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="2" :lg="4">
                          <a-form-item
                            :label="$t('Shipping City')"
                            name="ship_city"
                            :help="rules.ship_city ? rules.ship_city.message : null"
                            :validateStatus="rules.ship_city ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            @keydown.enter="focusshippingNext"
                           :id="'shipping_city_' + index"
                            v-model:value="
                              formDataLedger.shippingDetails[index].shipping_city
                            "
                            @input="onInputACCOUNTNUMBER"
                            autocomplete="off"
                          />
                        </a-col>
                        <a-col :xs="19" :sm="19" :md="2" :lg="2">
                          <svg
                            @click="addShippingDetail"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24"
                            height="24"
                            :class="{
                              'cursor-ml-2 cursor-pointer':
                                formDataLedger.shippingDetails.length < 3,
                              'cursor-not-allowed opacity-50':
                                formDataLedger.shippingDetails.length >= 3,
                            }"
                          >
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path
                              fill="green"
                              d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                            />
                          </svg>

                          <svg
                            v-if="formDataLedger.shippingDetails.length > 1"
                            @click="removeShippingDetail(index)"
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
                    </a-col>
                  </a-row>

                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                          <a-form-item
                            :label="$t('Shipping Pincode')"
                            name="ship_pincode"
                            :help="rules.ship_pincode ? rules.ship_pincode.message : null"
                            :validateStatus="rules.ship_pincode ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            type="Number"
                            @keydown.enter="focusshippingNext"
                           :id="'shipping_pincode_' + index"
                            autocomplete="off"
                            v-model:value="
                              formDataLedger.shippingDetails[index].shipping_pincode
                            "
                          />
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="2" :lg="4">
                          <a-form-item
                            :label="$t('Shipping Contact No')"
                            name="ship_contactno"
                            :help="
                              rules.ship_contactno ? rules.ship_contactno.message : null
                            "
                            :validateStatus="rules.ship_contactno ? 'error' : null"
                          >
                          </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                          <a-input
                            type="Number"
                            @keydown.enter="handleEnterKey"
                            :id="'shipping_contact_number_' + index"
                            v-model:value="
                              formDataLedger.shippingDetails[index]
                                .shipping_contact_number"
                            autocomplete="off"
                          />
                        </a-col>
                      </a-row>
                    </a-col>
                  </a-row>
                </a-col>
              </a-row>
            </a-tab-pane>

            <!-- my table end -->

            <!-- thid tab start-->
            <a-tab-pane key="visibility" tab="Others">
              <template #tab>
                <span> Others </span>
              </template>

              <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.discounts')"
                        name="discounts"
                        :help="rules.discounts ? rules.discounts.message : null"
                        :validateStatus="rules.discounts ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                      <a-button
                        :style="buttonStyle"
                        @click="showModal"
                        @keydown.enter="handleEnterKey"
                        ref="otherInput"
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
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.others')"
                        name="others"
                        :help="rules.others ? rules.others.message : null"
                        :validateStatus="rules.others ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
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
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.billing_rate')"
                        name="billing_rate"
                        :help="rules.billing_rate ? rules.billing_rate.message : null"
                        :validateStatus="rules.billing_rate ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                      <a-button :style="buttonStyle">
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

                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.copy_dealings')"
                        name="copy_dealings"
                        :help="rules.copy_dealings ? rules.copy_dealings.message : null"
                        :validateStatus="rules.copy_dealings ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                      <a-button :style="buttonStyle" @click="showCopyModal">
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

                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.geo_location')"
                        name="geo_location"
                        :help="rules.geo_location ? rules.geo_location.message : null"
                        :validateStatus="rules.geo_location ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="4" :lg="4">
                      <a-button :style="buttonStyle" @click="showLocationModal">
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
                    <a-col :xs="24" :sm="24" :md="3" :lg="3"> </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright"> </a-col>
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                      <a-form-item
                        :label="$t('stock.birthday')"
                        name="birthday"
                        :help="rules.birthday ? rules.birthday.message : null"
                        :validateStatus="rules.birthday ? 'error' : null"
                      >
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="4" :lg="4" class="borderright">
                      <a-button :style="buttonStyle" @click="showBirthdayModal">
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
            <a-tab-pane key="productdetails" tab="Custom Details">
              <template #tab class="actingtabs" id="activity">
                <span> Custom Details </span>
              </template>
              <a-row
                :gutter="16"
                v-for="(field, index) in formDataLedger.inputFields"
                :key="index"
              >
                <!-- Keyword Input -->
                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                  <a-form-item
                    :label="$t('stock.keyword')"
                    name="keyword"
                    :help="rules.keyword ? rules.keyword.message : null"
                    :validateStatus="rules.keyword ? 'error' : null"
                  >
                  </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                  <a-input
                    ref="productInput"
                     :id="'keyword_' + index"
                    @keydown.enter="focuscustomNext"
                    @focus="handlecontactFocus"
                    v-model:value="formDataLedger.inputFields[index].keyword"
                    autocomplete="off"
                    placeholder="Enter Keyword"
                  />
                </a-col>

                <!-- Value Input -->
                <a-col :xs="24" :sm="24" :md="2" :lg="2">
                  <a-form-item
                    :label="$t('stock.value')"
                    name="value"
                    :help="rules.value ? rules.value.message : null"
                    :validateStatus="rules.value ? 'error' : null"
                  >
                  </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="5" :lg="5">
                  <a-input
                     :id="'value_' + index"
                    @keydown.enter="focuscustomNext"
                    v-model:value="formDataLedger.inputFields[index].value"
                    placeholder="Enter Value"
                    autocomplete="off"
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
                    v-if="formDataLedger.inputFields.length > 1"
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
                class="btn default-btn ng-star-inserted gst"
              >
                <span class="box">
                  <span class="shortcut"><code>F4</code></span>
                  <span>Switch Tab</span>
                </span>
              </button>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7"> </a-col>
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <!-- verify bitton for without-gst -->
              <button
                v-if="formDataLedger.gst_available == '1'"
                @click="saveLedgerEntry"
                type="button"
                id="btn-Ledger"
                title="Ledger"
                class="btn default-btn ng-star-inserted gst"
              >
                <span class="box">
                  <span class="shortcut ng-star-inserted"><code>F8</code></span>
                  <span class="ng-star-inserted">Save</span>
                </span>
                <span class="effect"></span>
              </button>

              <!-- verify bitton for withgst -->
              <button
                v-if="formDataLedger.gst_available == '0'"
                @click="showVerifyNotification($event)"
                type="button"
                id="btn-Ledger"
                title="Ledger"
                class="btn default-btn ng-star-inserted gst"
              >
                <span class="box">
                  <span class="ng-star-inserted">Verify</span>
                </span>
                <span class="effect"></span>
              </button>

              <button
                type="button"
                id="btn-Ledger"
                title="Ledger"
                @click="closePay()"
                class="btn default-btn ng-star-inserted"
              >
                <span class="box"
                  ><span class="shortcut ng-star-inserted"><code>Esc</code></span
                  ><span class="ng-star-inserted">Close</span></span
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
import { defineComponent, reactive, onMounted, watch, ref } from "vue";
import {
  PlusOutlined,
  LoadingOutlined,
  SaveOutlined,
  EditOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
} from "@ant-design/icons-vue";

import { useI18n } from "vue-i18n";
import { useRouter, useRoute } from "vue-router";
import apiAdmin from "../../../../common/composable/apiAdmin";
import stockManagement from "../../stock-management/purchases/stockManagement";
import common from "../../../../common/composable/common";

import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import { some, forEach, find } from "lodash-es";
import { message, notification } from "ant-design-vue";
import CountySearchInput from "../../../../common/components/ledger/CountySearchInput.vue";
import StateSearchInput from "../../../../common/components/ledger/StateSearchInput.vue";
import ParentSearchInput from "../../../../common/components/ledger/ParentSearchInput.vue";
import PartiescategorySearchInput from "../../../../common/components/ledger/PartiescategorySearchInput.vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";
export default defineComponent({
  props: ["productData"],
  emits: ["valueSuccess", "valueChanged"],
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    DeleteOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
    PartiescategorySearchInput,
    stockManagement,

    //GSTModel,
    // SearchOutlined,
    CountySearchInput,
    StateSearchInput,
    ParentSearchInput,
    // StationSearchInput,
  },

  setup(props, { emit }) {
    const { addEditRequestAdmin, loading, rules } = apiAdmin();

    const onClose = () => {
      rules.value = {};
      emit("closed");
    };

    const valueChanged = (value, option) => {
      emit("valueChanged", value);
      emit("valueSuccess");
    };

    return {
      loading,
      rules,
      onClose,
      // formDataLedger,
      //formData,
      //ledgerSubmit,

      valueChanged,
      drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
    };
  },

  data() {
    return {
      formDataLedger: {
        party_name: "",
        trade_name: "",
        party_full_name: "",
        gst_available: 0,
        account_group: "Sundry Debtors ",
        station: "",
        mail_to: "",
        address: "",
        stock_country: "8",
        stock_state: "",
        stock_city: "",
        stock_pincode: "",
        parent_ledger: "",
        balancing_method: "To Collect",
        credit_type: "CR",
        opening_balance: "",
        credit_days: "",
        phone_number: "",
        mobile_number: "",
        whatsapp_number: "",
        ledger_type: "GST Not Registered",
        gender: "Male",
        account_type: "Current Account",
        customer_title: "Mr.",
        party_type: "1",
        gst_available: "0",
        with_gst_number: "",
        with_pan_number: "",
        contact_mobile: "",
        pan_number: "",
        gst_number: "",

        inputFields: [{ keyword: "", value: "" }],

        contactFields: [
          {
            contact_tittle: "Mr",
            contact_number: "",
            contact_whatsapp_number: "",
            contact_email_id: "",
            contact_name: "",
            contact_last_name: "",
            contact_designation: "",
          },
        ],

        bankDetails: [
          {
            bank_bank_name: "",
            bank_account_number: "",
            bank_branch: "",
            bank_account_type: "Current Account",
            bank_ifsc_code: "",
            bank_account_holder_name: "",
          },
        ],

        shippingDetails: [
          {
            shipping_address: "",
            shipping_city: "",
            shipping_pincode: "",
            shipping_contact_number: "",
          },
        ],
      },

      inputColor: "",
      partyNameColor: "",
      accountGroupColor: "",
      stationsColor: "",
      tabPressCount: 0,
      showInput: true,
      isModalVisible: false,
      isModalVisibles: false,
      isModalVisibleing: false,
      isModalBillingVisible: false,
      isModalCopy: false,
      isFocusing: false,
      notificationShown: false,
      isLocationModal: false,
      isModalBirthday: false,
      isModalGST: false,
      spinning: false,
      url: "your-url-here",
      addEditType: "add",
      pageTitle: "Create Ledger",
      successMessage: "Operation successful!",
      activeKey: "taxdetails",

      showShippingAddress: false,
      inputFields: [{ keyword: "", value: "" }],
      //contactFields: [{ contact_tittle: "", contact_number: "",contact_whatsapp_number: "",contact_email_id: "",contact_name:"",contact_last_name:"",contact_designation:"", }],
      resetForm() {
        this.formDataLedger = {
          party_name: "",
          trade_name: "",
          account_group: "",
          station: "",
          mail_to: "",
          address: "",
          stock_country: "India",
          stock_state: "",
          stock_city: "",
          stock_pincode: "",
          parent_ledger: "",
          balancing_method: "",
          credit_type: "",
          opening_balance: "",
          credit_days: "",
          phone_number: "",
          mobile_number: "",
          whatsapp_number: "",
        };
      },
      radioStyle: {
        padding: "10px",
      },
      activeTextStyle: {
        color: "blue",
        fontWeight: "bold",
      },
    };
  },

  methods: {
    async getCustomerData(id) {
      try {
        if (id > 0) {
          const response = await axiosAdmin.get(`fetch-ledger/${id}`);
          this.formDataLedger.party_name = response.data.party_name;
          this.formDataLedger.mail_to = response.data.mail_to;
          this.formDataLedger.trade_name = response.data.trade_name;

          this.formDataLedger.party_type = response.data.party_type;
          this.formDataLedger.address = response.data.Address;
          this.formDataLedger.address = response.data.Address;
          this.formDataLedger.stock_city = response.data.stock_city;
          this.formDataLedger.stock_pincode = response.data.stock_pincode;
          this.formDataLedger.stock_state = response.data.state_name;
          console.log(response.data.state_name);
          this.formDataLedger.stock_country = response.data.stock_country;
          this.formDataLedger.account_group = response.data.account_group;
          this.formDataLedger.parent_ledger = response.data.party_name;
          this.formDataLedger.balancing_method = response.data.balancing_method;
          this.formDataLedger.opening_balance = response.data.opening_balance;
          this.formDataLedger.credit_type = response.data.credit_type;
          this.formDataLedger.credit_days = response.data.credit_days;
          this.formDataLedger.mobile_number = response.data.mobile_number;
          this.formDataLedger.whatsapp_number = response.data.whatsapp_number;
          this.formDataLedger.ledger_type = response.data.ledger_type;
          this.formDataLedger.pan_number = response.data.pan_number;
          this.formDataLedger.gst_number = response.data.gst_number;
          this.formDataLedger.bank_name = response.data.bank_name;
          this.formDataLedger.branch = response.data.branch;
          this.formDataLedger.account_number = response.data.account_number;
          this.formDataLedger.account_holder_name = response.data.account_holder_name;

          this.formDataLedger.ifsc_code = response.data.ifsc_code;
          this.formDataLedger.ship_address = response.data.ship_address;
          this.formDataLedger.ship_city = response.data.ship_city;
          this.formDataLedger.ship_pincode = response.data.ship_pincode;
          this.formDataLedger.ship_contactno = response.data.ship_contactno;

          this.formDataLedger.phone_number = response.data.phone_number;
          this.formDataLedger.with_gst_number = response.data.with_gst_number;
          this.formDataLedger.with_pan_number = response.data.with_pan_number;
          if (response.ledger.gst_available == 0) {
            document.getElementById("withoutgst").click();
          } else {
            document.getElementById("withgst").click();
          }
        }

        if (id > 0) {
          const response = await axiosAdmin.get(`fetch-ledger/${id}`);
          this.formDataLedger.party_name = response.ledger.party_name;
          this.formDataLedger.id = id;
          this.formDataLedger.mail_to = response.ledger.mail_to;
          this.formDataLedger.trade_name = response.ledger.trade_name;

          this.formDataLedger.party_type = response.ledger.party_type;
          this.formDataLedger.address = response.ledger.Address;
          this.formDataLedger.address = response.ledger.Address;
          this.formDataLedger.stock_city = response.ledger.stock_city;
          this.formDataLedger.stock_pincode = response.ledger.stock_pincode;
          this.formDataLedger.stock_state = response.ledger.stock_state;
          console.log(response.ledger.state_name);
          this.formDataLedger.stock_country = response.ledger.stock_country;
          this.formDataLedger.category = response.ledger.category;
          this.formDataLedger.account_group = response.ledger.account_group;
          this.formDataLedger.parent_ledger = response.ledger.party_name;
          this.formDataLedger.balancing_method = response.ledger.balancing_method;
          this.formDataLedger.opening_balance = response.ledger.opening_balance;
          this.formDataLedger.credit_type = response.ledger.credit_type;
          this.formDataLedger.credit_days = response.ledger.credit_days;
          this.formDataLedger.mobile_number = response.ledger.mobile_number;
          this.formDataLedger.whatsapp_number = response.ledger.whatsapp_number;
          this.formDataLedger.ledger_type = response.ledger.ledger_type;
          this.formDataLedger.pan_number = response.ledger.pan_number;
          this.formDataLedger.gst_number = response.ledger.gst_number;
          this.formDataLedger.bank_name = response.ledger.bank_name;
          this.formDataLedger.branch = response.ledger.branch;
          this.formDataLedger.account_number = response.ledger.account_number;
          this.formDataLedger.account_holder_name = response.ledger.account_holder_name;
          this.formDataLedger.ship_address = response.data.ship_address;
          this.formDataLedger.ship_city = response.data.ship_city;
          this.formDataLedger.ship_pincode = response.data.ship_pincode;
          this.formDataLedger.ship_contactno = response.data.ship_contactno;

          this.formDataLedger.ifsc_code = response.ledger.ifsc_code;
          this.formDataLedger.phone_number = response.ledger.phone_number;
          this.formDataLedger.with_gst_number = response.ledger.with_gst_number;
          this.formDataLedger.with_pan_number = response.ledger.with_pan_number;
          if (response.contact.length > 0) {
            this.formDataLedger.contactFields = response.contact;
          }
          if (response.bank) {
            this.formDataLedger.bank_name = response.bank.bank_bank_name;
          }
          if (response.ship) {
            this.formDataLedger.ship_address = response.ship.ship_address;
          }

          if (response.custom && response.custom.length > 0) {
            this.formDataLedger.inputFields = response.custom;
          }

          if (response.ledger.gst_available == 0) {
            document.getElementById("withoutgst").click();
          } else {
            document.getElementById("withgst").click();
          }
        }

        console.log("<>", response);
      } catch (error) {
        console.error("Error fetching product data:", error);
      }
    },

    checkproductDuplicate(event) {
      console.log("API call");
      this.duplicate = false;
      if (
        this.formDataLedger.party_name != undefined &&
        this.formDataLedger.party_name != null &&
        this.formDataLedger.party_name != "" &&
        this.formDataLedger.party_name.trim() != "" &&
        event.key != "F8"
      ) {
        axiosAdmin
          .get("parentDuplicate/" + this.formDataLedger.party_name + "/id")
          .then((response) => {
            console.log(response);
            // Toastr Notificaiton
            if (response == 500) {
              this.duplicate = true;
              message.error("Party name already exist !");
            }
          })
          .catch((errorResponse) => {
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
          });
      }
    },

    switchTab() {
      // Check if validation is successful
      if (this.validateLedger()) {
        // List of tab keys
        const tabs = [
          "taxdetails",
          "contactinfo",
          "bankdetails",
          "shippingdetails",
          "visibility",
          "productdetails",
        ];

        // Get the current index and move to the next tab
        const currentIndex = tabs.indexOf(this.activeKey);
        this.activeKey = tabs[(currentIndex + 1) % tabs.length];
        this.$nextTick(() => {
          this.focusFirstFieldInTab();
        });
      } else {
        
      }
    },

    handleStateChange(value) {
      this.formDataLedger.stock_state = value;
      this.validateStateField();
    },
    validateStateField() {
      const stateValue = this.formDataLedger.stock_state;
      if (stateValue === null || stateValue === undefined) {
        this.rules.stock_state = null;
      } else if (!stateValue || String(stateValue).trim() === "") {
        this.rules.stock_state = { message: "This Field is required." };
      } else {
        this.rules.stock_state = null;
      }

      return !this.rules.stock_state;
    },

    //   validateState(event) {
    //   if (this.formDataLedger.stock_state !== '') {
    //     this.focusNext();
    //   } else {
    //     event.preventDefault(); // Prevent moving to the next field
    //     console.log("Stock state is empty. Please enter a value.");
    //   }
    // },

    // validateState() {
    //   console.log("checking", this.formData.receipt_mode);
    //   if (this.formDataLedger.stock_state !== "") {
    //     const targetElement = document.getElementById("country_search");
    //     if (targetElement) {
    //       targetElement.focus();
    //     }
    //   }
    // },

    //   validateAndMoveNext(event) {
    //   if (this.formDataLedger.stock_state === '') {
    //     event.preventDefault();
    //     console.log("Stock state is empty. Please enter a value.");
    //   } else {

    //     this.focusNext();
    //   }
    // },
    handleEnterKey(event) {
      event.preventDefault();
      this.switchTab();
    },
    handleEnterKeys(event) {
      event.preventDefault();
      this.switchTab();
    },
    focusFirstFieldInTab() {
      this.$nextTick(() => {
        let inputToFocus;

        if (this.activeKey === "taxdetails") {
          inputToFocus = this.$refs.taxInput;
        } else if (this.activeKey === "contactinfo") {
          inputToFocus = this.$refs.FirstName;
        } else if (this.activeKey === "bankdetails") {
          inputToFocus = this.$refs.bankInput;
        } else if (this.activeKey === "shippingdetails") {
          inputToFocus = this.$refs.shipInput;
        } else if (this.activeKey === "visibility") {
          inputToFocus = this.$refs.otherInput;
        } else if (this.activeKey === "productdetails") {
          inputToFocus = this.$refs.productInput;
        }

        if (inputToFocus) {
          if (Array.isArray(inputToFocus)) {
            inputToFocus = inputToFocus[0];
          }
          if (inputToFocus.focus) {
            inputToFocus.focus();
            console.log(`Focused on first input of ${this.activeKey}`);
          } else {
            console.warn("Input does not support focus()", inputToFocus);
          }
        } else {
          console.warn(`No input found to focus in tab: ${this.activeKey}`);
        }
      });
    },

    onClose() {
      console.log("close test confirmed");
      this.$emit("closed");
    },

    switchTabs() {
      console.log(this.activeKey);
      const gstTabs = ["0", "1"];
      const currentIndex = gstTabs.indexOf(this.formDataLedger.gst_available);
      this.formDataLedger.gst_available = gstTabs[(currentIndex + 1) % gstTabs.length];
    },

  
    async onSubmitLedger() {
      try {
        console.log("done woknuibnds    ");
        let isValid = true;
        this.formDataLedger.contactFields.forEach((contact, index) => {
          if (!this.validateContactFields(index)) {
            isValid = false;
            this.$message.error(
              `Please fill in the required fields for Contact ${index + 1}`
            );
          }
        });

        this.formDataLedger.bankDetails.forEach((bank, index) => {
          if (!this.validateBankFields(index)) {
            isValid = false;
            this.$message.error(
              `Please fill in the required fields for Bank ${index + 1}`
            );
          }
        });
        this.formDataLedger.shippingDetails.forEach((bank, index) => {
          if (!this.validateShippingDetails(index)) {
            isValid = false;
            this.$message.error(
              `Please fill in the required fields for shipping Details  ${index + 1}`
            );
          }
        });

        if (!isValid) {
          return;
        }

        await this.ledgerSubmit(this.formDataLedger);
        this.$message.success(this.$t("company.updateSuccess"));
      } catch (error) {
        this.$message.error(this.$t("company.updateFailed"));
        console.error(error);
      }
    },

    addContactInputField() {
      if (this.formDataLedger.contactFields.length < 5) {
        this.formDataLedger.contactFields.push({
          contact_tittle: "",
          contact_number: "",
          contact_whatsapp_number: "",
          contact_email_id: "",
          contact_name: "",
          contact_last_name: "",
          contact_designation: "",
          isContactNumberRequired: false,
          isEmailRequired: false,
        });
        this.$nextTick(() => {
          this.focusNewInputField();
        });
      }
    },
    focusNexts(event, index) {
      const currentField = event.target;
      const fields = this.$refs.contacttittle;
      const nextField = this.$el.querySelectorAll("input, select, textarea");
      const currentIndex = Array.prototype.indexOf.call(nextField, currentField);
      if (currentIndex + 1 < nextField.length) {
        nextField[currentIndex + 1].focus();
      }
    },
    focusNewInputField() {
      const lastIndex = this.formDataLedger.contactFields.length - 1;
      const inputElement = document.getElementById(`contact_title_${lastIndex}`);
      if (inputElement) {
        inputElement.focus();
      }
    },

    validateContactFields(index) {
      const contact = this.formDataLedger.contactFields[index];

      contact.contactNumberError = "";
      contact.emailError = "";

      if (contact.contact_name) {
        contact.isContactNumberRequired = true;
        contact.isEmailRequired = true;

        if (!contact.contact_number) {
          contact.contactNumberError = "Contact number is required.";
        }
      } else {
        contact.isContactNumberRequired = false;
        contact.isEmailRequired = false;
      }

      // Return true if the contact is valid (no error messages)
      const isValid = !contact.contactNumberError && !contact.emailError;

      return isValid;
    },

    removeInputField(index) {
      this.formDataLedger.inputFields.splice(index, 1);
    },

    removeContactInputField(index) {
      //   if (this.formDataLedger.contactFields.length > 1) {
      //   this.formDataLedger.contactFields.splice(index, 1);
      //   this.updateContactTitles();
      // }

      this.formDataLedger.contactFields.splice(index, 1);
    },

    addBankDetail() {
      if (this.formDataLedger.bankDetails.length < 5) {
        this.formDataLedger.bankDetails.push({
          bank_bank_name: "",
          bank_account_number: "",
          bank_branch: "",
          bank_account_type: "",
          bank_ifsc_code: "",
          bank_account_holder_name: "",
        });
        this.$nextTick(() => {
          this.focusNewBankInputField();
        });
      }
    },

    focusbankNext(event, index) {
      const currentField = event.target;
      const fields = this.$refs.bankInput;
      const nextField = this.$el.querySelectorAll("input, select, textarea");
      const currentIndex = Array.prototype.indexOf.call(nextField, currentField);
      if (currentIndex + 1 < nextField.length) {
        nextField[currentIndex + 1].focus();
      }
    },

    focusNewBankInputField(){
      const lastIndex = this.formDataLedger.bankDetails.length - 1;
      const inputElement = document.getElementById(`bank_account_holder_name_${lastIndex}`);
      if (inputElement) {
        inputElement.focus();
      }
    },

    removeBankDetail(index) {
      this.formDataLedger.bankDetails.splice(index, 1);
    },

    addShippingDetail() {
      if (this.formDataLedger.shippingDetails.length < 5) {
        this.formDataLedger.shippingDetails.push({
          shipping_address: "",
          shipping_city: "",
          shipping_pincode: "",
          shipping_contact_number: "",
        });
        this.$nextTick(() => {
          this.focusNewShippingInputField();
        });
      }
    },
    focusNewShippingInputField(){
      const lastIndex = this.formDataLedger.shippingDetails.length - 1;
      const inputElement = document.getElementById(`shipping_address_${lastIndex}`);
      if (inputElement) {
        inputElement.focus();
      }
    },

    focusshippingNext(event, index) {
      const currentField = event.target;
      const fields = this.$refs.shipInput;
      const nextField = this.$el.querySelectorAll("input, select, textarea");
      const currentIndex = Array.prototype.indexOf.call(nextField, currentField);
      if (currentIndex + 1 < nextField.length) {
        nextField[currentIndex + 1].focus();
      }
    },

    addInputField() {
      if (this.formDataLedger.inputFields.length < 5) {
        this.formDataLedger.inputFields.push({
          keyword: "",
          value: "",
        });
        this.$nextTick(() => {
          this.focusNewCustomInputField();
        });
        
      } 
    },

    focusNewCustomInputField(){
      const lastIndex = this.formDataLedger.shippingDetails.length - 1;
      const inputElement = document.getElementById(`keyword_${lastIndex}`);
      if (inputElement) {
        inputElement.focus();
      }
    },
    focuscustomNext(event, index) {
      const currentField = event.target;
      const fields = this.$refs.productInput;
      const nextField = this.$el.querySelectorAll("input, select, textarea");
      const currentIndex = Array.prototype.indexOf.call(nextField, currentField);
      if (currentIndex + 1 < nextField.length) {
        nextField[currentIndex + 1].focus();
      }
    },
    removeShippingDetail(index) {
      this.formDataLedger.shippingDetails.splice(index, 1);
    },

    resetForm() {
      if (this.$refs.myForm1) {
        this.$refs.myForm1.resetFields();
      }
    },

    PanValdiationWithoutgst(field) {
      let fieldValue = this.formDataLedger[field]?.trim() || "";
      if (!fieldValue) {
        this.rules[field] = { message: "This field is required." };
        return;
      }
      let value = fieldValue.toUpperCase();
      let formattedValue = "";
      const alphabets = value.replace(/[^A-Z]/g, "");
      const digits = value.replace(/[^0-9]/g, "");
      if (alphabets.length > 0) {
        formattedValue += alphabets.slice(0, 5);
      }
      if (formattedValue.length >= 5 && digits.length > 0) {
        formattedValue += digits.slice(0, 4);
      }
      if (formattedValue.length >= 9 && alphabets.length > 5) {
        formattedValue += alphabets.slice(5, 6);
      }
      if (formattedValue.length > 10) {
        formattedValue = formattedValue.slice(0, 10);
      }
      const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
      if (formattedValue.length === 10 && !panRegex.test(formattedValue)) {
        console.warn("Invalid PAN format");
        return;
      }
      this.formDataLedger[field] = formattedValue;
      this.rules[field] = null;
    },

    GstValdiationWithoutgst(field) {
      let fieldValue = this.formDataLedger[field]?.trim() || "";
      if (!fieldValue) {
        this.rules[field] = { message: "This field is required." };
        return;
      }
      let value = fieldValue.toUpperCase().replace(/[^A-Z0-9]/g, "");
      let formattedValue = "";
      const digits = value.replace(/[^0-9]/g, "");
      const alphabets = value.replace(/[^A-Z]/g, "");
      if (digits.length > 0) {
        formattedValue += digits.slice(0, 2);
      }
      if (formattedValue.length >= 2 && alphabets.length > 0) {
        formattedValue += alphabets.slice(0, 5);
      }
      if (formattedValue.length >= 7 && digits.length > 2) {
        formattedValue += digits.slice(2, 6);
      }
      if (formattedValue.length >= 11 && alphabets.length > 5) {
        formattedValue += alphabets.slice(5, 6);
      }
      if (formattedValue.length >= 12 && digits.length > 6) {
        formattedValue += digits.slice(6, 7);
      }
      if (formattedValue.length >= 13) {
        formattedValue += "Z";
      }
      if (formattedValue.length >= 14 && digits.length > 7) {
        formattedValue += digits.slice(7, 8);
      }
      if (formattedValue.length > 15) {
        formattedValue = formattedValue.slice(0, 15);
      }
      const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9]{1}$/;
      if (formattedValue.length === 15 && !gstRegex.test(formattedValue)) {
        console.warn("Invalid GSTIN format");
        return;
      }
      this.formDataLedger[field] = formattedValue;
      this.rules[field] = null;
    },

    clearValidationError(field) {
      const fieldValue = this.formDataLedger[field];
      const fieldLabel = this.rules[field]?.message || field;

      if (field === "stock_state") {
        if (!fieldValue || fieldValue.trim() === " ") {
          this.rules[field] = {
            message: "This Field is required.",
          };
        } else {
          this.rules[field] = null;
        }
      }

      if (field === "mobile_number") {
        if (!fieldValue || fieldValue.trim() === " ") {
          this.rules[field] = {
            message: "This Field is required.",
          };
        } else {
          this.rules[field] = null;
        }
      } else {
        if (!fieldValue || fieldValue.trim() === " ") {
          this.rules[field] = {
            message: "This Field is required.",
          };
        } else {
          this.rules[field] = null;
        }
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
      this.$nextTick(() => {
        //this.$refs.gstinput.focus();
      });
    },

    handleFocus() {
      this.$nextTick(() => {
        if (this.formDataLedger.gst_available == "0") {
          const gstInput = this.$refs.gstinput;
          if (gstInput) {
            gstInput.focus();
            console.log("GST Input focused");
          } else {
            console.error("GST input not found after mount/update.");
          }
        } else if (this.formDataLedger.gst_available == "1") {
          const partyInput = this.$refs.partyinput;
          if (partyInput) {
            partyInput.focus();
            console.log("Party Input focused");
          } else {
            console.error("Party input not found after mount/update.");
          }
        }
      });
    },

    handleGetDetailsClick() {
      console.log("Get Details button clicked");
      setTimeout(() => {
        this.formDataLedger.gst_available = 1;
      }, 100);
      //this.autoFocusInput();
    },
    showBirthdayModal() {
      this.isModalBirthday = false;
      document.addEventListener("keydown", this.handleKeydown);
    },
    showLocationModal() {
      this.isLocationModal = false;
      document.addEventListener("keydown", this.handleKeydown);
    },
    showCopyModal() {
      this.isModalCopy = false;
      document.addEventListener("keydown", this.handleKeydown);
    },
    // showBillingModal() {
    //   this.isModalBillingVisible = true;
    //   document.addEventListener("keydown", this.handleKeydown);
    // },
    // showModal() {
    //   this.isModalVisible = true;
    //   document.addEventListener("keydown", this.handleKeydown);
    // },
    showModales() {
      this.isModalVisibles = true;
      document.addEventListener("keydown", this.handleKeydown);
    },
    showOtherModal() {
      this.isModalVisibleing = false; // Open the modal
      document.addEventListener("keydown", this.handleKeydown);
    },
    handleLocationClose() {
      this.isLocationModal = false;
    },
    handleCopy() {
      this.isModalCopy = false;
    },

    handleClose() {
      this.isModalVisible = false;
      document.removeEventListener("keydown", this.handleKeydown);
    },

    handleCloses() {
      this.isModalVisibles = false;
      document.removeEventListener("keydown", this.handleKeydown);
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
      // this.isModalVisible = false;
      document.removeEventListener("keydown", this.handleKeydown);
      console.log("Success:", xid);
    },

    handleKeydown(event) {
      const targetId = event.target.id;

      console.log(event.key, event.keyCode);
      if (
        event.key === "Enter" &&
        [
          "country_search",
          "state_search",
          "parties_search",
          "parent_ledger",
          "brandsing",
          "unit_1st",
          "tax_category",
        ].includes(targetId)
      ) {
        this.focusNext(event);
      } else if (event.shiftKey && event.key === "Tab") {
        console.log("Shift key");
        return true;
      } else if (event.key === "Tab" && targetId === "gstnumber") {
        event.preventDefault();
        document.getElementById("pannumber").focus();
      } else if (event.key === "Tab" && targetId === "pannumber") {
        event.preventDefault();
        document.getElementById("gstnumber").focus();
      } else if (
        (event.key === "Escape" || event.keyCode === 27) &&
        ![
          "ledger_type",
          "state_search",
          "country_search",
          "parties_search",
          "balancing_method",
          "credit_type",
          "party_type",
        ].includes(targetId)
      ) {
        this.closePay();
      } else if (event.key === "F5" || event.keyCode === 116) {
        event.preventDefault();
        return false;
      } else if (event.key === "F9") {
        event.preventDefault();
      } else if (event.key === "F4") {
        event.preventDefault();
        this.switchTab();
      } else if (event.key === "F8") {
        event.preventDefault();
        if (this.validateLedger()) {
          this.saveLedgerEntry();
        }
      } else if (event.key === "Enter" || event.key === "Tab") {
        if (event.target.id == "gstnumber" || event.target.id == "pannumber") {
          this.fieldFocus(event);
        }
      } else if (event.key === "ArrowRight") {
        console.log(this.formDataLedger.gst_available);
        const gstRadio = document.getElementById("gstradio");
        if (gstRadio && this.formDataLedger.gst_available == 0) {
          this.formDataLedger.gst_available = 1;

          if (this.formDataLedger.gst_available === 1) {
            gstRadio.style.display = "none";
            console.log("Moved to Without GST");
          } else {
            gstRadio.style.display = "block";
            console.log("Moved to With GST");
          }

          this.setFocusToSelectedRadio();
          this.handleFocus();
        }
      }
    },

    closePay() {
      console.log("Esc called");
      let that = this;
      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Transaction data will be lost. Are you sure you want to close?",
        onOk() {
          localStorage.setItem("selectedInvoice", null);
          that.$router.push({
            name: `admin.party.index`,
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
          if (modal && (e.key === "ArrowLeft" || e.key === "ArrowRight")) {
            e.preventDefault();
            const buttons = document.querySelectorAll(".ant-modal-confirm .ant-btn");
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

    pageRefresh() {
      console.log("closePay called");
      let that = this;
      Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Your data will be lost. Are you sure you want to close?",
        onOk() {
          window.location.reload();
        },
        onCancel() {
          console.log("Modal close canceled");
        },
      });
    },

    setFocusToSelectedRadio() {
      if (this.formDataLedger.gst_available === 0) {
        this.$refs.withGST?.$el?.focus();
      } else if (this.formDataLedger.gst_available === 1) {
        this.$refs.withoutGST?.$el?.focus();
      }
    },

    autoFocusRadioButton() {
      // Check if the "With GST" option is selected (gst_available === 0)
      if (this.formDataLedger.gst_available === 0) {
        // Wait for the DOM to be updated and then focus the radio button
        this.$nextTick(() => {
          const radioButton = this.$refs.withGST?.$el?.querySelector("input");
          if (radioButton) {
            radioButton.focus();
          }
        });
      }
    },

    changeColorOnFocus(inputField) {
      if (inputField === "partyName") {
        this.partyNameColor = "#ffd451";
      } else if (inputField === "accountGroup") {
        this.accountGroupColor = "#ffd451";
      } else if (inputField === "stations") {
        this.stationsColor = "#ffd451";
      }
    },
    resetColorOnBlur(inputField) {
      if (inputField === "partyName") {
        this.partyNameColor = "";
      } else if (inputField === "accountGroup") {
        this.accountGroupColor = "";
      } else if (inputField === "stations") {
        this.stationsColor = "";
      }
    },
    onInputPhoneNumber(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.phone_number = value;
    },
    onInputMobileNumber(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.mobile_number = event.target.value;
      this.clearValidationError("mobile_number", value);
    },
    onInputWhatsappNumber(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.whatsapp_number = event.target.value;
    },

    onInputwithPanNumber(event) {
      console.log("Typing pan:", event.target.value); // Debugging

      let value = event.target.value.toUpperCase();
      let formattedValue = "";
      const alphabets = value.replace(/[^A-Z]/g, "");
      const digits = value.replace(/[^0-9]/g, "");
      if (alphabets.length > 0) {
        formattedValue += alphabets.slice(0, 5);
      }
      if (formattedValue.length >= 5 && digits.length > 0) {
        formattedValue += digits.slice(0, 4);
      }
      if (formattedValue.length >= 9 && alphabets.length > 5) {
        formattedValue += alphabets.slice(5, 6);
      }
      this.formDataLedger.with_pan_number = formattedValue;
    },

    onInputwithGstNumber(event) {
      let value = event.target.value.toUpperCase();
      value = value.replace(/[^A-Z0-9]/g, "");
      let formattedValue = "";
      const digits = value.replace(/[^0-9]/g, "");
      const alphabets = value.replace(/[^A-Z]/g, "");
      if (digits.length > 0) {
        formattedValue += digits.slice(0, 2);
      }
      if (formattedValue.length >= 2 && alphabets.length > 0) {
        formattedValue += alphabets.slice(0, 5);
      }
      if (formattedValue.length >= 7 && digits.length > 2) {
        formattedValue += digits.slice(2, 6);
      }
      if (formattedValue.length >= 11 && alphabets.length > 5) {
        formattedValue += alphabets.slice(5, 6);
      }
      if (formattedValue.length >= 12 && digits.length > 6) {
        formattedValue += digits.slice(6, 7);
      }
      if (formattedValue.length >= 13) {
        formattedValue += "Z";
      }
      if (formattedValue.length >= 14 && digits.length > 7) {
        formattedValue += digits.slice(7, 8);
      }
      if (formattedValue.length > 15) {
        formattedValue = formattedValue.slice(0, 15);
      }
      const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9]{1}$/;
      if (formattedValue.length === 15 && !gstRegex.test(formattedValue)) {
        console.warn("Invalid GSTIN format");
        return;
      }
      this.formDataLedger.with_gst_number = formattedValue;
      console.log("Updated GSTIN:", this.formDataLedger.with_gst_number);
    },

    onInputIFSCCODE(event) {
      let value = event.target.value.toUpperCase();
      value = value.replace(/[^A-Z0-9]/g, "");
      if (value.length > 11) {
        value = value.slice(0, 11);
      }
      this.formDataLedger.ifsc_code = value;
    },
    onInputACCOUNTNUMBER(event) {
      let value = event.target.value;
      value = value.replace(/[^a-zA-Z0-9]/g, "");
      if (value.length > 16) {
        value = value.slice(0, 16);
      }

      this.formDataLedger.account_number = value;
    },

    onInputOpeningBalance(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.opening_balance = value;
    },

    onInputCreditDays(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.credit_days = value;
    },

    onInputPincode(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 6) {
        value = value.slice(0, 6);
      }
      this.formDataLedger.stock_pincode = value;
    },

    validateEmail(email) {
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return emailRegex.test(email);
    },

    moveToNextField(event) {
      const elem = event.target;
      if (event.key === "Enter" && elem.value.trim() !== "") {
        const currentIndex = Array.from(elem.form.elements).indexOf(elem);
        elem.form.elements
          .item(currentIndex < elem.form.elements.length - 1 ? currentIndex + 1 : 0)
          .focus();
        event.preventDefault();
        console.log("Moved to the next field after pressing Enter with value.");
      } else if (event.key === "Enter") {
        console.log("Field is empty, staying on the current field.");
        event.preventDefault();
      }

      if (event.key === "Tab" && this.formDataLedger.gst_available === "0") {
        const gstFieldIndex = formElements.indexOf(this.$refs.gstinput);
        const panField = this.$refs.paninput; // Assuming a ref is added to the PAN field

        if (gstFieldIndex !== -1 && panField && elem === formElements[gstFieldIndex]) {
          panField.focus();
          event.preventDefault();
          console.log("Moved from GST to PAN on Tab key press.");
        }
      }
    },
    // focusNext(event) {
    //   const currentField = event.target;
    //   console.log("asdfgn", this.formDataLedger.stock_state);
    //   console.log("focusNext", event.target.id);

    //   const formElements = Array.from(
    //     currentField.form.querySelectorAll("input, select")
    //   );
    //   const currentIndex = formElements.indexOf(currentField);

    //   const nonMandatoryFieldsArray = [
    //     "address",
    //     "search_state",
    //     "website",
    //     "stock_pincode",
    //     "search_state",
    //     "country_search",
    //     "parties_search",
    //     "parent_ledger",
    //     "balancing_method",
    //     "opening_balance",
    //     "credit_days",
    //     "credit_type",
    //     "credit_limit",
    //     "phonenumber",
    //     "whatsapp_numbering",
    //     "pan_numbers",
    //     "ledger_type",
    //     "firstname",
    //     "lastname",
    //     "contact_tittle",
    //     "contactname",
    //     "whatsapp",
    //     "accountnumber",
    //     "shipcontactno",
    //     "shipaddress",
    //     "shipcity",
    //     "shippincode",
    //     "branch",
    //     "accounttype",
    //     "ifsccode",
    //     "keywords",
    //     "valued",
    //     "mail_to",
    //     "category",
    //     "accountholdername",
    //     "pan_numbers",
    //       "shipping_address",
    //       "shipping_city",
    //       "shipping_pincode",
    //       "shipping_contact_number"
    //   ];

    //   // my try
    //   if (
    //     this.formDataLedger.stock_state !== null &&
    //     this.formDataLedger.stock_state !== ""
    //   ) {
    //     formElements[currentIndex + 1].focus();
    //     console.log("focusNext my try");
    //   }
    //   // Handle both Enter and Tab keys
    //   if (event.key === "Enter" || event.key === "Tab") {
    //     event.preventDefault(); // Prevent default behavior for both keys

    //     // Check mandatory fields first
    //     if (currentField.value.trim() !== "") {
    //       // Special handling for state_search
    //       if (event.target.id === "state_search") {
    //         if (
    //           this.formDataLedger.stock_state !== null &&
    //           this.formDataLedger.stock_state !== "" &&
    //           this.formDataLedger.stock_state.trim() !== ""
    //         ) {
    //           if (currentIndex < formElements.length - 1) {
    //             formElements[currentIndex + 1].focus();
    //             console.log("focusNext: Valid state selected, moving to next field");
    //           }
    //         } else {
    //           console.log("focusNext: State validation failed - field required");
    //           return;
    //         }
    //       } else if (currentIndex < formElements.length - 1) {
    //         formElements[currentIndex + 1].focus();
    //         console.log(
    //           `Moved focus to next field after entering value in ${
    //             currentField.name || currentField.id
    //           }.`
    //         );
    //       } else {
    //         console.log("Reached the last field.");
    //       }
    //     } else {
    //       // Handle empty fields
    //       if (
    //         event.target.id === "party_name_ledger" &&
    //         this.formDataLedger.party_name != undefined &&
    //         this.formDataLedger.party_name != ""
    //       ) {
    //         formElements[currentIndex + 1].focus();
    //       } else if (
    //         event.target.id === "party_type" &&
    //         this.formDataLedger.party_type != undefined &&
    //         this.formDataLedger.party_type != ""
    //       ) {
    //         formElements[currentIndex + 1].focus();
    //       } else if (
    //         event.target.id === "account_group" &&
    //         this.formDataLedger.account_group != undefined &&
    //         this.formDataLedger.account_group != ""
    //       ) {
    //         formElements[currentIndex + 1].focus();
    //       } else if (nonMandatoryFieldsArray.indexOf(event.target.id) != -1) {
    //         if (currentIndex < formElements.length - 1) {
    //           formElements[currentIndex + 1].focus();
    //         }
    //       } else if (event.target.id === "state_search") {
    //         if (
    //           this.formDataLedger.stock_state !== null &&
    //           this.formDataLedger.stock_state !== "" &&
    //           this.formDataLedger.stock_state.trim() !== ""
    //         ) {
    //           if (currentIndex < formElements.length - 1) {
    //             formElements[currentIndex + 1].focus();
    //             console.log("focusNext: Valid state selected, moving to next field");
    //           }
    //         } else {
    //           console.log("focusNext: State validation failed - field required");
    //           return;
    //         }
    //       } else {
    //         console.log(`Validation failed: ${currentField.id} is required`);
    //         return;
    //       }
    //     }
    //   }
    // },

    focusNext(event) {
      const currentField = event.target;
      console.log("asdfgn", this.formDataLedger.stock_state);

      console.log("focusNext", event.target.id);
      const formElements = Array.from(
        currentField.form.querySelectorAll("input, select")
      );
      const currentIndex = formElements.indexOf(currentField);
      const nonMandatoryFieldsArray = [
        "address",
        "search_state",
        "website",
        "stock_pincode",
        "search_state",
        "country_search",
        "parties_search",
        "state_search",
        "parent_ledger",
        "balancing_method",
        "opening_balance",
        "credit_days",
        "credit_type",
        "credit_limit",
        "phonenumber",
        "whatsapp_numbering",
        "mobile_numbering",
        "pan_number",
        "ledger_type",
        "firstname",
        "lastname",
        "contactname",
        "whatsapp",
        "accountnumber",
        "shipcontactno",
        "shipaddress",
        "shipcity",
        "shippincode",
        "branch",
        "accounttype",
        "ifsccode",
        "keywords",
        "valued",
        "mail_to",
        "category",
        "accountholdername",
        "pan_numbers",
        "shipping_address",
        "shipping_city",
        "shipping_pincode",
        "shipping_contact_number",
        "contact_tittle",
      ];

      // my try
      if (
        this.formDataLedger.stock_state == null &&
        this.formDataLedger.stock_state.trim() !== ""
      ) {
        formElements[currentIndex + 1].focus();
        console.log("focusNext my try");
      }

      // Handle both Enter and Tab keys
      if (event.key === "Enter" || event.key === "Tab") {
        event.preventDefault(); // Prevent default behavior for both keys

        // Check mandatory fields first
        if (currentField.value.trim() !== "") {
          // Special handling for state_search
          if (event.target.id === "state_search") {
            if (
              this.formDataLedger.stock_state !== null &&
              this.formDataLedger.stock_state !== "" &&
              this.formDataLedger.stock_state.trim() !== ""
            ) {
              if (currentIndex < formElements.length - 1) {
                formElements[currentIndex + 1].focus();
                console.log("focusNext: Valid state selected, moving to next field");
              }
            } else {
              console.log("focusNext: State validation failed - field required");
              return;
            }
          } else if (currentIndex < formElements.length - 1) {
            formElements[currentIndex + 1].focus();
            console.log(
              `Moved focus to next field after entering value in ${
                currentField.name || currentField.id
              }.`
            );
          } else {
            console.log("Reached the last field.");
          }
        } else {
          // Handle empty fields
          if (
            event.target.id === "party_name_ledger" &&
            this.formDataLedger.party_name != undefined &&
            this.formDataLedger.party_name != ""
          ) {
            formElements[currentIndex + 1].focus();
          } else if (
            event.target.id === "party_type" &&
            this.formDataLedger.party_type != undefined &&
            this.formDataLedger.party_type != ""
          ) {
            formElements[currentIndex + 1].focus();
          } else if (
            event.target.id === "account_group" &&
            this.formDataLedger.account_group != undefined &&
            this.formDataLedger.account_group != ""
          ) {
            formElements[currentIndex + 1].focus();
          } else if (nonMandatoryFieldsArray.indexOf(event.target.id) != -1) {
            if (currentIndex < formElements.length - 1) {
              formElements[currentIndex + 1].focus();
            }
          } else if (event.target.id === "state_search") {
            if (
              this.formDataLedger.stock_state !== null &&
              this.formDataLedger.stock_state !== "" &&
              this.formDataLedger.stock_state.trim() !== ""
            ) {
              if (currentIndex < formElements.length - 1) {
                formElements[currentIndex + 1].focus();
                console.log("focusNext: Valid state selected, moving to next field");
              }
            } else {
              console.log("focusNext: State validation failed - field required");
              return;
            }
          } else {
            console.log(`Validation failed: ${currentField.id} is required`);
            return;
          }
        }
      }
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

    success(response) {
      let ledgerId = response.data.id;
      console.log(ledgerId, "Ledger save successful:", response);
      notification.success({
        placement: "bottomRight",
        message: "Ledger Saved Successfully!",
      });

      this.formDataLedger.inputFields.forEach((data) => {
        data["id"] = response.data.id;
      });

      axiosAdmin
        .post("ledger/ledgeritem", {
          data: this.formDataLedger.inputFields,
        })
        .then((response) => {
          console.log("Ledger items saved successfullyddd:", response);
          this.contactsuccess(response, ledgerId);
          this.banksuccess(response, ledgerId);
          this.shippingsuccess(response, ledgerId);
        })
        .catch((error) => {
          console.error("Error saving ledger items:", error);
        });
    },

    contactsuccess(response, ledgerId) {
      console.log("Contact save successful:", response);
      this.formDataLedger.contactFields.forEach((data) => {
        data["id"] = response.data.id;
      });
      axiosAdmin
        .post("contact/contactitem", {
          data: this.formDataLedger.contactFields,
        })
        .then((response) => {
          console.log("Contact items saved successfully:", response);
          this.$router.push({
            name: `admin.party.index`,
          });
          setTimeout(function () {
            document.getElementById("form_item_party_name_search").focus();
          }, 2000);
        })
        .catch((error) => {
          console.error("Error saving contact items:", error);
        });
    },

    banksuccess(response, ledgerId) {
      console.log("bank Details save successful:", response);

      this.formDataLedger.bankDetails.forEach((data) => {
        data["id"] = response.data.id;
      });

      axiosAdmin
        .post("bank/bankDetails", {
          //
          data: this.formDataLedger.bankDetails,
        })
        .then((response) => {
          console.log("bankDetails items saved successfully:", response);

          this.$emit("closed", ledgerId);
          // const partySearchInput = document.getElementById("form_item_party_name_search");
          // // if (partySearchInput) {
          // //   setTimeout(() => {
          // //     partySearchInput.focus();
          // //   }, 1200);
          // // }
        })
        .catch((error) => {
          console.error("Error saving bankDetails items:", error);
        });
    },

    shippingsuccess(response, ledgerId) {
      console.log("Shipping Details save successful:", response);

      this.formDataLedger.shippingDetails.forEach((data) => {
        data["id"] = response.data.id;
      });

      axiosAdmin
        .post("ship/shipDetails", {
          //
          data: this.formDataLedger.shippingDetails,
        })
        .then((response) => {
          console.log("shippingDetails items saved successfully:", response);

          this.$emit("closed", ledgerId);
          // const partySearchInput = document.getElementById("form_item_party_name_search");
          // // if (partySearchInput) {
          // //   setTimeout(() => {
          // //     partySearchInput.focus();
          // //   }, 1200);
          // // }
        })
        .catch((error) => {
          console.error("Error saving shippingDetails items:", error);
        });
    },

    handleClose() {
      this.isLegerModalVisible = false;
    },
    fieldFocus(event) {
      /*const gstNumber = this.formDataLedger.with_gst_number;
     const panNumber = this.formDataLedger.with_pan_number;*/
      const gstNumber = document.getElementById("gstnumber").value;
      const panNumber = document.getElementById("pannumber").value;
      this.formDataLedger.with_gst_number = gstNumber;
      this.formDataLedger.with_pan_number = panNumber;
      console.log(gstNumber, panNumber, event.target.id);
      const isGstValid =
        this.formDataLedger.with_gst_number &&
        /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}[Z]{1}[A-Z0-9]{1}$/i.test(
          this.formDataLedger.with_gst_number
        );
      const isPanValid =
        this.formDataLedger.with_pan_number &&
        /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(this.formDataLedger.with_pan_number);

      if (event.target.id == "gstnumber" && (gstNumber == undefined || gstNumber == "")) {
        this.$refs.paninput.focus();
      } else if (
        event.target.id == "pannumber" &&
        (gstNumber == undefined || gstNumber == "") &&
        (panNumber == undefined || panNumber == "")
      ) {
        this.$refs.gstinput.focus();
        this.rules.with_gst_number = {
          message: "Please enter either GST Number or PAN Number ",
        };
      } else if (
        event.target.id == "gstnumber" &&
        gstnumber != undefined &&
        gstnumber != ""
      ) {
        if (this.formDataLedger.with_gst_number != undefined && !isGstValid) {
          this.rules.with_gst_number = {
            message: "Please Enter valid GST Number",
          };
        } else {
          this.showVerifyNotification();
        }
      } else if (
        event.target.id == "pannumber" &&
        panNumber != undefined &&
        panNumber != ""
      ) {
        if (this.formDataLedger.with_pan_number != undefined && !isPanValid) {
          this.rules.with_pan_number = {
            message: "Please Enter valid PAN  Number",
          };
        } else {
          this.showVerifyNotification();
        }
      } else {
        console.log("ALL are valid");
        this.showVerifyNotification();
      }
    },

    showVerifyNotification(event) {
      this.spinning = true;
      const isGstValid =
        this.formDataLedger.with_gst_number &&
        /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}[Z]{1}[A-Z0-9]{1}$/i.test(
          this.formDataLedger.with_gst_number
        );
      const isPanValid =
        this.formDataLedger.with_pan_number &&
        /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(this.formDataLedger.with_pan_number);
      console.log("<>", this.formDataLedger.with_gst_number, isGstValid);
      if (isGstValid || isPanValid) {
        if (!this.notificationShown) {
          this.spinning = true;
          axiosAdmin.get("/getGstDetails/GSPTN1351G").then((response) => {
            if (response != null && response != "") {
              if (response.data != "") {
                console.log(response.data);

                // Set the ledger type based on the response
                this.formDataLedger.ledger_type = "Registered"; // Assuming the API response indicates a registered GST

                notification.success({
                  placement: "bottomRight",
                  message: "GST verification is done",
                  description: "You can now proceed with the  GST section.",
                });
                this.withoutGST();
                this.notificationShown = true;

                this.formDataLedger.gst_available = 1;
                this.formDataLedger.party_name = response.data.tradeNam;
                this.formDataLedger.address = Object.values(
                  response.data.pradr.addr
                ).join(",");
                this.formDataLedger.stock_pincode = response.data.pradr.addr.pncd;
                this.formDataLedger.stock_city = response.data.pradr.addr.dst;
                this.formDataLedger.stock_state = response.data.pradr.addr.stcd;
                this.formDataLedger.gst_number = response.data.gstin;
                this.formDataLedger.pan_number = response.data.gstin.substring(2, 12);
                console.log(this.formDataLedger);
                setTimeout(function () {
                  document.getElementById("party_name_ledger").focus();
                }, 1000);
              }
            } else {
              notification.error({
                placement: "bottomRight",
                message: "GST verification is failed",
                description: "Please check GST Input number",
              });
            }
            this.spinning = false;
          });
        }
      } else {
        if (this.formDataLedger.with_gst_number == undefined || !isGstValid) {
          this.rules.with_gst_number = {
            message: "Please Enter valid GST Number",
          };
          document.getElementById("gstnumber").focus();
        } else if (this.formDataLedger.with_pan_number == undefined || !isPanValid) {
          this.rules.with_pan_number = {
            message: "Please Enter valid PAN Number",
          };
          document.getElementById("pannumber").focus();
        }
      }
    },

    withoutGST() {
      document.getElementById("gstradio").style.display = "none";
    },
    resetNotificationFlag() {
      this.notificationShown = false;
    },

    saveLedgerEntry() {
      if (this.validateLedger()) {
        this.formDataLedger.party_full_name = this.formDataLedger.party_name;
        // Only proceed if validation is successful
        const {
          inputFields,
          contactFields,
          bankDetails,
          shippingDetails,
          ...restOfFormDataLedger
        } = this.formDataLedger;
        axiosAdmin
          .post("store-ledger", restOfFormDataLedger)
          .then((response) => {
            let newParty = {
              id: response.data.id,
              party_name: this.formDataLedger.party_name,
            };
            console.log(response);
            this.success(response);
            this.contactsuccess(response, newParty);
            this.banksuccess(response, newParty);
            this.shippingsuccess(response, newParty);
          })
          .catch((errorResponse) => {
            var err = errorResponse.response?.data || {};
            const errorCode = errorResponse.response?.status || 500;
            var errorRules = {};

            if (errorCode === 422) {
              if (err.error && typeof err.error.details !== "undefined") {
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
              message.error("Please fix the errors and try again.");
            } else if (err && err.message) {
              message.error(err.message);
            }
          });
      }
    },
    // contact validate//

    validateContact() {
      let isValid = false;

      this.formDataLedger.contactFields.forEach((field, index) => {
        if (field.contact_name && field.contact_name.trim() !== "") {
          this.$set(this.rules.contactFields, index, {
            contact_name: null,
          });
          isValid = true;
        } else {
          this.$set(this.rules.contactFields, index, {
            contact_name: { message: "Name is required." },
          });
        }

        if (field.contact_number && field.contact_number.trim() !== "") {
          this.$set(this.rules.contactFields, index, {
            contact_number: null,
          });
          isValid = true;
        } else {
          this.$set(this.rules.contactFields, index, {
            contact_number: {
              message: "Contact Number is required.",
            },
          });
        }

        // Validate contact_email_id
        if (field.contact_email_id && field.contact_email_id.trim() !== "") {
          if (!this.validateEmail(field.contact_email_id)) {
            this.$set(this.rules.contactFields, index, {
              contact_email_id: {
                message: "Please enter a valid email address.",
              },
            });
            isValid = false;
          } else {
            this.$set(this.rules.contactFields, index, {
              contact_email_id: null,
            });
            isValid = true;
          }
        } else {
          this.$set(this.rules.contactFields, index, {
            contact_email_id: { message: "Email Id is required." },
          });
        }
      });

      return isValid; // Return true if at least one field has data
    },

    // form validation//
    validateLedger() {
      let isValid = true;

      if (
        !this.formDataLedger.party_name ||
        this.formDataLedger.party_name.trim() === ""
      ) {
        this.rules.party_name = {
          message: "This Field is required.",
        };
        isValid = false;
      } else {
        this.rules.party_name = null;
      }

      // State validation - only triggers if a value was selected
      isValid = this.validateStateField() && isValid;

      if (this.formDataLedger.ledger_type === "Registered") {
        if (
          !this.formDataLedger.pan_number ||
          this.formDataLedger.pan_number.trim() === ""
        ) {
          this.rules.pan_number = {
            message: "This Field is required.",
          };
          isValid = false;
        } else {
          this.rules.pan_number = null;
        }

        if (
          !this.formDataLedger.gst_number ||
          this.formDataLedger.gst_number.trim() === ""
        ) {
          this.rules.gst_number = {
            message: "This Field is required.",
          };
          isValid = false;
        } else {
          this.rules.gst_number = null;
        }
      } else {
        // Clear any validation messages for PAN and GST if not "Registered"
        this.rules.pan_number = null;
        this.rules.gst_number = null;
      }

      if (
        !this.formDataLedger.mobile_number ||
        this.formDataLedger.mobile_number.trim() === ""
      ) {
        this.rules.mobile_number = {
          message: "This Field is required.",
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
          message: "This Field is required.",
        };
        isValid = false;
      } else {
        this.rules.stock_city = null;
      }

      //my try
      if (
        !this.formDataLedger.stock_state ||
        String(this.formDataLedger.stock_state).trim() === ""
      ) {
        this.rules.stock_state = { message: "This Field is required." };
        isValid = false;
      } else {
        this.rules.stock_state = null;
      }

      if (
        !this.formDataLedger.account_group ||
        this.formDataLedger.account_group.trim() === ""
      ) {
        this.rules.account_group = {
          message: "This Field is required.",
        };
        isValid = false;
      } else {
        this.rules.account_group = null;
      }

      if (isValid) {
        this.$nextTick(() => {});
      }
      return isValid;
    },
    updateCreditType() {
      if (this.formDataLedger.balancing_method === "1") {
        this.formDataLedger.credit_type = "1"; // Cr
      } else if (this.formDataLedger.balancing_method === "2") {
        this.formDataLedger.credit_type = "2"; // Dr
      }
    },
  },

  mounted() {
    document
      .getElementById("addledgerform")
      .addEventListener("keydown", this.handleKeydown);

    this.$nextTick(() => {
      this.setFocusToSelectedRadio();
      this.handleFocus();
      this.formDataLedger.gst_available = 0;
    });

    console.log("mounted", this.$route.params.id);
    this.getCustomerData(this.$route.params.id);

    if (this.$route.name === "admin.stock.party.supplier") {
      this.formDataLedger.party_type = "2";
    }
    this.updateCreditType();
  },

  beforeDestroy() {
    window.removeEventListener("keydown", this.handleKeydown);
  },

  watch: {
    "formDataLedger.stock_state"(newValue) {
      this.validateStateField();
    },

    "formDataLedger.mobile_number"(newMobileNumber) {
      this.formDataLedger.whatsapp_number = newMobileNumber;
    },

    "formDataLedger.party_name"(tradeName) {
      this.formDataLedger.trade_name = tradeName;
    },

    "formDataLedger.balancing_method": "updateCreditType",

    "formDataLedger.party_type"(newValue) {
      if (newValue === "1") {
        this.formDataLedger.account_group = "Sundry Debtors";
      } else if (newValue === "2") {
        this.formDataLedger.account_group = "Sundry Creditors";
      }
    },

    "formDataLedger.balancing_method"(newValue) {
      if (newValue === "1") {
        this.formDataLedger.credit_type = "CR";
      } else if (newValue === "2") {
        this.formDataLedger.credit_type = "DR";
      }
    },
  },
});
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

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs .ant-tabs-tab + .ant-tabs-tab {
  margin: 0 0 0 0px !important;
  border-left: 1px solid #e4e4e4 !important;
}

.ant-tabs-nav-wrap {
  background-color: #e4e4e4 !important;
  margin-top: 12px !important;
}
</style>
