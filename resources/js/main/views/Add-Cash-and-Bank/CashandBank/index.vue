<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`Bank Account`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`Add Bank`) }} 
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`Bank Account`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <a-row>
        <a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
            <admin-page-filters>
                <a-row :gutter="[16, 16]">
                    <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                        <a-space>
                            <template
                                v-if="
                                    permsArray.includes('bankdetails_create') ||
                                    permsArray.includes('admin')
                                "
                            >
                                <a-button type="primary" @click="addItem" style="background-color: #1f6d70;">
                                    <PlusOutlined />
                                    {{ $t("Add Bank Account") }} / F2  
                                </a-button>
                            </template>
                            <!-- <a-button
                                v-if="
                                    table.selectedRowKeys.length > 0 &&
                                    (permsArray.includes(
                                        'bankdetails_delete'
                                    ) ||
                                        permsArray.includes('admin'))
                                "
                                type="primary"
                                @click="showSelectedDeleteConfirm"
                                danger
                            >
                                <template #icon><DeleteOutlined /></template>
                                {{ $t("bankdetails_delete") }}
                            </a-button> -->
                        </a-space>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                        <a-row :gutter="[16, 16]" justify="end">
                            <a-col :xs="24" :sm="24" :md="16" :lg="12" :xl="10">
                                <a-input-group compact>
                                    <a-select
                                        style="width: 35%"
                                        v-model:value="table.searchColumn"
                                        :placeholder="
                                            $t('common.select_default_text', [
                                                '',
                                            ])
                                        "
                                    >
                                        <a-select-option
                                            v-for="filterableColumn in filterableColumns"
                                            :key="filterableColumn.key"
                                        >
                                            {{ filterableColumn.value }}
                                        </a-select-option>
                                    </a-select>
                                    <a-input-search
                                        ref="searchInputRef"
                                        style="width: 65%"
                                        v-model:value="table.searchString"
                                        @focus="isSearchFocused = true"
                                        @blur="isSearchFocused = false"
                                        @change="onTableSearch"
                                        @search="onTableSearch"
                                        :loading="table.filterLoading"
                                    />
                                </a-input-group>
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
                                :row-selection="{
                                    selectedRowKeys: table.selectedRowKeys,
                                    onChange: onRowSelectChange,
                                    getCheckboxProps: (record) => ({
                                        disabled:
                                            record.is_deletable &&
                                            (permsArray.includes(
                                                'bankdetails_delete'
                                            ) ||
                                                permsArray.includes('admin'))
                                                ? false
                                                : true,
                                        bankdetails_name: record.xid,
                                    }),
                                }"
                                :columns="columns"
                                :row-key="(record) => record.xid"
                                :data-source="table.data"
                                :pagination="table.pagination"
                                :loading="table.loading"
                                :scroll="{ y: 500 }"
                                @change="handleTableChange"
                                bordered
                                size="middle"
                            >
                                <template #bodyCell="{ column, record }">
                                    <template
                                        v-if="column.dataIndex === 'action'"
                                    >
                                        <a-button
                                            v-if="
                                                permsArray.includes(
                                                    'bankdetails_edit'
                                                ) ||
                                                permsArray.includes('admin')
                                            "
                                            type="primary"
                                            @click="editItem(record)"
                                            style="margin-left: 4px"
                                        >
                                            <template #icon
                                                ><EditOutlined
                                            /></template>
                                        </a-button>
                                        <a-button
                                            v-if="
                                                permsArray.includes(
                                                    'expense_categories_delete'
                                                ) ||
                                                permsArray.includes('admin')
                                            "
                                            type="primary"
                                            @click="
                                                showDeleteConfirm(record.xid)
                                            "
                                            style="margin-left: 4px"
                                        >
                                            <template #icon
                                                ><DeleteOutlined
                                            /></template>
                                        </a-button>
                                    </template>
                                </template>
                            </a-table>
                        </div>
                    </a-col>
                </a-row>
            </admin-page-table-content>
        </a-col>
    </a-row>
</template>
<script>
import { onMounted, onBeforeUnmount, nextTick, ref, watch } from "vue";
import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import fields from "../CashandBank/fields";
import AddEdit from "../CashandBank/AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
    },
    setup() {
        const selectedRowIndex = ref(-1);
        const searchInputRef = ref(null);
        const isSearchFocused = ref(false);
        const { permsArray } = common();
        const { url, addEditUrl, initData, columns, filterableColumns } =
            fields();
        const crudVariables = crud();
        const scrollSelectedRowIntoView = async () => {
            await nextTick();

            const tableBody = document.querySelector(".ant-table-body");
            const selectedRow = document.querySelector(
                ".ant-table-tbody > tr.ant-table-row-selected"
            );

            if (!tableBody || !selectedRow) {
                return;
            }

            const rowTop = selectedRow.offsetTop;
            const rowBottom = rowTop + selectedRow.offsetHeight;
            const visibleTop = tableBody.scrollTop;
            const visibleBottom = visibleTop + tableBody.clientHeight;

            if (rowTop < visibleTop) {
                tableBody.scrollTop = rowTop;
            } else if (rowBottom > visibleBottom) {
                tableBody.scrollTop = rowBottom - tableBody.clientHeight;
            }
        };

        const updateSelectedRow = async (row) => {
            if (!row) {
                return;
            }

            crudVariables.table.selectedRowKeys = [row.xid];
            await scrollSelectedRowIntoView();
        };

        const handleKeyDown = (event) => {
            if (crudVariables.addEditVisible.value) return;

            // F2 → Add Bank
            if (event.code === "F2") {
                event.preventDefault();

                crudVariables.addEditType.value = "add";
                crudVariables.formData.value = { ...crudVariables.initData.value };
                crudVariables.addEditVisible.value = true;

                return;
            }

            if (
                isSearchFocused.value &&
                !["ArrowUp", "ArrowDown", "Enter"].includes(event.key)
            ) {
                return;
            }

            const data = crudVariables.table.data;

            // Arrow Down
            if (event.key === "ArrowDown") {
                event.preventDefault();
                if (data.length === 0) return;

                if (selectedRowIndex.value < 0) {
                    selectedRowIndex.value = 0;
                } else if (selectedRowIndex.value < data.length - 1) {
                    selectedRowIndex.value++;
                }

                const row = data[selectedRowIndex.value];
                updateSelectedRow(row);
            }

            // Arrow Up
            if (event.key === "ArrowUp") {
                event.preventDefault();
                if (data.length === 0) return;

                if (selectedRowIndex.value < 0) {
                    selectedRowIndex.value = 0;
                } else if (selectedRowIndex.value > 0) {
                    selectedRowIndex.value--;
                }

                const row = data[selectedRowIndex.value];
                updateSelectedRow(row);
            }

            // Enter → Edit
            if (event.key === "Enter") {
                event.preventDefault();

                if (selectedRowIndex.value >= 0) {
                    const row = data[selectedRowIndex.value];
                    crudVariables.editItem(row);
                }
            }
        };
        const onCloseAddEdit = () => {
    crudVariables.addEditVisible.value = false;

    nextTick(() => {
        searchInputRef.value?.focus();
    });
};

        onMounted(async () => {
            crudVariables.table.pagination = {
                ...crudVariables.table.pagination,
                pageSize: 100,
                current: 1,
                currentPage: 1,
            };
            crudVariables.tableUrl.value = {
                url: "receiptbank?fields=id,xid,bank_name,accountant_name,ifsc_code,account_number",
            };

            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "bankdetails";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };

            window.addEventListener("keydown", handleKeyDown);

            await nextTick();
            searchInputRef.value?.focus();
        });
        onBeforeUnmount(() => {
    window.removeEventListener("keydown", handleKeyDown);
});
const onRowSelectChange = (selectedKeys) => {
    crudVariables.table.selectedRowKeys = selectedKeys;

    if (selectedKeys.length > 0) {
        selectedRowIndex.value = crudVariables.table.data.findIndex(
            (row) => row.xid === selectedKeys[0]
        );
        scrollSelectedRowIntoView();
    } else {
        selectedRowIndex.value = -1;
    }
};

        return {
            permsArray,
            columns,
            ...crudVariables,
            filterableColumns,
            selectedRowIndex,
            searchInputRef,
            isSearchFocused,
            onCloseAddEdit,
            onRowSelectChange
        };
    },
};
</script>

<style lang="less"></style>
