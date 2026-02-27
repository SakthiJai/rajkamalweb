<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t('Rate Enquiry / Modification')" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t('menu.dashboard') }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t('Rate Enquiry / Modification') }}
                </a-breadcrumb-item>
            </a-breadcrumb>
             <a-button
          type="primary"
          class="creating"
          @click="createNewpartyeditEntry()"
          style="float: right; margin-top: -3%; background-color: #1f6d70;"
        >
          <PlusOutlined />
          Create F2
        </a-button>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
          
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="6">
                        <ProductSearchInput
                            ref="productSearchRef"
                            @valueChanged="
                                (productId) => {
                                    filters.product_id = productId;
                                    reFetchDatatable();
                                }
                            "
                        />
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
        />

        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    
                <a-table
                    :columns="columns"
                    :data-source="tableData"
                    :row-key="record => record.sno"
                    bordered
                    size="middle"
                >
                    <template #action="{ record }">
                        <a-space size="small">
                            <a-button
                                type="primary"
                                shape="square"
                                @click="editItem(record)"
                            >
                                <EditOutlined />
                            </a-button>

                            <a-button
                                type="primary"
                                shape="square"
                                @click="showDeleteConfirm(record.xid || record.sno)"
                            >
                                <DeleteOutlined />
                            </a-button>
                        </a-space>
                    </template>
                </a-table>

                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
</template>
<script>
import { onMounted, onUnmounted, watch, nextTick } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import ProductSearchInput from "../../../../common/components/product/ProductSearchInput.vue";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        ProductSearchInput,
        AdminPageHeader,
    },

    setup() {
        const { url, addEditUrl, hashableColumns, initData, columns, filters } = fields();
        const crudVariables = crud();
        const { permsArray, selectedWarehouse } = common();

        // F2 keyboard shortcut handler
        let handleKeyDown = null;

        onMounted(() => {
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "Rate Enquiry / Modification";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];

            reFetchDatatable();

            // Auto-focus on ProductSearchInput when page loads
            nextTick(() => {
                const searchInput = document.querySelector('.ant-select input');
                if (searchInput) {
                    searchInput.focus();
                }
            });

            // F2 keyboard shortcut to open new rate addition modal
            handleKeyDown = (event) => {
                if (event.key === 'F2' || event.keyCode === 113) {
                    event.preventDefault();
                    crudVariables.addItem();
                }
            };
            window.addEventListener('keydown', handleKeyDown);
        });

        onUnmounted(() => {
            // Remove F2 keyboard listener when component unmounts
            if (handleKeyDown) {
                window.removeEventListener('keydown', handleKeyDown);
            }
        });

        const reFetchDatatable = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
            };

            crudVariables.fetch({
                page: 1,
            });
        };

        watch(selectedWarehouse, () => {
            reFetchDatatable();
        });

        const tableData = [
            
           
        ];

        return {
            columns,
            tableData,
            permsArray,
            ...crudVariables,
            filters,
            reFetchDatatable,
        };
    },
};
</script>