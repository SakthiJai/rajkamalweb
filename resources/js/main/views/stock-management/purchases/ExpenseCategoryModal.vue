<template>
    <a-modal :open="visible" :closable="false" :centered="true" :title="pageTitle" @ok="onSubmit">
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.multi_series')" name="name"
                        :help="rules.name ? rules.name.message : null" :validateStatus="rules.name ? 'error' : null"
                        class="required">
                        <a-select v-model:value="formData.business_type"
                            :placeholder="$t('common.select_default_text', [$t('expense_category.multi_series')])">
                            <a-select-option key="1" value="1">
                                Default
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Default
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item :label="$t('expense_category.bill_number')" name="gstin"
                        :help="rules.gstin ? rules.gstin.message : null" :validateStatus="rules.gstin ? 'error' : null"
                        class="required">
                        <a-input v-model:value="formData.gstin" :placeholder="$t('common.placeholder_default_text', [
                            $t('expense_category.bill_number'),
                        ])
                            " />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item :label="$t('expense_category.bill_number_to')" name="gstin"
                        :help="rules.gstin ? rules.gstin.message : null" :validateStatus="rules.gstin ? 'error' : null"
                        class="required">
                        <a-input v-model:value="formData.gstin" :placeholder="$t('common.placeholder_default_text', [
                            $t('expense_category.bill_number'),
                        ])
                            " />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.party')" name="name"
                        :help="rules.name ? rules.name.message : null" :validateStatus="rules.name ? 'error' : null"
                        class="required">
                        <a-select v-model:value="formData.party">
                            <a-select-option selected key="1" value="1">
                                All Ledgers
                            </a-select-option>
                           
                          </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.status')" name="name"
                        :help="rules.name ? rules.name.message : null" :validateStatus="rules.name ? 'error' : null"
                        class="required">
                        <a-select v-model:value="formData.status"
                           >
                            <a-select-option selected key="1" value="1">
                               All
                            </a-select-option>
                            <a-select-option key="2" value="2">
                               Pending
                            </a-select-option>
                            <a-select-option key="3" value="3">
                              Paid  Partial 
                             </a-select-option>
                             <a-select-option key="4" value="4">
                               Partial 
                               </a-select-option>

                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.user_name')" name="name"
                        :help="rules.name ? rules.name.message : null" :validateStatus="rules.name ? 'error' : null"
                        class="required">
                        <a-select v-model:value="formData.username">
                            <a-select-option selected key="1" value="1">
                                All Users
                            </a-select-option>
                           
                          </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.station')" name="name"
                        :help="rules.name ? rules.name.message : null" :validateStatus="rules.name ? 'error' : null"
                        class="required">
                        <a-select v-model:value="formData.station">
                            <a-select-option selected key="1" value="1">
                                All Station
                            </a-select-option>
                           
                          </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.print')" name="name"
                        :help="rules.name ? rules.name.message : null" :validateStatus="rules.name ? 'error' : null"
                        class="required">
                        <a-select v-model:value="formData.print"
                           >
                            <a-select-option selected key="1" value="1">
                               All
                            </a-select-option>
                            <a-select-option key="2" value="2">
                               Pending
                            </a-select-option>
                         

                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item :label="$t('expense_category.sale_above')" name="gstin"
                    :help="rules.gstin ? rules.gstin.message : null" :validateStatus="rules.gstin ? 'error' : null"
                    class="required">
                    <a-input v-model:value="formData.gstin" :placeholder="$t('common.placeholder_default_text', [
                        $t('expense_category.sale_above'),
                    ])
                        " />
                </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
                <SaveOutlined />
                {{ addEditType == "add" ? $t("common.submit") : $t("common.update") }}
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";

export default defineComponent({
    business_type: '2',
    props: [
        "formData",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        StaffMemberAddButton
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
    data() {
    return {
      formData: {
        party: '1',
        status :'1',
        username:'1',
        station:'1',
        print:'1'
        // Set the default value to '2'
      },
    };
  },
});

</script>

<style>

</style>