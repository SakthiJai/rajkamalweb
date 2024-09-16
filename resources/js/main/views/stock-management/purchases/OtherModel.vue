<template>
    <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @ok="onSubmit" @close="handleClose"
        class="otherpopus">
        <template v-slot:title>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                Other Info
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
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="12" :lg="12">

                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.freeze_upto')" name="stock.freeze_upto"
                            :help="rules.freeze_upto ? rules.freeze_upto.message : null"
                            :validateStatus="rules.freeze_upto ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <DateTimePicker :dateTime="formData.freeze_upto" @dateTimeChanged="(changedDateTime) =>
                            (formData.freeze_upto = changedDateTime)
                            " />
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.ledger_category')" name="stock.ledger_category"
                            :help="rules.ledger_category ? rules.ledger_category.message : null"
                            :validateStatus="rules.ledger_category ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select v-model:value="formData.ledger_category" class="balance">
                            <a-select-option key="1" value="1" aria-selected="true">
                                Others
                            </a-select-option>
                            <a-select-option key="2" value="2" aria-selected="true">
                                Retailer
                            </a-select-option>
                            <a-select-option key="3" value="3" aria-selected="true">
                                Stockist
                            </a-select-option>
                            <a-select-option key="4" value="4" aria-selected="true">
                                Distributors
                            </a-select-option>
                        </a-select>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.other_status')" name="stock.other_status"
                            :help="rules.other_status ? rules.other_status.message : null"
                            :validateStatus="rules.other_status ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select v-model:value="formData.other_status" class="balance">
                            <a-select-option key="1" value="1" aria-selected="true">
                                Active
                            </a-select-option>
                            <a-select-option key="2" value="2" aria-selected="true">
                                De-Active
                            </a-select-option>
                        </a-select>
                    </a-col>
                </a-row>

                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.other_visibility')" name="stock.other_visibility"
                            :help="rules.other_visibility ? rules.other_visibility.message : null"
                            :validateStatus="rules.other_visibility ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select v-model:value="formData.other_visibility" class="balance">
                            <a-select-option key="1" value="1" aria-selected="true">
                                Show
                            </a-select-option>
                            <a-select-option key="2" value="2" aria-selected="true">
                                Hide
                            </a-select-option>
                        </a-select>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.other_prohibited')" name="stock.other_prohibited"
                            :help="rules.other_prohibited ? rules.other_prohibited.message : null"
                            :validateStatus="rules.other_prohibited ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select v-model:value="formData.other_prohibited" class="balance">
                            <a-select-option key="1" value="1" aria-selected="true">
                                Yes
                            </a-select-option>
                            <a-select-option key="2" value="2" aria-selected="true">
                                No
                            </a-select-option>
                        </a-select>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="8" :lg="8">
                        <a-form-item :label="$t('stock.other_color')" name="stock.other_color"
                            :help="rules.other_color ? rules.other_color.message : null"
                            :validateStatus="rules.other_color ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select v-model:value="formData.other_color" class="balance">
                            <a-select-option key="1" value="1" aria-selected="true">
                                Defalut
                            </a-select-option>
                            <a-select-option key="2" value="2" aria-selected="true">
                                RED
                            </a-select-option>
                            <a-select-option key="3" value="3" aria-selected="true">
                                BLUE
                            </a-select-option>
                            <a-select-option key="4" value="4" aria-selected="true">
                                GREEN
                            </a-select-option>
                            <a-select-option key="5" value="5" aria-selected="true">
                                PURPLE
                            </a-select-option>
                        </a-select>
                    </a-col>
                </a-row>


            </a-col>

            <a-col :xs="24" :sm="24" :md="12" :lg="12" class="info">
                <fieldset>
                    <legend class="basicdetalis">
                        Note
                    </legend>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-row :gutter="16">
                                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                                    <a-form-item name="address" :help="rules.address ? rules.address.message : null"
                                        :validateStatus="rules.address ? 'error' : null">
                                        <a-textarea v-model:value="formData.address" :rows="4" />
                                    </a-form-item>
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                </fieldset>


                <div>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <table class="icon-table">
                                <tr class="fileuploads">
                                    <td colspan="2">
                                        <span class="icon-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30"
                                                height="30" aria-label="ERP Icon">
                                                <path
                                                    d="M12 2L2 7h20L12 2zm0 1.4l6.6 3.6H5.4L12 3.4zm9 5.2v9.4L12 22l-9-4.6V8.6l9 4.6 9-4.6zm-1.2 1.8l-7.8 4-7.8-4v7.2l7.8 4 7.8-4V10.4z">
                                                </path>
                                            </svg>
                                            <h2 class="title">Merg ERP 9+</h2>
                                        </span>
                                    </td>
                                </tr>
                                <tr class="fileuploads">
                                    <td>
                                        <span class="icon-wrapper" @click="triggerFileUpload">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30"
                                                height="30" aria-label="Edit Icon">
                                                <path
                                                    d="M3 21v-3l11-11 3 3L6 21H3zm15.5-13.5l-3-3 1.5-1.5c.4-.4 1-.4 1.4 0l1.6 1.6c.4.4.4 1 0 1.4L18.5 7.5z">
                                                </path>
                                            </svg>
                                            <p class="editicons">Edit</p>
                                        </span>
                                        <input type="file" ref="fileInput" @change="handleFileUpload"
                                            style="display: none" accept="image/*">
                                    </td>
                                    <td>
                                        <span class="icon-wrapper" @click="deleteFile">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-6"
                                                style="width:24px;height:24px; margin-top:-12px;">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <p class="deleteicons">Delete</p>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <!-- Display the uploaded image -->
                            <div v-if="imageUrl">
                                <img :src="imageUrl" alt="Uploaded Image"
                                    style="max-width: 100%; height: auto; margin-top: 20px;">
                            </div>
                        </a-col>
                    </a-row>
                </div>

            </a-col>
        </a-row>
        <fieldset class="tabed">
            <legend class="basicdetalis">
                <input type="checkbox" class="checkboxing" id="jack" value="Jack" v-model="checkedNames" />
                Invoice Format:
            </legend>

            <a-form layout="vertical">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                        <a-form-item :label="$t('stock.invoice_format')" name="stock.invoice_format"
                            :help="rules.invoice_format ? rules.invoice_format.message : null"
                            :validateStatus="rules.invoice_format ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="6" :lg="6">
                        <a-select v-model:value="formData.default" class="balance" @change="onDefaultChange">
                            <a-select-option key="1" value="1">
                                Default
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                Select
                            </a-select-option>
                        </a-select>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="6" :lg="6">
                        <a-select v-model:value="formData.dmf" class="balance" :disabled="isCentralDisabled">
                            <a-select-option key="1" value="1">
                                DMF
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                GUI
                            </a-select-option>
                        </a-select>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="9" :lg="9">
                        <a-select v-model:value="formData.central" class="balance" :disabled="isCentralDisabled">
                            <a-select-option key="1" value="1">
                                CL Landscape Central
                            </a-select-option>
                            <a-select-option key="2" value="2">
                                NT Landscape Local with Net Amount
                            </a-select-option>
                        </a-select>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="3" :lg="3">
                        <a-form-item :label="$t('stock.stock_export')" name="stock.stock_export"
                            :help="rules.stock_export ? rules.stock_export.message : null"
                            :validateStatus="rules.stock_export ? 'error' : null">
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select v-model:value="formData.gst_standard" class="balance">
                            <a-select-option key="1" value="1" aria-selected="true">
                                Excel
                            </a-select-option>
                            <a-select-option key="2" value="2" aria-selected="true">
                                GST Standard
                            </a-select-option>


                        </a-select>
                    </a-col>
                </a-row>
            </a-form>
        </fieldset>

        <template #footer class="floats">
            <div class="floats">
                <!-- <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
                <SaveOutlined />
                {{ addEditType == "add" ? $t("common.submit") : $t("common.update") }}
            </a-button> -->

                <button type="button" id="btn-Ledger" title="Ledger"
                    class="btn default-btn ng-star-inserted saving"><span class="box"><span
                            class="shortcut ng-star-inserted"><code>F10</code></span><span class="ng-star-inserted">Save
                            <SaveOutlined />
                        </span></span><span class="effect"></span></button>

            </div>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue"


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
        DateTimePicker,

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
                ledger_category: '1',
                other_status: '1',
                other_visibility: '1',
                Primary_limit: '0.00',
                credit_limit: '0.00',
                credit_Action: '1',
                other_prohibited: '2',
                other_color: '1',
                dmf: '1',
                central: '1',
                defalut: '1',
                gst_standard: "1",
                imageUrl: null,
            },

            isDMFDisabled: true,
            isCentralDisabled: true,
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Other Info',
            successMessage: 'Operation successful!',
            buttonStyle: {
                backgroundColor: '', // Initially no background color
                borderColor: '',
                color: '', // Initially no text color
            },
        };
    },
    methods: {
        triggerFileUpload() {
            this.$refs.fileInput.click(); // Programmatically trigger the file input click
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result; // Set the image URL to the result of the FileReader
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        },
        deleteFile() {
            this.imageUrl = null; // Clear the image URL when deleting
            this.$refs.fileInput.value = ''; // Reset the file input
        },
        showModals() {
            this.isModalVisible = true;
        },
        handleCloses() {
            this.isModalVisibles = false;
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
        },

        onDefaultChange() {

            this.isDMFDisabled = this.formData.default !== '1';
            this.isCentralDisabled = this.formData.default !== '2';
        },
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
.otherpopus {
    width: 70% !important;
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

.indiannumber {
    padding: 4px 7px !important;
    background: #f6f6f6;
    border-color: #c2c2c2;
    color: black;
}

.balanceing {
    width: 234px;
}

.merge {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;

    border: 1px solid #9a9a9a;
    margin-top: 21px;
}

.icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 6px;
}

.title {
    margin-left: 10px;
    font-size: 1.2em;
}

.icon-table {
    width: 100%;
    margin-top: 12px;
}

.fileuploads {
    background-color: #f6f6f6;
}
.editicons{
    margin-bottom: 1px;
}
.deleteicons{
    margin-bottom: 10px;
}
</style>