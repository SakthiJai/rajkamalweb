<template>
    <a-date-picker
        v-model:value="dateValue"
        :format="formatDates"
        :disabled-date="disabledDate"
        :placeholder="$t('common.date')"
        style="width: 100%"
        @change="dateChanged"
        :disabled="disabled"
    />
</template>

<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import common from "../../../composable/common";

export default defineComponent({
    props: {
        dateTime: {
            default: undefined,
        },
        disabled: {
            default: false,
        },
    },
    emits: ["dateChanged"],
    setup(props, { emit }) {
        const dateValue = ref(undefined);
        const { disabledDate, formatDates, dayjs } = common(); // Assuming you have formatDate defined

        onMounted(() => {
            setDate(props.dateTime);
        });

        const setDate = (setValue) => {
            if (setValue) {
                dateValue.value = dayjs(setValue).startOf('day'); // Ensure only date without time
            } else {
                dateValue.value = undefined;
            }
        };

        const formatDate = (newValue) => {
            return newValue ? newValue.format("YYYY-MM-DD") : undefined;
        };

        const dateChanged = (newValue) => {
            const emitValue = newValue ? newValue.format("YYYY-MM-DD") : undefined;
            emit("dateChanged", emitValue);
        };

        watch(
            () => props.dateTime,
            (newVal, oldVal) => {
                setDate(newVal);
            }
        );

        return {
            dateValue,
            disabledDate,
            formatDate,
            dateChanged,
        };
    },
});
</script>

<style></style>
