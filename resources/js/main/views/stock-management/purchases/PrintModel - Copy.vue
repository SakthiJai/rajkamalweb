<template>
    <a-modal :open="visible" :closable="false" @close="handleClose" :centered="true" :class="popup" @ok="onSubmit"
        @keydown.esc="onClose">
        <template v-slot:title>
            <div style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                <span>Print </span>
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
        <form>
            <a-col :xs="24" :sm="24" :md="24" :lg="24" class="info">
                <fieldset>
                    <legend class="basicdetalis">Format</legend>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-form-item :label="$t('Select Format')" name="name"
                                :help="rules.name ? rules.name.message : null"
                                :validateStatus="rules.name ? 'error' : null" class="required compact-form-item">
                                <select id="billing_print" v-model="formData.business_type" ref="billingPrintSelect"
                                    class="ant-input css-dev-only-do-not-override-wosfq4">
                                    <option value="1">General Billing</option>
                                </select>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </fieldset>
            </a-col>
        </form>

        <template #footer>
            <div @keydown="handleKeyDown" tabindex="0">
            <button @click="previewpdf" type="submit" id="btn-Ledger" title="Ledger"
                class="btn default-btn ng-star-inserted gst">
                <span class="box"><span class="shortcut ng-star-inserted"><code>F</code></span><span
                        class="ng-star-inserted">Preview</span>
                </span>
                <span class="effect"></span>
            </button>

            <button type="button" id="btn-Ledger" title="Ledger" @click="onClose"
                class="btn default-btn ng-star-inserted">
                <span class="box"><span class="shortcut ng-star-inserted"><code>Esc</code></span><span
                        class="ng-star-inserted">Close</span>
                    <svg focusable="false" data-icon="close" width="1em" height="1em" fill="currentColor"
                        aria-hidden="true" fill-rule="evenodd" viewBox="64 64 896 896">
                        <path
                            d="M799.86 166.31c.02 0 .04.02.08.06l57.69 57.7c.04.03.05.05.06.08a.12.12 0 010 .06c0 .03-.02.05-.06.09L569.93 512l287.7 287.7c.04.04.05.06.06.09a.12.12 0 010 .07c0 .02-.02.04-.06.08l-57.7 57.69c-.03.04-.05.05-.07.06a.12.12 0 01-.07 0c-.03 0-.05-.02-.09-.06L512 569.93l-287.7 287.7c-.04.04-.06.05-.09.06a.12.12 0 01-.07 0c-.02 0-.04-.02-.08-.06l-57.69-57.7c-.04-.03-.05-.05-.06-.07a.12.12 0 010-.07c0-.03.02-.05.06-.09L454.07 512l-287.7-287.7c-.04-.04-.05-.06-.06-.09a.12.12 0 010-.07c0-.02.02-.04.06-.08l57.7-57.69c.03-.04.05-.05.07-.06a.12.12 0 01.07 0c.03 0 .05.02.09.06L512 454.07l287.7-287.7c.04-.04.06-.05.09-.06a.12.12 0 01.07 0z">
                        </path>
                    </svg></span><span class="effect"></span>
            </button>
           
            <button type="button" id="btn-Ledger" title="Ledger" @click="PrinterOutlined" @keydown="handleKeydowns"
                tabindex="0" class="btn default-btn ng-star-inserted">
                <span class="box" @click="printpdf"><span class="shortcut ng-star-inserted"><code>P</code></span><span
                        class="ng-star-inserted">Print</span>
                    <svg focusable="false" data-icon="file-pdf" width="1em" height="1em" fill="currentColor"
                        aria-hidden="true" fill-rule="evenodd" viewBox="64 64 896 896">
                        <path
                            d="M19 7h-1V2H6v5H5c-1.1 0-2 .9-2 2v7h4v4h10v-4h4v-7c0-1.1-.9-2-2-2zM8 4h8v3H8V4zm8 15H8v-3h8v3zm2-5H6v-4h12v4z" />
                    </svg>
                </span>
                <!-- <PrinterOutlined /> -->
                <!-- svg and path place replace here fix the svg -->

                <span class="effect"></span>
            </button>
            <button type="button" id="btn-Ledger" title="Ledger" @click="WhatsAppOutlined"
                class="btn default-btn ng-star-inserted">
                <span class="box">
                    <span class="ng-star-inserted">whatsapp</span>
                    <svg focusable="false" data-icon="whatsapp" width="1em" height="1em" fill="currentColor"
                        aria-hidden="true" fill-rule="evenodd" viewBox="64 64 896 896">
                        <path
                            d="M16.027 0C7.177 0 .027 7.149.027 16c0 3.085.826 6.045 2.4 8.648L0 32l7.537-2.377c2.493 1.372 5.315 2.101 8.22 2.1h.003c8.85 0 16.001-7.149 16.001-16S24.876 0 16.027 0zm0 29.318c-2.624 0-5.178-.692-7.418-2.006l-.531-.316-4.476 1.413 1.46-4.357-.35-.546c-1.47-2.298-2.246-4.95-2.246-7.516 0-7.518 6.118-13.637 13.636-13.637 7.517 0 13.636 6.119 13.636 13.636.001 7.517-6.118 13.638-13.637 13.638zM22.54 19.82c-.37-.185-2.184-1.078-2.523-1.2-.34-.123-.587-.185-.834.186-.246.37-.957 1.2-1.17 1.445-.213.246-.426.277-.795.092-.37-.185-1.563-.575-2.978-1.833-1.102-.983-1.848-2.198-2.065-2.57-.214-.37-.023-.569.162-.753.166-.165.37-.43.555-.646.185-.217.246-.37.37-.616.123-.246.062-.462-.03-.646-.092-.185-.834-2.017-1.142-2.76-.3-.723-.606-.62-.834-.63-.215-.007-.462-.01-.707-.01-.246 0-.646.092-.984.462-.339.37-1.293 1.26-1.293 3.08 0 1.818 1.323 3.57 1.507 3.822.185.246 2.606 4.016 6.307 5.614.882.38 1.57.607 2.107.777.885.281 1.688.242 2.32.147.707-.108 2.184-.892 2.492-1.753.308-.862.308-1.6.216-1.753-.093-.154-.339-.246-.707-.37z" />
                    </svg>
                </span>

                <!-- svg and path place replace here fix the svg    <whatsappOutlined>-->
                <span class="effect"></span>
            </button>
        </div>
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
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import { message, notification } from "ant-design-vue";
// import PrinterOutlined from "@ant-design/icons-vue";
// import WhatsAppOutlined from "@ant-design/icons-vue";

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
        StaffMemberAddButton,
        PrinterOutlined,
        WhatsAppOutlined,
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
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
    data() {
        return {
            isLocationModal: false,
            isModalVisible: false,
            isModalVisibles: false,
            isModalVisibleing: false,
            //business_type: "2",
            formData: {
                business_type: "1",
            },

        };
    },

    mounted() {
        document.addEventListener('keyup', this.handleKeyDown);
        this.autoFocusInput();

    },
    beforeDestroy() {
        //window.removeEventListener("keydown", this.handleleKeydown);
    },

    methods: {
        onClose() {
            this.isModalVisible = false;
        },

        handleSuccess(xid) {
            this.isModalVisible = false;
            document.removeEventListener("keydown", this.handleKeydown);
            console.log("Success:", xid);
        },
        // handleKeydown(event) {
        //     if (event.key === "Escape") {
        //         this.handleClose();
        //     }
        // },
        handleLocationClose() {
            this.isLocationModal = false;
        },
        handleClose() {
            this.isModalVisible = false;
            document.removeEventListener("keydown", this.handleKeydown);
        },
        handleCloses() {
            this.isModalVisibles = false;
            document.removeEventListener("keydown", this.handleKeydown);
        },
        handleClosing() {
            this.isModalVisibleing = false;
        },
        handleKeydown(event) {
            if (event.key === "Escape") {
                this.handleClose();
            }
        },
        handlePrint() {
            console.log("Printing...");
        },

        handleKeydowns(event) {
            if (event.key === 'F9') {
                event.preventDefault();
                this.printpdf();
                console.log("demo");
            }
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

        handleKeyDown(event) {
      // Check if the 'P' key is pressed
      if (event.key === "p" || event.key === "P") {
        event.preventDefault(); 
        this.printpdf(); 
      } else if (event.key === "f" || event.key === "F"){
        event.preventDefault(); 
          this.previewpdf();
      }
    },
        printpdf() {
            console.log("PDF click");
            this.loading = true;
            axiosAdmin
                .post("sales/getInvoicePdf", { invoice: document.getElementById('form_item_bill_number').value })
                .then(response => {
                    this.$emit('closed');
                    let w = window.open(response, '_blank');
                    w.print();
                })
                .catch(errorResponse => {
                    var err = errorResponse.data;
                    const errorCode = errorResponse.status;
                    var errorRules = {};

                    if (errorCode == 422) {
                        if (err.error && typeof err.error.details != "undefined") {
                            var keys = Object.keys(err.error.details);
                            for (var i = 0; i < keys.length; i++) {
                                // Escape dot that comes with error in array fields
                                var key = keys[i].replace(".", "\\.");

                                errorRules[key] = {
                                    required: true,
                                    message: err.error.details[keys[i]][0],
                                };
                            }
                        }

                        rules.value = errorRules;
                        message.error(t("common.fix_errors"));
                    }

                    if (err && err.message) {
                        message.error(err.message);
                        err = {
                            error: {
                                ...err
                            }
                        }
                    }



                    //loading.value = false;
                });
            //  this.$emit('closed');


        },
        previewpdf() {
            console.log("PDF click");
            this.loading = true;
            axiosAdmin
                .post("sales/getInvoicePdf", { invoice: document.getElementById('form_item_bill_number').value })
                .then(response => {

                    let w = window.open(response, '_blank');
                    w.print();
                })
                .catch(errorResponse => {
                    var err = errorResponse.data;
                    const errorCode = errorResponse.status;
                    var errorRules = {};

                    if (errorCode == 422) {
                        if (err.error && typeof err.error.details != "undefined") {
                            var keys = Object.keys(err.error.details);
                            for (var i = 0; i < keys.length; i++) {
                                // Escape dot that comes with error in array fields
                                var key = keys[i].replace(".", "\\.");

                                errorRules[key] = {
                                    required: true,
                                    message: err.error.details[keys[i]][0],
                                };
                            }
                        }

                        rules.value = errorRules;
                        message.error(t("common.fix_errors"));
                    }

                    if (err && err.message) {
                        message.error(err.message);
                        err = {
                            error: {
                                ...err
                            }
                        }
                    }



                    //loading.value = false;
                });
            //  this.$emit('closed');


        },
        WhatsAppOutlined() {
            notification.warning({
                placement: "bottomRight",
                message: "We are on the way to bring the Whatsapp Integration !",
                description: ""
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
</style>
