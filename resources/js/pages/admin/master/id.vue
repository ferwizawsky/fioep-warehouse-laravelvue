<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import DropList from "@/components/inkia/form/DropList.vue";
import DropdownSearch from "@/components/inkia/form/DropdownSearch.vue";
import { jsonFormData, useMyFetch } from "@/composables/fetch";
import { useNotif } from "@/stores/notif";
import { useOption } from "@/stores/option";
import { useMaster } from "@/stores/master";

const master = useMaster();
const option = useOption();
const route = useRoute();
const router = useRouter();
const listMenu = [
    {
        name: "materials",
        key: ["name", "code", "img"],
    },
    {
        name: "suppliers",
        key: ["name", "description"],
    },
    {
        name: "storages",
        key: ["name", "description"],
    },
];
const item = ref({});
async function storeData() {
    let url = `/${listMenu[parseInt(route.query.index)]?.name?.substring(
        0,
        listMenu[parseInt(route.query.index)]?.name.length - 1
    )}`;
    if (route.params.id)
        url = `/${listMenu[parseInt(route.query.index)]?.name?.substring(
            0,
            listMenu[parseInt(route.query.index)]?.name.length - 1
        )}/${route.params.id}`;
    try {
        const { data } = await useMyFetch(
            route.params.id ? "PUT" : "POST",
            url,
            item.value
        );
        master.getData();
        useNotif().make("Success Update Data");
        router.go(-1);
    } catch (e) {
        console.log(e);
    }
}

onMounted(() => {
    for (let xe of listMenu[parseInt(route.query.index)]?.key) {
        item.value[xe] = "";
    }

    if (route.params.id) {
        for (let xe of listMenu[parseInt(route.query.index)]?.key) {
            let getId = route.query.no;
            item.value[xe] =
                master.listMaster?.[
                    listMenu[parseInt(route.query.index)]?.name
                ]?.[getId]?.[xe];
        }
    }
});
</script>

<template>
    <div class="p-4">
        <div></div>
        <div class="text-center uppercase font-semibold text-xl mb-5">
            {{ route.params.name }} Data
        </div>
        <div>
            <form class="max-w-lg mx-auto" @submit.prevent="storeData">
                <div class="grid grid-cols-2 gap-4">
                    <div v-for="(value, key) in item" class="f-input-group">
                        <div>{{ key }}</div>
                        <div v-if="key == 'description'" class=" ">
                            <Textarea
                                v-model="item[key]"
                                :disabled="
                                    route.params.name == 'detail' ? true : false
                                "
                            ></Textarea>
                        </div>
                        <div v-else class=" ">
                            <Input
                                v-model="item[key]"
                                :disabled="
                                    route.params.name == 'detail' ? true : false
                                "
                            />
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
