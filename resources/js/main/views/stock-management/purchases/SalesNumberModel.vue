<template>
    <a-modal
      title="Mobile Numbers"
      v-model:visible="isModalVisible"
      @ok="handleSuccess"
      @cancel="handleClose"
    >
      <!-- Form with search and select filter -->
      <a-form layout="vertical">
        <a-row :gutter="16">
          <a-col :xs="24" :sm="24" :md="12" :lg="12">
            <a-form-item name="party_name_search" class="required">
              <a-input-search
                v-model="searchQuery"
                style="width: 100%"
                placeholder="Search here.."
                @search="handleSearch"
              />
            </a-form-item>
          </a-col>
          <a-col :xs="24" :sm="24" :md="12" :lg="12">
            <a-form-item name="filter_by">
              <a-select v-model="filterBy">
                <a-select-option value="all">All</a-select-option>
                <a-select-option value="mobile_number">Mobile No</a-select-option>
                <a-select-option value="name">Name</a-select-option>
                <a-select-option value="patient_id">Patient ID</a-select-option>
                <a-select-option value="government_id">Government ID</a-select-option>
              </a-select>
            </a-form-item>
          </a-col>
        </a-row>
      </a-form>
  
      <!-- Table to display sales numbers -->
      <a-table :dataSource="salesNumbers" :columns="columns" rowKey="id">
        <template #bodyCell="{ column, text }">
          <span>{{ text }}</span>
        </template>
      </a-table>
    </a-modal>
  </template>
  
  <script>
  import { ref, onMounted, watch } from "vue";
  import apiAdmin from "../../../../common/composable/apiAdmin";
  
  export default {
    props: {
      visible: {
        type: Boolean,
        default: false
      }
    },
    setup(props) {
      const isModalVisible = ref(props.visible);
      const salesNumbers = ref([]);
      const searchQuery = ref("");
      const filterBy = ref("all");
  
      const columns = [
        { title: "Mobile Number", dataIndex: "mobile_number", key: "mobile_number" },
        { title: "ID", dataIndex: "id", key: "id" },
        { title: "Name", dataIndex: "name", key: "name" },
        { title: "Gender", dataIndex: "gender", key: "gender" },
        { title: "Age", dataIndex: "age", key: "age" },
        { title: "Status", dataIndex: "customer_status", key: "customer_status" },
        { title: "Action", key: "action", scopedSlots: { customRender: "action" } }
      ];
  
      // Fetch data from API
      const fetchData = async () => {
        try {
          const response = await apiAdmin.get("salesnumber");
          salesNumbers.value = response.data;
        } catch (error) {
          console.error("Error fetching sales numbers:", error);
        }
      };
  
      // Fetch data on component mount
      onMounted(() => {
           tableUrl.value = {
                url: "salesnumber?fields=id,xid,name,mobile_number,gender,age,billing_discount,customer_status,",
            };
           
            crudVariables.fetch({
                page: 1,
            });
      });
  
      
      const handleSearch = async () => {
        try {
          const response = await apiAdmin.get("salesnumber", {
            params: {
              query: searchQuery.value,
              filter: filterBy.value
            }
          });
          salesNumbers.value = response.data;
        } catch (error) {
          console.error("Error searching sales numbers:", error);
        }
      };
  
      const handleSuccess = () => {
        // Emit success and close modal
        props.$emit("addEditSuccess");
        isModalVisible.value = false;
      };
  
      const handleClose = () => {
        // Emit close event and close modal
        props.$emit("closed");
        isModalVisible.value = false;
      };
  
      // Watch for visibility change
      watch(
        () => props.visible,
        (newVal) => {
          isModalVisible.value = newVal;
        }
      );
  
      return {
        isModalVisible,
        salesNumbers,
        searchQuery,
        filterBy,
        columns,
        handleSearch,
        handleSuccess,
        handleClose
      };
    }
  };
  </script>
  
  <style scoped>
  /* Add custom styles if necessary */
  </style>
  