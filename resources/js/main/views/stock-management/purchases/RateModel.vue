<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @ok="onSubmit" class="discounts">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>{{ pageTitle }}</span>
                <button @click="onClose" type="button" aria-label="Close" class="ant-modal-close" id="closeicon">
                    <span class="ant-modal-close-x"><span role="img" aria-label="close"
                            class="anticon anticon-close ant-modal-close-icon">
                            <svg focusable="false" data-icon="close" width="1em" height="1em" fill="currentColor"
                                aria-hidden="true" fill-rule="evenodd" viewBox="64 64 896 896">
                                <path
                                    d="M799.86 166.31c.02 0 .04.02.08.06l57.69 57.7c.04.03.05.05.06.08a.12.12 0 010 .06c0 .03-.02.05-.06.09L569.93 512l287.7 287.7c.04.04.05.06.06.09a.12.12 0 010 .07c0 .02-.02.04-.06.08l-57.7 57.69c-.03.04-.05.05-.07.06a.12.12 0 01-.07 0c-.03 0-.05-.02-.09-.06L512 569.93l-287.7 287.7c-.04.04-.06.05-.09.06a.12.12 0 01-.07 0c-.02 0-.04-.02-.08-.06l-57.69-57.7c-.04-.03-.05-.05-.06-.07a.12.12 0 010-.07c0-.03.02-.05.06-.09L454.07 512l-287.7-287.7c-.04-.04-.05-.06-.06-.09a.12.12 0 010-.07c0-.02.02-.04.06-.08l57.7-57.69c.03-.04.05-.05.07-.06a.12.12 0 01.07 0c.03 0 .05.02.09.06L512 454.07l287.7-287.7c.04-.04.06-.05.09-.06a.12.12 0 01.07 0z">
                                </path>
                            </svg>
                        </span>
                    </span>
                </button>
            </div>
        </template>
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.stock_mrp')" name="stock.stock_mrp"
                                :help="rules.stock_mrp ? rules.stock_mrp.message : null"
                                :validateStatus="rules.stock_mrp ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">

                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.stock_rename')" name="stock.stock_rename"
                                :help="rules.stock_rename ? rules.stock_rename.message : null"
                                :validateStatus="rules.stock_rename ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-input v-model:value="formData.stock_rename"
                                :style="{ backgroundColor: accountGroupColor }"
                                @focus="changeColorOnFocus('accountGroup')" @blur="resetColorOnBlur('accountGroup')" />
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_a')" name="stock.rate_a"
                                :help="rules.rate_a ? rules.srate_a.message : null"
                                :validateStatus="rules.rate_a ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.stock_rename')" name="stock.rename_a"
                                :help="rules.rename_a ? rules.rename_a.message : null"
                                :validateStatus="rules.rename_a ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <a-input v-model:value="formData.rename_a" :style="{ backgroundColor: accountGroupColor }"
                                @focus="changeColorOnFocus('accountGroup')" @blur="resetColorOnBlur('accountGroup')" />
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_b')" name="stock.rate_b"
                                :help="rules.rate_b ? rules.rate_b.message : null"
                                :validateStatus="rules.rate_b ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_b" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                    Yes
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    No
                                </a-select-option>
                            </a-select>
                        </a-col>
                        <a-col v-if="formData.rate_b === '1'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.rename_b')" name="stock.rename_b"
                                :help="rules.rename_b ? rules.rename_b.message : null"
                                :validateStatus="rules.rename_b ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_b === '1'" v-model:value="formData.rename_b" />
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_c')" name="stock.rate_c"
                                :help="rules.rate_c ? rules.rate_c.message : null"
                                :validateStatus="rules.rate_c ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_c" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                   No
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    Yes
                                </a-select-option>
                            </a-select>
                        </a-col>
                        <a-col v-if="formData.rate_c === '2'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.rename_c')" name="stock.rename_c"
                                :help="rules.rename_b ? rules.rename_b.message : null"
                                :validateStatus="rules.rename_b ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_c === '2'" v-model:value="formData.rename_c" />
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_d')" name="stock.rate_b"
                                :help="rules.rate_b ? rules.rate_d.message : null"
                                :validateStatus="rules.rate_b ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_d" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                    Yes
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    No
                                </a-select-option>
                            </a-select>
                        </a-col>

                        <a-col v-if="formData.rate_d === '1'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.rename_d')" name="stock.rename_d"
                                :help="rules.rename_d ? rules.rename_d.message : null"
                                :validateStatus="rules.rename_d ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_d === '1'" v-model:value="formData.rename_d" />
                        </a-col>
                    </a-row>


                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_e')" name="stock.rate_b"
                                :help="rules.rate_e ? rules.rate_e.message : null"
                                :validateStatus="rules.rate_e ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_e" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                    Yes
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    No
                                </a-select-option>
                            </a-select>
                        </a-col>
                        <a-col v-if="formData.rate_e === '1'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.renames')" name="stock.renames"
                                :help="rules.renames ? rules.renames.message : null"
                                :validateStatus="rules.renames ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_e === '1'" v-model:value="formData.renames" />
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_f')" name="stock.rate_f"
                                :help="rules.rate_f ? rules.rate_d.message : null"
                                :validateStatus="rules.rate_f ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_f" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                    Yes
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    No
                                </a-select-option>
                            </a-select>
                        </a-col>

                        <a-col v-if="formData.rate_f === '1'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.rename_f')" name="stock.rename_f"
                                :help="rules.rename_f ? rules.rename_f.message : null"
                                :validateStatus="rules.rename_f ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_f === '1'" v-model:value="formData.rename_f" />
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_g')" name="stock.rate_g"
                                :help="rules.rate_g ? rules.rate_d.message : null"
                                :validateStatus="rules.rate_g ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_g" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                    Yes
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    No
                                </a-select-option>
                            </a-select>
                        </a-col>

                        <a-col v-if="formData.rate_g === '1'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.rename_g')" name="stock.rename_g"
                                :help="rules.rename_g ? rules.rename_g.message : null"
                                :validateStatus="rules.rename_g ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_g === '1'" v-model:value="formData.rename_g" />
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.rate_h')" name="stock.rate_h"
                                :help="rules.rate_h ? rules.rate_h.message : null"
                                :validateStatus="rules.rate_h ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-select v-model:value="formData.rate_h" class="balance">
                                <a-select-option key="1" value="1" aria-selected="true">
                                    Yes
                                </a-select-option>
                                <a-select-option key="2" value="2" aria-selected="true">
                                    No
                                </a-select-option>
                            </a-select>
                        </a-col>

                        <a-col v-if="formData.rate_h === '1'" :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('stock.rename_h')" name="stock.rename_h"
                                :help="rules.rename_h ? rules.rename_h.message : null"
                                :validateStatus="rules.rename_h ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <!-- Conditionally render the input field based on formData.rate_b -->
                            <a-input v-if="formData.rate_h === '1'" v-model:value="formData.rename_h" />
                        </a-col>
                    </a-row>

                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <div>
                <!-- <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
                <SaveOutlined />
                {{ addEditType == "add" ? $t("common.submit") : $t("common.update") }}
            </a-button> -->

                <button type="button" id="btn-Ledger" title="Ledger"
                    class="btn default-btn ng-star-inserted saving"><span class="box"><span
                            class="shortcut ng-star-inserted"><code>F10</code></span><span class="ng-star-inserted">Save
                            <SaveOutlined />
                        </span></span><span class="effect"></span></button>
                <!-- <a-button key="back" @click="onClose">
                    {{ $t("common.cancel") }}
                </a-button> -->
            </div>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
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
            headers: ["Ledger Name", "Station", "₹Balance", "Action"],
            items: [
                { name: "John Doe", age: 28, email: "john.doe@example.com" },
                { name: "Jane Smith", age: 34, email: "jane.smith@example.com" },
                // Add more items as needed
            ],
            isModalVisible: false,
            formData: {
                sales_name: '1',
                item_discount: '0.00',
                exp_discount: '0.00',
                stock_rename: 'M.R.P',
                rename_a: 'Sale Rate',
                sale: '1',
                rate_b: '1',
                rename_b: 'Rate-B',
                rate_c: '1',
                rename_c: 'Rate-C',
                rate_d: '1',
                rename_d: 'Rate-D',
                rate_e: '2',
                renames: 'Rate-E',
                rate_f: '1',
                rename_f: 'Rate-F',
                rate_g: '1',
                rename_g: 'Rate-G',
                rate_h: '1',
                rename_h: 'Rate-H',
                

            },
            rules: {
                rate_b: null, 
                rename_b: null,
                rate_c: null, 
                rename_c: null,
                rate_d: null, 
                rename_d: null,
                rate_e: null, 
                renames: null,
                rate_f: null, 
                rename_f: null,
                rate_g: null, 
                rename_g: null,
                rate_h: null, 
                rename_h: null,
            },
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Rate Info Settings',
            successMessage: 'Operation successful!',
            buttonStyle: {
                backgroundColor: '', // Initially no background color
                borderColor: '',
                color: '', // Initially no text color
            },
        };
    },
    methods: {
        showModal() {
            this.isModalVisible = true;
        },
        handleClose() {
            this.isModalVisible = false;
            // Reset button color when closing
            this.buttonStyle = {
                backgroundColor: '',
                borderColor: '',
                color: '',
            };
        },
        handleSuccess(xid) {
            // Handle success logic
            this.isModalVisible = false;
            console.log('Success:', xid);
        },
        handleKeydown(event) {
            if (event.key === 'F2') {
                // Change button color
                this.buttonStyle = {
                    backgroundColor: 'green',
                    borderColor: 'green',
                    color: 'white',
                };
                setTimeout(() => {
                    this.isModalVisible = true;
                }, 500);
            } else if (event.key === 'Escape') {
                this.handleClose();
            }
        }
    },
    mounted() {
        window.addEventListener('keydown', this.handleKeydown);
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.handleKeydown);
    }
});
</script>

<style>
.popups {
    width: 80% !important;
    /*vertical-align: top !important;
    top:15px !important;*/
}

.ant-modal-footer {
    background-color: #f6f6f6 !important;
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

:where(.css-dev-only-do-not-override-wosfq4).ant-form-item .ant-form-item-control-input {
    min-height: 4px !important;
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
    padding: .01rem .5rem;
    display: flex;
    align-items: center;
}

button.btn {
    padding: .375rem .3rem;
    border: none;
    border: solid rgba(0, 0, 0, .2);
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

.ant-modal-footer {
    background-color: #fbfbfb !important;
}

.discounts {
    width: 35% !important;
}

.saving {
    background-color: #1f6d70 !important;
    color: white !important;
}

#closeicon {
    top: 14px !important;
}
</style>