<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import DropList from "@/components/inkia/form/DropList.vue";
import DropdownSearch from "@/components/inkia/form/DropdownSearch.vue";
import { jsonFormData, useMyFetch } from "@/composables/fetch";
import { useNotif } from "@/stores/notif";
import { useOption } from "@/stores/option";
import { useMaster } from "@/stores/master";
import { Textarea } from "@/components/ui/textarea";

const option = useOption();
const master = useMaster();
const route = useRoute();
const router = useRouter();
const item = ref({
    description: "tester",
    supplier_id: 3,
    materials: [],
});
const selectedMaterial = ref({
    material_id: 1,
    quantity: 2,
});
async function storeData() {
    let url = `/purchase`;
    if (route.params.id) url = `/purchase/${route.params.id}/edit`;
    try {
        const { data } = await useMyFetch(
            "POST",
            url,
            jsonFormData(item.value)
        );
        useNotif().make("Success Update Data");
        router.go(-1);
    } catch (e) {
        console.log(e);
    }
}
function deleteMaterial(index) {
    item.value.materials.splice(index, 1);
}

async function getData() {
    try {
        const { data } = await useMyFetch(
            "GET",
            `/purchase/${route.params.id}`
        );
        item.value = data.data;
    } catch (e) {}
}

onMounted(() => {
    if (route.params.id) getData();
});
</script>

<template>
    <div class="p-4">
        <div></div>
        <div class="text-center uppercase font-semibold text-xl mb-5">
            {{ route.params.name }} Purchase Data
        </div>
        <div>
            <form class="max-w-xl mx-auto" @submit.prevent="storeData">
                <div class="f-input-group mb-4">
                    <div>Description</div>
                    <div class=" ">
                        <Textarea
                            v-model="item.description"
                            :disabled="
                                route.params.name == 'detail' ? true : false
                            "
                        ></Textarea>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="f-input-group">
                        <div>Supplier</div>
                        <div class=" ">
                            <DropdownSearch
                                v-model="item.supplier_id"
                                :list="master.listMaster?.suppliers"
                                :disabled="
                                    route.params.name == 'detail' ? true : false
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="pt-10" v-if="!route.params.id">
                    <div class="font-semibold mb-2">List Material</div>
                    <form
                        class="grid grid-cols-7 gap-4 items-center"
                        @submit.prevent="
                            {
                                item.materials.push({ ...selectedMaterial });
                            }
                        "
                    >
                        <div class="f-input-group col-span-3">
                            <div>Material</div>
                            <div class=" ">
                                <DropdownSearch
                                    v-model="selectedMaterial.material_id"
                                    :list="master.listMaster?.materials"
                                    :disabled="
                                        route.params.name == 'detail'
                                            ? true
                                            : false
                                    "
                                />
                            </div>
                        </div>
                        <div class="f-input-group col-span-3">
                            <div>Quantity</div>
                            <div class=" ">
                                <Input
                                    type="text"
                                    v-model="selectedMaterial.quantity"
                                    :disabled="
                                        route.params.name == 'detail'
                                            ? true
                                            : false
                                    "
                                />
                            </div>
                        </div>
                        <div>
                            <div class="pt-3"></div>
                            <Button type="submit" size="icon"> + </Button>
                        </div>
                    </form>

                    <div class="pt-6">
                        <div
                            v-for="(item, index) in item.materials"
                            class="flex items-center"
                        >
                            {{ index + 1 }}.
                            {{
                                item?.material?.name ??
                                master.listMaster?.materials?.find(
                                    (e) => e.id == item.material_id
                                )?.name
                            }}
                            <-> Qty: {{ item.quantity }}
                            <span
                                @click="deleteMaterial(index)"
                                class="ml-6 text-rose-600 cursor-pointer"
                            >
                                Delete
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pt-10" v-if="route.params.id">
                    <div class="font-semibold mb-2">List Material</div>
                    <div class="grid grid-cols-3 gap-4">
                        <div
                            class="border-primary/30 border text-center rounded-lg min-h-[200px] p-4"
                            v-for="tmp in item.materials"
                        >
                            <div class="pt-4">
                                <img
                                    class="w-20 h-20 object-cover rounded-full mx-auto"
                                    :src="tmp?.material?.img"
                                />
                            </div>
                            <div class="pt-4">
                                <div class="text-base font-semibold">
                                    {{ tmp?.material?.name }}
                                </div>
                                <div class="text-xs">Quantity</div>
                                <div class="font-semibold text-primary">
                                    {{ tmp?.quantity }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="text-center pt-10"
                    v-if="route.params.name != 'detail'"
                >
                    <Button type="submit" size="lg"> Submit </Button>
                </div>
            </form>
        </div>
    </div>
</template>
<style>
.f-input-group {
    /* @apply grid grid-cols-4 text-right items-center gap-2; */
    /* @apply mb-4; */
}
.f-input-group div:first-child {
    @apply text-xs font-medium;
}
</style>
