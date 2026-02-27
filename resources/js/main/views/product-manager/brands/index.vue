<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.brands`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ brands_name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.product_manager`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.brands`) }}
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
                            permsArray.includes('brands_create') ||
                            permsArray.includes('admin')
                        "
                    >
                        <a-space>
                            <a-button type="primary" @click="addItem" style="background-color: #1f6d70;">
                                <PlusOutlined />
                                {{ $t("brand.add") }} / F2
                            </a-button>
                            <ImportBrands
                                :pageTitle="$t('brand.import_brands')"
                                :sampleFileUrl="sampleFileUrl"
                                importUrl="brands/import"
                                @onUploadSuccess="setUrlData"
                            />
                        </a-space>
                    </template>
                    <!-- <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('brands_delete') ||
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
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="8">
                        <a-input-group compact>
                            <a-select
                                style="width: 25%"
                                v-model:value="table.searchColumn"
                                :placeholder="
                                    $t('common.select_default_text', [''])
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
                                @focus="isSearchFocused = true"
                                @blur="isSearchFocused = false"
                                @change="onTableSearch"
                                @search="onTableSearch"
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
                                brands_name: record.xid,
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
                        <template #bodyCell="{ column, text, record }">
                            <template v-if="column.dataIndex === 'image_url'">
                                <a-image :width="32" :src="text" />
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('brands_edit') ||
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
                                        permsArray.includes('brands_delete') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
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
</template>
<script>
import { onMounted ,nextTick , onBeforeUnmount, ref, watch } from "vue";
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
import ImportBrands from "../../../../common/core/ui/Import.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
        ImportBrands,
    },
 setup() {
    const { addEditUrl, initData, columns, filterableColumns } = fields();
    const crudVariables = crud();
    const { permsArray } = common();
    const sampleFileUrl = window.config.brand_sample_file;

    const selectedRowIndex = ref(-1);
    const searchInputRef = ref(null);
    const isSearchFocused = ref(false);
    const onCloseAddEdit = () => {
        crudVariables.onCloseAddEdit();
    };
    const handleKeyDown = (event) => {
        if (crudVariables.addEditVisible.value) return;
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

        if (event.key === "ArrowDown") {
            event.preventDefault();
            if (data.length === 0) return;

            if (selectedRowIndex.value < data.length - 1) {
                selectedRowIndex.value++;
            }

            const row = data[selectedRowIndex.value];
            crudVariables.table.selectedRowKeys = [row.xid];
        }

        if (event.key === "ArrowUp") {
            event.preventDefault();
            if (data.length === 0) return;

            if (selectedRowIndex.value > 0) {
                selectedRowIndex.value--;
            }

            const row = data[selectedRowIndex.value];
            crudVariables.table.selectedRowKeys = [row.xid];
        }

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
        }
    };

const setUrlData = () => {
    crudVariables.tableUrl.value = {
        url: "brands?fields=id,xid,brands_name,slug,image,image_url",
    };

    crudVariables.crudUrl.value = "brands";

    crudVariables.langKey.value = "brand";
    crudVariables.initData.value = { ...initData };
    crudVariables.formData.value = { ...initData };

    crudVariables.fetch({ page: 1 });
};

    onMounted(async () => {
        setUrlData();
        window.addEventListener("keydown", handleKeyDown);

        await nextTick();

        searchInputRef.value?.focus();
    });
    onBeforeUnmount(() => {
        window.removeEventListener("keydown", handleKeyDown);
    });

    return {
        columns,
        filterableColumns,
        permsArray,
        ...crudVariables,
        onCloseAddEdit,
        sampleFileUrl,
        setUrlData,
        onRowSelectChange,
        searchInputRef,
        isSearchFocused,
    };
},
};
</script>
