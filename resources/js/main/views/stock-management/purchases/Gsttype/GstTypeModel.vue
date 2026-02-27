<template>
    <a-modal :open="visible" :closable="false" @close="handleClose" :centered="true" @ok="onSubmit"
         class="gstpopup">
        <template v-slot:title>
            <div style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                <span>GST </span>
            </div>
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
        </template>

        <!-- ledger modal-->
        <LedgerModel v-if="isLegerModalVisible" :visible="isLegerModalVisible" :formData="formData" :url="url"
            :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
            @addEditSuccess="handleSuccess" @closed="handleledger" />
        <!-- end of ledger modal-->
        <a-form>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">

                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-form-item :label="$t('stock.With_gst')" name="customers_visibility">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="2" :lg="2">
                            <a-radio-group v-model:value="formData.customers_visibility">
                                <a-radio :style="radioStyle" value="GST">
                                </a-radio>
                            </a-radio-group>
                        </a-col>

                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <a-form-item :label="$t('stock.Without_Gst')" name="customers_visibilitys">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <a-radio-group v-model:value="formData.customers_visibilitys" >
                                <a-radio :style="radioStyle" value="Non-GST" >
                                </a-radio>
                            </a-radio-group>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>

            <a-row v-if="formData.customers_visibility === 'GST'" :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('gst.gst_numers')" name="gst_numers">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="7" :lg="7">
                            <a-input name="gst_numers"></a-input>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="5" :lg="5">
                            <a-form-item :label="$t('gst.pan_numbers')" name="pan_numbers">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="7" :lg="7">
                            <a-input name="pan_numbers"></a-input>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </a-form>

        <template #footer>
            <button @click="previewpdf" type="submit" id="btn-Ledger" title="Ledger"
                class="btn default-btn ng-star-inserted gst">
                <span class="box"><span class="shortcut ng-star-inserted"><code>F8</code></span><span
                        class="ng-star-inserted">Submit</span>
                </span>
                <span class="effect"></span>
            </button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    WhatsAppOutlined,
    PrinterOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import { message, notification } from "ant-design-vue";
import LedgerModel from '../LedgerModel.vue';

export default defineComponent({

    props: [
        "formData",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "onClose",
        "onSubmit",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        PrinterOutlined,
        WhatsAppOutlined,
        LedgerModel,
    },

    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const onClose = () => {
            rules.value = {};
            emit("closed");
        };
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
            drawerWidth: window.innerWidth <= 991 ? "90%" : "67%",
        };
    },
    data() {
        return {
            isLocationModal: false,
            isModalVisible: false,
            isModalVisibles: false,
            isModalVisibleing: false,
            isLegerModalVisible: false,
            //business_type: "2",
            formData: {
                business_type: "1",
                customers_visibility: 'GST', 
            },

        };
    },

    mounted() {
        document.addEventListener('keydown', this.handleKeyDownPrint);
        this.autoFocusInput();

    },
    beforeDestroy() {
        //window.removeEventListener("keydown", this.handleleKeydown);
    },

    methods: {
        handleSuccess(xid) {
            this.isModalVisible = false;
            console.log("Success:", xid);
        },
        handleLocationClose() {
            this.isLocationModal = false;
        },
        handleledger(){
            this.isLegerModalVisible = false;
            this.formData.customers_visibilitys = '';
          
        },

        handleClose() {
            this.isModalVisible = false;
            //document.removeEventListener("keydown", this.handleKeydown);
        },
        previewpdf() {
            this.isLegerModalVisible = true;
        },
        handleRadioChange(e) {
      if (e.target.value === 'Non-GST') {
        this.isLegerModalVisible = true;
      }
    },
        handleCloses() {
            this.isModalVisibles = false;
            //document.removeEventListener("keydown", this.handleKeydown);
        },
        handleClosing() {
            this.isModalVisibleing = false;
        },
        sendWhatsAppMessage() {
            console.log("Sending WhatsApp message...");
            // ":/" use  the WhatsApp API
        },
        success(data) {
            javascipt: window.open('/storage/app/public/fun.pdf');
        },

        autoFocusInput() {
            this.$nextTick(() => {
                // Check if the element exists before calling focus()
                if (this.$refs.billingPrintSelect) {
                    this.$refs.billingPrintSelect.focus();
                }
            });
        },
    },
});
</script>

<style>
.compact-form-item {
    margin-bottom: 8px;
}

.compact-form-item .ant-form-item-label {
    margin-bottom: 4px;
}

.compact-form-item .ant-form-item-control {
    margin-top: 0;
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

#ledger {
    display: flex !important;
    flex-flow: revert !important;
    min-width: 0 !important;
}

.ant-modal-header {
    padding: 7px 10px !important;
}

.balance {
    width: 100% !important;
}

.opening {
    display: flex;
}

.selecting {
    height: 37px !important;
}

.buttonstype {
    background-color: #ffffff;
    border-color: #d9d9d9;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.02);
    margin: 1px;
    color: black;
    padding: 3px 7px !important;
}

.info {
    margin-top: -24px;
}

#closeicon {
    top: 14px !important;
}

.gst {
    background-color: #1f6d70 !important;
    color: white !important;
}

button.btn {
    padding: 4px 7px !important;
}

.gstpopup {
    width: 50% !important;
}
</style>
