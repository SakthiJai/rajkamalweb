<template>
    <a-date-picker
        ref="pickerRef"
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
import { defineComponent, onMounted, ref, watch, nextTick } from "vue";
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
        const handleArrowNavigation = async (event) => {
            if (!dateTimeValue.value) return;

            const current = dateTimeValue.value;
            let updated = current;

            switch (event.key) {
                case "ArrowRight":
                    updated = current.add(1, "day");
                    break;

                case "ArrowLeft":
                    updated = current.subtract(1, "day");
                    break;

                case "ArrowUp":
                    updated = current.subtract(7, "day");
                    break;

                case "ArrowDown":
                    updated = current.add(7, "day");
                    break;

                default:
                    return; 
            }

            event.preventDefault();
            event.stopPropagation();

            dateTimeValue.value = updated;
 
            const emitValue = updated
                ? updated.utc().format("YYYY-MM-DDTHH:mm:ssZ")
                : undefined;

            emit("dateTimeChanged", emitValue);
 
            await nextTick();
            event.target.focus();
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
