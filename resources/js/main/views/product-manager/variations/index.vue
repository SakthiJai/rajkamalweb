<template>
    <div id="variationsindex">
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.variations`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.product_manager`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.variations`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space>
                    <template
                        v-if="
                            permsArray.includes('variations_create') ||
                            permsArray.includes('admin')
                        "
                    >
                        <a-button type="primary" @click="addItem" style="background-color: #1f6d70;">
                            <PlusOutlined />
                            {{ $t("variation.add") }} / F2
                        </a-button>
                    </template>
                    <!-- <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('variations_delete') ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        @click="showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button> -->
                </a-space>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <a-input-group compact>
                            <a-select
                                style="width: 25%"
                                v-model:value="table.searchColumn"
                                :placeholder="$t('common.select_default_text', [''])"
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
                                style="width: 75%"
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
                                disabled: false,
                                name: record.xid,
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
                            <template v-if="column.dataIndex === 'name'">
                                <span v-if="record.x_parent_id == ''">
                                    <a-row :gutter="[16, 16]" style="margin-top: 10px">
                                        <a-col :xs="24" :sm="24" :md="24" :lg="24"
                                            >{{
                                                record.x_parent_id == ""
                                                    ? record.name
                                                    : ""
                                            }}
                                        </a-col>
                                    </a-row>
                                </span>
                            </template>
                            <template v-if="column.dataIndex === 'value'">
                                <ul v-if="record.sub_variations.length > 0">
                                    <li
                                        v-for="variation in record.sub_variations"
                                        :key="variation.name"
                                    >
                                        {{ variation.name }}
                                    </li>
                                </ul>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('variations_edit') ||
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
                                        permsArray.includes('variations_delete') ||
                                        permsArray.includes('admin')
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
    </div>
</template>
<script>
import { onMounted, nextTick, onBeforeUnmount, ref, watch } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import dayjs from "dayjs";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,

        AddEdit,
        AdminPageHeader,
    },
    setup() {
        const {
            addEditUrl,
            url,
            initData,
            columns,
            filterableColumns,
            hashableColumns,
        } = fields();
        const selectedRowIndex = ref(-1);
        const searchInputRef = ref(null);
        const isSearchFocused = ref(false);
        const crudVariables = crud();
        const { permsArray } = common();

        const scrollSelectedRowIntoView = async () => {
            await nextTick();

            const tableBody = document.querySelector(
                "#variationsindex .ant-table-body"
            );
            const selectedRow = document.querySelector(
                "#variationsindex .ant-table-tbody > tr.ant-table-row-selected"
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

        const onCloseAddEdit = () => {
            crudVariables.onCloseAddEdit();
        };

        const setUrlData = () => {
            crudVariables.table.pagination = {
                ...crudVariables.table.pagination,
                pageSize: 100,
                current: 1,
            };

            const filterValue = encodeURIComponent("parent_id eq null");

            crudVariables.tableUrl.value = {
                url: `variations?fields=id,xid,name,parent_id,x_parent_id,subVariations{id,xid,parent_id,x_parent_id,name}&filters=${filterValue}`,
            };

            crudVariables.table.filterableColumns = filterableColumns;

            // IMPORTANT
            crudVariables.crudUrl.value = "variations";

            crudVariables.langKey.value = "variation";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];
        };

        onMounted(async () => {
            setUrlData();

            crudVariables.fetch({
                page: 1,
            });

            window.addEventListener("keydown", handleKeyDown);

            await nextTick();

        if (searchInputRef.value) {
            searchInputRef.value.focus();
        }
        });
        onBeforeUnmount(() => {
            window.removeEventListener("keydown", handleKeyDown);
        });
        const handleKeyDown = (event) => {
            if (crudVariables.addEditVisible.value) return;

            // F2 → Add
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
        watch(
            () => crudVariables.addEditVisible.value,
            async (visible) => {
                if (!visible) {
                    await nextTick();
                    searchInputRef.value?.focus();
                }
            }
        );
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
            columns,
            ...crudVariables,
            filterableColumns,
            permsArray,
            searchInputRef,
            isSearchFocused,
            onRowSelectChange,
            onCloseAddEdit
        };
    },
};
</script>
<style>
#variationsindex .ant-table-thead > tr > th,
#variationsindex .ant-table-tbody > tr > td {
    padding: 2px !important;
}
</style>