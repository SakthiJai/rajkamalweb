<template>
  <a-modal
    :open="visible"
    :width="drawerWidth"
    :closable="false"
    :centered="true"
    @ok="onSubmit"
    class="discounts"
  >
    <template v-slot:title>
      <div style="display: flex; justify-content: space-between; align-items: center">
        <span>Create HSN/SAC</span>
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
      </div>
    </template>
    <a-form ref="myhsnForm1" id="addhsnform" layout="vertical" @submit.prevent="false">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('stock.hsn_code')"
                name="stock.latitude"
                :help="rules.code ? rules.code.message : null"
                :validateStatus="rules.code ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
                v-model:value="formData.code"
                ref="HsnInput"
                id="hsncode"
                @input="clearValidationError('code')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('Description')"
                name="stock.name"
                :help="rules.name ? rules.name.message : null"
                :validateStatus="rules.name ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
                v-model:value="formData.name"
                id="hsnname"
                @input="clearValidationError('name')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('GST Rate(%)')"
                name="stock.name"
                :help="rules.gst ? rules.gst.message : null"
                :validateStatus="rules.gst ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
              type="number"
              aria-autocomplete="off"
                v-model:value="formData.gst"
                id="gst"
                 @input="clearValidationError('gst')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('CGST Rate(%)')"
                name="stock.name"
                :help="rules.cgst ? rules.cgst.message : null"
                :validateStatus="rules.cgst ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
               type="number"
               aria-autocomplete="off"
                v-model:value="formData.cgst"
                id="cgst"
                  @input="clearValidationError('cgst')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('SGST Rate(%)')"
                name="stock.name"
                :help="rules.sgst ? rules.sgst.message : null"
                :validateStatus="rules.sgst ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
               type="number"
               aria-autocomplete="off"
                v-model:value="formData.sgst"
                id="sgst"
                 @input="clearValidationError('sgst')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('IGST Rate(%)')"
                name="stock.name"
                :help="rules.lgst ? rules.lgst.message : null"
                :validateStatus="rules.lgst ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
               type="number"
               aria-autocomplete="off"
                v-model:value="formData.lgst"
                id="lgst"
                 @input="clearValidationError('lgst')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>

          <!-- CESS -->
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="8" :lg="8">
              <a-form-item
                :label="$t('CESS Rate(%)')"
                name="stock.name"
                :help="rules.cess ? rules.cess.message : null"
                :validateStatus="rules.cess ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
              <a-input
               type="number"
               aria-autocomplete="off"
                v-model:value="formData.cess"
                id="cess"
                 @input="clearValidationError('cess')"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>
        </a-col>
      </a-row>
    </a-form>
    <template #footer>
      <div>
        <button
          type="button"
          id="save-item"
          title="Ledger"
          class="btn default-btn ng-star-inserted gst"
          @click="saveHsnItem($event)"
        >
          <span class="box"
            ><span class="shortcut ng-star-inserted"><code>F8</code></span
            ><span class="ng-star-inserted">Save</span></span
          ><span class="effect"></span>
        </button>
        <button
          type="button"
          id="btn-Ledger"
          title="Ledger"
          class="btn default-btn ng-star-inserted"
          @click="resethsnform"
        >
          <span class="box"
            ><span class="shortcut ng-star-inserted"><code>F9</code></span
            ><span class="ng-star-inserted">Clear</span></span
          ><span class="effect"></span></button
        ><button
          type="button"
          id="btn-Ledger"
          title="Ledger"
          class="btn default-btn ng-star-inserted"
          @click="showconfirm"
        >
          <span class="box"
            ><span class="shortcut ng-star-inserted"><code>Esc</code></span
            ><span class="ng-star-inserted">Close</span></span
          ><span class="effect"></span>
        </button>
      </div>
    </template>
  </a-modal>
</template>

<script>
import { onMounted, onUnmounted, ref } from "vue";
import { useI18n } from "vue-i18n";
import { defineComponent } from "vue";
import {
  PlusOutlined,
  LoadingOutlined,
  SaveOutlined,
  EditOutlined,
  DeleteOutlined,
  ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import common from "../../../../../common/composable/common";
import StaffMemberAddButton from "../../../../../../js/main/views/users/StaffAddButton.vue";
import { message, notification } from "ant-design-vue";
import { Modal } from "ant-design-vue";
import { createVNode } from "vue";
export default defineComponent({
  props: ["formData", "visible", "url", "addEditType", "pageTitle", "successMessage"],
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
    const { permsArray, appSetting, dayjsObject, appType } = common();
    const formData = ref({});
    const stock = appSetting.value;
    const { t } = useI18n();

    onMounted(() => {
      setFormData();
    });

    const setFormData = () => {
      formData.value = {
        code: "",
        name: "",
        _method: "POST",
      };
    };
    const ledgerSubmit = (formData) => {
      addEditRequestAdmin({
        url: `create-hsc`,
        data: formData,
        success: (res) => {
        
          console.log("API Response:", res);

         
          const productId = res.id;
          console.log("Product ID:", productId);

          notification.success({
            placement: "bottomRight",
            message: "Product saved successfully",
          });

          document.getElementById("addhsnform").reset();

          setTimeout(function () {
            // Pass the productId to emit when the form is closed
            console.log("Emitting 'closed' event with productId:", productId);
            formData.id = productId;
            emit("closed", formData);

            document.getElementById("company").focus();
          }, 2000);
        },
      });
    };

    const onClose = () => {
      rules.value = {};
      emit("closed");
      document.getElementById("hsncode").focus();

    };
    return {
      formData,
      loading,
      rules,
      onClose,
      // onSubmit,
      ledgerSubmit,
    };
  },
  data() {
    return {};
  },
  methods: {
    showModal() {
      this.isModalVisible = true;
    },
    handleClose() {
      this.isModalVisible = false;
      // Reset button color when closing
      this.buttonStyle = {
        backgroundColor: "",
        borderColor: "",
        color: "",
      };
    },
    handleSuccess(xid) {
      // Handle success logic
      this.isModalVisible = false;
      console.log("Success:", xid);
    },
    handleKeydown(event) {
      if (event.key === "F2") {
        // Change button color
        this.buttonStyle = {
          backgroundColor: "green",
          borderColor: "green",
          color: "white",
        };
        setTimeout(() => {
          this.isModalVisible = true;
        }, 500);
      } else if (event.key === "F8") {
        event.preventDefault();
        if (this.validateInput()) {
          this.onSubmitLedger(event.key);
        }
      } else if (event.key === "F9") {
        this.resethsnform();
      } else if (event.key === "Escape") {
        this.showconfirm();
      }
    },
    autoFocusInput() {
      let datas = this.$route.params.data;
      this.$nextTick(() => {
        if (this.$refs.HsnInput) this.$refs.HsnInput.focus();
      });
    },
    resethsnform() {
      this.formData = {
        code: "",
        name: "",
      };
    },

    showconfirm() {
      console.log("Esc called");
      let that = this;
      const modal = Modal.confirm({
        title: "Confirmation",
        icon: createVNode(ExclamationCircleOutlined),
        content: "Transaction data will be lost. Are you sure you want to close?",
        onOk() {
          that.$emit("closed");
          document.getElementById("ProductInput").focus();
        },
        onCancel() {
          that.$refs.HsnInput && that.$refs.HsnInput.focus();
        },
        okText: "OK",
        cancelText: "Cancel",
        autoFocusButton: "cancel",
      });

      setTimeout(() => {
        document.addEventListener("keydown", (e) => {
          if (modal && (e.key === "ArrowLeft" || e.key === "ArrowRight")) {
            e.preventDefault();

            const cancelBtn = document.querySelector(".ant-btn-dangerous");
            const okBtn = document.querySelector(".ant-btn-primary");

            if (e.key === "ArrowRight") {
              if (okBtn) {
                okBtn.focus();
              }
            } else if (e.key === "ArrowLeft") {
              if (cancelBtn) {
                cancelBtn.focus();
              }
            }
          }
        });
      }, 0);
    },
   
   nameValidation(){
   console.log("asdfg")
   },

    clearValidationError(field) {
      
      const fieldValue = this.formData[field];

      const fieldLabel = this.rules[field]?.message || field;

      if (!fieldValue || fieldValue.trim() === "") {
        this.rules[field] = {
          message: `${fieldLabel} ${this.$t("is required.")}`,
        };
      } else {
        this.rules[field] = null;
      }
    },

    focusNext(e) {
       console.log(this.formData.name);
      
      console.log("Test");
      this.currentFocusElement = e.target;
      const formElements = Array.from(
        e.target.form.querySelectorAll("input, select,date")
      );
      const currentIndex = formElements.indexOf(e.target);
      //console.log(e.target.id)
      if (currentIndex < formElements.length - 1) {
        if (
          e.target.id == "hsncode" &&
          this.formData.code != undefined &&
          this.formData.code != ""
        ) {
          formElements[currentIndex + 1].focus();
        } if (
          e.target.id == "hsnname" &&
          this.formData.name != undefined &&
          this.formData.name != ""
        ) {

          formElements[currentIndex + 1].focus();
        } 
        if (
          e.target.id == "gst" &&
          this.formData.gst != undefined &&
          this.formData.gst != ""
        ) {

          formElements[currentIndex + 1].focus();
        }
         if (
          e.target.id == "cgst" &&
          this.formData.cgst != undefined &&
          this.formData.cgst != ""
        ) {

          formElements[currentIndex + 1].focus();
        }

         if (
          e.target.id == "sgst" &&
          this.formData.sgst != undefined &&
          this.formData.sgst != ""
        ) {

          formElements[currentIndex + 1].focus();
        }
         if (
          e.target.id == "lgst" &&
          this.formData.lgst != undefined &&
          this.formData.lgst != ""
        ) {

          formElements[currentIndex + 1].focus();
        }
        
        else {
          console.log(e.target.id, this.formData.name);
        }
      }
    },


// validateInput1(e){
//    console.log(e.target.id)
//    var a = e.target.id;
//    if(!this.formData.a || this.formData.a === ''){
//         this.rules.a = {
//           message: `${a} is required.`,
//         };
        
//       }else {
//         this.rules.a = null;
//       }
// },

    /// form validation
    validateInput() {
      console.log(this.formData.name)
 
      //HSN/SAC
      let isValid = true;
      if (!this.formData.code || this.formData.code.trim() === "") {
       
        this.rules.code = {
          message: "HSN/SAC Code is required.",
        };
        isValid = false;
      } else if (!/^\d{8}$/.test(this.formData.code.trim())) {
        // Check if the code is exactly 8 digits and numeric
        this.rules.code = {
          message: "HSN/SAC Code must be exactly 8 digits.",
        };
        isValid = false;
        document.getElementById("hsncode").focus();
      } else {
        this.rules.code = null;
      }

       //Description
      if(!this.formData.name || this.formData.name === ''){
        this.rules.name = {
          message: "Description is required.",
        };
        isValid = false;
      }else {
        this.rules.name = null;
      }
      //GST Rate(%)
      if(!this.formData.gst || this.formData.gst === ''){
        this.rules.gst = {
          message: "GST Rate is required.",
        };
        isValid = false;
      }
      else {
        this.rules.gst = null;
      }
      //CGST Rate(%)
      if(!this.formData.cgst || this.formData.cgst === ''){
        this.rules.cgst = {
          message: "CGST Rate is required.",
        };
        isValid = false;
      }
      else {
        this.rules.cgst = null;
      }
      //sgst
       if(!this.formData.sgst || this.formData.sgst === ''){
        this.rules.sgst = {
          message: "SGST Rate is required.",
        };
        isValid = false;
      }
      else {
        this.rules.sgst = null;
      }
     //lgst
     if(!this.formData.lgst || this.formData.lgst === ''){
        this.rules.lgst = {
          message: "LGST Rate is required.",
        };
        isValid = false;
      }
      else {
        this.rules.lgst = null;
      }

      //cess
     if(!this.formData.cess || this.formData.cess === ''){
        this.rules.cess = {
          message: "CESS Rate is required.",
        };
        isValid = false;
      }
      else {
        this.rules.lgst = null;
      }
      
      
   return isValid;
      
    },
    // end form validation//

    onSubmitLedger(eventKey) {
      try {
        console.log(eventKey, "<>", this.formData);
        if (eventKey == "F8" && this.validateInput()) {
          this.ledgerSubmit(this.formData);
        }
      } catch (error) {
        this.$message.error(this.$t("Issue while Product Save !"));
        console.error(error);
      }
    },
    saveHsnItem(eventKey) {
      try {
        console.log("saveProductItem");
        if (this.validateInput()) {
          this.ledgerSubmit(this.formData);
        }
      } catch (error) {
        this.$message.error(this.$t("Issue while Product Save !"));
        console.error(error);
      }
    },
  },
  mounted() {
    this.autoFocusInput();
    document.getElementById("addhsnform").addEventListener("keyup", this.handleKeydown);
  },
  beforeDestroy() {
    window.removeEventListener("keydown", this.handleKeydown);
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
