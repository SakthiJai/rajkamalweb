<template>
  <a-modal
    :open="visible"
    :width="drawerWidth"
    :closable="false"
    :centered="true"
    @ok="onSubmit"
    class="companycreate"
  >
    <template v-slot:title>
      <div style="display: flex; justify-content: space-between; align-items: center">
        <span>Create Company</span>
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
    <a-form ref="myhsnForm1" id="addcompanyform" layout="vertical" @submit.prevent="false">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.company_name')"
                name="stock.name"
                :help="rules.name ? rules.name.message : null"
                :validateStatus="rules.name ? 'error' : null"
                class="required"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input
                v-model:value="formData.name"
                ref="CompanyInput"
                id="companyname"
                @input="clearValidationError('name')"
                @keyup.enter="focusNext"
              />
            </a-col>
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.print_remark')"
                name="stock.print_remark"
                :help="rules.print_remark ? rules.print_remark.message : null"
                :validateStatus="rules.print_remark ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input
                v-model:value="formData.print_remark"
                id="printremark"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>

          <a-row :gutter="24">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.company_status')"
                name="stock.status"
                :help="rules.status ? rules.status.message : null"
                :validateStatus="rules.status ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-select
                id="companystatus"
                v-model:value="formData.status"
                style="width: 100%"
                @keyup.enter="focusNext"
              >
                <a-select-option key="continue" value="Continue" aria-selected="true">
                  Continue
                </a-select-option>
                <a-select-option
                  key="Discontinue"
                  value="Discontinue"
                  aria-selected="true"
                >
                  Discontinue
                </a-select-option>
              </a-select>
            </a-col>
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.reorder_perferences')"
                name="stock.reorder_perference"
                :help="rules.reorder_perference ? rules.reorder_perference.message : null"
                :validateStatus="rules.reorder_perference ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input
                v-model:value="formData.reorder_perference"
                id="reorderperference"
                @keyup.enter="focusNext"
              />
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.store_room')"
                name="stock.store_room"
                :help="rules.store_room ? rules.store_room.message : null"
                :validateStatus="rules.store_room ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input
                v-model:value="formData.store_room"
                @keyup.enter="focusNext"
                id="storeroom"
                style="margin-top: 4px"
              />
            </a-col>
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.prohibit')"
                name="stock.prohibit"
                :help="rules.prohibit ? rules.prohibit.message : null"
                :validateStatus="rules.prohibit ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-select
                id="prohibiting"
                v-model:value="formData.prohibit"
                style="width: 100%"
                @keyup.enter="focusNext"
              >
                <a-select-option key="Yes" value="Yes" aria-selected="true">
                  Yes
                </a-select-option>
                <a-select-option key="No" value="No" aria-selected="true">
                  No
                </a-select-option>
              </a-select>
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.invoice_printing')"
                name="stock.invoice_printing"
                :help="rules.invoice_printing ? rules.invoice_printing.message : null"
                :validateStatus="rules.invoice_printing ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input
                v-model:value="formData.invoice_printing"
                id="reorderperference"
                @keyup.enter="focusNext"
              />
            </a-col>
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.dump_days')"
                name="stock.dump_days"
                :help="rules.dump_days ? rules.dump_days.message : null"
                :validateStatus="rules.dump_days ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input
                v-model:value="formData.dump_days"
                id="dumpdays"
                @keyup.enter="focusNext"
                style="margin-top: 3px"
              />
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.reorder_formula')"
                name="stock.reorder_formula"
                :help="rules.reorder_formula ? rules.reorder_formula.message : null"
                :validateStatus="rules.reorder_formula ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input-group compact>
                <a-span type="primary" class="productnumber"> ₹ </a-span>
                <a-input
                  autocomplete="off"
                  v-model:value="formData.reorder_formula"
                  id="reorderformula"
                  style="width: 91%"
                  type="text"
                  maxlength="10"
                  :placeholder="'0.00'"
                  @keyup.enter="focusNext"
                />
              </a-input-group>
            </a-col>
            <a-col :xs="24" :sm="24" :md="4" :lg="4">
              <a-form-item
                :label="$t('stock.minimum_margin')"
                name="stock.minimum_margin"
                :help="rules.minimum_margin ? rules.minimum_margin.message : null"
                :validateStatus="rules.minimum_margin ? 'error' : null"
              >
              </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="7" :lg="7">
              <a-input-group compact style="margin-top: 2px;">
                <a-span type="primary" class="productnumber"> ₹ </a-span>
                <a-input
                  autocomplete="off"
                  id="minimummargin"
                  v-model:value="formData.minimum_margin"
                  style="width: 91%;"
                  type="text"
                  maxlength="10"
                  :placeholder="'0.00'"
                  @keyup.enter="focusNext"
                                   />
              </a-input-group>
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
        print_remark: "",
        status: "Continue",
        reorder_perference: "",
        store_room: "",
        prohibit: "No",
        invoice_printing: "",
        dump_days: "",
        reorder_formula: "",
        minimum_margin: "",
        _method: "POST",
      };
    };
    const ledgerSubmit = (formData) => {
      addEditRequestAdmin({
        url: `create-productcompany`,
        data: formData,
        success: (res) => {
          const productId = res.id;
          console.log("Product ID:", productId);
          notification.success({
           
            placement: "bottomRight",
            message: "Company saved successfully",
          });
          document.getElementById("addcompanyform").reset();
          setTimeout(function () {
            console.log("Emitting 'closed' event with productId:", productId);
            formData.id = productId;
            emit("closed",formData);
            document.getElementById("god_owns").focus();
          }, 2000);
        },
      });
    };

    const onClose = () => {
      rules.value = {};
      emit("closed");
      document.getElementById("companyname").focus();
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
    return {

      
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
        if (this.$refs.CompanyInput) this.$refs.CompanyInput.focus();
      });
    },
    resethsnform() {
      this.formData = {
        name: "",
        print_remark: "",
        status: "",
        reorder_perference: "",
        store_room: "",
        prohibit: "",
        invoice_printing: "",
        dump_days: "",
        reorder_formula: "",
        minimum_margin: "",
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
          that.$refs.CompanyInput && that.$refs.CompanyInput.focus();
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
      console.log("Test");
      this.currentFocusElement = e.target;
      const formElements = Array.from(
        e.target.form.querySelectorAll("input, select,date")
      );
      const currentIndex = formElements.indexOf(e.target);
      //console.log(e.target.id)
      if (currentIndex < formElements.length - 1) {
        if (
          e.target.id == "companyname" &&
          this.formData.name != undefined &&
          this.formData.name != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "printremark" &&
          this.formData.print_remark != undefined &&
          this.formData.print_remark != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "companystatus" &&
          this.formData.status != undefined &&
          this.formData.status != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "reorderperference" &&
          this.formData.reorder_perference != undefined &&
          this.formData.reorder_perference != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "storeroom" &&
          this.formData.store_room != undefined &&
          this.formData.store_room != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "prohibiting" &&
          this.formData.prohibit != undefined &&
          this.formData.prohibit != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "dumpdays" &&
          this.formData.dump_days != undefined &&
          this.formData.dump_days != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "dumpdainvoiceprintingys" &&
          this.formData.invoice_printing != undefined &&
          this.formData.invoice_printing != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "reorderformula" &&
          this.formData.reorder_formula != undefined &&
          this.formData.reorder_formula != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else if (
          e.target.id == "minimummargin" &&
          this.formData.minimum_margin != undefined &&
          this.formData.minimum_margin != ""
        ) {
          formElements[currentIndex + 1].focus();
        } else {
          console.log(e.target.id, this.formData.minimum_margin);
        }
      }
    },

    /// form validation
    validateInput() {
      let isValid = true;
      if (!this.formData.name || this.formData.name.trim() === "") {
        this.rules.name = {
          message: "Company Name is requried",
        };
        isValid = false;
      } else {
        this.rules.code = null;
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
    document.getElementById("addcompanyform").addEventListener("keyup", this.handleKeydown);
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

.companycreate {
  width: 65% !important;
}

.saving {
  background-color: #1f6d70 !important;
  color: white !important;
}

#closeicon {
  top: 14px !important;
}
</style>
