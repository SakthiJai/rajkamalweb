<template>
    <admin-page-table-content>
        <AddProduct
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


        <!--- add edit start-->



        <!--- end add edit end-->

        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled: false,
                                name: record.xid,

                            }),
                        }"
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <template #bodyCell="{ column, record }">

                            <template v-if="column.dataIndex === 'name'">
                                <a-typography-text
                                  v-if="record.adjustment_type === 'add'"
                                  type="success"
                                  strong
                                  @click="onCloseing(record)"
                                >
                                  +{{ record.name }}
                                </a-typography-text>
                              </template>


                            <template v-if="column.dataIndex === 'packing'">
                                <a-typography-text
                                  v-if="record.adjustment_type === 'add'"
                                  type="success"
                                  strong
                                  @click="onCloseing(record)"
                                >
                                  +{{ record.packing }}
                                </a-typography-text>
                              </template>

                              <template v-if="column.dataIndex === 'stock'">
                                <a-typography-text
                                  v-if="record.adjustment_type === 'add'"
                                  type="success"
                                  strong
                                  @click="onCloseing(record)"
                                >
                                  +{{ record.stock }}
                                </a-typography-text>
                              </template>
                              <template v-if="column.dataIndex === 'unit_name'">
                                <a-typography-text
                                  v-if="record.adjustment_type === 'add'"
                                  type="success"
                                  strong
                                  @click="onCloseing(record)"
                                >
                                  +{{ record.unit_name }}
                                </a-typography-text>
                              </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('expense_categories_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        permsArray.includes(
                                            'expense_categories_delete'
                                        ) || permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><DeleteOutlined /></template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
</template>
<script>
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import fields from "../../views/stock-management/purchases/ProductItem/mainfield";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import AddEdit from "../../../../js/main/views/stock-management/purchases/ProductItem/AddEdit.vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import AddProduct from "../../../main/views/stock-management/purchases/Product/AddProduct.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AddProduct,
        AdminPageHeader,
    },
    setup() {
        const { addEditUrl, initData, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const { permsArray } = common();

        onMounted(() => {
            crudVariables.tableUrl.value = {
                url: "products?fields=xid,id,name,packing,margin,hsn,free_scheme_1,free_scheme_2,mfr_name,w_o_free,deal_free,stock,cgst,lgst,sgst,hsn_sac,company_name,unit_name,sale_rate,unit_1st,unit,mrp,purchase_rate,cost,tax_category,company_id,barcode_value",
            };
            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "expense_category";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        });

        return {
            columns,
            ...crudVariables,
            filterableColumns,
            permsArray,
        };
    },
};
</script>
