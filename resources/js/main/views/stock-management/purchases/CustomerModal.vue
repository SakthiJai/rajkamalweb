<template>
  <a-modal
    :open="visible"
    :width="drawerWidth"
    :closable="false"
    :centered="true"
    @close="handleClose"
    class="popups"
  >
    <!-- Loader Container -->
    <div v-if="isLoading" class="loader-container">
      <div class="loader">
        <svg
          focusable="false"
          class="anticon-spin"
          data-icon="sync"
          width="30px"
          height="30px"
          fill="currentColor"
          aria-hidden="true"
          viewBox="64 64 896 896"
        >
          <path
            d="M168 504.2c1-43.7 10-86.1 26.9-126 17.3-41 42.1-77.7 73.7-109.4S337 212.3 378 195c42.4-17.9 87.4-27 133.9-27s91.5 9.1 133.8 27A341.5 341.5 0 01755 268.8c9.9 9.9 19.2 20.4 27.8 31.4l-60.2 47a8 8 0 003 14.1l175.7 43c5 1.2 9.9-2.6 9.9-7.7l.8-180.9c0-6.7-7.7-10.5-12.9-12.3l-175.7 43a8 8 0 00-3 14.1l60.2 47a341.5 341.5 0 01-27.8 31.4A341.5 341.5 0 01412.7 536.1a341.5 341.5 0 01-133.9-27.8c-41.6-31.7-56.4-68.4-73.7-109.4-16.9-39.9-25.9-82.3-26.9-126H168z"
          ></path>
        </svg>
      </div>
    </div>

    <!-- end of loader container-->
    <template v-slot:title>
      <div style="display: flex; justify-content: space-between; align-items: center">
        <span>Create Customer</span>
        <button
          @click="closeCreateCustomer()"
          type="button"
          aria-label="Close"
          class="ant-modal-close"
          id="closeicon"
        >
          <span class="ant-modal-close-x"
            ><span
              role="img"
              aria-label="close"
              class="anticon anticon-close ant-modal-close-icon"
            >
              <svg
                focusable="false"
                data-icon="close"
                width="1em"
                height="1em"
                fill="currentColor"
                aria-hidden="true"
                fill-rule="evenodd"
                viewBox="64 64 896 896"
              >
                <path
                  d="M799.86 166.31c.02 0 .04.02.08.06l57.69 57.7c.04.03.05.05.06.08a.12.12 0 010 .06c0 .03-.02.05-.06.09L569.93 512l287.7 287.7c.04.04.05.06.06.09a.12.12 0 010 .07c0 .02-.02.04-.06.08l-57.7 57.69c-.03.04-.05.05-.07.06a.12.12 0 01-.07 0c-.03 0-.05-.02-.09-.06L512 569.93l-287.7 287.7c-.04.04-.06.05-.09.06a.12.12 0 01-.07 0c-.02 0-.04-.02-.08-.06l-57.69-57.7c-.04-.03-.05-.05-.06-.07a.12.12 0 010-.07c0-.03.02-.05.06-.09L454.07 512l-287.7-287.7c-.04-.04-.05-.06-.06-.09a.12.12 0 010-.07c0-.02.02-.04.06-.08l57.7-57.69c.03-.04.05-.05.07-.06a.12.12 0 01.07 0c.03 0 .05.02.09.06L512 454.07l287.7-287.7c.04-.04.06-.05.09-.06a.12.12 0 01.07 0z"
                ></path>
              </svg>
            </span>
          </span>
        </button>
      </div>
    </template>

    <form ref="myForm1" id="myForm1" layout="vertical" @submit.prevent="">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="13" :lg="13" class="info">
          <fieldset class="">
            <legend class="basicdetalis">Customer Details</legend>
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="5" :lg="5">
                <a-form-item
                  :label="$t('customers.mobile_number')"
                  name="mobile_number"
                  :help="rules.mobile_number ? rules.mobile_number.message : null"
                  :validateStatus="rules.mobile_number ? 'error' : null"
                  class="required"
                >
                </a-form-item>
              </a-col>
              <a-col :xs="24" :sm="24" :md="19" :lg="19">
                <a-input-group compact>
                  <a-input
                    ref="phoneNumber"
                    :max="11"
                    @keyup.enter="focusNext"
                    v-model:value="formDataLedger.mobile_number"
                    id="phonenumber"
                    @keypress="onlyForNumber"
                    autocomplete="off"
                    
                  />
                </a-input-group>
              </a-col>
            </a-row>

            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item
                      :label="$t('common.name')"
                      name="cus_name"
                      :help="rules.cus_name ? rules.cus_name.message : null"
                      :validateStatus="rules.cus_name ? 'error' : null"
                      class="required"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="19" :lg="19">
                    <a-input
                      v-model:value="formDataLedger.cus_name"
                      autocomplete="off"
                      id="cus_name"
                      name="cus_name"
                      @keyup.enter="focusNext"
                       
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
                      :label="$t('stock.gender')"
                      name="custome_gender"
                      :help="rules.custome_gender ? rules.custome_gender.message : null"
                      :validateStatus="rules.custome_gender ? 'error' : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <a-select
                      id="genders"
                      name="gender"
                      v-model:value="formDataLedger.gender"
                      style="width: 100%"
                      @keyup.enter="focusNext"
                    >
                      <a-select-option key="Male" value="0" aria-selected="true">
                        Male
                      </a-select-option>
                      <a-select-option key="Female" value="1" aria-selected="true">
                        Female
                      </a-select-option>
                      <a-select-option
                        key="Transgender"
                        value="3"
                        aria-selected="true"
                      >
                        Transgender
                      </a-select-option>
                    </a-select>
                  </a-col>
<!--
                  <a-col :xs="24" :sm="24" :md="5" :lg="5" class="hidden">
                    <a-form-item
                      :label="$t('Age')"
                      name="customer_age"
                      :help="rules.customer_age ? rules.customer_age.message : null"
                      :validateStatus="rules.customer_age ? 'error' : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="7" :lg="7" class="hidden">
                    <a-input
                      id="age"
                      name="age"
                      v-model:value="formDataLedger.age"
                      @keyup.enter="focusNext"
                      autocomplete="off"
                    />
                  </a-col> -->
                </a-row>
              </a-col>
            </a-row>
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item :label="$t('stock.parent_ledger')" name="parent_ledger">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="19" :lg="19">
                    <ParentSearchInput
                      id="ledger_id"
                      name="ledger_id"
                      ref="partySearch"
                      @valueChanged="
                        (productId) => (formDataLedger.ledger_id = productId)
                      "
                      @valueSuccess="getStockValue"
                      :productData="data"
                      @keyup.enter="focusNext"
                      v-model:value="formDataLedger.ledger_id"
                    />
                  </a-col>
                </a-row>
              </a-col>
            </a-row>
          </fieldset>
        </a-col>
        <a-col :xs="24" :sm="24" :md="11" :lg="11" class="info">
          <fieldset class="">
            <legend class="basicdetalis">Contact Details</legend>

            <!-- accountsgroup-->
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
                      name="address"
                      v-model:value="formDataLedger.address"
                      @keyup.enter="focusNext"
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
                    <a-form-item :label="$t('common.email')" name="mail_to">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="19" :lg="19">
                    <a-input
                      id="email"
                      name="email"
                      v-model:value="formDataLedger.email"
                      type="email"
                      autocomplete="off"
                      @keyup.enter="focusNext"
                    />
                  </a-col>
                </a-row>
              </a-col>
            </a-row>

            <!-- city and pincode  -->

            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item :label="$t('City')" name="customer_city">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-input
                      id="customer_city"
                      name="customer_city"
                      v-model:value="formDataLedger.customer_city"
                       autocomplete="off"
                      @keyup.enter="focusNext"
                    />
                  </a-col>


                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item
                      :label="$t('Pincode')"
                      name="customer_pincode"
                      :help="rules.customer_pincode ? rules.customer_pincode.message : null"
                      :validateStatus="rules.customer_pincode ? 'error' : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-input
                      id="pin_number"
                      name="pin_number"
                      v-model:value="formDataLedger.pin_number"
                      @keypress="onlyForNumber"
                      @keyup.enter="focusNext"
                      autocomplete="off"

                    />
                  </a-col>
                </a-row>
              </a-col>
            </a-row>
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="24">

                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-form-item
                      :label="$t('Whatsapp Number')"
                      name="whatsapp_numbner"
                      :help="rules.customer_whatsno ? rules.customer_whatsno.message : null"
                      :validateStatus="rules.customer_whatsno ? 'error' : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-input-group compact>
                      <a-button type="primary" class="pannumber">+91</a-button>
                      <a-input
                        id="whatsapp_numbner"
                        name="whatsapp_numbner"
                        v-model:value="formDataLedger.whatsapp_numbner"
                        @keyup.enter="focusNext"
                        style="width: 70%"
                        @keypress="onlyForNumber"
                         
                        autocomplete="off"
                      />
                    </a-input-group>
                  </a-col>
                </a-row>
              </a-col>
            </a-row>
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="5" :lg="5">
                    <a-form-item :label="$t('State')" name="parent_ledger">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="19" :lg="19">
                    <StateSearchInput
                      id="state_search"
                      @valueChanged="
                        (productId) => (formDataLedger.customer_state = productId)
                      "
                      @valueSuccess="getStockValue"
                      :productData="data"
                      @keyup.enter="focusNext"
                      v-model:value="formDataLedger.customer_state"
                    />
                  </a-col>
                </a-row>
              </a-col>
            </a-row>



            <!-- end countrys-->
          </fieldset>
        </a-col>
      </a-row>
      <a-row :gutter="16" class="hidden">
        <a-col :xs="24" :sm="24" :md="12" :lg="24" class="">
          <fieldset class="">
            <legend class="basicdetalis">Other Details</legend>
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="3" :lg="3">
                <a-form-item
                  :label="$t('Dob')"
                  name="custome_mobile"
                  :help="rules.custome_mobile ? rules.custome_mobile.message : null"
                  :validateStatus="rules.custome_mobile ? 'error' : null"
                >
                </a-form-item>
              </a-col>
              <a-col :xs="24" :sm="24" :md="6" :lg="6">
                <a-input
                type="date"
                id="whatsapp_numbner"
                name="whatsapp_numbner"
                @keyup.enter="focusNext"
                v-model:value="formDataLedger.dob"
                @keypress="onlyForNumber"
                autocomplete="off"
              />

              </a-col>
              <a-col :xs="12" :sm="24" :md="3" :lg="3">
                <a-form-item
                  :label="$t('Customer Type')"
                  name="customer_type"
                  :help="rules.custome_gender ? rules.custome_gender.message : null"
                  :validateStatus="rules.custome_gender ? 'error' : null"
                >
                </a-form-item>
              </a-col>
              <a-col :xs="12" :sm="24" :md="7" :lg="7">
                <a-select
                  id="genders"
                  name="genders"
                  v-model:value="formDataLedger.customer_type"
                  style="width: 73%"
                  @keyup.enter="focusNext"
                >
                  <a-select-option key="Regular" value="Regular" aria-selected="true">
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
                    value=" Pensioner"
                    aria-selected="true"
                  >
                    Pensioner
                  </a-select-option>
                  <a-select-option key=" One Time" value=" One Time" aria-selected="true">
                    One Time
                  </a-select-option>
                </a-select>
              </a-col>
            </a-row>

            <!-- countrys-->
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="3" :lg="3">
                    <a-form-item :label="$t('Billing Discount')" name="billing_discount">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-input-group compact>
                      <a-span type="primary" class="pannumber">&#8377;</a-span>

                      <a-input
                        id="billing_discount"
                        name="billing_discount"
                        v-model:value="formDataLedger.billing_discount"
                        style="width: 88%"
                        @keypress="onlyForNumber"
                        @keyup.enter="focusNext"
                      />
                    </a-input-group>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="3" :lg="3">
                    <a-form-item :label="$t('Government Id')" name="Government_Id">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <a-input
                      id="government_id"
                      name="government_id"
                      @keyup.enter="focusNext"
                      v-model:value="formDataLedger.government_id"
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
        <a-col :xs="24" :sm="24" :md="9" :lg="9">
          <button
            @click="onSubmitLedger"
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
            @click="closeCreateCustomer()"
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
    <template #footer class="floats" style="display: none !important">
      <div class="floats" style="display: none">
        <a-button
          type="button"
          id="btn-Ledger"
          title="Ledger"
          class="btn default-btn ng-star-inserted"
          @click="EnterprisesModel"
          ><span class="box"
            ><span class="shortcut ng-star-inserted"><code>F4</code></span
            ><span class="ng-star-inserted">Ledger</span></span
          ><span class="effect"></span
        ></a-button>
        <a-button
          type="button"
          id="btn-Ledger"
          title="Ledger"
          class="btn default-btn ng-star-inserted"
          ><span class="box"
            ><span class="shortcut ng-star-inserted"><code>F8</code></span
            ><span class="ng-star-inserted">Outstanding</span></span
          ><span class="effect"></span
        ></a-button>
        <a-button
          type="button"
          id="btn-Ledger"
          title="Ledger"
          class="btn default-btn ng-star-inserted"
          ><span class="box">
            <span class="shortcut ng-star-inserted"><code>F7</code></span
            ><span class="ng-star-inserted">All</span></span
          ><span class="effect"></span
        ></a-button>
      </div>
    </template>
  </a-modal>
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
import fields from "./fields";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import common from "../../../../common/composable/common";
import { debounce } from "lodash-es";
import ExpenseCategoryModal from "./ExpenseCategoryModal.vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import DiscountModel from "./DiscountModel.vue";
import LimitModel from "./LimitModel.vue";
import OtherModel from "./OtherModel.vue";
import BillingModel from "./BillingModel.vue";
import CopyModel from "./CopyModel.vue";
import LocationModel from "./LocationModel.vue";
import BirthdayModel from "./BirthdayModel.vue";
import GSTModel from "./GSTModel.vue";
import CountySearchInput from "../../.../../../../common/components/ledger/CountySearchInput.vue";
import StateSearchInput from "../../.../../../../common/components/ledger/StateSearchInput.vue";
import ParentSearchInput from "../.../../../../../common/components/ledger/ParentSearchInput.vue";
import StationSearchInput from "../.../../../../../common/components/ledger/StationSearchInput.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue";
import { message, notification } from "ant-design-vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";

export default defineComponent({
  props: ["productData", "ledgerId"],
  emits: ["valueSuccess", "valueChanged", "closed"],
  created() {
    console.log("created:", this.ledgerId);
  },
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    StaffMemberAddButton,
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
    DateTimePicker,
    ExclamationCircleOutlined,
  },

  setup(props, { emit }) {
    const { addEditRequestAdmin, loading, rules } = apiAdmin();

    // setup(props, { emit }) {
    // const { addEditRequestAdmin, loading, rules } = apiAdmin();
    // const closeCreateCustomer = () => {
    //     rules.value = {};
    //     emit("closed");
    // };

    const ledgerSubmit = (formDataLedger) => {
      addEditRequestAdmin({
        url: `store-ledger/customer`,
        data: formDataLedger,
        success: (res) => {
          notification.success({
            placement: "bottomRight",
            message: "Customer saved successfully",
          });
          setTimeout(function () {
            emit("closed");
            document.getElementById("form_item_party_name_search").focus();
          }, 1500);
        },
      });
    };

    const valueChanged = (value, option) => {
      emit("valueChanged", value);
      emit("valueSuccess");
    };

    return {
      loading,
      rules,
      //closeCreateCustomer,
      // formDataLedger,
      ledgerSubmit,
      valueChanged,
      drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
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
        customer_city:"",
        ledger_id: "",
        gender: "0",
        age: 0,
        billing_discount: "",
        government_id: "",
        customer_type: "One Time",
        address: "",
        dob: "",
      },
      currentFocusElement: null,
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
      isLocationModal: false,
      isModalBirthday: false,
      isModalGST: false,
      isLoading: false,
      //duplicate: false,
      url: "your-url-here",
      addEditType: "add",
      pageTitle: "Create Ledger",
      successMessage: "Operation successful!",
      activeKey: "taxdetails",

      resetForm() {
        this.formDataLedger = {
          party_name: "",
          account_group: "",
          station: "",
          mail_to: "",
          gender:"",
          address: "",
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
          customer_city:"",
          ledger_id: "",
        };
      },
    };
  },

  methods: {
    onlyForNumber($event) {
      // console.log($event.keyCode); //keyCodes value
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;

      // only allow number and one dot
      if (
        (keyCode < 48 || keyCode > 57) &&
        (keyCode !== 46 || this.price.indexOf(".") != -1)
      ) {
        // 46 is dot
        $event.preventDefault();
      }

      // restrict to 2 decimal places
      if (
        this.price != null &&
        this.price.indexOf(".") > -1 &&
        this.price.split(".")[1].length > 1
      ) {
        $event.preventDefault();
      }
    },
    closeCreateCustomer() {
      console.log("close customer");
      this.$emit("closed");
    },
    switchTab() {
      console.log(this.activeKey);
      const tabs = ["taxdetails", "contactinfo", "bankdetails", "visibility"];
      const currentIndex = tabs.indexOf(this.activeKey);
      this.activeKey = tabs[(currentIndex + 1) % tabs.length];
    },
    switchEnter(event) {
      if (event.key === "Enter") {
        const tabs = ["taxdetails", "contactinfo", "bankdetails", "visibility"];
        const currentIndex = tabs.indexOf(this.activeKey);
        this.activeKey = tabs[(currentIndex + 1) % tabs.length];
        const nextInput = this.$refs[`input-${this.activeKey}`];
        if (nextInput) {
          nextInput.focus();
        }
        event.preventDefault();
      } else if (event.key === "F6") {
        event.preventDefault();
        this.switchTab();
      }
    },

    resetForm() {
      if (this.$refs.myForm1) {
        this.$refs.myForm1.resetFields();
      }
    },
    async onSubmitLedger() {
      try {
        this.formDataLedger.ledger_id = this.ledgerId;
        this.ledgerSubmit(this.formDataLedger);
      } catch (error) {
        this.$message.error(this.$t("company.updateFailed"));
        console.error(error);
        this.onSubmitLedger();
      }
      this.autoFocusInput();
    },

    showGSTModal() {
      this.isModalGST = true;
    },
    showBirthdayModal() {
      this.isModalBirthday = true;
    },

    showLocationModal() {
      this.isLocationModal = true;
    },
    showCopyModal() {
      this.isModalCopy = true;
    },
    showBillingModal() {
      this.isModalBillingVisible = true;
    },
    showModal() {
      this.isModalVisible = true;
    },
    showModales() {
      this.isModalVisibles = true;
    },
    showOtherModal() {
      this.isModalVisibleing = true; // Open the modal
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
    // handleCloses() {
    //     this.isModalVisibles = false;
    //     document.removeEventListener('keydown', this.handleKeydown);
    // },
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
      document.removeEventListener("keydown", this.handleKeydown);
      console.log("Success:", xid);
    },
    handleKeydown(event) {
      console.log(event.key, event.which);
      if (event.key === "Escape") {
        this.cancelAddCustomer();
      } else if (event.key === "F9") {
        event.preventDefault();
        this.resetForm();
      } else if (event.key === "F6") {
        event.preventDefault();
        this.switchTab();
      } else if (event.key === "F8") {
        event.preventDefault();
        this.onSubmitLedger();
      } else if (event.key === "Enter") {
        this.moveToNextField(event);
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
      this.formDataLedger.mobile_number = value;
    },
    onInputWattsappNumber(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.whatsapp_number = value;
    },
    onInputPanNumber(event) {
      let value = event.target.value.toUpperCase();
      value = value.replace(/[^A-Z0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.pan_number = value;
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
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 15) {
        value = value.slice(0, 15);
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
    moveToNextField(e) {
      const formElements = Array.from(
        e.target.form.querySelectorAll("input, select, textarea")
      );
      const currentIndex = formElements.indexOf(e.target);
      if (currentIndex < formElements.length - 1) {
        formElements[currentIndex + 1].focus();
      }
    },

    autoFocusInput() {
      this.$nextTick(() => {
        this.$refs.phoneNumber.focus();
      });
    },

    cancelAddCustomer() {
      console.log("Esc called");
      let that = this;
      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Transaction data will be lost. Are you sure you want to close?",
        onOk() {
          that.$emit("closed");
        },
        onCancel() {
          that.$refs.phoneNumber.focus();
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
      }, 0);
    },


//     checkproductDuplicate(event) {
//   console.log("API call");
//   this.duplicate = false;
//   if (
//     this.formDataLedger.cus_name != undefined &&
//     this.formDataLedger.cus_name != null &&
//     this.formDataLedger.cus_name != "" &&
//     this.formDataLedger.cus_name.trim() != "" &&
//     event.key != "F8"
//   ) {
//     axiosAdmin
//       .get(
//         "customerDuplicate/" + this.formDataLedger.cus_name + "/id", 
//         {
//           params: {
//             mobile_number: this.formDataLedger.mobile_number,
//             whatsapp_numbner: this.formDataLedger.whatsapp_numbner
//           }
//         }
//       )
//       .then((response) => {
//         console.log(response);
//         // Check if the response status is 500 (duplicate found)
//         if (response.status == 500) {
//           this.duplicate = true;
//           message.error("Contact already exists! Please solve this.");
//         }
//       })
//       .catch((errorResponse) => {
//         var err = errorResponse.data;
//         const errorCode = errorResponse.status;
//         var errorRules = {};

//         if (errorCode == 422) {
//           if (err.error && typeof err.error.details != "undefined") {
//             var keys = Object.keys(err.error.details);
//             for (var i = 0; i < keys.length; i++) {
//               var key = keys[i].replace(".", "\\.");
//               errorRules[key] = {
//                 required: true,
//                 message: err.error.details[keys[i]][0],
//               };
//             }
//           }

//           rules.value = errorRules;
//           message.error(t("common.fix_errors"));
//         }

//         if (err && err.message) {
//           message.error(err.message);
//           err = {
//             error: {
//               ...err,
//             },
//           };
//         }
//       });
//   }
// },

    focusNext(e) {
      console.log("Test =>", this.formDataLedger.hsn_sac);
      this.currentFocusElement = e.target;
      const formElements = Array.from(
        e.target.form.querySelectorAll("input, select,date")
      );
      const currentIndex = formElements.indexOf(e.target);
      //console.log(e.target.id)
      if (currentIndex < formElements.length - 1) {
        if (
          e.target.id == "phonenumber" &&
          this.formDataLedger.mobile_number != undefined &&
          this.formDataLedger.mobile_number != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "cus_name" &&
          this.formDataLedger.cus_name != undefined &&
          this.formDataLedger.cus_name != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "genders" &&
          this.formDataLedger.genders != undefined &&
          this.formDataLedger.genders != ""
        ) {
          formElements[currentIndex + 1].focus();
        }
        //  else if (
        //   e.target.id == "age" &&
        //   this.formDataLedger.age != undefined &&
        //   this.formDataLedger.age != ""
        // ) {
        //   formElements[currentIndex + 1].focus();
        // }
         else if (
          e.target.id == "ledger_id" &&
          this.formDataLedger.ledger_id != undefined &&
          this.formDataLedger.ledger_id != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "address" &&
          this.formDataLedger.address != undefined &&
          this.formDataLedger.address != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "email" &&
          this.formDataLedger.email != undefined &&
          this.formDataLedger.email != ""
        ) {
          formElements[currentIndex + 1].focus();
        }
         else if (
          e.target.id == "customer_city" &&
          this.formDataLedger.customer_city != undefined &&
          this.formDataLedger.customer_city != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "pin_number" &&
          this.formDataLedger.pin_number != undefined &&
          this.formDataLedger.pin_number != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "whatsapp_numbner" &&
          this.formDataLedger.whatsapp_numbner != undefined &&
          this.formDataLedger.whatsapp_numbner != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "genders" &&
          this.formDataLedger.genders != undefined &&
          this.formDataLedger.genders != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "billing_discount" &&
          this.formDataLedger.billing_discount != undefined &&
          this.formDataLedger.billing_discount != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "government_id" &&
          this.formDataLedger.government_id != undefined &&
          this.formDataLedger.government_id != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else {
          console.log(e.target.id, this.formDataLedger.cus_name);
          // document.getElementById('save-item').focus();
        }
      }
    },
  },

  mounted() {
    this.autoFocusInput();
    this.$refs.partySearch.fetchProducts("");
    // Add keydown event listener to capture F9 key
    document.getElementById("myForm1").addEventListener("keydown", this.handleKeydown);
  },
  beforeDestroy() {
    // Remove event listener when component is destroyed
    //  document.getElementById('customervue').removeEventListener('keydown', this.handleKeydown);
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
}

.fieldheight {
  height: 142px;
}

.fieldheights {
  height: 143px;
}

.fieldheightsss
{
    height: 150px;
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
  margin-top: -10px;
}

#closeicon {
  top: 14px !important;
}

.pannumber {
  padding: 2px 6px !important;
  background: #f6f6f6;
  border-color: #c2c2c2;
  color: black;

  text-align: center;
  height: 25px;
  font-size: 12px;
  border: 1px solid #ccc;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-tabs .ant-tabs-tab + .ant-tabs-tab {
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

.loader-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.7);
  /* Optional: semi-transparent background */
  z-index: 9999;
}

.loader {
  text-align: center;
}

.hidden {
    display: none;
}
</style>
