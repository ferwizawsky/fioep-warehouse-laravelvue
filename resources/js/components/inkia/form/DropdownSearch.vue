<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { Check, ChevronsUpDown } from "lucide-vue-next";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import Input from "@/components/ui/input/Input.vue";

const open = ref(false);
const value = ref(0);
const search = ref("");
const emit = defineEmits(["update:modelValue"]);
const props = defineProps(["modelValue", "list", "disabled"]);

let filteredList = computed(() =>
    search.value == ""
        ? props?.list
        : props?.list.filter((obj) =>
              obj.name
                  .toLowerCase()
                  .replace(/\s+/g, "")
                  .includes(search.value.toLowerCase().replace(/\s+/g, ""))
          )
);

watch(
    () => value.value,
    (e) => {
        emit("update:modelValue", e);
    }
);
onMounted(() => {
    value.value = props?.modelValue;
});
</script>
<template>
    <div>
        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    role="combobox"
                    :aria-expanded="open"
                    class="justify-between w-full"
                    :disabled="props?.disabled"
                >
                    {{
                        value
                            ? list?.find((framework) => framework.id == value)
                                  ?.name
                            : "Filter..."
                    }}
                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                </Button>
            </PopoverTrigger>
            <PopoverContent class="p-0">
                <Command>
                    <div class="p-2">
                        <Input
                            class="h-9"
                            placeholder="Search..."
                            v-model="search"
                        />
                    </div>
                    <CommandEmpty>No data found.</CommandEmpty>
                    <CommandList>
                        <CommandGroup>
                            <CommandItem
                                v-for="framework in filteredList"
                                :key="framework.id"
                                :value="framework.id"
                                @select="
                                    (ev) => {
                                        value = ev.detail.value;
                                        open = false;
                                    }
                                "
                            >
                                {{ framework.name }}
                                <Check
                                    :class="
                                        cn(
                                            'ml-auto h-4 w-4',
                                            value == framework.id
                                                ? 'opacity-100'
                                                : 'opacity-0'
                                        )
                                    "
                                />
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    </div>
</template>
