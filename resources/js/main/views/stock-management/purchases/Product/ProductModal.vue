<template>
    <AddProduct
        v-if="isAddProductModalVisible"
        :visible="isAddProductModalVisible"
        :formData="formData"
        :url="url"
        :addEditType="addEditType"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        @addEditSuccess="handleSuccess"
        @closed="handleClose"
    />
    <a-modal
        :open="visible"
        :width="drawerWidth"
        :closable="false"
        :centered="true"
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
                <span>Select Item</span>
                <div @keydown="onKeydown">
                    <a-button
                        :style="buttonStyle"
                        @click="showAddProductModal"
                        class="closing"
                    >
                        <PlusOutlined />
                        Create/F2
                    </a-button>
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
        </template>
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        name="party_name_search"
                        @keydown="test"
                        ref="dummykeyboard"
                        class="required"
                    >
                        <a-input-search
                            ref="searchInput"
                            style="width: 100%"
                            placeholder="Search here.."
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item name="sales_names">
                        <a-select v-model:value="formData.sales_names">
                            <a-select-option key="1" value="1">
                                Description
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Any Where
                            </a-select-option>
                            <a-select-option key="3" value="3">
                                Top Sales
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <admin-page-table-content>
            <a-row>
                <a-col :span="24">
                    <div class="table-responsive">
                        <a-table
                            :columns="columns"
                            :row-key="(record) => record.id"
                            :data-source="table.data"
                            :pagination="table.pagination"
                            :loading="table.loading"
                            :rowSelection="{
                                selectedRowKeys: selectedRowKeysValue,
                                onChange: onSelectChange,
                                hideDefaultSelections: true,
                                selections: true,
                                type: 'radio',
                            }"
                            bordered
                            size="middle"
                        >
                            <template
                                #bodyCell="{ column, record }"
                                v-for="(item, index) in items"
                                :key="index"
                                :class="{ highlight: index === selectedIndex }"
                                @click="selectProduct(record)"
                            >
                                <template v-if="column.dataIndex === 'id'">
                                    <a-badge>
                                        {{ record.product.name }}
                                    </a-badge>
                                </template>

                                <!-- <template v-if="column.dataIndex === 'name'">
                                    <a-typography-text
                                        type="success"
                                        strong
                                        @click.stop="selectProduct(record)"
                                    >
                                        +{{ record.name }}
                                    </a-typography-text>
                                </template>

 -->

                                <template v-if="column.dataIndex === 'name'">
                                    <a-typography-text
                                        :type="
                                            record.name === formData.name
                                                ? 'success'
                                                : ''
                                        "
                                        strong
                                        @click.stop="selectProduct(record)"
                                    >
                                        +{{ record.name }}
                                    </a-typography-text>
                                </template>

                                <template v-if="column.dataIndex === 'station'">
                                    <a-typography-text
                                        v-if="record.adjustment_type == 'add'"
                                        type="success"
                                        strong
                                    >
                                        +{{ record.packing }}
                                    </a-typography-text>
                                </template>
                                <template
                                    v-if="
                                        column.dataIndex === 'opening_balance'
                                    "
                                >
                                    <a-typography-text
                                        v-if="record.adjustment_type == 'add'"
                                        type="success"
                                        strong
                                    >
                                        +{{ record.opening_balance }}
                                    </a-typography-text>
                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button
                                        v-if="
                                            permsArray.includes(
                                                'stock_adjustments_edit'
                                            ) || permsArray.includes('admin')
                                        "
                                        type="primary"
                                        @click="editItem(record)"
                                        style="margin-left: 4px"
                                    >
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                    <a-button
                                        v-if="
                                            permsArray.includes(
                                                'stock_adjustments_delete'
                                            ) || permsArray.includes('admin')
                                        "
                                        type="primary"
                                        @click="showDeleteConfirm(record.xid)"
                                        style="margin-left: 4px"
                                    >
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                    </a-button>
                                </template>
                            </template>
                        </a-table>
                    </div>
                </a-col>
            </a-row>
        </admin-page-table-content>
        <!--- end-->

        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="5" :lg="5">
                <fieldset style="height: 167px">
                    <legend class="font-style-in-prouct-model">
                        Purchase Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            M.R.P
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.mrp }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Pur. Rate
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.purchase_rate }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Cost
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.cost }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Cost+Tax
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.cost }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Pur. Disc
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.discount }}</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="5" :lg="5">
                <fieldset style="height: 167px">
                    <legend class="font-style-in-prouct-model">
                        Sale Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Rate
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.sale_rate }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Margin
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.margin }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Deal Free
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.deal_free }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            W/o Deal
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ </span>
                            <span>{{ formData.w_o_free }}</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
            <a-col :xs="24" :sm="24" :md="5" :lg="5">
                <fieldset style="height: 167px">
                    <legend class="font-style-in-prouct-model">Tax Info</legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            HSN/SAC
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.hsc_sac }}</span>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            IGST %
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.igst }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            CGST %
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.cgst }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            SGST %
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>₹ {{ formData.sgst }}</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>

            <a-col :xs="9" :sm="9" :md="9" :lg="9">
                <fieldset style="height: 167px">
                    <legend class="font-style-in-prouct-model">
                        Other Info
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Company
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>{{ formData.name }}</span>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            Mfr.
                        </a-col>
                        <a-col :xs="1" :sm="1" :md="1" :lg="1">
                            <span>:</span>
                        </a-col>
                        <a-col :xs="10" :sm="10" :md="10" :lg="10">
                            <span>{{ formData.mfr_name }}</span>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
        </a-row>
        <template #footer class="floats">
            <div class="floats">
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
        </template>
    </a-modal>
</template>

<script>
import { onMounted, watch } from "vue";
import { defineComponent } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    EditOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../../views/users/StaffAddButton.vue";
import LedgerModel from "./../LedgerModel.vue";
import EnterprisesModel from "./../EnterprisesModel.vue";
import fields from "./../Product/fields";
import AddProduct from "./../Product/AddProduct.vue";
import crud from "../../../../../common/composable/crud";
import common from "../../../../../common/composable/common";
export default defineComponent({
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
        StaffMemberAddButton,
        DeleteOutlined,
        EditOutlined,
        LedgerModel,
        EnterprisesModel,
        AddProduct,
    },

    setup(props, { emit }) {
        const { url, addEditUrl, hashableColumns, initData, columns, filters } =
            fields();
        const crudVariables = crud();
        const { permsArray, selectedWarehouse } = common();

        onMounted(() => {
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "expense_category";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];

            reFetchDatatable();
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

        const onClose = () => {
            emit("closed");
        };
        const onSelectChange = (changableRowKeys) => {
            // selectedRowKeysValue=[];
            console.log("selectedRowKeys changed: ", changableRowKeys);
            //selectedRowKeys.value = changableRowKeys;
            selectedRowKeysValue = changableRowKeys;
            console.log("selectedRowKeys changed: ", selectedRowKeysValue);
        };
        let selectedRowKeysValue = [];
        watch(selectedWarehouse, (newVal, oldVal) => {
            reFetchDatatable();
        });

        return {
            columns,
            permsArray,
            ...crudVariables,
            onClose,
            filters,
            reFetchDatatable,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },

    data() {
        return {
            headers: ["Ledger Name", "Station", "₹ Balance", "Actions"],
            items: [
                {
                    name: "Waste Furniture",
                    location: "Bangalore",
                    value: "2,000.00 Cr",
                },
                {
                    name: "Recycled Paper",
                    location: "Delhi",
                    value: "1,500.00 Cr",
                },
                {
                    name: "Scrap Metal",
                    location: "Mumbai",
                    value: "3,000.00 Cr",
                },
            ],
            formData: { id: 0, name: "" },
            selectedIndex: 0,
            focus: null,
            isAddProductModalVisible: false,
            isEnterModal: false,
            isLoading: false,
            dialog: false,
            formData: {
                sales_names: "1",
            },

            url: "your-url-here",
            addEditType: "add",
            pageTitle: "Select Party",
            successMessage: "Operation successful!",
            buttonStyle: {
                backgroundColor: "", // Initially no background color
                borderColor: "",
                color: "", // Initially no text color
            },
            buttonStyle: {},
        };
    },

    mounted() {
        // document.addEventListener('keydown', this.handleKeyDown);
        document.addEventListener("keyup", this.handleKeyDown);
        this.autoFocusInput();
    },
    beforeDestroy() {
        document.removeEventListener("keydown", this.handleKeyDown);
    },

    methods: {
        editProduct(id) {
            console.log("Edit product with ID:", id);
            // Implement your edit logic here
        },
        deleteProduct(id) {
            console.log("Delete product with ID:", id);
        },

        customRow(record) {
            return {
                onClick: (event) => {
                    this.rowSelection = event;
                    //console.log('record', record, 'event', record.party_name);
                },
            };
        },

        // selectProduct(product) {
        //     this.formData = product;
        // },

        // test: function (event) {
        //     console.log("focus");
        //     switch (event.keyCode) {
        //         case 38:
        //             document
        //                 .getElementsByClassName("ant-radio-input")
        //                 [this.focus].click();
        //             const temp = document
        //                 .getElementsByClassName("ant-radio-input")
        //                 [this.focus].closest("tr");
        //             console.log("up=>", temp);
        //             console.log(
        //                 "up=>",
        //                 temp
        //                     .getElementsByTagName("td")[1]
        //                     .innerHTML.replace(/<[^>]*>?/gm, "")
        //             );
        //             this.formData.id =
        //                 temp.getAttribute("data-row-key");
        //             this.formData.name = temp
        //                 .getElementsByTagName("td")[1]
        //                 .innerHTML.replace(/<[^>]*>?/gm, "");

        //             if (this.focus === null) {
        //                 this.focus = 0;
        //             } else if (this.focus > 0) {
        //                 this.focus--;
        //             }
        //             break;
        //         case 40:
        //             if (this.focus === null) {
        //                 this.focus = 0;
        //             } else if (this.focus < this.items.length - 1) {
        //                 this.focus++;
        //             }
        //             document
        //                 .getElementsByClassName("ant-radio-input")
        //                 [this.focus].click();
        //             const temp1 = document
        //                 .getElementsByClassName("ant-radio-input")
        //                 [this.focus].closest("tr");
        //             console.log("down=>", temp1.getAttribute("data-row-key"));
        //             this.formData.id =
        //                 temp1.getAttribute("data-row-key");
        //             this.formData.name = temp1
        //                 .getElementsByTagName("td")[1]
        //                 .innerHTML.replace(/<[^>]*>?/gm, "");
        //             this.formData.packing = temp1
        //                 .getElementsByTagName("td")[2]
        //                 .innerHTML.replace(/<[^>]*>?/gm, "");
        //             this.formData.quantity = temp1
        //                 .getElementsByTagName("td")[3]
        //                 .innerHTML.replace(/<[^>]*>?/gm, "");
        //             this.formData.single_unit_price = temp1
        //                 .getElementsByTagName("td")[5]
        //                 .innerHTML.replace(/<[^>]*>?/gm, "");
        //             this.$emit("child-method", this.formData);
        //             console.log(this.table.data);
        //             break;
        //     }
        // },

        test(event) {
            switch (event.keyCode) {
                case 38: // Arrow up
                    if (this.focus === null) {
                        this.focus = 0;
                    } else if (this.focus > 0) {
                        this.focus--;
                    }
                    this.updateSelection();
                    break;
                case 40: // Arrow down
                    if (this.focus === null) {
                        this.focus = 0;
                    } else if (this.focus < this.table.data.length - 1) {
                        this.focus++;
                    }
                    this.updateSelection();
                    break;
                case 46:
                    //this.autoFocusInput();
                    // this.showPopupModal();
                    break;
            }
        },

        updateSelection() {
            const currentRadioInput =
                document.getElementsByClassName("ant-radio-input")[this.focus];
            currentRadioInput.click();
            const currentRow = currentRadioInput.closest("tr");
            const selectedRowKey = currentRow.getAttribute("data-row-key");
            console.log("Selected Row Key:", selectedRowKey);

            // Update product model selection with basic info
            this.formData.id = selectedRowKey;
            this.formData.name = currentRow
                .getElementsByTagName("td")[1]
                .innerHTML.replace(/<[^>]*>?/gm, "");

            // Ensure table data is available
            if (Array.isArray(this.table.data) && this.table.data.length > 0) {
                this.table.data.forEach((row) => {
                    if (row.id === selectedRowKey || row.id == selectedRowKey) {
                        // Purchase Info
                        this.formData.mrp = row.mrp;
                        this.formData.purchase_rate = row.purchase_rate;
                        this.formData.cost = row.cost;
                        this.formData.costPlusTax = row.cost_plus_tax;
                        this.formData.discount = row.discount;

                        // Sale Info
                        this.formData.sale_rate = row.sale_rate;
                        this.formData.margin = row.margin;
                        this.formData.deal_free = row.deal_free;
                        this.formData.w_o_free = row.w_o_free;

                        // Tax Info
                        this.formData.hsnSac = row.hsn_sac;
                        this.formData.igst = row.igst;
                        this.formData.cgst = row.cgst;
                        this.formData.sgst = row.sgst;

                        // Other Info
                        this.formData.name = row.name;
                        this.formData.mfr_name = row.mfr_name;

                        console.log("Matched Row:", row);
                        console.log("Product Info:", this.formData);
                    }
                });
            } else {
                console.error("Table data is not an array or is empty.");
            }

            // Emit selected product details to parent
            this.$emit("child-method", this.formData);
            console.log(this.table.data);
        },

        showAddProductModal() {
            this.isAddProductModalVisible = true;
        },

        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.searchInput.focus(); // Automatically focus the input
            });
        },
        hideModal() {
            document.documentElement
                .querySelector(".ant-modal-close-x")
                .click();
            //this.isAddProductModalVisible = false;
        },

        EnterprisesModel() {
            this.isEnterModal = true;
            //this.isLoading = true;
        },
        handleClose() {
            this.isAddProductModalVisible = false;
            // Reset button color when closing
            this.buttonStyle = {
                backgroundColor: "",
                borderColor: "",
                color: "",
            };
        },
        handleSuccess(xid) {
            // Handle success logic
            this.isAddProductModalVisible = false;
            console.log("Success:", xid);
        },
        handleKeyDown(event) {
            console.log("key=>", event.key);
            if (event.key === "F2") {
                // Show loader
                this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                    this.buttonStyle = {
                        backgroundColor: "green",
                        borderColor: "green",
                        color: "white",
                    };
                    this.isAddProductModalVisible = true;
                }, 500);
            } else if (event.key === "F4") {
                this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                    this.isEnterModal = true;
                }, 500);
            } else if (event.key === "ArrowUp") {
                if (this.selectedIndex === null) {
                    this.selectedIndex = this.items.length - 1;
                } else {
                    this.selectedIndex =
                        (this.selectedIndex - 1 + this.items.length) %
                        this.items.length;
                }
                this.handleClose(); // Call this if necessary
            } else if (event.key === "ArrowDown") {
                if (this.selectedIndex === null) {
                    this.selectedIndex = 0;
                } else {
                    this.selectedIndex =
                        (this.selectedIndex + 1) % this.items.length;
                }
            } else if (event.key === "Escape" || event.key === "Esc") {
                // Hide the popup
                this.isAddProductModalVisible = false;
                this.isEnterModal = false;
                this.isLoading = false;
            } else if (event.key === "Enter") {
                console.log(
                    "key=>",
                    event.key,
                    document.documentElement.querySelector(".ant-modal-close-x")
                );
                document.documentElement
                    .querySelector(".ant-modal-close-x")
                    .click();
            }
        },
    },
});
</script>

<style>
.popups {
    width: 80% !important;
    /*vertical-align: top !important;
    top:15px !important;*/
}

.ant-modal-footer {
    background-color: #e4e4e4 important;
}

.ant-modal-body {
    background-color: #f6f6f6 !important;
}

.table-container {
    overflow-x: auto;
    margin: 0 auto;
}

.responsive-table {
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

.font-style-in-prouct-model {
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

.closing {
    margin-right: 44px;
}

#closeicon {
    top: 14px !important;
}

.highlight {
    background-color: #ffd451;
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
