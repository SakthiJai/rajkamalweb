<template>
  <a-modal :open="visible" :width="drawerWidth" :closable="false" :centered="true" @ok="onSubmit" class="discounts" @cancel="handlePopupClose">
    <a-form layout="vertical">
      <div class="page-wrapper">
        <div class="custom-modal">
          <div class="danger danger-animation icon-top">
            <svg style="margin-bottom:-8px;" height="35px" viewBox="0 0 30 30" width="35px" xmlns="http://www.w3.org/2000/svg">
              <defs></defs>
              <title>Asset 1</title>
              <g data-name="Layer 2" id="Layer_1">
                <g data-name="Layer 1" id="Layer_1-1">
                  <path class="theme-fill-color" d="M25.61,4.39A15,15,0,1,0,30,15,14.93,14.93,0,0,0,25.61,4.39ZM15,28.24A13.24,13.24,0,1,1,28.24,15,13.26,13.26,0,0,1,15,28.24Zm.33-20.75A.94.94,0,0,0,14.5,7h0a1,1,0,0,0-.84.49L7.07,19.63a.92.92,0,0,0,0,.92A1,1,0,0,0,7.9,21H21.06a.94.94,0,0,0,.84-1.37ZM14.5,19.13a.93.93,0,1,1,.94-.93A.93.93,0,0,1,14.5,19.13Zm.94-3.72a.95.95,0,0,1-1.89,0V11.68a.95.95,0,0,1,1.89,0Z"></path>
                </g>
              </g>
            </svg>
          </div>

          <div class="content">
            <h1 class="type conformation">Confirmation</h1>
            <p class="message-type closemessage">Are you sure  want to close?</p>

            <!-- Yes button to close the popup -->
            <button class="yesbutton" type="button"  @click="handlePopupClose()">Yes</button>

            <!-- No button to keep the popup open -->
            <button class="nobutton" type="button">No</button>

          </div>
        </div>
      </div>
    </a-form>
  </a-modal>
</template>


<script>
import { ref, defineComponent,createVNode } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, EditOutlined, DeleteOutlined,ExclamationCircleOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import common from "../../../../common/composable/common";
import { Modal, notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";

export default defineComponent({
  props: ["formData", "visible", "url", "addEditType", "pageTitle", "successMessage"],
  
  components: {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    EditOutlined,
    DeleteOutlined,
  },
  
  setup(props, { emit }) {
    const { addEditRequestAdmin, loading, rules } = apiAdmin();
    const { permsArray, appSetting, dayjsObject, appType } = common();
    const { t } = useI18n();
    const langKey = ref("");
    const formData = ref({});
    const stock = appSetting.value;
    const crudUrl = ref("");

    const handlePopupClose = () => {
      // Emit event to parent to close the modal
      console.log(true)
      emit("closed");
    };
//     const handlePopupClose = (id) => {
//     console.log("ID passed to handlePopupClose:", id); // Debugging line
   
//     axiosAdmin
//         .delete(`store-ledger/${id}`)
//         .then((successResponse) => {
//             updateSubscriptionModules();
//             fetch({
//                 page: currentPage.value,
//             });
//             notification.success({
//                 message: t("common.success"),
//                 description: t(`${langKey.value}.deleted`),
//                 placement: "bottomRight",
//             });
//         })
//         .catch((error) => {
//             console.error("Failed to delete:", error);
//         });
// };




    return {
      formData,
      crudUrl,
      loading,
      rules,
      //showDeleteConfirm,
      handlePopupClose,
    };
  },


});
</script>


<style>
.succes {
    background-color: white;
  }
  .succes-animation {
    animation: succes-pulse 2s infinite;
  }
  
  .danger {
    background-color: white;
  }
  .danger-animation {
    animation: danger-pulse 2s infinite;
  }
  
  .custom-modal {
    position: relative;
    width: 450px;
    min-height: 210px;
    background-color: #fff;
    border-radius: 30px;
    margin: 40px 10px;
  }
  .custom-modal .content { 
    position: absolute;
    width: 100%;
    text-align: center;
    bottom: 11px;
  }
  .custom-modal .content .type {
    font-size: 18px;
    color: #999;
  }
  .custom-modal .content .message-type {
    font-size: 18px;
    color: #000;
  }
  .custom-modal .border-bottom {
    position: absolute;
    width: 300px;
    height: 20px;
    border-radius: 0 0 30px 30px;
    bottom: -20px;
    margin: 0 25px;
  }
  .custom-modal .icon-top {
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    top: -30px;
    left: 30px;
    margin: 0 125px;
    font-size: 30px;
    color: #fff;
    line-height: 100px;
    text-align: center;
    box-shadow: 1px 2px 6px #999;

  }
 
  .conformation{
    color:#144f51 !important;
    font-weight:bold;
  }
  
  .closemessage{
  font-size:23px;
  }
  .page-wrapper {
    height: 100vh;
    background-color: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px 0;
  }
  body { margin:0; font-family: 'Roboto';}
  @media only screen and (max-width: 800px) {
    .page-wrapper {
      flex-direction: column;
    }
  }

  .yesbutton{
    background-color:#1f6d70;
    border: 1px solid #1f6d70;
    color:white;
    padding: 10px 30px;
    border-radius: 50px;
  }

  .nobutton{
    color:black;
    padding: 10px 30px;
    border-radius: 50px;
    border: 1px solid #cfcfcf;
    background: #fff;
  }
</style>

