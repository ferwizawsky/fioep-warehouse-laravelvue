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
import { useMaster } from "@/stores/master";

const master = useMaster();
const notif = useNotif();
const router = useRouter();
const route = useRoute();
const page = ref(1);

const listMenu = [
    {
        name: "materials",
        key: ["name", "description", "code", "img"],
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
const selectedMenuIndex = ref(0);

onMounted(() => {});

async function deleteData(e) {
    try {
        const { data } = await useMyFetch(
            "delete",
            `/${listMenu[selectedMenuIndex.value]?.name?.substring(
                0,
                listMenu[selectedMenuIndex.value]?.name.length - 1
            )}/${e.id}`
        );
        master.getData();
        notif.make(`Sukses Delete data`);
    } catch (error) {
    } finally {
    }
}
</script>
<template>
    <div class="pt-4">
        <div class="lg:flex items-center justify-between mb-6">
            <div class="space-x-4">
                <RouterLink
                    :to="`${route.path}/add?index=${selectedMenuIndex}`"
                >
                    <Button variant="outline">Create</Button>
                </RouterLink>
            </div>
            <div class="flex items-center space-x-4">
                <Button
                    v-for="(item, index) in listMenu"
                    @click="selectedMenuIndex = index"
                    class="uppercase"
                    :variant="
                        index == selectedMenuIndex ? 'primary' : 'default'
                    "
                >
                    {{ listMenu[index]?.name }}
                </Button>
                <!-- <form class="relative" @submit.prevent="getData()">
                    <Input
                        v-model="search"
                        class="pr-8"
                        placeholder="Search...."
                    />
                    <Search
                        class="w-4 absolute top-2 right-3 cursor-pointer text-foreground/50"
                    />
                </form> -->
            </div>
        </div>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class=""> No. </TableHead>
                    <TableHead
                        v-for="item in listMenu[selectedMenuIndex]?.key"
                        class="capitalize"
                        >{{ item }}</TableHead
                    >
                    <TableHead> Action </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow
                    v-for="(item, index) in master?.listMaster?.[
                        listMenu[selectedMenuIndex]?.name
                    ]"
                >
                    <TableCell>{{ (page - 1) * 10 + index + 1 }}</TableCell>
                    <TableCell v-for="x in listMenu[selectedMenuIndex]?.key">{{
                        item?.[x]
                    }}</TableCell>
                    <TableCell class="flex items-center space-x-2">
                        <!-- <RouterLink
                            :to="`${route.path}/detail/${item?.id}?index=${selectedMenuIndex}`"
                        >
                            <FunctionButton
                                class="bg-primary/20 hover:bg-primary/50 text-primary"
                            >
                                <FileText class="w-4 h-4" />
                            </FunctionButton>
                        </RouterLink> -->
                        <RouterLink
                            :to="`${route.path}/edit/${item?.id}?index=${selectedMenuIndex}&no=${index}`"
                        >
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
    </div>
</template>
