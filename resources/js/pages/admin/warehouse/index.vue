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
    PackageOpen,
} from "lucide-vue-next";
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import FunctionButton from "@/components/inkia/table/FunctionButton.vue";
import { useMyFetch } from "@/composables/fetch";
import { useNotif } from "@/stores/notif";
import { dateFormatter } from "@/composables/timeFormatter";
import { useOption } from "@/stores/option";
import { Textarea } from "@/components/ui/textarea";
import { useMaster } from "@/stores/master";
import DropdownSearch from "@/components/inkia/form/DropdownSearch.vue";

const master = useMaster();
const option = useOption();
const notif = useNotif();
const router = useRouter();
const route = useRoute();
const page = ref(1);
const search = ref("");
const listData = ref([]);
const selectedItem = ref([]);

const isListWarehouse = ref(false);
const limitPaginate = ref(10);
const meta = ref({});
const itemObj = ref({
    storage_id: 1,
    description: "tester",
    purchase_id: 0,
});

onMounted(() => {
    if (route.query?.page) page.value = route.query?.page;
    getData();
});

async function getData() {
    listData.value = [];
    try {
        const { data } = await useMyFetch(
            "GET",
            `/${
                isListWarehouse.value
                    ? "warehouse"
                    : "warehouse/list-processed-purchase"
            }?search=${search.value}&page=${page.value}&limit=${
                limitPaginate.value
            }`
        );
        listData.value = [...data.data];
        meta.value = { ...data.meta };
    } catch (e) {}
}

async function submitProcess(e) {
    try {
        const { data } = await useMyFetch("POST", `/warehouse/${e.id}/delete`);
        getData();
        notif.make(`Sukses Update data`);
    } catch (error) {
    } finally {
    }
}

async function deleteData(e) {
    try {
        const { data } = await useMyFetch("POST", `/warehouse/${e.id}/delete`);
        getData();
        notif.make(`Sukses Update data`);
    } catch (error) {
    } finally {
    }
}

watch(
    () => isListWarehouse.value,
    (e) => {
        getData();
    }
);

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
                    <Button variant="default">Create</Button>
                </RouterLink>
            </div>
            <div class="flex items-center space-x-4">
                <Button
                    @click="isListWarehouse = true"
                    :variant="isListWarehouse ? 'ghost' : 'default'"
                    >Received</Button
                >
                <Button
                    @click="isListWarehouse = false"
                    :variant="!isListWarehouse ? 'ghost' : 'default'"
                    >Processed</Button
                >
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
                    <TableHead class=""> ID Purchase</TableHead>
                    <TableHead>Requester</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead> Responded By</TableHead>
                    <TableHead> Responded At </TableHead>

                    <TableHead>Supplier</TableHead>

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
                    <TableCell>{{ item.email }}</TableCell>
                    <TableCell>{{ item.status }}</TableCell>
                    <TableCell>{{ item.supplier?.name }}</TableCell>
                    <TableCell class="capitalize">
                        {{ dateFormatter(item.created_at) }}
                    </TableCell>
                    <TableCell class="flex items-center space-x-2">
                        <RouterLink
                            :to="`${route.path}/detail/${item?.id}`"
                            v-if="isListWarehouse"
                        >
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FileText class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink>
                        <!-- <RouterLink :to="`${route.path}/edit/${item?.id}`">
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FilePenLine class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink> -->

                        <AlertDialog v-if="!isListWarehouse">
                            <AlertDialogTrigger as-child>
                                <FunctionButton
                                    @click="itemObj.purchase_id = item.id"
                                    class="bg-primary/20 hover:bg-primary/50 text-primary"
                                >
                                    <PackageOpen class="w-4 h-4" />
                                </FunctionButton>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle
                                        >Receive Material
                                    </AlertDialogTitle>
                                    <AlertDialogDescription>
                                        Fill form below and choose storage you
                                        want to use
                                    </AlertDialogDescription>
                                </AlertDialogHeader>

                                <div class="f-input-group">
                                    <div class="text-xs">Description</div>
                                    <div class=" ">
                                        <Textarea
                                            v-model="itemObj.description"
                                        ></Textarea>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="text-xs">Storage</div>
                                    <div class=" ">
                                        <DropdownSearch
                                            v-model="itemObj.storage_id"
                                            :list="master.listMaster?.storages"
                                        />
                                    </div>
                                </div>

                                <AlertDialogFooter>
                                    <AlertDialogCancel>
                                        Batal
                                    </AlertDialogCancel>
                                    <AlertDialogAction>
                                        Lanjut
                                    </AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>

                        <!-- <AlertDialog>
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
                        </AlertDialog> -->
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
