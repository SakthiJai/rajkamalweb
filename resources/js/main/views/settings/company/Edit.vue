<template>
  <AdminPageHeader>
    <template #header>
      <a-page-header :title="$t(`menu.company`)" class="p-0">
        <template
          v-if="
            permsArray.includes('companies_edit') ||
            permsArray.includes('admin')
          "
          #extra
        >
          <a-space>
            <a-button type="primary" @click="onSubmit">
              <template #icon>
                <SaveOutlined />
              </template>
              {{ $t("common.update") }}
            </a-button>
            <CreateMenuSetting @success="addMenuSettingUpdated" />
          </a-space>
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
          {{ $t("menu.settings") }}
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          {{ $t("menu.company") }}
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>
  </AdminPageHeader>

  <a-row>
    <a-col
      :xs="24"
      :sm="24"
      :md="24"
      :lg="4"
      :xl="4"
      class="bg-setting-sidebar"
    >
      <SettingSidebar />
    </a-col>
    <a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20" class="basciss">
      <admin-page-table-content>
        <a-card class="page-content-container mt-20 mb-20 cards">
          <a-form :model="formData" :rules="rules" layout="vertical" ref="form">
            <a-row :gutter="16">
              <a-col :xs="24" :sm="24" :md="12" :lg="15">
                <fieldset>
                  <legend class="basicdetalis">Basic Details:</legend>
                  <a-row :gutter="16" class="logo_align">
                    <a-col :xs="24" :sm="8" :md="8" :lg="8">
                      <a-form-item
                        :label="$t('company.logo')"
                        name="logo"
                        :help="rules.logo ? rules.logo.message : null"
                        :validateStatus="rules.logo ? 'error' : null"
                        class="required"
                      >
                        <Upload
                          :formData="formData"
                          folder="company"
                          imageField="logo"
                          @onFileUploaded="
                            (file) => {
                              formData.logo = file.file;
                              formData.logo_url = file.file_url;
                            }
                          "
                        />
                      </a-form-item>
                    </a-col>

 <!-- Dark logo -->
  
            <a-col :xs="24" :sm="8" :md="8" :lg="8">        
    <a-form-item
      :label="$t('Dark logo')"
      name="dark"
      :help="rules.banner ? rules.banner.message : null"
      :validateStatus="rules.banner ? 'error' : null"
     
    >
      <UplodeDark
        :formData="formData"
        folder="company"
        imageField="dark"
        @onFileUploaded="
          (file) => {
            formData.dark = file.file;
            formData.dark_url = file.file_url;
          }
        "
      />
    </a-form-item>
                </a-col>
                <!-- End logo -->

 <!-- light logo -->
  
            <a-col :xs="24" :sm="8" :md="8" :lg="8">        
    <a-form-item
      :label="$t('Light logo')"
      name="light"
      :help="rules.banner ? rules.banner.message : null"
      :validateStatus="rules.banner ? 'error' : null"
    
    >
      <UplodeLight
        :formData="formData"
        folder="company"
        imageField="light"
        @onFileUploaded="
          (file) => {
            formData.light = file.file;
            formData.light_url = file.file_url;
          }
        "
      />
    </a-form-item>
                </a-col>
                <!-- End logo -->
  </a-row>   
                  
 
                  <a-row>
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.name')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required"
                      >
                        <a-input
                          v-model:value="formData.name"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.name'),
                            ])
                          "
                        />
                      </a-form-item>
                    </a-col>

                    <!-- Trade Name -->
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('Trade Name')"
                        name="Trade_name"
                        :help="rules.name ? rules.trade_name.message : null"
                        :validateStatus="rules.trade_name ? 'error' : null"
                        class="required"
                      >
                        <a-input
                          v-model:value="formData.trade_name"
                          
                        />
                      </a-form-item>
                    </a-col>

                    
                    
                  </a-row>
               
               

                  
                  <a-row :gutter="16" class="row">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.address')"
                        name="address"
                        :help="rules.address ? rules.address.message : null"
                        :validateStatus="rules.address ? 'error' : null"
                        class="required"
                      >
                        <a-textarea
                          v-model:value="formData.address"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.address'),
                            ])
                          "
                          :rows="4"
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <!-- Address 2 -->
                   <a-row :gutter="16" class="row">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('Address 2')"
                        name="address_two"
                        
                      >
                        <a-textarea
                          v-model:value="formData.address_two"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.address'),
                            ])
                          "
                          :rows="4"
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>


                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                      <a-form-item
                        :label="$t('company.country')"
                        name="country"
                        :help="rules.country ? rules.country.message : null"
                        :validateStatus="rules.country ? 'error' : null"
                        class="required"
                      >

                      <CompanyCountrySearchInput


                       @valueChanged="
                        (productId) => (formData.country = productId)"
                          @valueSuccess="getStockValue"
                          :productData="formData.country"
                         
                            v-model:value="formData.country"
                        
                          />

                      <!-- <CountrySearchInput
                        @valueChanged="
                        (productId) => (formData.state = productId)"
                          @valueSuccess="getStockValue"
                          :productData="formData.state"
                          v-model:value="formData.state"
                          id="state_search"
                           ref="statesearch"
                        /> -->
                        <!-- <a-input
                          v-model:value="formData.country"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.country'),
                            ])
                          "
                        /> -->
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                      <a-form-item
                        :label="$t('company.state')"
                        name="state"
                        :help="rules.state ? rules.state.message : null"
                        :validateStatus="rules.state ? 'error' : null"
                        class="required"
                      >
                        <!-- <a-input
                          v-model:value="formData.state"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.state'),
                            ])
                          "
                        /> -->
                        <CompanyStateSearchInput
                        @valueChanged="
                        (productId) => (formData.state = productId)"
                          @valueSuccess="getStockValue"
                          :productData="formData.state"
                          v-model:value="formData.state"
                          id="state_search"
                           ref="statesearch"
                        />
                      </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                      <a-form-item
                        :label="$t('company.pincode')"
                        name="pincode"
                        :help="rules.pincode ? rules.pincode.message : null"
                        :validateStatus="rules.pincode ? 'error' : null"
                        class="required"
                      >
                        <a-input
                          v-model:value="formData.pincode"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.pincode'),
                            ])
                          "
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>
                </fieldset>
              </a-col>
              <a-col :xs="24" :sm="24" :md="24" :lg="9">
                <fieldset>
                  <legend class="basicdetalis">Company Details:</legend>
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.brachcode')"
                        name="branch_code"
                        :help="
                          rules.branch_code ? rules.branch_code.message : null
                        "
                        :validateStatus="rules.branch_code ? 'error' : null"
                        class="required"
                      >
                        <a-input
                          v-model:value="formData.branch_code"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.brachcode'),
                            ])
                          "
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>

                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.business_type')"
                        name="business_type"
                        :help="
                          rules.business_type
                            ? rules.business_type.message
                            : null
                        "
                        :validateStatus="rules.business_type ? 'error' : null"
                        class="required"
                      >
                        <a-select
                          v-model:value="formData.business_type"
                          :placeholder="
                            $t('common.select_default_text', [
                              $t('company.business_type'),
                            ])
                          "
                        >
                          <a-select-option key="1" value="1">
                            Billing (General)
                          </a-select-option>
                          <a-select-option key="2" value="2">
                            Billing (Tax)
                          </a-select-option>
                        </a-select>
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.financial')"
                        name="financial_year_from_date"
                        :help="
                          rules.financial_year_from_date
                            ? rules.financial_year_from_date.message
                            : null
                        "
                        :validateStatus="
                          rules.financial_year_from_date ? 'error' : null
                        "
                        class="required"
                      ><!-- test-->
                        <DateTimePicker 
                          :dateTime="formData.financial_year_from_date"
                          @dateTimeChanged="
                            (changedDateTime) =>
                              (formData.financial_year_from_date =
                                changedDateTime)
                          "
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.financialtodate')"
                        name="financial_year_to_date"
                        :help="
                          rules.financial_year_to_date
                            ? rules.financial_year_to_date.message
                            : null
                        "
                        :validateStatus="
                          rules.financial_year_to_date ? 'error' : null
                        "
                        class="required"
                      >
                        <DateTimePicker
                          :dateTime="formData.financial_year_to_date"
                          @dateTimeChanged="
                            (changedDateTime) =>
                              (formData.financial_year_to_date =
                                changedDateTime)
                          "
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.taxtype')"
                        name="tax_type"
                        :help="rules.tax_type ? rules.tax_type.message : null"
                        :validateStatus="rules.tax_type ? 'error' : null"
                        class="required"
                      >
                        <a-select
                          v-model:value="formData.tax_type"
                          :placeholder="
                            $t('common.select_default_text', [
                              $t('company.tax_type'),
                            ])
                          "
                        >
                          <a-select-option key="1" value="1">
                            GST
                          </a-select-option>
                          <a-select-option key="2" value="2">
                            SGST
                          </a-select-option>
                        </a-select>
                      </a-form-item>
                    </a-col>
                  </a-row>
                </fieldset>
              </a-col>
            </a-row>

            <!--- company details --->
            <a-row :gutter="24">
              <a-col :xs="24" :sm="24" :md="24" :lg="24" class="contactdetalis">
                <fieldset>
                  <legend class="contactdetalis">
                    <input
                      type="checkbox"
                      class="checkboxing"
                      id="jack"
                      value="Jack"
                      v-model="checkedNames"
                    />
                    Contact Details:
                  </legend>
                  <a-row :gutter="16" class="rows">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.phone')"
                        name="phone"
                        :help="rules.phone ? rules.phone.message : null"
                        :validateStatus="rules.phone ? 'error' : null"
                        class="required"
                      >
                        <a-input
                          v-model:value="formData.phone"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.phone'),
                            ])
                          "
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <a-row :gutter="16" class="rows">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.website')"
                        name="website"
                        :help="rules.website ? rules.website.message : null"
                        :validateStatus="rules.website ? 'error' : null"
                        class="required"
                      >
                        <a-input
                          v-model:value="formData.website"
                          :placeholder="
                            $t('common.placeholder_default_text', [
                              $t('company.website'),
                            ])
                          "
                        />
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('company.emailid')"
                        name="emailid"
                        :help="rules.emailid ? rules.emailid.message : null"
                        :validateStatus="rules.emailid ? 'error' : null"
                        class="required"
                      >
                        <span style="display: flex">
                          <a-input
                            v-model:value="formData.email"
                            :placeholder="
                              $t('common.placeholder_default_text', [
                                $t('company.emailid'),
                              ])
                            "
                          />
                          <a-button type="primary" class="buttons">
                            <PlusOutlined />
                          </a-button>
                        </span>
                      </a-form-item>
                    </a-col>
                  </a-row>
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('Contact Number')"
                        name="contact_number"
                        :help="
                          rules.contact_number
                            ? rules.contact_number.message
                            : null
                        "
                        :validateStatus="rules.contact_number ? 'error' : null"
                        class="required"
                      >
                        <span style="display: flex">
                          <a-input
                            v-model:value="formData.contact_number"
                            :placeholder="
                              $t('common.placeholder_default_text', [
                                $t('company.contact_number'),
                              ])
                            "
                          />
                        </span>
                      </a-form-item>
                    </a-col>
                  </a-row>

                  <!-- whatsapp field -->
                  <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                      <a-form-item
                        :label="$t('Whatsapp Number')"
                        name="whatsapp_number"
                        :help="
                          rules.whatsapp_number
                            ? rules.whatsapp_number.message
                            : null
                        "
                        :validateStatus="rules.whatsapp_number ? 'error' : null"
                        class="required"
                      >
                        <span style="display: flex">
                          <a-input
                            v-model:value="formData.whatsapp_number"
                            :placeholder="
                              $t('common.placeholder_default_text', [
                                $t('company.whatsapp_number'),
                              ])
                            "
                          />
                        </span>
                      </a-form-item>
                    </a-col>
                  </a-row>
                </fieldset>
              </a-col>
            </a-row>
            <!--end company detalis-->

            <!--- tabs--->
            <fieldset class="tabed">
              <legend class="contactdetalis">
                <input
                  type="checkbox"
                  class="checkboxing"
                  id="jack"
                  value="Jack"
                  v-model="checkedNames"
                />
                More Info:
              </legend>

              <a-form layout="vertical">
                <a-tabs v-model:activeKey="activeKey">
                  <a-tab-pane key="taxdetalis">
                    <template #tab>
                      <span>
                        {{ $t("company.taxdetalis") }}
                      </span>
                    </template>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.regtype')"
                              name="company_reg_type"
                              :help="
                                rules.company_reg_type
                                  ? rules.company_reg_type.message
                                  : null
                              "
                              :validateStatus="
                                rules.company_reg_type ? 'error' : null
                              "
                              class="required"
                            >
                              <a-select
                                v-model:value="formData.company_reg_type"
                                :placeholder="
                                  $t('common.select_default_text', [
                                    $t('company.company_reg_type'),
                                  ])
                                "
                              >
                                <a-select-option key="1" value="1">
                                  Registered
                                </a-select-option>
                                <a-select-option key="2" value="2">
                                  Not Registered
                                </a-select-option>
                              </a-select>
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.gstin')"
                              name="gstin"
                              :help="rules.gstin ? rules.gstin.message : null"
                              :validateStatus="rules.gstin ? 'error' : null"
                              class="required"
                            >
                              <a-input
                                v-model:value="formData.gstin"
                                :placeholder="
                                  $t('common.placeholder_default_text', [
                                    $t('company.gstin'),
                                  ])
                                "
                              />
                            </a-form-item>
                          </a-col>
                        </a-row>

                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.dates')"
                              name="reg_date"
                              :help="
                                rules.reg_date ? rules.reg_date.message : null
                              "
                              :validateStatus="rules.reg_date ? 'error' : null"
                              class="required"
                            >
                              <DateTimePicker
                                :dateTime="formData.reg_date"
                                @dateTimeChanged="
                                  (changedDateTime) =>
                                    (formData.reg_date = changedDateTime)
                                "
                              />
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.panno')"
                              name="pan_number"
                              :help="
                                rules.pan_number
                                  ? rules.pan_number.message
                                  : null
                              "
                              :validateStatus="
                                rules.pan_number ? 'error' : null
                              "
                              class="required"
                            >
                              <a-input
                                v-model:value="formData.pan_number"
                                @input="allowOnlyPanFormat"
                                maxlength="10"
                                :placeholder="
                                  $t('AAAAA1234A', [$t('company.pan_number')])
                                "
                              />
                            </a-form-item>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.tds')"
                              name="tds"
                              :help="rules.tds ? rules.tds.message : null"
                              :validateStatus="rules.tds ? 'error' : null"
                              class="required"
                            >
                              <a-select
                                v-model:value="formData.tds"
                                :placeholder="
                                  $t('common.select_default_text', [
                                    $t('company.tds'),
                                  ])
                                "
                              >
                                <a-select-option key="Yes" value="Yes">
                                  Yes
                                </a-select-option>
                                <a-select-option key="No" value="No">
                                  No
                                </a-select-option>
                              </a-select>
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.tcs')"
                              name="tcs"
                              :help="rules.tcs ? rules.tcs.message : null"
                              :validateStatus="rules.tcs ? 'error' : null"
                              class="required"
                            >
                              <a-select
                                v-model:value="formData.tcs"
                                :placeholder="
                                  $t('common.select_default_text', [
                                    $t('company.tcs'),
                                  ])
                                "
                              >
                                <a-select-option key="Yes" value="Yes">
                                  Yes
                                </a-select-option>
                                <a-select-option key="No" value="No">
                                  No
                                </a-select-option>
                              </a-select>
                            </a-form-item>
                          </a-col>
                        </a-row>
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.einvoicing')"
                              name="einvoicing"
                              :help="
                                rules.einvoicing
                                  ? rules.einvoicing.message
                                  : null
                              "
                              :validateStatus="
                                rules.einvoicing ? 'error' : null
                              "
                              class="required"
                            >
                              <a-select
                                v-model:value="formData.einvoicing"
                                :placeholder="
                                  $t('common.select_default_text', [
                                    $t('company.einvoicing'),
                                  ])
                                "
                              >
                                <a-select-option key="Yes" value="Yes">
                                  Yes
                                </a-select-option>
                                <a-select-option key="No" value="No">
                                  No
                                </a-select-option>
                              </a-select>
                            </a-form-item>
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>
                  </a-tab-pane>

                  <a-tab-pane key="visibility">
                    <template #tab>
                      <span>
                        {{ $t("company.info") }}
                      </span>
                    </template>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.licno')"
                              name="drug_lic_no"
                              :help="
                                rules.drug_lic_no
                                  ? rules.drug_lic_no.message
                                  : null
                              "
                              :validateStatus="
                                rules.drug_lic_no ? 'error' : null
                              "
                              class="required"
                            >
                              <a-input v-model:value="formData.drug_lic_no" />
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.expdate')"
                              name="exp_date"
                              :help="
                                rules.exp_date ? rules.exp_date.message : null
                              "
                              :validateStatus="rules.exp_date ? 'error' : null"
                              class="required"
                            >
                              <span style="display: flex">
                                <DateTimePicker
                                  :dateTime="formData.exp_date"
                                  @dateTimeChanged="
                                    (changedDateTime) =>
                                      (formData.exp_date = changedDateTime)
                                  "
                                />

                                <a-button type="primary" class="buttons">
                                  <PlusOutlined />
                                </a-button>
                              </span>
                            </a-form-item>
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>
                  </a-tab-pane>
                  <a-tab-pane key="otherinfo">
                    <template #tab>
                      <span>
                        {{ $t("company.otherinfo") }}
                      </span>
                    </template>

                    <a-row :gutter="16">
                      <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-row :gutter="16">
                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.diction')"
                              name="juris_dicition"
                              :help="
                                rules.juris_dicition
                                  ? rules.juris_dicition.message
                                  : null
                              "
                              :validateStatus="
                                rules.juris_dicition ? 'error' : null
                              "
                              class="required"
                            >
                              <a-input
                                v-model:value="formData.juris_dicition"
                                :placeholder="
                                  $t('common.placeholder_default_text', [
                                    $t('company.diction'),
                                  ])
                                "
                              />
                            </a-form-item>
                          </a-col>

                          <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                              :label="$t('company.style')"
                              name="working_style"
                              :help="
                                rules.working_style
                                  ? rules.working_style.message
                                  : null
                              "
                              :validateStatus="
                                rules.working_style ? 'error' : null
                              "
                              class="required"
                            >
                              <a-select
                                v-model:value="formData.working_style"
                                :placeholder="
                                  $t('common.select_default_text', [
                                    $t('company.working_style'),
                                  ])
                                "
                              >
                                <a-select-option key="1" value="1">
                                  Normal w/0 Secoundary
                                </a-select-option>
                                <a-select-option key="2" value="2">
                                  Secoundary w/0 Normal
                                </a-select-option>
                              </a-select>
                            </a-form-item>
                          </a-col>
                        </a-row>
                      </a-col>
                    </a-row>
                  </a-tab-pane>
                </a-tabs>
              </a-form>
            </fieldset>
            <!--- end tabs--->

            <a-row :gutter="16" class="update">
              <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-form-item>
                  <a-button type="primary" @click="onSubmit" :loading="loading">
                    <template #icon>
                      <SaveOutlined />
                    </template>
                    {{ $t("common.update") }}
                  </a-button>
                </a-form-item>
              </a-col>
            </a-row>
          </a-form>
        </a-card>
      </admin-page-table-content>
    </a-col>
  </a-row>

  <!--- key words content -->

  <!--- end of key words content -->
</template>

<script>
import { onMounted, onUnmounted, ref,watch } from "vue";
import {
  EyeOutlined,
  PlusOutlined,
  EditOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
  SaveOutlined,
  SettingOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import UplodeDark from "../../../../common/core/ui/file/UplodeDark.vue"
import UplodeLight from "../../../../common/core/ui/file/UplodeLight.vue"
import apiAdmin from "../../../../common/composable/apiAdmin";
import CurrencyAddButton from "../../common/settings/currency/AddButton.vue";
import LanguageAddButton from "../../common/settings/translations/langs/AddButton.vue";
import WarehouseAddButton from "../warehouses/AddButton.vue";
import common from "../../../../common/composable/common";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import CreateMenuSetting from "./CreateMenuSetting.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue";
import CompanyStateSearchInput from "../../../../common/components/ledger/CompanyStateSearchInput.vue";
import CompanyCountrySearchInput from "../../../../common/components/ledger/CompanyCountrySearchInput.vue";

import axios from "axios";

export default {
  components: {
    EyeOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    ExclamationCircleOutlined,
    SaveOutlined,
    SettingOutlined,
    ColorPicker,
    DateTimePicker,
    Upload,
    UplodeDark,
    UplodeLight,
    CurrencyAddButton,
    WarehouseAddButton,
    SettingSidebar,
    AdminPageHeader,
    CreateMenuSetting,
    LanguageAddButton,
    CompanyStateSearchInput,
    CompanyCountrySearchInput,
  },
  setup() {
    
    const { addEditRequestAdmin, loading, rules } = apiAdmin();
    const { permsArray, appSetting, dayjsObject, appType } = common();
    const { t } = useI18n();
    const store = useStore();
    const formData = ref({ pan_number: ""});
    const currencies = ref([]);
    const warehouses = ref([]);
    const timezones = ref([]);
    const dateFormats = ref([]);
    const timeFormats = ref([]);
    const allLangs = ref([]);
    const company = appSetting.value;

    const currencyUrl = "currencies?limit=10000";
    const warehouseUrl = "warehouses?limit=10000";
    const timezoneUrl = "timezones";
    const langUrl = "all-langs";
    const gradientColor = ref(
      "linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 1) 100%)"
    );
    const appThemeMode = window.config.theme_mode;

    onMounted(() => {
      const warehousePromise = axiosAdmin.get(warehouseUrl);
      const currencyPromise = axiosAdmin.get(currencyUrl);
      const timezonePromise = axiosAdmin.get(timezoneUrl);
      const langPromise = axiosAdmin.get(langUrl);

      Promise.all([
        warehousePromise,
        currencyPromise,
        timezonePromise,
        langPromise,
      ]).then(
        ([
          warehousesResponse,
          currenciesResponse,
          timezonesResponse,
          langResponse,
        ]) => {
          warehouses.value = warehousesResponse.data;
          currencies.value = currenciesResponse.data;
          timezones.value = timezonesResponse.data.timezones;
          dateFormats.value = timezonesResponse.data.date_formates;
          timeFormats.value = timezonesResponse.data.time_formates;
          allLangs.value = langResponse.data.langs;

          setFormData();
        }
      );
    });

    const setFormData = () => {
      formData.value = {
        name: company.name,
        short_name: company.short_name,
        email: company.email,
        logo: company.logo,
        logo_url: company.logo_url,
        dark_url: company.dark_url,
        light_url: company.light_url,
        contact_number: company.contact_number,
        //whatsapp number
        whatsapp_number: company.whatsapp_number,
        tcs: company.tcs,
        tds: company.tds,
        einvoicing: company.einvoicing,
        phone: company.phone,
        website: company.website,
        country: company.country,
        pincode: company.pincode,
        state: company.state,
        address: company.address,
        address_two: company.address_two,
        branch_code: company.branch_code,
        business_type: company.business_type,
        financial_year_from_date: company.financial_year_from_date,
        financial_year_to_date: company.financial_year_to_date,
        tax_type: company.tax_type,
        company_reg_type: company.company_reg_type,
        gstin: company.gstin,
        reg_date: company.reg_date,
        pan_number: company.pan_number,
        tcs: company.tcs,
        tds: company.tds,
        einvoicing: company.einvoicing,
        trade_name:company.trade_name,
        drug_lic_no: company.drug_lic_no,
        exp_date: company.exp_date,
        juris_dicition: company.juris_dicition,
        working_style: company.working_style,
        left_sidebar_theme: company.left_sidebar_theme,
        dark_logo: company.dark_logo,
        dark_logo_url: company.dark_logo_url,
        light_logo: company.light_logo,
        light_logo_url: company.light_logo_url,
        small_dark_logo: company.small_dark_logo,
        small_light_logo: company.small_light_logo,
        small_dark_logo_url: company.small_dark_logo_url,
        small_light_logo_url: company.small_light_logo_url,
        login_image: company.login_image,
        login_image_url: company.login_image_url,
        shortcut_menus: company.shortcut_menus,
        rtl: company.rtl,
        currency_id: company.x_currency_id,
        lang_id: company.x_lang_id,
        warehouse_id: company.x_warehouse_id,
        primary_color: company.primary_color,
        timezone: company.timezone,
        date_format: company.date_format,
        time_format: company.time_format,
        auto_detect_timezone: company.auto_detect_timezone,
        app_debug: company.app_debug,
        update_app_notification: company.update_app_notification,
        _method: "PUT",
      };
    };





    const onSubmit = () => {
      console.log("📤 Submitting formData:",formData.value.state);
      addEditRequestAdmin({
        url: `companies/${company.xid}`,
        data: formData.value,
        successMessage: t("company.updated"),
        success: (res) => {
          store.dispatch("auth/updateApp");
        },
      });
    };

    const currencyAdded = () => {
      axiosAdmin.get(currencyUrl).then((response) => {
        currencies.value = response.data;
      });
    };

    const warehouseAdded = () => {
      axiosAdmin.get(warehouseUrl).then((response) => {
        warehouses.value = response.data;
      });
    };

    const languageAdded = () => {
      axiosAdmin.get(langUrl).then((response) => {
        allLangs.value = response.data.langs;
      });
    };

    const addMenuSettingUpdated = (menuPosition) => {
      formData.value.shortcut_menus = menuPosition;
    };

    const allowOnlyPanFormat = (e) => {
      const panRegex = /^[A-Z]{0,5}[0-9]{0,4}[A-Z]?$/;
      const value = e.target.value.toUpperCase();
      if (panRegex.test(value)) {
        formData.value.pan_number = value;
      } else {
        e.target.value = formData.value.pan_number;
      }
    };
   

    return {
      appType,
      permsArray,
      formData,
      loading,
      rules,
      warehouses,
      currencies,
      allLangs,
      onSubmit,
      currencyAdded,
      warehouseAdded,
      gradientColor,
      timezones,
      dateFormats,
      timeFormats,
      dayjsObject,
      appThemeMode,
      addMenuSettingUpdated,
      languageAdded,
      allowOnlyPanFormat,
    };
  },

  watch: {
    
      // Watching formData.state
    "formData.state"(newValue, oldValue) {
      console.log("🔄 formData.value.state changed:");
      console.log("Old Value:", oldValue);
      console.log("New Value:", newValue);

      
      
   
    },

    
    "formData.name"(TradeName) {
      
      this.formData.Trade_name = TradeName;
    }
},
};

</script>

<style>
.basic-details-heading {
  margin-bottom: 0.5em;
  color: rgba(0, 0, 0, 0.88);
  font-weight: 700;
  font-size: 22px;
  line-height: 2;
}

.company-details-heading {
  margin-bottom: 0.5em;
  color: rgba(0, 0, 0, 0.88);
  font-weight: 700;
  font-size: 22px;
  line-height: 4.5;
}

.basicdetalis {
  border-bottom: none !important;
  color: black !important;
  margin-bottom: 0px !important;
}

.contactdetalis {
  border-bottom: none !important;
  color: black !important;
  margin-top: 12px !important;
}

legend {
  width: auto !important;
}

.cards {
  background-color: #f5f5f5;
}

.basciss {
  background-color: white;
}

.checkboxing {
  margin-left: 6px;
}

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

.merge {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;

  width: 80%;
  /* Adjust the width as needed */
  max-width: 600px;
  /* Optional max-width for responsiveness */
  border: 1px solid #9a9a9a;
  /* Optional border for visibility */
  /* Optional rounded corners */
  margin-top: 21px;
  /* Optional shadow */
}
.container {
  display: flex;
  flex-direction: column;
  height: 200px;
  justify-content: space-between;
}

.header {
  display: flex;
  align-items: center;
}

.title {
  margin-left: 10px;
  font-size: 17px;
  /* Make text bigger */
}

.icon-table {
  width: 100%;
  border-collapse: collapse;
}

.icon-wrapper {
  display: flex;
  justify-content: center;
}

.title {
  margin-left: 10px;
  font-size: 1.2em;
}

.spacer {
  width: 100%;
}

.icon-table {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}

.icon-table td {
  border: 1px solid #ccc;
  padding: 10px;
}

.icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}

.title {
  margin-left: 10px;
  font-size: 1.2em;
}

.mergo {
  justify-content: center !important;
  border: solid 1px #ccc;
}

.edit {
  border: solid 1px #ccc;
}

.rows {
  /* margin-top: -13px; */
}

.tabed {
  margin-top: 12px;
}

.update {
  margin-top: 12px;
}

:where(.css-dev-only-do-not-override-wosfq4).ant-form-item {
  margin-bottom: 13px !important;
}

.buttons {
  background-color: #ffffff;
  border-color: #d9d9d9;
  box-shadow: 0 2px 0 rgba(0, 0, 0, 0.02);
  margin: 1px;
  color: black;
}

.ant-upload-select div img {
  max-width: 90px !important;
}

.logo_align{
    display: flex;
  align-items: center;
  justify-content: space-between;
}
</style>
