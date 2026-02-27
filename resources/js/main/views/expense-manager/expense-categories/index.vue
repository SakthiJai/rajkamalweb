<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.expense_categories`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`Expenses`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`Expense Category List`) }}
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
                                    permsArray.includes(
                                        'expense_categories_create'
                                    ) || permsArray.includes('admin')
                                "
                            >
                                <a-button type="primary" @click="addItem"  style="background-color: #1f6d70;">
                                    <PlusOutlined />
                                    {{ $t("Add New Expense Category") }} / F2
                                </a-button>
                            </template>
                            <!-- <a-button
                                v-if="
                                    table.selectedRowKeys.length > 0 &&
                                    (permsArray.includes(
                                        'expense_categories_delete'
                                    ) ||
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
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="10">
                                <a-input-group compact>
                                    <a-select
                                        style="width: 25%"
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
                                        style="width: 75%"
                                        v-model:value="table.searchString"
                                        show-search
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
                                        category_name: record.xid,
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
                                    <template
                                        v-if="column.dataIndex === 'action'"
                                    >
                                        <a-button
                                            v-if="
                                                permsArray.includes(
                                                    'expense_categories_edit'
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
import { onMounted, nextTick, onBeforeUnmount, ref, watch } from "vue";
import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AddEdit from "./AddEdit.vue";

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
        const { addEditUrl, initData, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const { permsArray } = common();
        const selectedRowIndex = ref(-1);
        const searchInputRef = ref(null);
        const isSearchFocused = ref(false);

onMounted(async () => {
    crudVariables.tableUrl.value = {
        url: "expense-categories?fields=id,xid,name,description",
    };
    crudVariables.table.filterableColumns = filterableColumns;

    crudVariables.fetch({
        page: 1,
    });

    crudVariables.crudUrl.value = addEditUrl;
    crudVariables.langKey.value = "expense_category";
    crudVariables.initData.value = { ...initData };
    crudVariables.formData.value = { ...initData };

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

        if (selectedRowIndex.value < data.length - 1) {
            selectedRowIndex.value++;
        }

        const row = data[selectedRowIndex.value];
        crudVariables.table.selectedRowKeys = [row.xid];
    }

    // Arrow Up
    if (event.key === "ArrowUp") {
        event.preventDefault();
        if (data.length === 0) return;

        if (selectedRowIndex.value > 0) {
            selectedRowIndex.value--;
        }

        const row = data[selectedRowIndex.value];
        crudVariables.table.selectedRowKeys = [row.xid];
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
const onRowSelectChange = (selectedKeys) => {
    crudVariables.table.selectedRowKeys = selectedKeys;

    if (selectedKeys.length > 0) {
        selectedRowIndex.value = crudVariables.table.data.findIndex(
            (row) => row.xid === selectedKeys[0]
        );
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

        return {
            columns,
            addEditUrl, 
            ...crudVariables,
            filterableColumns,
            permsArray,
            searchInputRef,
            isSearchFocused,
            onRowSelectChange,
        };
    },
};
</script>
