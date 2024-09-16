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
                        <p>
                            Enter GSTIN number and get party details automatically
                        </p>
                        <a-col :xs="24" :sm="24" :md="8" :lg="8">
                            <a-form-item :label="$t('stock.gst_number')" name="stock.gst_number"
                                :help="rules.gst_number ? rules.gst_number.message : null"
                                :validateStatus="rules.gst_number ? 'error' : null">
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-input v-model:value="formData.gst_number" ref="gstInput"
                                :style="{ backgroundColor: accountGroupColor }"
                                @focus="changeColorOnFocus('accountGroup')" 
                                @blur="resetColorOnBlur('accountGroup')" 
                                placeholder="GST Number"/>
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

            <button type="button" id="btn-Ledger" title="Ledger" class="btn default-btn ng-star-inserted gst">
                <span class="box">
                   
                    <span class="ng-star-inserted">Verify</span>
                </span>
                
            </button>
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
            
        };
    },
    methods: {
        showModal() {
            this.isModalVisible = true;
        },
        autoFocusInput() {
            this.$nextTick(() => {
                this.$refs.gstInput.focus();  // Automatically focus the input
            });
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
        this.autoFocusInput();
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