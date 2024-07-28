<script setup>
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import Paginate from "@/components/inkia/table/Paginate.vue";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/components/ui/alert-dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

import {
    FileText,
    FilePenLine,
    Trash2,
    Search,
    Printer,
} from "lucide-vue-next";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import FunctionButton from "@/components/inkia/table/FunctionButton.vue";
import { useMyFetch } from "@/composables/fetch";
import { useNotif } from "@/stores/notif";
import { dateFormatter } from "@/composables/timeFormatter";
import { useOption } from "@/stores/option";

const option = useOption();
const notif = useNotif();
const router = useRouter();
const route = useRoute();
const page = ref(1);
const search = ref("");
const listData = ref([]);
const selectedItem = ref([]);
const limitPaginate = ref(10);
const meta = ref({});

onMounted(() => {
    if (route.query?.page) page.value = route.query?.page;
    getData();
});

async function getData() {
    listData.value = [];
    try {
        const { data } = await useMyFetch(
            "GET",
            `/user?search=${search.value}&page=${page.value}&limit=${limitPaginate.value}`
        );
        listData.value = [...data.data];
        meta.value = { ...data.meta };
    } catch (e) {}
}

async function deleteData(e) {
    try {
        const { data } = await useMyFetch("POST", `/user/${e.id}/delete`);
        getData();
        notif.make(`Sukses Update data`);
    } catch (error) {
    } finally {
    }
}

function setPage(e) {
    router.push(`${route.path}?page=${e}`);
    page.value = e;
    getData();
}
</script>
<template>
    <div class="pt-4">
        <div class="lg:flex items-center justify-between mb-6">
            <div class="space-x-4">
                <RouterLink :to="`${route.path}/add`">
                    <Button variant="outline">Create</Button>
                </RouterLink>
            </div>
            <div class="flex items-center space-x-4">
                <form class="relative" @submit.prevent="getData()">
                    <Input
                        v-model="search"
                        class="pr-8"
                        placeholder="Search...."
                    />
                    <Search
                        class="w-4 absolute top-2 right-3 cursor-pointer text-foreground/50"
                    />
                </form>
            </div>
        </div>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class=""> No. </TableHead>
                    <TableHead class=""> Name</TableHead>
                    <TableHead>Username</TableHead>
                    <TableHead>E-mail</TableHead>
                    <TableHead>Role</TableHead>
                    <TableHead> Created At </TableHead>
                    <TableHead> Action </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(item, index) in listData">
                    <TableCell>{{ (page - 1) * 10 + index + 1 }}</TableCell>
                    <TableCell class="font-medium">
                        {{ item.name }}
                    </TableCell>
                    <TableCell>{{ item.username }}</TableCell>
                    <TableCell>{{ item.email }}</TableCell>
                    <TableCell class="capitalize">
                        {{
                            option.roleList
                                ?.find((e) => e?.id == item.role_id)
                                ?.name?.replace("_", " ")
                        }}
                    </TableCell>
                    <TableCell class="capitalize">
                        {{ dateFormatter(item.created_at) }}
                    </TableCell>
                    <TableCell class="flex items-center space-x-2">
                        <RouterLink :to="`${route.path}/detail/${item?.id}`">
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FileText class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink>
                        <RouterLink :to="`${route.path}/edit/${item?.id}`">
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FilePenLine class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink>
                        <AlertDialog>
                            <AlertDialogTrigger as-child>
                                <FunctionButton
                                    class="bg-rose-400/20 hover:bg-rose-400/50 text-rose-500"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </FunctionButton>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle
                                        >Apakah Anda Yakin?</AlertDialogTitle
                                    >
                                    <AlertDialogDescription>
                                        Data setelah dihapus tidak bisa
                                        dikembalikan lagi dari server!
                                    </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                    <AlertDialogCancel>
                                        Batal
                                    </AlertDialogCancel>
                                    <AlertDialogAction
                                        @click="deleteData(item)"
                                    >
                                        Lanjut
                                    </AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <Paginate
            @move="setPage($event)"
            :page="page"
            :list="meta?.links"
            :meta="meta"
            :limit-paginate="limitPaginate"
            @update="limitPaginate = $event"
        />
    </div>
</template>
