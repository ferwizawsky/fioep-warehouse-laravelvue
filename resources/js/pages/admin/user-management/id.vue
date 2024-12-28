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

const option = useOption();
const route = useRoute();
const router = useRouter();
const item = ref({
    username: "tester",
    name: "Test",
    password: "tester123",
    email: "test@gmail.com",
    role_id: "1",
});
async function storeData() {
    let url = `/user`;
    if (route.params.id) url = `/user/${route.params.id}/edit`;
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

async function getData() {
    try {
        const { data } = await useMyFetch("GET", `/user/${route.params.id}`);
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
            {{ route.params.name }} Data
        </div>
        <div>
            <form class="max-w-lg mx-auto" @submit.prevent="storeData">
                <div class="f-input-group mb-4">
                    <div>Roles</div>
                    <div class=" ">
                        <DropList
                            v-model="item.role_id"
                            :list="option?.roleList"
                            :disabled="
                                route.params.name == 'detail' ? true : false
                            "
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="f-input-group">
                        <div>Name</div>
                        <div class=" ">
                            <Input
                                v-model="item.name"
                                :disabled="
                                    route.params.name == 'detail' ? true : false
                                "
                            />
                        </div>
                    </div>

                    <div class="f-input-group">
                        <div>Username</div>
                        <div class=" ">
                            <Input
                                v-model="item.username"
                                :disabled="
                                    route.params.name == 'detail' ? true : false
                                "
                            />
                        </div>
                    </div>

                    <div class="f-input-group">
                        <div>Password</div>
                        <div class=" ">
                            <Input
                                v-model="item.password"
                                :disabled="
                                    route.params.name == 'detail' ? true : false
                                "
                            />
                        </div>
                    </div>

                    <div class="f-input-group">
                        <div>E-mail</div>
                        <div class=" ">
                            <Input
                                type="email"
                                v-model="item.email"
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
