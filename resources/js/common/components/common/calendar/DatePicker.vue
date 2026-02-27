<template>
    <a-date-picker
        v-model:value="dateValue"
        :format="dateFormat"
        :disabled-date="disabledDate"
        style="width: 100%"
        @change="dateChanged"
        @keydown="kewydownCall"
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
        const { disabledDate, dayjs } = common(); // Ensure dayjs is available
        const dateValue = ref(props.dateTime ? dayjs(props.dateTime).startOf('day') : dayjs().startOf('day')); // Set default to current date
        const dateFormat = "DD-MM-YYYY";

        onMounted(() => {
            setDate(props.dateTime);
        });

        const setDate = (setValue) => {
            if (setValue) {
                dateValue.value = dayjs(setValue).startOf("day");
            } else {
                dateValue.value = dayjs().startOf("day"); // Default to today if no date is provided
            }
        };

        const dateChanged = (newValue) => {
            const emitValue = newValue ? newValue.format("DD/MM/YYYY") : undefined;
            emit("dateChanged", emitValue);
        };

        const kewydownCall = (event) => {
            console.log("event", event);
        };

        watch(
            () => props.dateTime,
            (newVal) => {
                setDate(newVal);
            }
        );

        return {
            dateValue,
            disabledDate,
            dateChanged,
            dateFormat,
        };
    },
});
</script>

<style></style>
