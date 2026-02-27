<template>
    <a-date-picker
        v-model:value="dateTimeValue"
        :format="formatOrderDate"
        :disabled-date="disabledDate"
        show-time
       @keydown="handleArrowNavigation"
        style="width: 100%"
        @change="dateTimeChanged"
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
    emits: ["dateTimeChanged", "enterPressed"],
    setup(props, { emit }) {
        
        const { disabledDate, formatDateTime, dayjs } = common();
        const dateTimeValue = ref(dayjs());
        const handleArrowNavigation = (event) => {
    if (!dateTimeValue.value) return;

    const current = dateTimeValue.value;

    switch (event.key) {
        case "ArrowRight":
            event.preventDefault();
            dateTimeValue.value = current.add(1, "day");
            break;

        case "ArrowLeft":
            event.preventDefault();
            dateTimeValue.value = current.subtract(1, "day");
            break;

        case "ArrowUp":
            event.preventDefault();
            dateTimeValue.value = current.subtract(7, "day");
            break;

        case "ArrowDown":
            event.preventDefault();
            dateTimeValue.value = current.add(7, "day");
            break;
    }
};
        const handleKeydown = (event) => {
            if (event.key !== "Enter") return;

            if (!dateTimeValue.value) return;

            event.preventDefault();
            emit("enterPressed");
        };

        onMounted(() => {
            setDateTime(props.dateTime);
        });

        const setDateTime = (setValue) => {
            if (props.dateTime) {
                dateTimeValue.value = dayjs(setValue);
            } else {
                dateTimeValue.value = undefined;
            }
        };

        const formatOrderDate = (newValue) => {
            return newValue ? formatDateTime(newValue.format()) : undefined;
        };

        const dateTimeChanged = (newValue) => {
            const emitValue = newValue
                ? newValue.utc().format("YYYY-MM-DDTHH:mm:ssZ")
                : undefined;
            emit("dateTimeChanged", emitValue);
        };

        watch(
            () => props.dateTime,
            (newVal, oldVal) => {
                setDateTime(newVal);
            }
        );

        return {
            dateTimeValue,
            disabledDate,
            formatOrderDate,
            dateTimeChanged,
            handleKeydown,
            handleArrowNavigation,
        };
    },
});
</script>

<style></style>
