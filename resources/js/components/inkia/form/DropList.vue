<script setup>
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

import { ref, watch } from "vue";

const props = defineProps(["modelValue", "list", "disabled"]);
const emit = defineEmits(["update:modelValue"]);
const val = ref(props.modelValue);
watch(
    () => val.value,
    (e) => {
        emit("update:modelValue", val.value);
    }
);
watch(
    () => props.modelValue,
    (e) => {
        val.value = String(e);
    }
);
</script>

<template>
    <Select v-model="val" :disabled="props?.disabled">
        <SelectTrigger>
            <SelectValue class="capitalize" placeholder="Pilih ......." />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem
                    v-for="item in props?.list"
                    :value="String(item.val)"
                    class="uppercase"
                >
                    {{ item?.text?.replace("_", " ") }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
