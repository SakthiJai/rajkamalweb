<template>
    <div class="dropdown">
        <button type="button" class="dropdown-toggleing px-4" @click="toggleDropdown">
            {{ buttonLabel }}
        </button>
        <ul v-if="isDropdownOpen" class="dropdown-menusing">
            <div class="dropdown-menusing cust-date-filter shadow p-4 show" :style="dropdownStyle">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a class="dropdown-item border mb-2" href="javascript:;" @click="selectDateRange('Today')">
                            Today <span class="float-right" v-if="selectedRange === 'Today'">✔</span>
                        </a>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a class="dropdown-item border mb-2" href="javascript:;" @click="selectDateRange('Yesterday')">
                            Yesterday <span class="float-right" v-if="selectedRange === 'Yesterday'">✔</span>
                        </a>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a class="dropdown-item border mb-2" href="javascript:;" @click="selectDateRange('This Week')">
                            This Week <span class="float-right" v-if="selectedRange === 'This Week'">✔</span>
                        </a>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a class="dropdown-item border mb-2" href="javascript:;" @click="selectDateRange('Last 7 days')">
                            Last 7 days <span class="float-right" v-if="selectedRange === 'Last 7 days'">✔</span>
                        </a>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a class="dropdown-item border mb-2" href="javascript:;" @click="selectDateRange('This Month')">
                            This Month <span class="float-right" v-if="selectedRange === 'This Month'">✔</span>
                        </a>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a class="dropdown-item border mb-2" href="javascript:;" @click="selectDateRange('Previous Month')">
                            Previous Month <span class="float-right" v-if="selectedRange === 'Previous Month'">✔</span>
                        </a>
                    </a-col>
                </a-row>

                <hr class="hrtages" />
                
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('stock.customs')"></a-form-item>
                    </a-col>
                </a-row>

                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <DateRangePicker
                            @dateTimeChanged="(changedDateTime) => {
                                filters.dates = changedDateTime;
                            }"
                        />
                    </a-col>
                </a-row>

                <a-col :xs="24" :sm="24" :md="24" :lg="24" id="customdate">
                    <a-button type="primary" class="creating" @click="applyFilter">Apply</a-button>
                </a-col>
            </div>
        </ul>

        <div class="data-display">
            <h3>Data for: {{ selectedRange }}</h3>
            <ul>
                <li v-for="item in filteredData" :key="item.id">{{ item.name }}</li>
            </ul>
        </div>
    </div>
</template>
<script>
import { defineComponent, ref } from "vue";
import DateRangePicker from "./DateRangePicker.vue";
// import common from "../../../../common/composable/common";

export default defineComponent({
    components: { DateRangePicker },
    setup() {
        const isDropdownOpen = ref(false);
        const selectedRange = ref('');
        const buttonLabel = ref('Select Date');
        const originalData = ref([
            { id: 1, name: 'Item 1', date: '2024-10-11' }, // Replace with your actual data
            { id: 2, name: 'Item 2', date: '2024-10-10' },
            { id: 3, name: 'Item 3', date: '2024-10-09' },
            // Add more data as needed
        ]);
        const filteredData = ref([]);

        const toggleDropdown = () => {
            isDropdownOpen.value = !isDropdownOpen.value;
        };

        const selectDateRange = (range) => {
            selectedRange.value = range;
            buttonLabel.value = range;

            // Call the function to filter data based on the selected date range
            filterData(range);
        };

        const filterData = (range) => {
            const today = new Date().toISOString().split('T')[0]; // Format as YYYY-MM-DD
            let startDate, endDate;

            switch (range) {
                case 'Today':
                    startDate = today;
                    endDate = today;
                    break;
                case 'Yesterday':
                    const yesterday = new Date();
                    yesterday.setDate(yesterday.getDate() - 1);
                    startDate = endDate = yesterday.toISOString().split('T')[0];
                    break;
                case 'This Week':
                    const startOfWeek = new Date();
                    startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay()); // Sunday
                    startDate = startOfWeek.toISOString().split('T')[0];
                    endDate = today;
                    break;
                case 'Last 7 days':
                    const lastWeek = new Date();
                    lastWeek.setDate(lastWeek.getDate() - 7);
                    startDate = lastWeek.toISOString().split('T')[0];
                    endDate = today;
                    break;
                case 'This Month':
                    const startOfMonth = new Date();
                    startOfMonth.setDate(1);
                    startDate = startOfMonth.toISOString().split('T')[0];
                    endDate = today;
                    break;
                case 'Previous Month':
                    const prevMonth = new Date();
                    prevMonth.setMonth(prevMonth.getMonth() - 1);
                    startDate = new Date(prevMonth.getFullYear(), prevMonth.getMonth(), 1).toISOString().split('T')[0];
                    endDate = new Date(prevMonth.getFullYear(), prevMonth.getMonth() + 1, 0).toISOString().split('T')[0]; // Last day of prev month
                    break;
                default:
                    startDate = endDate = today;
            }

            // Filter the original data based on the selected date range
            filteredData.value = originalData.value.filter(item => {
                return item.date >= startDate && item.date <= endDate;
            });
        };

        const applyFilter = () => {
            // Logic to apply any additional filters if needed
        };

        return {
            isDropdownOpen,
            selectedRange,
            buttonLabel,
            filteredData,
            toggleDropdown,
            selectDateRange,
            applyFilter,
        };
    },
});
</script>
