<template>
  <a-modal
    :open="visible"
    :width="drawerWidth"
    :closable="false"
    :centered="true"
    @close="handleClose"
    class="ewaybillpopup"
  >
    <template v-slot:title>
      <div style="display: flex; justify-content: space-between; align-items: center">
        <span>E-Way Bill</span>
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
                  d="M168 504.2c1-43.7 10-86.1 26.9-126 17.3-41 42.1-77.7 73.7-109.4S337 212.3 378 195c42.4-17.9 87.4-27 133.9-27s91.5 9.1 133.8 27A341.5 341.5 0 01755 268.8c9.9 9.9 19.2 20.4 27.8 31.4l-60.2 47a8 8 0 003 14.1l175.7 43c5 1.2 9.9-2.6 9.9-7.7l.8-180.9c0-6.7-7.7-10.5-12.9-6.3l-56.4 44.1C765.8 155.1 646.2 92 511.8 92 282.7 92 96.3 275.6 92 503.8a8 8 0 008 8.2h60c4.4 0 7.9-3.5 8-7.8zm756 7.8h-60c-4.4 0-7.9 3.5-8 7.8-1 43.7-10 86.1-26.9 126-17.3 41-42.1 77.8-73.7 109.4A342.45 342.45 0 01512.1 856a342.24 342.24 0 01-243.2-100.8c-9.9-9.9-19.2-20.4-27.8-31.4l60.2-47a8 8 0 00-3-14.1l-175.7-43c-5-1.2-9.9 2.6-9.9 7.7l-.7 181c0 6.7 7.7 10.5 12.9 6.3l56.4-44.1C258.2 868.9 377.8 932 512.2 932c229.2 0 415.5-183.7 419.8-411.8a8 8 0 00-8-8.2z"
                ></path>
              </svg>
            </div>
          </div>
        <button
          @click="onClose"
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
    <!-- <form  @submit.prevent="onSubmitLedger" layout="vertical" ref="myForm" id="myForm1"> -->
    <form ref="myForm1" id="addproductform" layout="vertical" @submit.prevent="false">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="10" :lg="20">
          BillNo -  <span class="billnumber">{{  }}</span>
        </a-col>
        <a-col :xs="24" :sm="24" :md="4" :lg="4">
          Date -  <span class="billnumber">17-02-2025</span>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="24" :lg="24" class="">
          <fieldset class="">
            <legend class="basicdetalis">Transporter Details</legend>

            <!-- accountsgroup-->
            <a-row :gutter="24">
              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item
                      :label="$t('Supply Type')"
                      name="name"
                      :help="rules.supply_type ? rules.supply_type.message : null"
                      :validateStatus="rules.supply_type ? 'error' : null"

                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="12">
                    <a-select
                      id="product_type"
                      name="product_type"
                        ref="ProductInput"
                      v-model:value="formDataLedger.supply_type"
                       @input="validateField('supply_type')"
                      style="width: 100%"
                      @keyup.enter="focusNext"
                    >
                      <a-select-option key="Supply" value="Supply" aria-selected="true">
                        Supply
                      </a-select-option>
                      <a-select-option key="Goods" value="Goods" aria-selected="true">
                        Goods
                      </a-select-option>
                      <a-select-option key="Export" value="Export" aria-selected="true">
                        Export
                      </a-select-option>
                    </a-select>
                  </a-col>

                </a-row>
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item :label="$t('Document Type')" name="packing"
                    :help="rules.document_type ? rules.document_type.message : null"
                      :validateStatus="rules.document_type ? 'error' : null"
                     >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-select
                    id="product_type"
                    name="product_type"
                    v-model:value="formDataLedger.document_type"
                    style="width: 100%"
                    @keyup.enter="focusNext"
                      @input="validateField('document_type')"
                  >
                    <a-select-option key="Goods" value="Goods" aria-selected="true">
                      Tax Invoice
                    </a-select-option>
                    <a-select-option key="Service" value="Service" aria-selected="true">
                      Bill Of supply
                    </a-select-option>
                    <a-select-option key=" Bill Of Entry" value=" Bill Of Entry" aria-selected="true">
                     Bill Of Entry
                    </a-select-option>
                  </a-select>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Transporter Name')"
                          name="unit_1st">
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        v-model:value="formDataLedger.transporter_name"
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Transporter ID/GSTIN')"
                          name="unit_in_decimal"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        v-model:value="formDataLedger.transporter_id"
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Docoment No')"
                          name="hsn_sac1">
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        v-model:value="formDataLedger.document_number"

                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Document Date')"
                          name="tax_category" >
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <input
                        id="as_on_date"
                        type="date"
                        v-model="formDataLedger.document_date"
                        @keyup.enter="focusNext"
                        class="ant-input css-dev-only-do-not-override-wosfq4"
                      />
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
              </a-col>
              <!-- second colunm -->
              <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-form-item
                      :label="$t('Mode')"
                      name="mrp"
                      :validateStatus="rules.mode ? 'error' : ''"
                      :help="rules.mode ? rules.mode.message : ''"

                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-select
                    id="product_type"
                    name="product_type"
                    v-model:value="formDataLedger.mode"
                    style="width: 100%"
                    @keyup.enter="focusNext"
                     @input="validateField('mode')"
                  >
                    <a-select-option key="Road" value="Road" aria-selected="true">
                     Road
                    </a-select-option>
                    <a-select-option key="Rail" value="Rail" aria-selected="true">
                     Rail
                    </a-select-option>
                    <a-select-option key="Air" value="Air" aria-selected="true">
                     Air
                    </a-select-option>
                  </a-select>
                  </a-col>
                </a-row>

                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-form-item
                      :label="$t('Vehicle Type')"
                      name="purchase_rate"

                      :validateStatus="rules.vehicle_type ? 'error' : null"
                      :help="rules.vehicle_type ? rules.vehicle_type.message : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-select
                    id="product_type"
                    name="product_type"
                    v-model:value="formDataLedger.vehicle_type"
                    style="width: 100%"
                    @keyup.enter="focusNext"
                     @input="validateField('vehicle_type')"
                  >
                    <a-select-option key="Regular" value="Regular" aria-selected="true">
                   Regular
                    </a-select-option>
                    <a-select-option key="Dimensional" value="Dimensional" aria-selected="true">
                     Over Dimensional Cargo
                    </a-select-option>

                  </a-select>
                  </a-col>
                </a-row>

                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-form-item
                      :label="$t('Vehicle No')"
                      name="cost"
                      :help="rules.cost ? rules.cost.message : null"
                      :validateStatus="rules.cost ? 'error' : null"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        v-model:value="formDataLedger.vehicle_number"
                        @input="clearValidationError('name')"
                        value=""
                        @keyup.enter="focusNext"
                      ></a-input>
                  </a-col>
                </a-row>
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-form-item
                      :label="$t('Distance (in K.M.)')"
                      value=""
                      :help="rules.distance ? rules.distance.message : null"
                      :validateStatus="rules.distance ? 'error' : null"

                    >
                    </a-form-item>
                  </a-col>

                  <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-input
                      ref="free_scheme_1"
                      autocomplete="off"
                      name="free_scheme_1"
                      v-model:value="formDataLedger.transDistance"
                        @input="clearValidationError('distance')"
                      value=""
                      type="text"
                    >
                    </a-input>
                  </a-col>

                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a href="https://ewaybillgst.gov.in/Others/P2PDistance.aspx"
                    type="button"
                    id="save-itemss"
                    title="Ledger"
                    class="calculatedistance"
                    target="_blank"
                  >
                    <span class="box">
                      <span class="ng-star-inserted">Calculate Distance</span>
                    </span>
                  </a>
                  </a-col>
                </a-row>
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="10" :lg="10">
                    <a-form-item
                      :label="$t('Transaction Type')"
                      name="scheme_type"
                      :help="rules.transaction_type ? rules.transaction_type.message : null"
                      :validateStatus="rules.transaction_type ? 'error' : null"
                    ></a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="14" :lg="14">
                    <a-select
                      id="scheme_type"
                      v-model:value="formDataLedger.transaction_type"
                      class="balance"
                      @keyup.enter="focusNext"
                      @input="validateField('transaction_type')"
                    >
                      <a-select-option
                        key="Full Scheme"
                        value="Full Scheme"
                        aria-selected="true"
                      >
                       Regular
                      </a-select-option>

                      <a-select-option
                        key="bill-to"
                        value="bill-to"
                        aria-selected="true"
                      >
                       Bill-To Ship-top
                      </a-select-option>
                      <a-select-option
                        key="bill-to"
                        value="bill-to"
                        aria-selected="true"
                      >
                      Bill From Dispatch Form
                      </a-select-option>
                    </a-select>
                  </a-col>
                </a-row>
              </a-col>

              <!--  end second colunm -->
            </a-row>
            <!-- end category field-->
          </fieldset>
        </a-col>

      </a-row>

      <!--  consigne design-->
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="12" :lg="12" class="">
          <fieldset class="">
            <legend class="basicdetalis">Consigner</legend>

            <!-- accountsgroup-->
            <a-row :gutter="24">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item
                      :label="$t('Name')"
                      name="name"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        readonly
                        v-model:value="formDataLedger.fromTrdName"
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                  </a-col>

                </a-row>
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item :label="$t('Address')" name="packing">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-textarea
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    readonly
                    v-model:value="formDataLedger.fromAddr1"
                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-textarea>
                  </a-col>
                </a-row>
                <a-row :gutter="24">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item :label="$t('Address2')" name="packing">
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-textarea
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    readonly
                    v-model:value="formDataLedger.fromAddr2"
                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-textarea>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('State')"
                          name="unit_1st"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="16" :lg="16" style="margin-top:5px;">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        readonly
                        name="name"
                        v-model:value="formDataLedger.fromStateCode"
                        @input="clearValidationError('name')"
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Place')"
                          name="unit_in_decimal"
                        >
                        </a-form-item>
                      </a-col>
                      <a-col :xs="24" :sm="24" :md="16" :lg="16">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        readonly
                        v-model:value="formDataLedger.fromPlace"
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('Pin Code')"
                          name="hsn_sac1"
                        >
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="16" :lg="16">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        readonly
                        v-model:value="formDataLedger.fromPincode"
                        @input="clearValidationError('name')"
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>

                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item
                          :label="$t('GSTIN')"
                          name="tax_category">
                        </a-form-item>
                      </a-col>

                      <a-col :xs="24" :sm="24" :md="16" :lg="16">
                        <a-input
                        autocomplete="off"
                        id="ProductInput"
                        name="name"
                        v-model:value="formDataLedger.toGstin"
                        readonly
                        value=""
                        @keyup.enter="focusNext"
                      >
                      </a-input>
                      </a-col>
                    </a-row>
                  </a-col>
                </a-row>
              </a-col>

            </a-row>
            <!-- end category field-->
          </fieldset>
        </a-col>
    <!-- second clounm-->
    <a-col :xs="24" :sm="24" :md="12" :lg="12" class="">
      <fieldset class="">
        <legend class="basicdetalis">Consignee</legend>

        <!-- accountsgroup-->
        <a-row :gutter="24">
          <a-col :xs="24" :sm="24" :md="24" :lg="24">
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                <a-form-item
                  :label="$t('Name')"
                  name="name"
                >
                </a-form-item>
              </a-col>
              <a-col :xs="24" :sm="24" :md="16" :lg="16">
                <a-input
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    v-model:value="formDataLedger.toTrdName"
                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-input>
              </a-col>

            </a-row>
            <a-row :gutter="24">
              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                <a-form-item :label="$t('Address1')" name="packing">
                </a-form-item>
              </a-col>
              <a-col :xs="24" :sm="24" :md="16" :lg="16">
                <a-textarea
                autocomplete="off"
                id="ProductInput"
                name="name"
                v-model:value="formDataLedger.toAddr1"
                value=""
                @keyup.enter="focusNext"
              >
              </a-textarea>
              </a-col>
            </a-row>
            <a-row :gutter="24">
              <a-col :xs="24" :sm="24" :md="8" :lg="8">
                <a-form-item :label="$t('Address2')" name="packing">
                </a-form-item>
              </a-col>
              <a-col :xs="24" :sm="24" :md="16" :lg="16">
                <a-textarea
                autocomplete="off"
                id="ProductInput"
                name="name"
                v-model:value="formDataLedger.toAddr2"
                value=""
                @keyup.enter="focusNext"
              >
              </a-textarea>
              </a-col>
            </a-row>

            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item
                      :label="$t('State')"
                      name="unit_1st"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="16" :lg="16" style="margin-top:5px;">
                    <a-input
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    v-model:value="formDataLedger.actToStateCode"
                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-input>
                  </a-col>
                </a-row>
              </a-col>
            </a-row>
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item
                      :label="$t('Place')"
                      name="unit_in_decimal"
                    >
                    </a-form-item>
                  </a-col>
                  <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-input
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    v-model:value="formDataLedger.toPlace"
                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-input>
                  </a-col>
                </a-row>
              </a-col>
            </a-row>

            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item
                      :label="$t('Pin Code')"
                      name="hsn_sac1">
                    </a-form-item>
                  </a-col>

                  <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-input
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    v-model:value="formDataLedger.toPincode"
                    @input="clearValidationError('name')"
                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-input>
                  </a-col>
                </a-row>
              </a-col>
            </a-row>

            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-row :gutter="16">
                  <a-col :xs="24" :sm="24" :md="8" :lg="8">
                    <a-form-item
                      :label="$t('GSTIN')"
                      name="tax_category">
                    </a-form-item>
                  </a-col>

                  <a-col :xs="24" :sm="24" :md="16" :lg="16">
                    <a-input
                    autocomplete="off"
                    id="ProductInput"
                    name="name"
                    v-model:value="formDataLedger.consignee_gstin"

                    value=""
                    @keyup.enter="focusNext"
                  >
                  </a-input>
                  </a-col>
                </a-row>
              </a-col>
            </a-row>
          </a-col>

        </a-row>
        <!-- end category field-->
      </fieldset>
    </a-col>
     <!-- end second clounm -->
      </a-row>
       <!--  end consigne design-->
      <div class="floats">
        <a-row :gutter="16" style="margin-top: 20px">
          <a-col :xs="24" :sm="24" :md="7" :lg="7">
          </a-col>

          <GSTModel
            v-if="isModalGST"
            :visible="isModalGST"
            :formDataLedger="formDataLedger"
            :url="url"
            :addEditType="addEditType"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
            @addEditSuccess="handleSuccess"
            @closed="handleGST"
          />

          <a-col :xs="24" :sm="24" :md="7" :lg="7"> </a-col>
          <a-col :xs="24" :sm="24" :md="10" :lg="10">
            <div style="float: right">
              <button
                @click="saveProductItem($event)"
                type="button"
                id="save-item"
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
                @click="showprint"
              >
                  <span class="shortcut ng-star-inserted"><code>Exit</code></span>
              </button>
            </div>
          </a-col>
        </a-row>
      </div>
    </form>
    <template #footer class="floats" style="display: none !important"> </template>
    <SalesModel v-if="isModalVisible" :visible="isModalVisible" :formData="formData" :url="url"
    :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
    @addEditSuccess="handleSuccess" @closed="handleCloseCustomer" v-on:child-method="updateParent"  v-on:close-method="focusEelment" />

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
import apiAdmin from "../../../../common/composable/apiAdmin";
import common from "../../../../common/composable/common";
import { SearchOutlined } from "@ant-design/icons-vue";
import fields from "../../stock-management/purchases/Ledger/fields";
import { message, notification } from "ant-design-vue";
import ProductCompanySearchInput from "../../../../common/components/product/ProductCompanySearchInput.vue";
import TaxCategorySearchInput from "../../../../common/components/product/TaxCategorySearchInput.vue";
import CategorySearchInput from "../../../../common/components/product/CategorySearchInput.vue";
import UnitSearchInput from "../../../../common/components/product/UnitSearchInput.vue";
import HscSacSearchInput from "../../../../common/components/product/HscSacSearchInput.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DateTimePicker.vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";
import GodownsSearchInput from "../../../../common/components/product/GodownsSearchInput.vue";
import SalesModel from "../../../../main/views/stock-management/purchases/SalesModel.vue"
// import PrintModel from "./PrintModel.vue";
export default defineComponent({
 props: [
    "formData",
    "visible",
    "url",
    "addEditType",
    "pageTitle",
    "successMessage",
    "payItems",
    "accountAumber"

  ],
  created() {
    console.log("created:",  this.payItems );
    if(this.payItems){
    this.updateEwayDetails(this.payItems);

    }
    //this.reFetchDatatable(this.billNumber);
  },
  emits: ["valueSuccess", "valueChanged"],
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    DeleteOutlined,
    EditOutlined,
    SearchOutlined,
    ProductCompanySearchInput,
    TaxCategorySearchInput,
    UnitSearchInput,
    HscSacSearchInput,
    ExclamationCircleOutlined,
    DateTimePicker,
    CategorySearchInput,
    GodownsSearchInput,
    SalesModel,
    // PrintModel,
  },

  setup(props, { emit }) {
    const { addEditRequestAdmin, loading, rules } = apiAdmin();
    const { permsArray, appSetting, dayjsObject, appType } = common();
    const company = appSetting.value;
    console.log('company',company)
    const { adjustmentTypes } = fields();
    const onClose = () => {
      rules.value = {};
      emit("closed");
    };

    const ledgerSubmit = (formDataLedger) => {
      addEditRequestAdmin({
        url: `eway-bill`,
        data: formDataLedger,
        success: (res) => {
          notification.success({
            placement: "bottomRight",
            message: "Product saved successfully",
          });
          document.getElementById("addproductform").reset();
          setTimeout(function () {
            emit("closed");
            document.getElementById("form_item_party_name_search").focus();
          }, 2000);
        },
      });
    };

    const valueChanged = (value, option) => {
      emit("valueChanged", value);
      emit("valueSuccess");
    };



    return {
      loading,
      company,
      rules,
      onClose,
      //formDataLedger,
      ledgerSubmit,
      //nvoiceData,
      //itemList,
      // getStockValue,
      valueChanged,
      drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
    };
  },

  data() {
    return {
      isLoading: false,
      formDataLedger: {

        supplyType: "Goods",
        transporterId:"29AAACG0569P1Z3",
        subSupplyType: "1",
        subSupplyDesc: "",
        docType: "BIL",
        docNo:"s",

       // document_date: new Date().toISOString().split("T")[0],
        docDate: null,
        fromGstin: "29AAACG0569P1Z3",
        fromTrdName: "welton",
        fromAddr1:"",
        fromAddr2: "GROUND FLOOR OSBORNE ROAD",
        fromPlace: "FRAZER TOWN",
        fromPincode: 560042,
        actFromStateCode: 29,
        fromStateCode: 29,
        toGstin: "02EHFPS5910D2Z0",
        transporter_name:"DigitalCloudies",
        toTrdName: "sthuthya",
        toAddr1: "Shree Nilaya",
        toAddr2: "Dasarahosahalli",
        toPlace: "Beml Nagar",
        toPincode: "",
        actToStateCode: 29,
        toStateCode: 28,


        fromPincode:"",
        transactionType: 4,
        dispatchFromGSTIN: "29AAAAA1303P1ZV",
        dispatchFromTradeName: "ABC Traders",
        shipToGSTIN: "29ALSPR1722R1Z3",
        shipToTradeName: "XYZ Traders",
        otherValue: -100,
        totalValue: 0,
        cgstValue: 0,
        sgstValue: 0,
        igstValue: 0,
        cessValue: 0,
        cessNonAdvolValue: 0,
        totInvValue: 0,
        transporterId: "",
        transporterName: "",
        transDocNo: "",
        transMode: "1",//"Mode of transport (Road-1, Rail-2, Air-3, Ship-4) "
        transDistance: 0,
        transDocDate: "",
        vehicleNo: "PVC1234",
        distance:200,

        vehicleType: "R",
        itemList: [],



      },

      duplicate: false,
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
      isPrintModalVisible:false,
      isModalGST: false,
      url: "your-url-here",
      addEditType: "add",
      pageTitle: "Modify Ledger",
      successMessage: "Operation successful!",
      activeKey: "taxdetails",
      currentFocusElement: null,

      rules: {
        mrp: null,
        purchase_rate: null,
        cost: null,
        sale_rate: null,
        rate_b: null,
        rate_d: null,
        rate_f: null,
      },
      validationStatus: {
        mrp: "",
        purchase_rate: "",
        cost: "",
        sale_rate: "",
        rate_b: "",
        rate_d: "",
        rate_f: "",
      },

      resetForm() {
        this.formDataLedger = {
          product_type: "Goods",
        };
      },
    };
  },

  methods: {
    updateEwayDetails(formData)
    {
      console.log("updateEwayDetails:", formData,this.company);
      const response =  axiosAdmin.get(`fetch-ledger/${formData.party_id}`);
      this.formDataLedger.docNo=formData.bill_number;
      this.formDataLedger.fromTrdName=this.company.name;
      this.formDataLedger.fromAddr1=this.company.address;
      this.formDataLedger.fromAddr2=this.company.address_two;
      this.formDataLedger.fromStateCode=this.company.state;
      this.formDataLedger.fromPlace=this.company.state;
      this.formDataLedger.fromPincode=this.company.pincode;
      console.log(this.company.address);
      this.formDataLedger.docDate=this.updateDateFormat(formData.order_date);
      this.formDataLedger.totInvValue=formData.total;
      if(formData.items && formData.items.length>0)
      {
        formData.items.forEach((data)=>{
          if(data.item_id>0){
           var item=  {
              productName: data.item_name,
              productDesc:  data.item_name,
              hsnCode:  data.hsnCode,
              quantity: data.quantity,
              qtyUnit: data.qtyUnit,
              cgstRate: data.cgst,
              sgstRate: data.sgst,
              igstRate: (data.cgst+data.sgst),
              cessRate: 0,
              cessNonAdvol: 0,
              taxableAmount: data.amount
            }
            this.formDataLedger.itemList.push(item);
          }

        })
      }
      //this.formDataLedger = formData;
    },
    updateDateFormat()
    {
      const date = new Date('2025-02-21');
      const day = String(date.getDate()).padStart(2, '0'); // Adds leading zero if day is single digit
      const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() is 0-based
      const year = date.getFullYear();

      const formattedDate = `${day}-${month}-${year}`;
      console.log(formattedDate);  // Output: 21-02-2025
      return formattedDate;
    },
    //All vladiation occurs here
    validateInput() {
      let isValid = true;
      const fieldsToValidate = [
        { key: "supply_type", label: "This Field is required." },
        { key: "document_type", label: "This Field is required." },
        { key: "mode", label: "This Field is required." },
        { key: "vehicle_type", label: "This Field is required." },
        { key: "distance", label: "This Field is required." },
        { key: "transaction_type", label: "This Field is required." },
        { key: "mrp", label: "This Field is required." },
        { key: "purchase_rate", label: "This Field is required." },
        { key: "sale_rate", label: "This Field is required." },
      ];

      fieldsToValidate.forEach((field) => {
        const value = this.formDataLedger[field.key];
        console.log(`Validating ${field.key}: `, value);

        let isFieldValid = true;
        // if (
        //   field.key === "mrp" ||
        //   field.key === "purchase_rate" ||
        //   field.key === "sale_rate"
        // ) {
        //   const numericValue = parseFloat(value);
        //   if (isNaN(numericValue) || numericValue <= 0) {
        //     isFieldValid = false;
        //   }
        // } else {
        //   if (!value || (typeof value === "string" && value.trim() === "")) {
        //     isFieldValid = false;
        //   }
        // }

        if (!isFieldValid) {
          this.rules[field.key] = { message: field.label };
          isValid = false;
        } else {
          this.rules[field.key] = null;
        }
      });

      return isValid;
    },






    //for productname clear
    clearValidationError(field) {
      const fieldValue = this.formDataLedger[field];

      const fieldLabel = this.rules[field]?.message || field;

      if (!fieldValue || fieldValue.trim() === "") {
        this.rules[field] = {
          message: "This Field is required.",
        };
      } else {
        this.rules[field] = null;
      }
    },

    //for search clear
    validateField(field) {
      const value = this.formDataLedger[field];
      const valueed = this.rules[field]?.message || field;

      if (!value) {
        this.rules[field] = {
          message: "This Field is required.",
        };
      } else {
        this.rules[field] = null;
      }
    },





    getStockValue(value) {
      console.log();
      document.getElementById("hsn_sac").innerHtml = "demo";
      //this.formDataLedger.hsn_sac=2;
    },


    focusNextField() {
      if (this.validatePurchaseRate()) {
        this.$nextTick(() => {
          this.$refs.costInput.focus();
        });
      }
    },
    //focus input//
    focusNextFields() {
      if (this.validateCostRate()) {
        this.$nextTick(() => {
          this.$refs.salesrate.focus();
        });
      }
    },

    //end foucs input//

    focusNextFieldsales() {
      console.log("testttt");
      if (this.validateSaleRate()) {
        this.$nextTick(() => {
          this.$refs.free_scheme_1.focus();
        });
      }
    },

    // Validate Cost
    validateSaleRate() {
      const mrp = parseFloat(this.formDataLedger.mrp);
      const saleRate = parseFloat(this.formDataLedger.sale_rate);

      // Reset error message
      this.rules.sale_rate = null;

      // Check if saleRate is greater than or equal to MRP
      if (saleRate >= mrp) {
        this.rules.sale_rate = "Sale rate should be less than MRP.";
        notification.error({
          placement: "bottomRight",
          message: "Validation Error",
          description: this.rules.sale_rate,
        });
        return false;
      }

      // Return true if validation passes
      return true;
    },



    onInputMrp() {
      this.validateMRP();
    },

    onInputPurchaserate() {
      this.validatePurchaseRate();
    },
    onInputCost() {
      this.validateCostRate();
    },
    onInputSaleRate() {
      this.validateSaleRate();
    },

    onInputRateB() {
      // this.validateRateB();
    },

    onInputRateD() {
      ///this.validateRateD();
    },

    onInputRateF() {
      //this.validateRateF();
    },

    switchTab() {
      const tabs = ["taxdetails", "contactinfo", "bankdetails", "visibility"];
      const currentIndex = tabs.indexOf(this.activeKey);
      this.activeKey = tabs[(currentIndex + 1) % tabs.length];

      this.$nextTick(() => {
        const refName = `${this.activeKey}FirstInput`;
        if (this.$refs[refName]) {
          this.$refs[refName].focus();
        }
      });
    },

    focusTab(tabKey) {
      console.log(`Switching to tab: ${tabKey}`);
      this.activeKey = tabKey;
      this.$nextTick(() => {
        this.focusFirstInput(tabKey);
      });
    },
    handleTabChange(activeKey) {
      this.activeKey = activeKey;
      console.log(`Switched to tab: ${activeKey}`);

      this.$nextTick(() => {
        this.focusFirstInput(activeKey);
      });
    },

    focusFirstInput(activeTab) {
      const firstInputRefs = {
        taxdetails: "taxdetailsFirstInput",
        contactinfo: "contactinfoFirstInput",
        bankdetails: "bankdetailsFirstInput",
        visibility: "visibilityFirstInput",
      };

      const firstInputRef = firstInputRefs[activeTab];
      console.log(`Focusing input for tab: ${activeTab}, ref: ${firstInputRef}`);

      if (this.$refs[firstInputRef]) {
        this.$refs[firstInputRef].focus();
        console.log(`Selected Heading : ${firstInputRef}`);
      } else {
        console.warn(`Input ref not found for tab: ${activeTab}`);
      }
    },

    // inr or Rupees valdiation
    onInputMrp(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.mrp = value;
    },

    onInputOpeningStock(event) {
      let value = event.target.value;
      value = value.replace(/[^0-5]/g, "");
      if (value.length > 5) {
        value = value.slice(0, 5);
      }
      this.formDataLedger.opening_stock = value;
    },

    onInputPurchaserate(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.purchase_rate = value;
    },

    onInputCost(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.cost = value;
    },
    onInputSaleRate(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.sale_rate = value;
    },

    onInputRateB(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.rate_b = value;
    },

    onInputRateD(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.rate_d = value;
    },

    onInputRateF(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.rate_f = value;
    },

    onInputFreeScheme1(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.free_scheme_1 = value;
    },

    onInputFreeScheme2(event) {
      let value = event.target.value;
      value = value.replace(/[^0-9]/g, "");
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      this.formDataLedger.free_scheme_2 = value;
    },

    resetForm() {
      if (this.$refs.myForm1) {
        this.$refs.myForm1.resetFields();
      }
    },
    onSubmitLedger(eventKey) {
      try {
        console.log(eventKey, "<>", this.formDataLedger);
        if (eventKey == "F8" && this.validateInput()) {
          this.ledgerSubmit(this.formDataLedger);
        }
      } catch (error) {
        this.$message.error(this.$t("Issue while Product Save !"));
        console.error(error);
      }
    },
    saveProductItem(eventKey) {
      try {console.log(this.formDataLedger);


        this.isLoading= true;
            axiosAdmin
            .post("generateEWayBill", this.formDataLedger)
            .then(response => {  //console.log(response)
                // Toastr Notificaiton
                this.isLoading= false;
               console.log(response)
            })
            .catch(errorResponse => {
              this.isLoading= false;
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

      } catch (error) {
        this.$message.error(this.$t("Issue while Product Save !"));
        console.error(error);
      }
    },

    handleLocationClose() {
      this.isLocationModal = false;
    },
    handleCopy() {
      this.isModalCopy = false;
    },
    handleClose() {
      this.showconfirm();
    },
    showprint(){
      this.isPrintModalVisible=true;
    },

    showconfirm() {
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
          that.$refs.ProductInput && that.$refs.ProductInput.focus();
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
      });
    },

    handleCloses() {
      this.showconfirm();
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
      document.removeEventListener("keydown", this.handleKeydown);
      console.log("Success:", xid);
    },
    handleKeydown(event) {
      console.log("Product event", event.target.id);

      if (
        event.keyCode === 13 &&
        (event.target.id === "hsn_sac" ||
          event.target.id === "company" ||
          event.target.id === "god_owns")
      ) {
        this.focusNext(event);
      }

      //   if (event.key === "Escape") {
      //     this.handleClose();
      //   }
      else if (event.key === "Escape" || event.key === 27) {
        console.log(
          "ESC pressed on element:",
          event.target.id,
          "Tag:",
          event.target.tagName
        );
        if (
          event.target.id &&
          [
            "unit_in_decimal",
            "low_stock",
            "unit_1st",
            "color_type",
            "category_field",
            "hsn_sac",
            "brandsing",
          ].includes(event.target.id)
        ) {
          console.log("ESC ignored for:", event.target.id);
        }
      } else if (event.key === "F9") {
        event.preventDefault();
        this.resetForm();
      } else if (event.key === "F4") {
        event.preventDefault();
        this.switchTab();
        this.focusinputvalue();
      } else if (event.key === "F8") {
        event.preventDefault();
        if (this.validateInput()) {
          this.onSubmitLedger(event.key);
        }
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

    focusNext(e) {
      const currentField = e.target;

      console.log("focusNext", e.target.id);
      const formElements = Array.from(
        currentField.form.querySelectorAll("input, select,date")
      );

      const currentIndex = formElements.indexOf(currentField);

      const nonMandatoryFieldsArray = [
        "product_type",
        "packing",
        "unit_in_decimal",
        "as_on_date",
        "low_stock",
        "opening_stock",
        "cost",
        "color_type",
        "category_field",
        "hsn_sac",
      ];
      //console.log(e.target.id)
      if (currentIndex < formElements.length - 1) {
        if (
          e.target.id == "product_type" &&
          this.formDataLedger.product_type != undefined &&
          this.formDataLedger.product_type != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "unit_1st" &&
          this.formDataLedger.unit_1st != undefined &&
          this.formDataLedger.unit_1st != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "opening_stock" &&
          this.formDataLedger.opening_stock != undefined &&
          this.formDataLedger.opening_stock != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "category_field" &&
          this.formDataLedger.category_field != undefined &&
          this.formDataLedger.category_field != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "hsn_sac" &&
          this.formDataLedger.hsn_sac != undefined &&
          this.formDataLedger.hsn_sac != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "tax_category" &&
          this.formDataLedger.tax_category != undefined &&
          this.formDataLedger.tax_category != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "unit_in_decimal" &&
          this.formDataLedger.unit_in_decimal != undefined &&
          this.formDataLedger.unit_in_decimal != "" &&
          this.formDataLedger.unit_in_decimal.trim() != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "company" &&
          this.formDataLedger.company != undefined &&
          this.formDataLedger.company != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "god_owns" &&
          this.formDataLedger.god_owns != undefined &&
          this.formDataLedger.god_owns != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "scheme_type" &&
          this.formDataLedger.scheme_type != undefined &&
          this.formDataLedger.scheme_type != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "stock_status" &&
          this.formDataLedger.status != undefined &&
          this.formDataLedger.status != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "color_type" &&
          this.formDataLedger.color_type != undefined &&
          this.formDataLedger.color_type != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "discount" &&
          this.formDataLedger.discount != undefined &&
          this.formDataLedger.discount != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "visibility" &&
          this.formDataLedger.visibility != undefined &&
          this.formDataLedger.visibility != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "rate_d" &&
          this.formDataLedger.rate_d != undefined &&
          this.formDataLedger.rate_d != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "brand_name" &&
          this.formDataLedger.brand_name != undefined &&
          this.formDataLedger.brand_name != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "rate_f" &&
          this.formDataLedger.rate_f != undefined &&
          this.formDataLedger.rate_f != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          formElements[currentIndex].value != undefined &&
          formElements[currentIndex].value != "" &&
          formElements[currentIndex].value.trim() != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (nonMandatoryFieldsArray.indexOf(e.target.id) != -1) {
          formElements[currentIndex + 1].focus();
        } else {
          console.log(e.target.id, this.formDataLedger.hsn_sac);
          // document.getElementById('save-item').focus();
        }
      }
    },

    checkproductDuplicate(event) {
      console.log("API call");
      this.duplicate = false;
      if (
        this.formDataLedger.name != undefined &&
        this.formDataLedger.name != null &&
        this.formDataLedger.name != "" &&
        this.formDataLedger.name.trim() != "" &&
        event.key != "F8"
      ) {
        axiosAdmin
          .get("productsDuplicate/" + this.formDataLedger.name)
          .then((response) => {
            console.log(response);
            // Toastr Notificaiton
            if (response == 500) {
              this.duplicate = true;
              message.error("Product name already exist !");
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

    focusinputvalue(event) {
      if (event.target) {
        event.target.select();
        console.log(`Input focused and text selected: ${event.target.name}`);
      }
    },

    autoFocusInput() {
      this.$nextTick(() => {
        const gstInput = this.$refs.ProductInput;

        if (gstInput) {
          gstInput.focus();
          console.log("ProductInput focused");
        } else {
          console.error("ProductInputnot found after mount/update.");
        }
      });
    },


    handleCloseEwaybill(){
            this.isEwaybillModalVisible=false;
        },
  },

  watch: {
    "formDataLedger.supply_type"(newValue) {
      this.validateField("supply_type");
    },
    "formDataLedger.document_type"(newValue) {
      this.validateField("document_type");
    },
    "formDataLedger.mode"(newValue) {
      this.validateField("mode");
    },
    "formDataLedger.vehicle_type"(newValue) {
      this.validateField("vehicle_type");
    },
    "formDataLedger.transaction_type"(newValue) {
      this.validateField("transaction_type");
    },



  },

  mounted() {
    // Add keydown event listener to capture F9 key
    document
      .getElementById("addproductform")
      .addEventListener("keyup", this.handleKeydown);
    this.autoFocusInput();
  },
  beforeDestroy() {
    // Remove event listener when component is destroyed
    window.removeEventListener("keydown", this.handleKeydown);
  },
});
</script>

<style>
.ant-form-item {
  margin-bottom: 5px !important;
}

.ewaybillpopup {
  width: 69% !important;
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

indiannumber .responsive-table {
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
  font-weight: bolder;
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
  margin-top: -24px;
}

#closeicon {
  top: 14px !important;
}

.productnumber {
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
  font-size:13px;
  width:101px;
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

.error-message {
  color: green;
  font-size: 12px;
  margin-top: 4px;
}

button.btn {
  cursor: pointer;
}

.ant-form-item-explain-error {
  font-size: 13px;
}
.select-focused {
  background-color: #e0f7fa; /* Light blue */
}
.ant-select-selection-search-input {
  padding: 3% !important;
}
.ant-select-selection-search-input:focus {
  background-color: #ffd451 !important;
}

.inputing .ant-input:focus {
  border: 2px solid red !important;
  outline: none !important; /* Remove default outline */
}
.a-span:focus {
  border: 1px solid red !important;
  outline: none !important; /* Remove default outline */
}
.ant-select-selection-search {
  margin-left: -10px !important;
}
.css-dev-only-do-not-override-wosfq4.ant-select-dropdown
  .ant-select-item-option-selected:not(.ant-select-item-option-disabled) {
  color: rgb(244, 241, 241) !important;
  background-color: #5fa6c9 !important;
}
.billnumber{
  font-weight:bold;
}
.calculatedistance{
  padding: 4px 7px !important;
  font-size: 13px;
  background-color: #1f6d70;
  color: white;
    border-radius: 4px;
width:124px;
}
body.is-loading {
  filter: blur(5px);
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
</style>
