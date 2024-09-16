<template>
    <a-drawer :title="pageTitle" :width="drawerWidth" :open="visible" :body-style="{ paddingBottom: '80px' }"
        class="leftpopup" :footer-style="{ textAlign: 'right' }" :maskClosable="false" @close="onClose">
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="7" :lg="7">
                            <a-form-item name="party_name_search" ref="dummykeyboard"
                                :help="rules.party_name ? rules.party_name.message : null"
                                :validateStatus="rules.party_name ? 'error' : null" class="required">
                                <a-input-search ref="searchInput" style="width: 100%" placeholder="Search here.."
                                    />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="14" :lg="14">
                            <div class="help">
                            </div>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <div class="help">
                                Action | Help
                            </div>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="7" :lg="7">
                            <div class="rateinfo">
                                Rate Info
                            </div>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="10" :lg="10">
                            <div class="help">

                            </div>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="4" :lg="4">
                            <div class="help">
                                <a-button :loading="loading" @click="showRateodal" style="margin-right: 8px"
                                    class="configure">
                                    Configure
                                </a-button>
                            </div>
                        </a-col>
                        <RateModel v-if="isModalVisible" :visible="isModalVisible" :formData="formData" :url="url"
                            :addEditType="addEditType" :pageTitle="pageTitle" :successMessage="successMessage"
                            @addEditSuccess="handleSuccess" @closed="handleClose" />

                        <a-col :xs="24" :sm="24" :md="3" :lg="3">
                            <div class="help">
                                <svg height="20px" viewBox="0 0 30.05 30.05" width="20px" xmlns=""><title>Play</title><g data-name="Layer 2" id="Layer_2"><g data-name="Layer 1" id="Layer_1-2"><path d="M13.74,9.9A.73.73,0,0,0,13,9.84a.76.76,0,0,0-.41.67v9.07a.76.76,0,0,0,.41.67.81.81,0,0,0,.34.08.73.73,0,0,0,.44-.15L20,15.65a.75.75,0,0,0,.31-.61.7.7,0,0,0-.31-.6ZM15,0a15,15,0,1,0,15,15A15,15,0,0,0,15,0Zm0,27.54A12.52,12.52,0,1,1,27.54,15,12.51,12.51,0,0,1,15,27.54Z"></path></g></g></svg>
                                <svg  style="margin-left:12px;color:blue;"class="svg-inline--fa fa-question-circle fa-w-16" height="20" viewBox="0 0 512 512" xmlns=""><path class="" d="M256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28zm7.67-24h-16c-6.627 0-12-5.373-12-12v-.381c0-70.343 77.44-63.619 77.44-107.408 0-20.016-17.761-40.211-57.44-40.211-29.144 0-44.265 9.649-59.211 28.692-3.908 4.98-11.054 5.995-16.248 2.376l-13.134-9.15c-5.625-3.919-6.86-11.771-2.645-17.177C185.658 133.514 210.842 116 255.67 116c52.32 0 97.44 29.751 97.44 80.211 0 67.414-77.44 63.849-77.44 107.408V304c0 6.627-5.373 12-12 12zM256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8z" fill="currentColor"></path></svg>
                            </div>
                          
                        </a-col>
                        
                    </a-row>
                </a-col>
            </a-row>
        </a-form>

    </a-drawer>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import StaffMemberAddButton from "../../../views/users/StaffAddButton.vue";
import RateModel from "./RateModel.vue";
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
        RateModel

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
                sale: '1'
            },
            url: 'your-url-here',
            addEditType: 'add',
            pageTitle: 'Ledger Setting',
            successMessage: 'Operation successful!',
            buttonStyle: {
                backgroundColor: '', // Initially no background color
                borderColor: '',
                color: '', // Initially no text color
            },
        };
    },
    methods: {
        showRateodal() {
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
.ant-drawer-content-wrapper {
    width: 60% !important;
}

.configure {
    background-color: #1f6d70;
    border-radius: 4px;
    color: #fff;
    line-height: 1;
    box-shadow: none;
    padding: 6px 16px;
}
</style>