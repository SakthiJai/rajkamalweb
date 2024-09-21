<template>
    <a-modal
        :open="visible"
        :width="drawerWidth"
        :closable="false"
        :centered="true"
        @close="handleClose"
        class="popups"
    >
        <template v-slot:title>
            <div
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                "
            >
                <span>Create Item</span>
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
        <form ref="myForm1" layout="vertical" @submit.prevent="onSubmitLedger">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="16" :lg="16" class="info">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">Basic Info</legend>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="
                                                $t('stock.add_product_name')
                                            "
                                            name="name"
                                            :help="
                                                rules.name
                                                    ? rules.name.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.name ? 'error' : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="14" :lg="14">
                                        <a-input
                                            name="name"
                                            v-model:value="formDataLedger.name"
                                            value=""
                                        >
                                        </a-input>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-select
                                            name="product_type"
                                            v-model:value="
                                                formDataLedger.product_type
                                            "
                                            style="width: 100%"
                                        >
                                            <a-select-option
                                                key="Goods"
                                                value="Goods"
                                                aria-selected="true"
                                            >
                                                Goods
                                            </a-select-option>
                                            <a-select-option
                                                key="Service"
                                                value="Service"
                                                aria-selected="true"
                                            >
                                                Service
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
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.packing')"
                                            name="packing"
                                            :help="
                                                rules.packing
                                                    ? rules.packing.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.packing ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="packing"
                                            v-model:value="
                                                formDataLedger.packing
                                            "
                                            value=""
                                            :help="
                                                rules.name
                                                    ? rules.name.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.name ? 'error' : null
                                            "
                                        >
                                        </a-input>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.M_R_P ')"
                                            name="mrp"
                                            :help="
                                                rules.M_R_P
                                                    ? rules.M_R_P.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.M_R_P ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input-group compact>
                                            <a-button
                                                type="primary"
                                                class="indiannumber"
                                            >
                                                INR
                                            </a-button>
                                            <a-input
                                                v-model:value="
                                                    formDataLedger.mrp
                                                "
                                                style="width: 79%"
                                                type="number"
                                                maxlength="10"
                                                @input="onInputPhoneNumber"
                                                :help="
                                                    rules.name
                                                        ? rules.name.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.name ? 'error' : null
                                                "
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
                                        <a-form-item
                                            :label="$t('stock.Unit_1st')"
                                            name="unit_1st"
                                            :help="
                                                rules.unit_1st
                                                    ? rules.unit_1st.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.unit_1st ? 'error' : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="unit_1st"
                                            v-model:value="
                                                formDataLedger.unit_1st
                                            "
                                            value=""
                                            placeholder="PCS"
                                        />
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Purchase_Rate')"
                                            name="purchase_rate"
                                            :help="
                                                rules.Purchase_Rates
                                                    ? rules.Purchase_Rate
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Purchase_Rate
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input-group compact>
                                            <a-button
                                                type="primary"
                                                class="indiannumber"
                                            >
                                                INR
                                            </a-button>
                                            <a-input
                                                v-model:value="
                                                    formDataLedger.purchase_rate
                                                "
                                                style="width: 79%"
                                                type="number"
                                                maxlength="10"
                                                @input="onInputPhoneNumber"
                                                :help="
                                                    rules.name
                                                        ? rules.name.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.name ? 'error' : null
                                                "
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
                                        <a-form-item
                                            :label="$t('stock.Unit_in_Decimal')"
                                            name="unit_in_decimal"
                                            :help="
                                                rules.Unit_in_Decimal
                                                    ? rules.Unit_in_Decimal
                                                          .message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Unit_in_Decimal
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-select
                                            v-model="
                                                formDataLedger.unit_in_decimal
                                            "
                                            style="width: 100%"
                                        >
                                            <a-select-option
                                                key="No"
                                                value="No"
                                                aria-selected="true"
                                            >
                                                No
                                            </a-select-option>
                                            <a-select-option
                                                key="Yes"
                                                value="Yes"
                                                aria-selected="true"
                                            >
                                                Yes
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Cost')"
                                            name="cost"
                                            :help="
                                                rules.Cost
                                                    ? rules.Cost.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Cost ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input-group compact>
                                            <a-button
                                                type="primary"
                                                class="indiannumber"
                                            >
                                                INR
                                            </a-button>
                                            <a-input
                                                v-model:value="
                                                    formDataLedger.cost
                                                "
                                                style="width: 79%"
                                                type="number"
                                                maxlength="10"
                                                @input="onInputPhoneNumber"
                                                :help="
                                                    rules.name
                                                        ? rules.name.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.name ? 'error' : null
                                                "
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
                                        <a-form-item
                                            :label="$t('stock.HSN_SAC')"
                                            name="hsn_sac
"
                                            :help="
                                                rules.hsn_sac
                                                    ? rules.hsn_sac.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.hsn_sac ? 'error' : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="hsn_sac"
                                            v-model:value="
                                                formDataLedger.hsn_sac
                                            "
                                        />
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Sale_Rate')"
                                            name="sale_rate"
                                            :help="
                                                rules.Sale_Rate
                                                    ? rules.Sale_Rate.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Sale_Rate ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input-group compact>
                                            <a-button
                                                type="primary"
                                                class="indiannumber"
                                            >
                                                INR
                                            </a-button>
                                            <a-input
                                                v-model:value="
                                                    formDataLedger.sale_rate
                                                "
                                                style="width: 79%"
                                                type="number"
                                                maxlength="10"
                                                @input="onInputPhoneNumber"
                                                :help="
                                                    rules.name
                                                        ? rules.name.message
                                                        : null
                                                "
                                                :validateStatus="
                                                    rules.name ? 'error' : null
                                                "
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
                                        <a-form-item
                                            :label="$t('stock.Tax_Category')"
                                            name="tax_category"
                                            :help="
                                                rules.tax_category
                                                    ? rules.tax_category.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.tax_category
                                                    ? 'error'
                                                    : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="tax_category"
                                            v-model:value="
                                                formDataLedger.tax_category
                                            "
                                            class="required"
                                            placeholder="Select Tax Category"
                                        />
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Rate_B')"
                                            name="rate_b"
                                            :help="
                                                rules.Rate_B
                                                    ? rules.Rate_B.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Rate_B ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input-group compact>
                                            <a-button
                                                type="primary"
                                                class="indiannumber"
                                            >
                                                INR
                                            </a-button>
                                            <a-input
                                                v-model:value="
                                                    formDataLedger.rate_b
                                                "
                                                style="width: 79%"
                                                type="number"
                                                maxlength="10"
                                                @input="onInputPhoneNumber"
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
                                        <a-form-item
                                            :label="$t('stock.Company')"
                                            name="company"
                                            :help="
                                                rules.company
                                                    ? rules.company.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.company ? 'error' : null
                                            "
                                            class="required"
                                        >
                                        </a-form-item>
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-input
                                            name="company"
                                            v-model:value="
                                                formDataLedger.company
                                            "
                                            placeholder="--Blank--"
                                        />
                                    </a-col>

                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Free_Scheme')"
                                            value=""
                                            :help="
                                                rules.Free_Scheme
                                                    ? rules.Free_Scheme.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Free_Scheme
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                        <a-input
                                            name="free_scheme_1"
                                            v-model:value="
                                                formDataLedger.free_scheme_1
                                            "
                                        >
                                        </a-input>
                                    </a-col>
                                    +
                                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                                        <a-input
                                            name="free_scheme_2"
                                            v-model:value="
                                                formDataLedger.free_scheme_2
                                            "
                                            value=""
                                            style="width: 127%"
                                            :help="
                                                rules.name
                                                    ? rules.name.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.name ? 'error' : null
                                            "
                                        >
                                        </a-input>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <!-- <a-form-item :label="$t('stock.Company')" name="station"
                                            :help="rules.stations ? rules.stations.message : null"
                                            :validateStatus="rules.stations ? 'error' : null" class="required">
                                        </a-form-item> -->
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <!-- <a-input name="party_name" v-model:value="formDataLedger.party_name"
                                            value="">
                                        </a-input> -->
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Scheme_Type')"
                                            name="scheme_type"
                                            :help="
                                                rules.Scheme_Type
                                                    ? rules.Scheme_Type.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Scheme_Type
                                                    ? 'error'
                                                    : null
                                            "
                                        ></a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-select
                                            v-model:value="
                                                formDataLedger.scheme_type
                                            "
                                            class="balance"
                                        >
                                            <a-select-option
                                                key="Full Scheme"
                                                value="Full Scheme"
                                                aria-selected="true"
                                            >
                                                Full Scheme
                                            </a-select-option>
                                            <a-select-option
                                                key="Half Scheme"
                                                value="Half Scheme"
                                                aria-selected="true"
                                            >
                                                Half Scheme
                                            </a-select-option>
                                            <a-select-option
                                                key="1/3 Scheme"
                                                value="1/3 Scheme"
                                                aria-selected="true"
                                            >
                                                1/3 Scheme
                                            </a-select-option>
                                            <a-select-option
                                                key="Qtr. Scheme"
                                                value="Qtr. Scheme"
                                                aria-selected="true"
                                            >
                                                Qtr. Scheme
                                            </a-select-option>
                                            <a-select-option
                                                key="All Scheme"
                                                value="All Scheme"
                                                aria-selected="true"
                                            >
                                                All Scheme
                                            </a-select-option>
                                            <a-select-option
                                                key="Whole Scheme"
                                                value="Whole Scheme"
                                                aria-selected="true"
                                            >
                                                Whole Scheme
                                            </a-select-option>
                                            <a-select-option
                                                key="No Scheme"
                                                value="No Scheme"
                                                aria-selected="true"
                                            >
                                                No Scheme
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <!-- <a-form-item :label="$t('stock.Company')" name="station"
                                            :help="rules.stations ? rules.stations.message : null"
                                            :validateStatus="rules.stations ? 'error' : null" class="required">
                                        </a-form-item> -->
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <!-- <a-input name="party_name" v-model:value="formDataLedger.party_name"
                                            value="">
                                        </a-input> -->
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Status')"
                                            name="status"
                                            :help="
                                                rules.Status
                                                    ? rules.Status.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Status ? 'error' : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-select
                                            v-model:value="
                                                formDataLedger.status
                                            "
                                            class="balance"
                                        >
                                            <a-select-option
                                                key="Continue"
                                                value="Continue"
                                                aria-selected="true"
                                            >
                                                Continue
                                            </a-select-option>
                                            <a-select-option
                                                key="Discontinue"
                                                value="Discontinue"
                                                aria-selected="true"
                                            >
                                                Discontinue
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <!-- <a-form-item :label="$t('stock.Company')" name="station"
                                            :help="rules.stations ? rules.stations.message : null"
                                            :validateStatus="rules.stations ? 'error' : null" class="required">
                                        </a-form-item> -->
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <!-- <a-input name="party_name" v-model:value="formDataLedger.party_name"
                                            value="">
                                        </a-input> -->
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="5" :lg="5">
                                        <a-form-item
                                            :label="$t('stock.Color_Type')"
                                            name="color_type"
                                            :help="
                                                rules.Color_Type
                                                    ? rules.Color_Type.message
                                                    : null
                                            "
                                            :validateStatus="
                                                rules.Color_Type
                                                    ? 'error'
                                                    : null
                                            "
                                        >
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="24" :md="7" :lg="7">
                                        <a-select
                                            v-model:value="
                                                formDataLedger.color_type
                                            "
                                            class="balance"
                                        >
                                            <a-select-option
                                                key=""
                                                value=""
                                                aria-selected="true"
                                                style="
                                                    background-color: skyblue;
                                                "
                                            >
                                                --Blank--
                                            </a-select-option>
                                            <a-select-option
                                                key="RED"
                                                value="RED"
                                                aria-selected="true"
                                                style="background-color: red"
                                            >
                                                RED
                                            </a-select-option>
                                            <a-select-option
                                                key="Yellow"
                                                value="Yellow"
                                                aria-selected="true"
                                                style="background-color: yellow"
                                            >
                                                Yellow
                                            </a-select-option>
                                            <a-select-option
                                                key="Blue"
                                                value="Blue"
                                                aria-selected="true"
                                                style="background-color: blue"
                                            >
                                                Blue
                                            </a-select-option>
                                            <a-select-option
                                                key="Green"
                                                value="Green"
                                                aria-selected="true"
                                                style="background-color: green"
                                            >
                                                Green
                                            </a-select-option>
                                        </a-select>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </fieldset>
                </a-col>
                <a-col :xs="24" :sm="24" :md="8" :lg="8" class="info">
                    <fieldset class="fieldheightsss">
                        <legend class="basicdetalis">Advance Info</legend>
                        <a-tabs v-model:activeKey="activeKey">
                            <a-tab-pane key="taxdetails" tab="Rate">
                                <template #tab class="actingtabs" id="activity">
                                    <span> Rate </span>
                                </template>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="$t('stock.Rate_D')"
                                                    name="rate_d"
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
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input-group compact>
                                                    <a-button
                                                        type="primary"
                                                        class="indiannumber"
                                                    >
                                                        INR
                                                    </a-button>
                                                    <a-input
                                                        v-model:value="
                                                            formDataLedger.rate_d
                                                        "
                                                        style="width: 78%"
                                                        type="number"
                                                        maxlength="10"
                                                        @input="
                                                            onInputPhoneNumber
                                                        "
                                                    />
                                                </a-input-group>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="$t('stock.Rate_F')"
                                                    name="rate_f"
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
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input-group compact>
                                                    <a-button
                                                        type="primary"
                                                        class="indiannumber"
                                                    >
                                                        INR
                                                    </a-button>
                                                    <a-input
                                                        v-model:value="
                                                            formDataLedger.rate_f
                                                        "
                                                        style="width: 78%"
                                                        type="number"
                                                        maxlength="10"
                                                        @input="
                                                            onInputPhoneNumber
                                                        "
                                                    />
                                                </a-input-group>
                                            </a-col>
                                        </a-row>
                                        <a-form-item>
                                            <span style="display: flex">
                                                <ProductSearchInput
                                                    @valueChanged="
                                                        (productId) =>
                                                            (formData.product_id =
                                                                productId)
                                                    "
                                                    @valueSuccess="
                                                        getStockValue
                                                    "
                                                    :productData="data"
                                                />
                                            </span>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-tab-pane>
                            <a-tab-pane key="contactinfo" tab="Discount">
                                <template #tab>
                                    <span> Discount </span>
                                </template>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Discount')
                                                    "
                                                    name="discount"
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
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-select
                                                    v-model:value="
                                                        formDataLedger.discount
                                                    "
                                                    style="width: 100%"
                                                >
                                                    <a-select-option
                                                        key="Applicable"
                                                        value="Applicable"
                                                        aria-selected="true"
                                                    >
                                                        Applicable
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="No Services"
                                                        value="No Services"
                                                        aria-selected="true"
                                                    >
                                                        No Services
                                                    </a-select-option>
                                                </a-select>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Item_Disc_1 ')
                                                    "
                                                    name="item_disc"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="party_name"
                                                    v-model:value="
                                                        formDataLedger.item_disc
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Volume_Dis_1')
                                                    "
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="volume_dis"
                                                    v-model:value="
                                                        formDataLedger.volume_dis
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Max_Disc')
                                                    "
                                                    name="max_disc"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="max_disc"
                                                    v-model:value="
                                                        formDataLedger.max_disc
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-form-item>
                                            <span style="display: flex">
                                                <ProductSearchInput
                                                    @valueChanged="
                                                        (productId) =>
                                                            (formData.product_id =
                                                                productId)
                                                    "
                                                    @valueSuccess="
                                                        getStockValue
                                                    "
                                                    :productData="data"
                                                />
                                            </span>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-tab-pane>
                            <a-tab-pane key="bankdetails" tab="Quantity">
                                <template class="actingtabs">
                                    <span> Quantity </span>
                                </template>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Min_Quantity')
                                                    "
                                                    name="min_quantity"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="min_quantity"
                                                    v-model:value="
                                                        formDataLedger.min_quantity
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Max_Quantity')
                                                    "
                                                    name="max_quantity"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="max_quantity"
                                                    v-model:value="
                                                        formDataLedger.max_quantity
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.ReorderDays')
                                                    "
                                                    name="reorder_days"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="reorder_days"
                                                    v-model:value="
                                                        formDataLedger.reorder_days
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.ReordeQty')
                                                    "
                                                    name="reorder_qty"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="reorder_qty"
                                                    v-model:value="
                                                        formDataLedger.reorder_qty
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-form-item>
                                            <span style="display: flex">
                                                <ProductSearchInput
                                                    @valueChanged="
                                                        (productId) =>
                                                            (formData.product_id =
                                                                productId)
                                                    "
                                                    @valueSuccess="
                                                        getStockValue
                                                    "
                                                    :productData="data"
                                                />
                                            </span>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-tab-pane>
                            <a-tab-pane key="visibility" tab="Others Info">
                                <template #tab>
                                    <span> Others Info </span>
                                </template>
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t(
                                                            'stock.Min_Margin_others'
                                                        )
                                                    "
                                                    name="min_margin_others"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="min_margin_others"
                                                    v-model:value="
                                                        formDataLedger.min_margin_others
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Prohibited')
                                                    "
                                                    name="prohibited"
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
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-select
                                                    v-model:value="
                                                        formDataLedger.prohibited
                                                    "
                                                    style="width: 100%"
                                                >
                                                    <a-select-option
                                                        key="Registered"
                                                        value="Registered"
                                                        aria-selected="true"
                                                    >
                                                        No
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="Composition"
                                                        value="Composition"
                                                        aria-selected="true"
                                                    >
                                                        Yes
                                                    </a-select-option>
                                                </a-select>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Visibility')
                                                    "
                                                    name="visibility"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-select
                                                    v-model:value="
                                                        formDataLedger.visibility
                                                    "
                                                    style="width: 100%"
                                                >
                                                    <a-select-option
                                                        key="Registered"
                                                        value="Registered"
                                                        aria-selected="true"
                                                    >
                                                        Show
                                                    </a-select-option>
                                                    <a-select-option
                                                        key="Composition"
                                                        value="Composition"
                                                        aria-selected="true"
                                                    >
                                                        Hide
                                                    </a-select-option>
                                                </a-select>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.MFR_Name')
                                                    "
                                                    name="mfr_name"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="mfr_name"
                                                    v-model:value="
                                                        formDataLedger.mfr_name
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-row :gutter="16">
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="10"
                                                :lg="10"
                                            >
                                                <a-form-item
                                                    :label="
                                                        $t('stock.Upload_Image')
                                                    "
                                                    name="image"
                                                >
                                                </a-form-item>
                                            </a-col>
                                            <a-col
                                                :xs="24"
                                                :sm="24"
                                                :md="14"
                                                :lg="14"
                                            >
                                                <a-input
                                                    name="image"
                                                    v-model:value="
                                                        formDataLedger.image
                                                    "
                                                    value=""
                                                >
                                                </a-input>
                                            </a-col>
                                        </a-row>
                                        <a-form-item>
                                            <span style="display: flex">
                                                <ProductSearchInput
                                                    @valueChanged="
                                                        (productId) =>
                                                            (formData.product_id =
                                                                productId)
                                                    "
                                                    @valueSuccess="
                                                        getStockValue
                                                    "
                                                    :productData="data"
                                                />
                                            </span>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-tab-pane>
                        </a-tabs>
                    </fieldset>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="7" :lg="7">
                    <button
                        type="button"
                        id="btn-Ledger"
                        @click="switchTab"
                        title="Ledger"
                        class="btn default-btn"
                    >
                        <span class="box">
                            <span class="shortcut"><code>F5</code></span>
                            <span>Switch Tab</span>
                        </span>
                    </button>
                    <button
                        @click="showGSTModal"
                        id="btn-Ledger"
                        title="Ledger"
                        class="btn default-btn ng-star-inserted gst"
                    >
                        <span class="box">
                            <span class="ng-star-inserted"
                                >GST Verification</span
                            ></span
                        >
                    </button>
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
                <a-col :xs="24" :sm="24" :md="9" :lg="9">
                    <button
                        @click="onSubmit"
                        type="submit"
                        id="btn-Ledger"
                        title="Ledger"
                        class="btn default-btn ng-star-inserted gst"
                    >
                        <span class="box">
                            <span class="shortcut ng-star-inserted"
                                ><code>F10</code></span
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
                        class="btn default-btn ng-star-inserted"
                    >
                        <span class="box"
                            ><span class="shortcut ng-star-inserted"
                                ><code>Esc</code></span
                            ><span class="ng-star-inserted">Close</span></span
                        ><span class="effect"></span>
                    </button>
                </a-col>
            </a-row>
            <div class="floats" style="display: none">
                <a-button
                    type="button"
                    id="btn-Ledger"
                    title="Ledger"
                    class="btn default-btn ng-star-inserted"
                    @click="EnterprisesModel"
                    ><span class="box"
                        ><span class="shortcut ng-star-inserted"
                            ><code>F4</code></span
                        ><span class="ng-star-inserted">Ledger</span></span
                    ><span class="effect"></span
                ></a-button>
                <a-button
                    type="button"
                    id="btn-Ledger"
                    title="Ledger"
                    class="btn default-btn ng-star-inserted"
                    ><span class="box"
                        ><span class="shortcut ng-star-inserted"
                            ><code>F8</code></span
                        ><span class="ng-star-inserted">Outstanding</span></span
                    ><span class="effect"></span
                ></a-button>
                <a-button
                    type="button"
                    id="btn-Ledger"
                    title="Ledger"
                    class="btn default-btn ng-star-inserted"
                    ><span class="box">
                        <span class="shortcut ng-star-inserted"
                            ><code>F7</code></span
                        ><span class="ng-star-inserted">All</span></span
                    ><span class="effect"></span
                ></a-button>
            </div>
        </form>
        <template #footer class="floats" style="display: none !important">
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
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import ExpenseCategoryModal from "../ExpenseCategoryModal.vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import DiscountModel from "../DiscountModel.vue";
import LimitModel from "../LimitModel.vue";
import OtherModel from "../OtherModel.vue";
import BillingModel from "../BillingModel.vue";
import CopyModel from "../CopyModel.vue";
import LocationModel from "../LocationModel.vue";
import BirthdayModel from "../BirthdayModel.vue";
import GSTModel from "../GSTModel.vue";
import fields from "./../Ledger/fields";
import ProductCompanySearchInput from "../../.../../../../../common/components/product/ProductCompanySearchInput.vue";
import TaxCategorySearchInput from "../.../../../../../../common/components/product/TaxCategorySearchInput.vue";
import UnitSearchInput from "../.../../../../../../common/components/product/UnitSearchInput.vue";
import HscSacSearchInput from "../.../../../../../../common/components/product/HscSacSearchInput.vue";
export default defineComponent({
    props: ["productData"],
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
        ProductCompanySearchInput,
        TaxCategorySearchInput,
        UnitSearchInput,
        HscSacSearchInput,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { adjustmentTypes } = fields();
        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        const ledgerSubmit = (formDataLedger) => {
            addEditRequestAdmin({
                url: `products`,
                data: formDataLedger,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
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
            onClose,
            //formDataLedger,
            ledgerSubmit,
            // getStockValue,
            valueChanged,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },

    data() {
        return {
            formDataLedger: {
                id: "",
                company_id: "",
                warehouse_id: "",
                product_type: "Goods",
                parent_id: "",
                parent_item_code: "",
                name: "",
                slug: "",
                barcode_symbology: "",
                item_code: "",
                image: "",
                category_id: "",
                brand_id: "",
                unit_id: "",
                description: "",
                user_id: "",
                created_at: "",
                updated_at: "",
                unit_1st: "",
                unit_in_decimal: "",
                hsn_sac: "",
                tax_category: "",
                company: "",
                mrp: "",
                purchase_rate: "",
                cost: "",
                sale_rate: "",
                rate_b: "",
                free_scheme_1: "",
                free_scheme_2: "",
                scheme_type: "Full Scheme",
                status: "Continue",
                color_type: "",
                rate_d: "",
                rate_f: "",
                discount: "Applicable",
                item_disc_1_percent: "",
                volume_disc_1: "",
                max_disc_percent: "",
                min_quantity: "",
                max_quantity: "",
                reorder_days: "",
                reorder_qty: "",
                min_margin_percent: "",
                prohibited: "Yes",
                visibility: "Show",
                mfr_name: "",
                upload_image: "",
                packing: "",

                sales_rate: "",
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
            isLocationModal: false,
            isModalBirthday: false,
            isModalGST: false,
            url: "your-url-here",
            addEditType: "add",
            pageTitle: "Modify Ledger",
            successMessage: "Operation successful!",
            activeKey: "taxdetails",

            resetForm() {
                this.formDataLedger = {
                    product_type: "Goods",
                };
            },
        };
    },

    methods: {
        switchTab() {
            const tabs = [
                "taxdetails",
                "contactinfo",
                "bankdetails",
                "visibility",
            ];
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
                console.log(this.formDataLedger);
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
            document.addEventListener("keydown", this.handleKeydown);
        },
        showBirthdayModal() {
            this.isModalBirthday = true;

            document.addEventListener("keydown", this.handleKeydown);
        },
        showLocationModal() {
            this.isLocationModal = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showCopyModal() {
            this.isModalCopy = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showBillingModal() {
            this.isModalBillingVisible = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showModal() {
            this.isModalVisible = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showModales() {
            this.isModalVisibles = true;
            document.addEventListener("keydown", this.handleKeydown);
        },
        showOtherModal() {
            this.isModalVisibleing = true;
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
            this.isModalVisible = false;
            document.removeEventListener("keydown", this.handleKeydown);
            console.log("Success:", xid);
        },
        handleKeydown(event) {
            if (event.key === "Escape") {
                this.handleClose();
            } else if (event.key === "F9") {
                event.preventDefault();
                this.resetForm();
            } else if (event.key === "F5") {
                event.preventDefault();
                this.switchTab();
            } else if (event.key === "F10") {
                event.preventDefault();
                this.onSubmitLedger();
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
    },
    mounted() {
        // Add keydown event listener to capture F9 key
        window.addEventListener("keydown", this.handleKeydown);
    },
    beforeDestroy() {
        // Remove event listener when component is destroyed
        window.removeEventListener("keydown", this.handleKeydown);
    },
});
</script>

<style>
:where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
    margin-bottom: 6px !important;
}

.popups {
    width: 81% !important;
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

.indiannumber {
    padding: 4px 7px !important;
    background: #f6f6f6;
    border-color: #c2c2c2;
    color: black;
    width: 21%;
    text-align: center;
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
