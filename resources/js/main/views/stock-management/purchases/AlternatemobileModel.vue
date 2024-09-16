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

                        <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item :label="$t('stock.mobile_numberthree')" name="stock.mobile_numberthree"
                                :help="rules.mobile_numberthree ? rules.mobile_numberthree.message : null"
                                :validateStatus="rules.mobile_numberthree ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="15" :lg="15">
                            <a-input-group compact>
                                <a-button type="primary" class="indiannumber">
                                    +91
                                </a-button>
                                <a-input v-model:value="formData.mobile_numberthree" ref="limitInput"
                                    @keydown="changeColorOnTab" :style="{ backgroundColor: MobileNumberColor }"
                                    @focus="changeColorOnFocus('MobileNumber')" @blur="resetColorOnBlur('MobileNumber')"
                                    style="width:74%;" />
                            </a-input-group>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">

                        <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item :label="$t('stock.mobile_four')" name="stock.mobile_four"
                                :help="rules.mobile_four ? rules.mobile_four.message : null"
                                :validateStatus="rules.mobile_four ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="15" :lg="15">
                            <a-input-group compact>
                                <a-button type="primary" class="indiannumber">
                                    +91
                                </a-button>
                                <a-input v-model:value="formData.mobile_four"
                                    :style="{ backgroundColor: MobileNumberFourColor }"
                                    @focus="changeColorOnFocus('MobileNumberFour')"
                                    @blur="resetColorOnBlur('MobileNumberFour')" style="width:74%;" />
                            </a-input-group>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">

                        <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item :label="$t('stock.mobile_five')" name="stock.mobile_five"
                                :help="rules.mobile_five ? rules.mobile_five.message : null"
                                :validateStatus="rules.mobile_five ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="15" :lg="15">
                            <a-input-group compact>
                                <a-button type="primary" class="indiannumber">
                                    +91
                                </a-button>
                                <a-input v-model:value="formData.mobile_five" :style="{ backgroundColor: MobileNumberFiveColor }"
                                @focus="changeColorOnFocus('MobileNumberFive')"
                                @blur="resetColorOnBlur('MobileNumberFive')" style="width:74%;" />
                            </a-input-group>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <div>
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
                Primary_limit: '0.00',
                credit_limit: '0.00',
                credit_Action: '1'
            },

            inputColor: "",
            MobileNumberColor: '',
            MobileNumberFourColor: '',
            MobileNumberFiveColor: '',
            tabPressCount: 0,
            showInput: true,
            inputColor: '',
            showInput: true,
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Alternate Mobile Numbers',
            successMessage: 'Operation successful!',

        };
    },
    methods: {
        showModals() {
            this.isModalVisible = true;
        },
        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.limitInput.focus();  // Automatically focus the input
            });
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
        handleEscKey(event) {
            if (event.key === "Escape" || event.key === "Esc") {
                this.onClose();
            }

        },


        changeColorOnFocus(inputField) {
            if (inputField === 'MobileNumber') {
                this.MobileNumberColor = '#ffd451';
            } else if (inputField === 'MobileNumberFour') {
                this.MobileNumberFourColor = '#ffd451';
            } else if (inputField === 'MobileNumberFive') {
                this.MobileNumberFiveColor = '#ffd451';
            }
        },
        resetColorOnBlur(inputField) {
            if (inputField === 'MobileNumber') {
                this.MobileNumberColor = '';
            } else if (inputField === 'MobileNumberFour') {
                this.MobileNumberFourColor = '';
            } else if (inputField === 'MobileNumberFive') {
                this.MobileNumberFiveColor = '';
            }
        }
    },
    mounted() {
        window.addEventListener("keydown", this.handleEscKey);
        this.autoFocusInput();
    },
    beforeDestroy() {
        window.removeEventListener("keydown", this.handleEscKey);
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
    background-color: #f6f6f6 !important;
}

.ant-modal-body {
    background-color: #f6f6f6 !important;
}



:where(.css-dev-only-do-not-override-wosfq4).ant-form-item .ant-form-item-control-input {
    min-height: 4px !important;
}

.buttons {
    margin-left: 5px;
}

.basicdetalis {
    font-size: 15px !important;
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
</style>